<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Пакеты услуг'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Создать новый', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->product_id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->product_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Пакет услуг "<?php echo $model->name; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'product_id',
		[
			'name' => 'image',
			'type' => 'raw',
			'value' => ($model->image_ext ? CHtml::image($this->baseImgUrl .  '/' . $model->product_id . '.' . $model->image_ext, $model->name) : 'Отсутствует')
		],
		'name',
		'description',
		[
			'name' => 'price',
			'type' => 'raw',
			'value' => $this->priceFormat($model->price)
		],
		'currency_id',
		'created_at',
		'modified_at',
	),
)); ?>
