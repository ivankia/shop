<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Пакеты услуг'=>array('index'),
	'Список',
);

$this->menu=array(
	array('label'=>'Создать новый', 'url'=>array('create')),
);
?>

<h1>Список пакетов услуг</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'products-grid',
	'dataProvider'=>$model->search(),
	'summaryText' => '',
	'pager' => [],
	'columns'=>array(
		'product_id',
		[
			'name' => 'image',
			'type' => 'raw',
			'value' => function($data) { echo CHtml::image($this->baseImgUrl .  '/' . $data->product_id . '.' . $data->image_ext, $data->name); }
		],
		'name',
		'description',
		[
			'name' => 'price',
			'value' => function ($data) { echo $this->priceFormat($data->price); }
		],
		[
			'name' => 'currency_id',
			'type' => 'raw',
			'value' => function($data) { echo Currency::model()->find('currency_id=:currency_id', ['currency_id' => $data->currency_id])->code; }
		],
		[
			'name' => 'modified_at',
			'type' => 'html',
			'value' => function($data) { echo date('Y-m-d', strtotime($data->modified_at)); }
		],
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
