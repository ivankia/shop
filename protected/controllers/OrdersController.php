<?php

class OrdersController extends Controller
{
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Orders the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Orders::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->actionAdmin();
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Orders('search');
        $model->unsetAttributes();

        if (isset($_GET['Orders'])) {
            $model->attributes = $_GET['Orders'];
        }

        $model->from_date = !empty($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
        $model->to_date = !empty($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-t');

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function getTotal($ids)
    {
        return Orders::getTotal($ids);
    }

    /**
     * Performs the AJAX validation.
     * @param Orders $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'orders-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
