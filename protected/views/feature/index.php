<?php
/* @var $this FeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Features',
);

$this->menu=array(
	array('label'=>'Create Feature', 'url'=>array('create')),
	array('label'=>'Manage Feature', 'url'=>array('admin')),
);
?>

<h1>Features</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>