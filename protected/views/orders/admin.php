<?php
/* @var $this OrdersController */
/* @var $model Orders */

$this->breadcrumbs=array(
	'История платежей' => array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#orders-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>История платежей</h1>

<div class="search-form">
<?php $this->renderPartial('_search', array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'orders-grid',
	'dataProvider'=>$model->search(),
	'summaryText' => 'Показано с {start} по {end} из {count}',
	'emptyText' => 'Нет записей.',
	'pager' => [
		'header' => 'Страницы: ',
	],
	'columns' => [
		'email',
		[
			'name' => 'product_id',
			'value' => function ($data) { echo $data->product->name; },
		],
		[
			'name' => 'created_at',
			'value' => function ($data) { echo strlen($data->created_at) ? date('Y-m-d', strtotime($data->created_at)) : ''; },
		],
		[
			'header' => 'Текущая цена',
			'name' => 'price',
			'value' => function ($data) { echo $this->priceFormat($data->product->price) . ' ' . $data->currency->code; },
		],
		[
			'name' => 'price',
			'value' => function ($data) { echo $this->priceFormat($data->price) . ' ' . $data->currency->code; },
		],
	],
)); ?>