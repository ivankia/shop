<?php $form=$this->beginWidget('CActiveForm', []); ?>
<div class="row left" style="width: 25%; text-align: center; border: 1px solid darkgrey; border-radius: 10px; min-height: 200px; margin: 0 10px;  padding: 10px 0;">
    <div style="width: 100%; height: 60px; text-align: center; vertical-align: middle;"><?= $data->image_ext ? CHtml::image($this->baseImgUrl .  '/' . $data->product_id . '.' . $data->image_ext, $data->name) : 'Отсутствует' ?></div>
    <div style="padding: 10px 0; font-size: 18px; font-weight: bold;"><?= $data->name ?></div>
    <div style="padding: 10px 0; font-size: 14px"><?= $data->description ?></div>
    <div style="padding: 10px 0;"><?= CHtml::link('Купить', Yii::app()->createUrl('site/checkout/' . $data->product_id), ['class' => 'btn']); ?></div>
</div>
<?php $this->endWidget(); ?>

