<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Пакеты услуг'=>array('index'),
	'Новый',
);

$this->menu=array(
	array('label'=>'Список пакетов услуг', 'url'=>array('admin')),
);
?>

<h1>Новый пакет услуг</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>