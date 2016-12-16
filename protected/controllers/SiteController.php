<?php
require_once(YII_PATH.DS.'..'.DS.'vendor'.DS.'stripe'.DS.'vendor'.DS.'autoload.php');

class SiteController extends Controller
{
    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionResult()
    {
        dump($_REQUEST);
        die();
    }

    public function actionCheckout($id)
    {
        $product = null;
        // set product_id for payment
        if ($id) {
            Yii::app()->user->setState('productId', $id);
            $product = Products::model()->find('product_id=' . (int)$id);
        }

        $stripe = array(
            "secret_key" => Yii::app()->params['sk'],
            "publishable_key" => Yii::app()->params['pk'],
            "name" => $product->name,
            "price" => (int)$product->price*100,
        );

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        // check user email
        if (!empty($_POST['userEmail'])) {
            if (!preg_match('/([\w.-]+@([\w-]+)\.+\w{2,})/', trim($_POST['userEmail']))) {
                $this->render('../orders/checkout', [
                    'result' => 'invalid-email',
                    'email' => trim($_POST['userEmail']),
                    'message' => 'Введен неправильный E-mail!',
                    'needEmail' => true
                ]);
                Yii::app()->end();
            }

            Yii::app()->user->setState('userEmail', trim($_POST['userEmail']));
            $this->redirect(Yii::app()->createUrl('site/checkout/' . $id));
        }

        // payment
        if (Yii::app()->user->getState('userEmail')) {
            // response
            if (!empty($_POST['stripeToken'])) {
                $customer = \Stripe\Customer::create(array(
                    'email' => Yii::app()->user->getState('userEmail'),
                    'source' => $_POST['stripeToken']
                ));

                \Stripe\Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => (int)$product->price*100,
                    'currency' => $product->currency->code
                ));

                // store payment
                $order = new Orders();
                $order->product_id = $product->product_id;
                $order->price = (int)$product->price*100;
                $order->currency_id = $product->currency_id;
                $order->email = Yii::app()->user->getState('userEmail');
                $order->status = 1;

                try {
                    $order->save();
                } catch (CDbException $ex) {
                    throw new CException('Ошибка при сохранении платежа. ', $ex->getMessage());
                }

                $this->render('../orders/checkout', [
                    'result' => 'success',
                    'message' => 'Оплата ' . $this->priceFormat($product->price) . ' ' . $product->currency->code . ' произведена успешно!'
                ]);

                Yii::app()->end();
            }

            $this->render('../orders/checkout', ['stripe' => $stripe]);
        } else {
            $this->render('../orders/checkout', ['needEmail' => true]);
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->render('index', ['dataProvider' => Products::model()->search()]);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new Login;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['Login'])) {
            $model->attributes = $_POST['Login'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->request->baseUrl . '/products/index');
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}