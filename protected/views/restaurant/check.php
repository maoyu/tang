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
	'dataProvider'=>$model->searchUnchecked(),
	'cssFile' => Yii::app()->request->baseUrl. '/css/tang_uc_style.css',
	'itemsCssClass'=>'table table-hover table-uc',
	'emptyText'=>'没有数据',
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		// 'phone',
		// 'business_hour',
		'address',
		'county_id',	// TODO 直接显示中文的“区”，如涧西区。
		'is_checked',	// TODO 直接显示中文的‘通过’或“未通过'。
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
			'viewButtonImageUrl'=>false,
			'viewButtonLabel'=>'',
			'viewButtonOptions'=>array('class'=>'fa fa-search'),
			'viewButtonUrl'=>'Yii::app()->createUrl("details/$data->id")',
			'updateButtonImageUrl'=>false,
			'updateButtonLabel'=>'',
			'updateButtonOptions'=>array('class'=>'fa fa-pencil'),
			'deleteButtonImageUrl'=>false,
			'deleteButtonLabel'=>'',
			'deleteButtonOptions'=>array('class'=>'fa fa-times-circle'),
		),
	),
	'pagerCssClass'=>'tang-pager',
	'pager'=>array('header'=>'',
			'prevPageLabel'=>'«',
			'nextPageLabel'=>'»',
			'firstPageLabel'=>'首页',
			'lastPageLabel'=>'末页',
			'cssFile'=>Yii::app()->request->baseUrl.'/css/pager.css'
			),
)); ?>
