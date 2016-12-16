<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<h1>Добро пожаловать!</h1>
<h3>Подберите свой пакет услуг сейчас!</h3>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'ajaxUpdate' => false,
    'itemView' => '../products/_product',
    'summaryText' => '',
    'emptyText' => 'Пакеты услуг отсутствуют',
    'sorterHeader' => '',
    'sortableAttributes' => false,
    'htmlOptions' => [
        'class' => 'products-items'
    ],
    'itemsTagName' => 'div',
));
?>