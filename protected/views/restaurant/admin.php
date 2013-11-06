<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */

// $this->breadcrumbs=array(
// 	'Restaurants'=>array('index'),
// 	'Manage',
// );

// $this->menu=array(
// 	array('label'=>'List Restaurant', 'url'=>array('index')),
// 	array('label'=>'Create Restaurant', 'url'=>array('create')),
// );

// Yii::app()->clientScript->registerScript('search', "
// $('.search-button').click(function(){
// 	$('.search-form').toggle();
// 	return false;
// });
// $('.search-form form').submit(function(){
// 	$('#restaurant-grid').yiiGridView('update', {
// 		data: $(this).serialize()
// 	});
// 	return false;
// });
// ");
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'restaurant-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		// 'phone',
		// 'business_hour',
		'address',
		'county_id',	// TODO 直接显示中文的“区”，如涧西区。
		/*
		'area',
		'is_shutdown',
		'image_url',
		'latitude',
		'longitude',
		'description',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
