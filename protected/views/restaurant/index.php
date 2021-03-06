<?php
/* @var $this RestaurantController */
/* @var $dataProvider CActiveDataProvider */
?>


<div class="tang-tooltip">
	<div class="bottomtitle"></div>
	<div class="content">

	</div>
</div>


<div class="restaurant-content">
	<?php if (! empty($areaMenu) &&  count($areaMenu) >= 1) { ?>
	<div class="county-menu-title"><span>区域</span>
		<div id="county-menu">
			<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$areaMenu,
			'activeCssClass'=>false,
			//'firstItemCssClass'=>'active',
			)); ?>
		</div><!-- area-menu -->
	</div>
	<div class="clear"></div>
	<?php } ?>


	<?php if (! empty($typeMenu)) { ?>
	<div class="county-menu-title"><span>分类</span>
		<div id="area-menu">
			<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$typeMenu,
			'activeCssClass'=>'active',
			)); ?>
		</div><!-- type-menu -->
	</div><div class="clear"></div>
	<?php } ?>

	<div>
		<div class="restaurant-left">
			<?php

			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'_view',
				'cssFile' => Yii::app()->request->baseUrl. '/css/_restaurant_item.css',
				'template' => "{pager}\n{summary}\n{items}\n{pager}",
				'ajaxUpdate'=> false,
		// 'pagerCssClass'=>'tang-pager',
		// 'pager'=>array('header'=>'',
		// 		'prevPageLabel'=>'«',
		// 		'nextPageLabel'=>'»',
		// 		'firstPageLabel'=>'首页',
		// 		'lastPageLabel'=>'末页',
		// 		'cssFile'=>Yii::app()->request->baseUrl.'/css/pager.css'),

				));
				?>
				<div class="list-footer-load"><span><i class="fa fa-spinner fa-spin fa-2" id="icon-load"></i> 正在加载中...</span>
				</div>
			</div>
			<div class="right-content">
				<div id="last_votes">
					<span class="header">最新评分</span>
					<div class="content">
						<ul>
							<?php foreach ($lastVotes as $value) {
								?>
								<li>
									<a href="#"><img src="<?php echo $value->user->image_url;?>" title="<?php echo $value->user->nick_name; ?>"/></a>
									<div>
										<span class="source"><?php echo $value->user->nick_name; ?> 添加了评分</span>
										<span class="title"><?php echo CHtml::link('<span>'.$value->restaurant->name.'</span>', array('comment/index', 'restaurantId'=>$value->restaurant_id),array('target'=>'_blank')); ?></span>
										<div class="rating-widget">
											<span class="rating-widget-lable">评分:</span><!--<span class="rating-imdb " style="width: 0px; display:block;"></span>-->
											<div class="rating-list m" isclick="false" data-rating-default="<?php echo sprintf("%.1f",CHtml::encode($value->rating)); ?>" 
												data-clicknum="0" 
												data-user="<?php echo Yii::app()->user->id ?>"
												data-id="<?php echo CHtml::encode($value->restaurant->id);?>"
												data-userlogin="<?php echo Yii::app()->user->isGuest ?>">
												<span class="rating-stars">
													<a class="rating-icon star-on" data-title="不推荐"><span>1</span></a>
													<a class="rating-icon star-on" data-title="聊胜于无"><span>2</span></a>
													<a class="rating-icon star-on" data-title="日常饮食"><span>3</span></a>
													<a class="rating-icon star-on" data-title="值得品尝"><span>4</span></a>
													<a class="rating-icon star-on" data-title="汤中一绝"><span>5</span></a>
												</span>
												<span class="rating-rating">
													<span class="fonttext-shadow-2-3-5-000 value"><?php echo sprintf("%.1f",CHtml::encode($value->rating)); ?></span>
													<span class="grey">/</span>
													<span class="grey">5</span>
												</span>
												<span class="rating-cancel ">
													<a title="删除">
														<span>X</span>
													</a>
												</span>
											</div>		
										</div>
										<div class="clear"><!--清除浮动--></div>
									</div>
								</li>
								<?php
							} ?>
						</ul>
					</div>
				</div>
				<div id="last_comments">
					<span class="header">最新评论</span>
					<div class="content">
						<ul>
							<?php foreach ($lastComments as $value) {
								?>
								<li>
									<a href="#"><img src="<?php echo $value->user->image_url;?>"  title="<?php echo $value->user->nick_name; ?>" align="left"/></a>
									<div>
										<span class="source"><?php echo $value->user->nick_name; ?> 添加了评论</span>
										<span class="title"><?php echo CHtml::link(CHtml::encode($value->restaurant->name), array('comment/index', 'restaurantId'=>$value->restaurant_id),array('target'=>'_blank')); ?></span>
										<span><?php echo mb_strlen($value->content, "UTF-8") >= 71 ? mb_substr($value->content,0,71, "UTF-8").'...':$value->content;?></span>
									</div>
								</li>
								<?php
							} ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<script type="text/javascript">
$(function(){
/*
 *分页
 *@pageCurrent 从1开始是第二页，0是第一页已经在面页加载时加载过
 */

var count=<?php echo $count;?>,
area=<?php echo $area;?>,
type=<?php echo $type;?>,
county=<?php echo $county;?>,
pageCurrent=1,
limit=10,
itemIndex=10;
var isdataload=true;

if (count>limit) {
	$(window).scrollTop(0);
	$(window).scroll(function(event){
		event.preventDefault();
		if (itemIndex>=count) {
			return false;
		}
		if (isdataload && $(window).scrollTop()+10 >= $(document).height() - $(window).height()){
			if (isdataload) {
				isdataload=false;
					nextPage();
			}
		}

	});
}
	function nextPage(){
		if (count>limit) 
		{
			$(".list-footer-load>span").show();
			$.get("<?php echo $this->createUrl('restaurant/indexByPage');?>",{county:county,area:area,type:type,page:pageCurrent,limit:limit},function(data){
				if (data.length<limit){
				    if(data!=null){
				    	setTimeout(function(){
				    		loadData(data);
				    		$(".list-footer-load>span").hide();
				    		isdataload=false;
				    		pageCurrent++;
				    	},1000);
				    }
				    
				}else{
				    if(data!=null){
				        setTimeout(function(){
				    		loadData(data);
				    		$(".list-footer-load>span").hide();
					        isdataload=true;
					        pageCurrent++;
				    	},1000);
				    }
				    
				}
			},"json");
		}
	}

	//加载分页时，动态DOM
	function loadData(data)
	{
		
		var strData='';
		for(var i in data){
			itemIndex++;
			var item=data[i];
			//console.log("a="+item["name"]);
			strData+=	'<div class="view-item">'+
			'<span class="ranking badge1">'+itemIndex+'</span>';
			if (item["restaurant"]["image_url"]) {
				strData+='<a href="<?php echo $this->createUrl("comment/index",array("restaurantId"=>"")); ?>'+item["restaurant"]["id"]+'" class="restaurant_img"  target＝"_blank"><img src="'+item["restaurant"]["image_url"]+'"></a>';
			}else{
				strData+='<span class="restaurant_defalut_img"><i class="fa fa-smile-o"></i></span>';
			}
			strData+='<div class="restaurant-detail">'+
			'<ul>'+
			'<li>'+
			'<strong>'+
			'<a href="<?php echo $this->createUrl("comment/index",array("restaurantId"=>"")); ?>'+item["restaurant"]["id"]+'" target="_blank">'+item["restaurant"]["name"]+'</a>'+
			'</strong>'+
			'</li>'+
			'<li>'+
			'<span class="title">地址:</span>'+
			'<span class="detail-value">'+item["restaurant"]["address"]+'</span> ';
			if (item["restaurant"]["coordinate"]!=0){
				strData+='<a href="<?php echo $this->createUrl("comment/index",array("restaurantId"=>"")); ?>'+item["restaurant"]["id"]+'" title="看看汤馆的位置"  target="_blank"><i class="fa fa-map-marker"></i></a>';
			}
			strData+='</li>';
			//console.log("features="+(item["features"]));
			if(item["features"]){
				strData+='<li><span class="title">特色:</span>';
				for(var b in item["features"]){
					strData+='<span class="feature">'+item["features"][b]["name"]+'</span>';
				}
				strData+='</li>';
			}

			strData+='<li>'+
			'<div class="rating-widget">'+
			'<span class="rating-widget-lable">平均得分:</span>'+
			'<div class="rating-list m" isclick="false" data-rating-default="'+(new Number(item["restaurant"]["average_points"])).toFixed(1)+'" '+
			'data-clicknum="0" '+
			'data-user="<?php echo Yii::app()->user->id ?>"'+
			'data-id="'+item["restaurant"]["id"]+'" '+
			'data-name="'+item["restaurant"]["name"]+'" '+
			'data-userlogin="<?php echo Yii::app()->user->isGuest ?>">'+
			'<span class="rating-stars">'+
			'<a class="rating-icon star-on" data-title="不推荐"><span>1</span></a>'+
			'<a class="rating-icon star-on" data-title="聊胜于无"><span>2</span></a>'+
			'<a class="rating-icon star-on" data-title="日常饮食"><span>3</span></a>'+
			'<a class="rating-icon star-on" data-title="值得品尝"><span>4</span></a>'+
			'<a class="rating-icon star-on" data-title="汤中一绝"><span>5</span></a>'+
			'</span>'+
			'<span class="rating-rating">'+
			'<span class="fonttext-shadow-2-3-5-000 value">'+(new Number(item["restaurant"]["average_points"])).toFixed(1)+'</span>'+
			'<span class="grey">/</span>'+
			'<span class="grey">5</span>'+
			'</span>'+
			'<span class="rating-cancel ">'+
			'<a title="删除">'+
			'<span>X</span>'+
			'</a>'+
			'</span>'+
			'</div>';

			if (item["restaurant"]["votes"]>0){
				strData+='<div class="rating-count-p">'+
				'<span>'+item["restaurant"]["votes"]+'</span>个评分'+
				'</div>';
			}
			strData+='</div>'+
			'<div class="fenxiang"><a class="sina" href="http://service.weibo.com/share/share.php?url=http://www.laotangguan.com&pic=&title=原来汤馆也可以这么玩，快来看看我已经对【'+item["restaurant"]["name"]+'】打过分了&appkey=3495571392&ralateUid="  target="_blank"><i class="fa fa-share"></i> 分享</a></div>'+
			'<div class="clear"><!--清除浮动--></div>'+
			'</li>'+
			'<div class="clear"></div>'+
			'</ul>'+
			'</div>';

			<?php if (Yii::app()->user->isAdmin) {
				?>	
				strData+='<!--编辑功能-->';
				strData+='<div class="view-edit-btn" >'+
				'<div class="view-edit-header"><a title="'+item["restaurant"]["name"]+'" class="fa fa-pencil"></a>'+
				'<ul>'+
				'<li class="feature-btn">贴标</li>'+
				'<li class="itemEdit-btn" data-item-url="/restaurant/update/id/'+item["restaurant"]["id"]+'"><a href="/restaurant/update/id/'+item["restaurant"]["id"]+'" target="_blank">修改</a></li>'+
				'</ul>'+
				'</div>'+
				'<div class="feature-content" data-item-id="'+item["restaurant"]["id"]+'" data-selected-items="';
				for(var a in item["features"]){
					strData+=item["features"][a]["id"]+',';
				}
				strData+='">'+
				'<div class="feature-content-content"></div>'+
				'<div class="feature-content-footer"><button id="feature-edit-submit">提交</button><button id="feature-edit-close">关闭</button></div>'+
				'</div>'+
				'</div>';
				<?php } ?>
				strData+='<div style="clear:both;"></div>'+
				'</div>';
			}

	//console.log(strData);
	$(".list-view .items").append(strData);
	//var rating_list_dome1=$(strData).find(".rating-widget .rating-list");alert(rating_list_dome1.eq(0).html());
	var rating_list_dome1=$(".rating-widget .rating-list",$(".restaurant-left"));
	tang_main_rating(rating_list_dome1,true);
	<?php if (Yii::app()->user->isAdmin){?>
	editbutton();
	<?php } ?>
	
}

var rating_list_dome=$(".rating-widget .rating-list",$(".restaurant-left"));
tang_main_rating(rating_list_dome,true);
tang_main_rating($(".rating-widget .rating-list",$(".right-content")),false);
$(".restaurant-left .view-item>.ranking:lt(3)").removeClass('badge1').addClass('badge2');//removeClass('badge1').
function tang_main_rating(rating_list,ismouseover)
{
/*
 *评分组件 @rating_list 为评分组件集，@ismouseover是否加载鼠移上去事件
 *@当鼠标移到星星上（A标签），就给小于等于当前鼠标位置的元素加上选中的样式，
 *大于当前位置的元素为原始样式，同时给class=value的span(评分值)赋值
 *@当鼠标移出rating-list（星星的父容器）时，判断是否评分成功，给给定数量的星星加上评分的样式，
 *如果未评分就还原默认的数字
 */

 rating_list.each(function(){

	var a_this=$(this);//当前遍历rating-list的jqueryDOM对象
	var a_arr=$(".rating-stars a",a_this);//取出当前rating-list下的所有a对象 
	var raing_value=$(".rating-rating>.value",a_this);//评分的值
	var raing_default=a_this.attr("data-rating-default");//评分的默认值
	//raing_default=parseFloat(raing_default)==0? '-':raing_default;
	ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);

	if (ismouseover) {
		a_this.unbind("hover");
		a_this.parent().parent().parent().parent().parent().hover(function(){
			$(this).find(".fenxiang").show();
		},function(){
			$(this).find(".fenxiang").hide();
		});
		a_arr.unbind('click'); 
		//单击星星时发生
		a_arr.live("click",function(event){
			event.stopPropagation();
			
		if (a_this.attr("isclick")=="true") {
			return false;
		}
		var i=parseInt($("span",$(this)).text());
		var selected_a=$(".rating-stars a:lt("+i+")",a_this);
		var no_selected_a=$(".rating-stars a:gt("+(i-1)+")",a_this);
		//event.preventDefault()
		//event.stopPropagation();
		//console.log("tagname="+$(this)[0].tagName+" user_id="+a_this.attr("data-user")+"  data-id="+a_this.attr("data-id")+"  value="+raing_value.text());

		if (a_this.attr('data-user')=="") {
			//点击登陆弹出模态窗口
			loginModal();
			return false;
		}
		a_this.attr("data-clicknum",parseInt($("span",$(this)).text()));
		selected_a.removeClass();
		selected_a.addClass("rating-icon rating-off");
		var rating_cancel=$(".rating-cancel",a_this);
		rating_cancel.addClass('rating-pending');
		//执行评分的ajax
			
		//console.log("user_id="+a_this.attr("data-user")+"  data-id="+a_this.attr("data-id")+"  value="+raing_value.text());
		
		//增加过渡窗口
		var alertModalDialog=$(".alertModal-dialog");
		var alertModalTitle=$(".alertModal-header .alertModal-title");
		var alertModalBody=$(".alertModal-dialog .alertModal-body");
		var alertModalBody_ratinglist=alertModalBody.find(".rating-list");
		var commentContent=$("#commentContent",alertModalBody);
			commentContent.val("");
		//alert(alertModalBody_ratinglist.find("a").length);
		var selected_a=$("a:lt("+i+")",alertModalBody_ratinglist);
		selected_a.removeClass();
		selected_a.addClass("rating-icon rating-off");
		alertModalBody.find(".rating-value").text(i);
		alertModalBody.find(".value-desc").text($(this).attr('data-title'));
		var no_selected_a=$("a:gt("+(i-1)+")",alertModalBody_ratinglist);
		no_selected_a.removeClass();
		no_selected_a.addClass("rating-icon star-on");

		alertModalTitle.text(a_this.attr('data-name'));
		//alertModalBody.text('您将对'+a_this.attr('data-name')+'打'+raing_value.text()+'分('+$(this).attr('data-title')+')');

		alertModalDialog.show();
		$(".alertModal-footer #alertModalClose").click(function(){
			alertModalDialog.hide();
			rating_cancel.removeClass('rating-pending');
			a_this.attr("data-clicknum","0");
			raing_value.text(raing_default);
			//console.log(rating_cancel_result+"abc");
			ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);
		});

		$(".alertModal-header .close").click(function(){
			alertModalDialog.hide();
			rating_cancel.removeClass('rating-pending');
			a_this.attr("data-clicknum","0");
			raing_value.text(raing_default);
			ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);
			
			$(".alertModal-footer #alertModalSubmit").removeAttr('disabled');
			$(".alertModal-footer #alertModalSubmit").html('提交');
		});
		$(".alertModal-footer #alertModalSubmit").unbind('click');
		$(".alertModal-footer #alertModalSubmit").bind("click",function(event){
			event.stopPropagation();
			var btnsubmit_this=$(this);
			btnsubmit_this.attr("disabled","disabled");//增加按钮状态锁定
			btnsubmit_this.html('<span class="btn-loading"><i class="fa fa-spinner fa-spin fa-2" id="icon-load"></i> 正在提交中...</span>');
			
			//提交评分的开始
			$.post("<?php echo $this->createUrl('vote/create')?>",{Vote:{user_id:a_this.attr("data-user"),restaurant_id:a_this.attr("data-id"),
			rating:raing_value.text()},Comment:commentContent.val()},function(resultdata){
			//console.log("aa="+resultdata.voteid);

				if (resultdata.code==0) {//0：成功，-2：频率过快,点评失败：-3
					a_this.attr('voteid',resultdata.voteid);//将voteid邦定到dom对象上
					rating_cancel.removeClass('rating-pending').addClass("rating-icon rating-your");
					var tooltip=$(".tang-tooltip"); 
					rating_cancel.hover(function(){
						var a_offset=$(this).offset();						
						$("div:eq(0)",tooltip).removeClass().addClass("lefttitle");
						tooltip.find('.content').text("你要删除打分吗？");
						tooltip.css({'top':a_offset.top-$(this).height()/2,'left':a_offset.left+$(this).width()+10}).show();
					},function(){
						tooltip.hide();
					});			
					rating_cancel.one('click',function(){
						rating_cancel.removeClass('rating-icon rating-your').addClass("rating-pending");
						$.post("/vote/delete",{Vote:{id:a_this.attr("voteid")}},function(rating_cancel_result){								
							if (rating_cancel_result.msg==="0") {
								a_this.removeAttr('voteid');
								rating_cancel.removeClass('rating-pending');
								a_this.attr("data-clicknum","0");
								raing_value.text(raing_default);
								//console.log(rating_cancel_result+"abc");
								ratingInit(a_this,"rating-icon rating-init",Math.round(parseFloat(raing_default)),raing_value);
							}else{
							//服务器出错

							}
						},"json");
					});
					//还原默认值
					btnsubmit_this.removeAttr('disabled');
					btnsubmit_this.html('提交');
					alertModalDialog.hide();

				}else if(resultdata.code==-2){
					var i=resultdata.delay;
					var votetime=setInterval(function(){
						i--;
						btnsubmit_this.html('<span class="btn-loading">'+resultdata.msg+'('+i+')秒</span>');
						if (i<=0) {
							clearInterval(votetime);
							btnsubmit_this.removeAttr('disabled');
							btnsubmit_this.html('提交');
						};
					},1000);

				}else{
				//服务器出错
				}
			},"json");
			//提交评分的结束
			a_this.attr("isclick","true");
		});
			event.stopPropagation();
		});

			a_arr.hover(function(){
				var a_offset=$(this).offset();
				var tooltip=$(".tang-tooltip");
				$("div:eq(0)",tooltip).removeClass().addClass("bottomtitle");
				tooltip.find('.content').text($(this).attr('data-title'));
				tooltip.css({'top':a_offset.top-30,'left':a_offset.left-$(this).width()/2-20}).show();

				//当鼠标移到a标签上时的事件
				var i=parseInt($("span",$(this)).text());
				var selected_a=$(".rating-stars a:lt("+i+")",a_this);
				selected_a.removeClass();
				selected_a.addClass("rating-icon rating-hover");

				var no_selected_a=$(".rating-stars a:gt("+(i-1)+")",a_this);
				no_selected_a.removeClass();
				no_selected_a.addClass("rating-icon star-on");

				raing_value.text(i);
			},function(){
				$(".tang-tooltip").hide();
				a_this.attr("isclick","flase");
			});

			//当鼠标移出rating-list的矩形时根据状态还原星星的样式
			$(".rating-stars",a_this).bind("mouseout",function(){	
				var clicknum=a_this.attr("data-clicknum");
				if (clicknum=="0" && parseInt(raing_default)==0) {
					a_arr.removeClass();
					a_arr.addClass("rating-icon star-on");
					raing_value.text(parseInt(raing_default)==0?'-':raing_default);		
				}else if(clicknum=="0" && parseInt(raing_default)>0){
					ratingInit(a_this,"rating-icon rating-init",Math.round(raing_default),raing_value);
					raing_value.text(raing_default);
				}
				else{
					ratingInit(a_this,"rating-icon rating-off",parseInt(clicknum),raing_value);
					raing_value.text(clicknum);
				}
			});
	}

	});

	}

	function ratingInit(e_this,classname,i,evalue)
	{	
		if (i==0) {
			evalue.text("-");
		}

		var selected_a=$(".rating-stars a:lt("+i+")",e_this);
		selected_a.removeClass();
		selected_a.addClass(classname);
		var no_selected_a=$(".rating-stars a:gt("+(i-1)+")",e_this);
		no_selected_a.removeClass();
		no_selected_a.addClass("rating-icon star-on");
	}

		//清除评分
		function ratingCancelClick(event)
		{
			//console.log("a="+event.data.rating);
		}

		<?php if (Yii::app()->user->isAdmin) { ?>
			editbutton();
			/*当用户角色是管理员，就显示编辑功能*/
			function editbutton(){
				var btnedit_div=$(".view-edit-btn");
				$(".view-edit-header",btnedit_div).hover(function(){
					var d_this=$(this),p_this=d_this.parent();
					d_this.find("ul").show();
					d_this.find(".feature-btn").bind("click",function(){
					var feature_selected_items=$(".feature-content",p_this).attr('data-selected-items').split(',');
						//ajax加载数据
						$.get("/restaurantFeature/query",{},function(data){

							var t="<ul>";
							if (data) {
								$.each(data,function(a){
									if (isContain(feature_selected_items,data[a].id)) {
										t+='<li><label><input type="checkbox" value='+data[a].id+' checked="true" />'+data[a].name+'</label> </li>';
									}
									else{
										t+='<li><label><input type="checkbox" value='+data[a].id+' />'+data[a].name+'</label> </li>';
									}
								});
							}
							t+="</ul>";
							$(".feature-content .feature-content-content",p_this).html(t);
						},"json");


						$(".feature-content",p_this).css({'display':'block',
							'top':p_this.offset().top+25,
							'left':p_this.offset().left}).animate(
							{	
								width:'200px',
								minHeight:'200px',
								left:$(this).offset().left-$(this).width()-200,
								top:$(this).offset().top-25

							},200);
					});
					
				},function(){
					$(this).find("ul").hide();
					$(this).find(".feature-btn").unbind("click");
				});

				$("#feature-edit-close",btnedit_div).click(function(){
					hide_edit_btn_div($(this));
					
				});

				$("#feature-edit-submit",btnedit_div).click(function(){
					var d_this=$(this);
					var parent_edit_dom=$(this).parent().parent();
					
					var features_items_str="";
					$("input:checked",parent_edit_dom.find(".feature-content-content")).each(function(){
						features_items_str+=$(this).val()+",";		
					});
					features_items_str=features_items_str.substring(0,features_items_str.length-1);
					//console.log("parent_content="+parent_edit_dom.attr("data-item-id"));
					$.post("/feature/addrestaurantfeature",{Feature:{restaurant_id:parent_edit_dom.attr("data-item-id"),features:features_items_str}},function(data){
						if (data.success) {
							//当提交成功时关闭窗体
							hide_edit_btn_div(d_this);
							//刷新页面
							location="/index.php"+window.location.search;
							//当提交成功时动态更新特色数据

							//console.log("className="+$(".restaurant-detail>ul>li>ul:eq(0) li",parent_edit_dom.parent().parent()).eq(0).text());

							//$("<li>adfsadf</li>").appendTo($(".restaurant-detail>ul>li>ul:eq(0) li:eq(1)",parent_edit_dom.parent().parent()));
						}else
						{
							//提示错误信息
						}
					},"json");
				});
			}

	function hide_edit_btn_div(a)
	{
		a.parent().parent().hide(100,function(){
			$(this).css({'width':'100px','min-height':'50px','left':$(this).parent().offset().left,'top':$(this).parent().offset().top+25});
			$(".feature-content-content",a.parent().parent()).html('');
		});
	}

	//数组中是否包含一个元素
	function isContain(a,b)
	{
		for(var i in a)
		{
			if (a[i]==b) {
				return true;
			}
		}
		return false;
	}

	<?php } ?>

	});

</script>
