<?php

class ProductsController extends Controller
{
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Products the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Products::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if (!empty($model->image_ext) &&
            file_exists(Yii::getPathOfAlias('images') . DS . $model->product_id . '.' . $model->image_ext
        )) {
            $model->image = $this->baseImgUrl . '/' . $model->product_id . '.' . $model->image_ext;
        }

        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Products;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Products'])) {
            if (!empty($_POST['Currency']['currency_id'])) {
                $_POST['Products']['currency_id'] = $_POST['Currency']['currency_id'];
            }

            $model->attributes = $_POST['Products'];

            if (!empty($_FILES['Products']['size']['image'])) {
                $model->image = CUploadedFile::getInstance($model, 'image');
                $model->image_ext = CFileHelper::getExtension($model->image->getName());
            }

            if($model->save()) {
                if ($model->image_ext) {
                    $path = Yii::getPathOfAlias('images') . DS . $model->product_id . '.' . $model->image_ext;
                    $model->image->saveAs($path);
                }

                $this->redirect(array('view', 'id' => $model->product_id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws CException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $isNewImage = false;

        if (isset($_POST['Products'])) {
            if (!empty($_POST['Currency']['currency_id'])) {
                $_POST['Products']['currency_id'] = $_POST['Currency']['currency_id'];
            }

            $model->attributes = $_POST['Products'];

            if (!empty($_FILES['Products']['size']['image'])) {
                $model->image = CUploadedFile::getInstance($model, 'image');
                $model->image_ext = CFileHelper::getExtension($model->image->getName());
                $isNewImage = true;
            }

            $model->modified_at = date('Y-m-d H:i:s');

            try {
                if ($model->save()) {
                    if ($isNewImage) {
                        $path = Yii::getPathOfAlias('images') . DS . $model->product_id . '.' . $model->image_ext;
                        $model->image->saveAs($path);
                    }

                    $this->redirect(array('view', 'id' => $model->product_id));
                }
            } catch (CDbException $ex) {
                throw new CException('Ошибка сохранения записи. '. $ex->getCode());
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CException
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->loadModel($id);
            $path = Yii::getPathOfAlias('images') . DS . $model->product_id . '.' . $model->image_ext;

            $model->delete();
            unlink($path);
        } catch (CDbException $ex) {
            throw new CException("Ошибка удаления записи ID=$id. " . $ex->getMessage());
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
        $this->render('admin', array(
            'model' => new Products(),
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Products $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'products-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
