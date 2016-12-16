<?php
/* @var $this OrdersController */
/* @var $model Orders */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'id' => 'filter-form',
	'enableAjaxValidation'=>true,
)); ?>


	<div class="row">
		<h4>Найти платеж</h4>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'product_id'); ?>
		<?php echo $form->dropDownList($model, 'product_id',
			CHtml::listData(Products::model()->findAll(), 'product_id', 'name'), [
				'empty' => 'Выберите пакет услуг', 'options' => isset($model->product_id) ? [$model->product_id => ['selected' => true]] : []
			]); ?>
		<?php echo $form->error($model,'product_id'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Период', 'from_date', []); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'from_date',
				'value'=>$model->from_date,
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			));
		?>
		<b> &ndash; </b>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'to_date',
				'value'=>$model->to_date,
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'yy-mm-dd',

				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			));
		?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton('Найти'); ?>
		<br />
		<a href="<?php echo Yii::app()->createUrl('orders'); ?>">Сбросить</a>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->