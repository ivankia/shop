<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->name=>array('view','id'=>$model->product_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Создать новый', 'url'=>array('create')),
	array('label'=>'Просмотреть', 'url'=>array('view', 'id'=>$model->product_id)),
	array('label'=>'Список пакетов услуг', 'url'=>array('admin')),
);
?>

<h1>Update Products <?php echo $model->product_id; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>