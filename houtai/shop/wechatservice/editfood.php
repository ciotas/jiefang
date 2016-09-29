<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditFood{
	public function getFoodtypesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodtypesByShopid($shopid);
	}
	public function getZonesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getZonesByShopid($shopid);
	}
	public function getOneFoodData($foodid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getOneFoodData($foodid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$editfood=new EditFood();
$openid=$_REQUEST['openid'];

$shopid=$editfood->getShopidByOpenid($openid);
// $shopid="556df2f616c109ee3e8b4578";
$zonearr=$editfood->getZonesByShopid($shopid);
$foodtypearr=$editfood->getFoodtypesByShopid($shopid);
$onefood=array();
$typeno="0";
$foodid="";
if(isset($_GET['foodid'])){
	$foodid=$_GET['foodid'];
	$typeno=$_GET['typeno'];
	$onefood=$editfood->getOneFoodData($foodid);
}
// print_r($onefood);exit;
?>
<script type="text/javascript">
<!--
function shutup(){
// 	val=document.getElementById("mustorder").checked;
// 	if(val){
// // 		alert(document.getElementById("showout").checked);
// 		document.getElementById("cusee").style.display="none";
// 	}else{
// 		document.getElementById("cusee").style.display="block";
// 	}
}
//-->
</script>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>我的菜单</title>

	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta content="" name="description" />

	<meta content="" name="author" />

	<link href="../media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style.css" rel="stylesheet" type="text/css"/>
	
	<link href="../media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="../media/css/timeline.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="../media/css/bootstrap-toggle-buttons.css" />

</head>

<!-- END HEAD -->
<body>
<div class="page-container row-fluid">
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->

				</div>
				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					

				</div>

				<!-- END PAGE HEADER-->
	<div class="alert alert-error">
					<button class="close" data-dismiss="alert"></button>
					<strong>红色字体部分为必填项，否则会无法正常下单。</strong>
				</div>
				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">商品编辑</span>

								</div>

							</div>
					
							<div class="portlet-body form">
								<div class="tabbable portlet-tabs">
										<br>
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="../interface/addwechatfood.php" method="post" class="form-horizontal">
												<input type="hidden" name="openid" value="<?php echo $openid;?>">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="foodid" value="<?php echo $foodid;?>">
												<input type="hidden" name="typeno" value="<?php echo $typeno;?>">
												<div class="control-group">

													<label class="control-label" style="color: red">美食名</label>

													<div class="controls">

														<input type="text" placeholder="必填，美食名"  name="foodname" class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['foodname'];}?>" />

														<span class="help-inline"></span>

													</div>

												</div>
												<div class="control-group">

													<label class="control-label" >英文名</label>

													<div class="controls">

														<input type="text" placeholder="选填"  name="foodengname" class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['foodengname'];}?>" />

														<span class="help-inline"></span>

													</div>

												</div>
												<div class="control-group">
													<label class="control-label">排序序号</label>
													<div class="controls">
														<input type="text" placeholder="选填，排序序号"  name="sortno" id="sortno"  class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['sortno'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">编码</label>
													<div class="controls">
														<input type="text" placeholder="选填，编码"  name="foodcode" id="foodcode"  class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['foodcode'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">价格</label>
													<div class="controls">
														<input type="text" placeholder="必填，数字类型 请不要带单位"  name="foodprice" id="foodprice"  class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['foodprice'];}?>">
														<span style="color:red">必填，数字类型，请不要带单位</span>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">点菜单位</label>
													<div class="controls">
														<input type="text" placeholder="必填，点菜单位"  name="orderunit" id="orderunit"  class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['orderunit'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">计量单位</label>
													<div class="controls">
														<input type="text" placeholder="必填，计量单位"  name="foodunit" id="foodunit"  class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['foodunit'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">口味</label>
													<div class="controls">
														<input type="text" placeholder="多种用顿号、分隔"  name="foodcooktype" id="foodcooktype"  class="m-wrap span6" value="<?php if(!empty($onefood)){echo $onefood['foodcooktype'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">档口</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="zoneid" name="zoneid" >
<!-- 															<option value="0">---请选择档口---</option> -->
															<?php foreach ($zonearr as $pkey=>$pval){?>
															<option value="<?php echo $pval['zoneid'];?>" <?php if($onefood['zoneid']==$pval['zoneid']){echo "selected";}?>><?php echo $pval['zonename']?></option>
															<?php }?>
														</select>
														<span style="color:red">必选，菜品所在档口</span>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">类别</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="ftid" name="ftid">
<!-- 															<option value="0">---请选择类别---</option> -->
															<?php foreach ($foodtypearr as $pkey=>$pval){?>
															<option value="<?php echo $pval['ftid'];?>" <?php if($onefood['ftid']==$pval['ftid']){echo "selected";}?>><?php echo $pval['ftname']?></option>
															<?php }?>
														</select>
														<span style="color:red">必选，菜品所属类别</span>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">优惠</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="fooddisaccount"  name="fooddisaccount[]"  <?php if(!empty($onefood)){if($onefood['fooddisaccount']=="1"){echo "checked";}}else{echo "checked";}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">称重</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="isweight"  name="isweight[]"   <?php if(!empty($onefood)){if($onefood['isweight']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">推荐</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="isweight"  name="ishot[]"  <?php if(!empty($onefood)){if($onefood['ishot']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">套餐</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="ispack"  name="ispack[]"  <?php if(!empty($onefood)){if($onefood['ispack']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">估清</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="foodguqing"  name="foodguqing[]"  <?php if(!empty($onefood)){if($onefood['foodguqing']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">酒水标记</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="autostock"  name="autostock[]"  <?php if(!empty($onefood)){if($onefood['autostock']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">服务员可见</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="showserver"  name="showserver[]"  <?php if(!empty($onefood)){if($onefood['showserver']=="1"){echo "checked";}}else{echo "checked";}?>  />
													</div>
													</div>
												</div>
												<div class="control-group" id="cusee">
												<label class="control-label">消费者可见</label>
												<div class="controls">
													<div class="info-toggle-button">
														<input type="checkbox" class="toggle"  id="showout"  name="showout[]"  <?php if(!empty($onefood)){if($onefood['showout']=="1"){echo "checked";}}else{echo "checked";}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">必点菜</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="mustorder"  name="mustorder[]" onchange="shutup()" <?php if(!empty($onefood)){if($onefood['mustorder']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">按人数</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="orderbynum"  name="orderbynum[]"  <?php if(!empty($onefood)){if($onefood['orderbynum']=="1"){echo "checked";}}?>  />
													</div><span style="color:orange">必点菜开启才有效</span>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">简介</label>
													<div class="controls">
														<textarea  placeholder="选填，简介（不超过500字）" rows="3"  name="foodintro" id="foodintro"  class="m-wrap span6" ><?php if(!empty($onefood)){echo $onefood['foodintro'];}?></textarea>
													</div>
												</div>
												<div class="form-actions">

													<button type="submit" class="btn blue"><i class="icon-ok"></i> 保存</button>

													<button type="button" class="btn"  onclick="window.location.href='./foodmanage.php?typeno=<?php echo $typeno;?>&openid=<?php echo $openid;?>' ">取消</button>

												</div>

											</form>

											<!-- END FORM-->  

										</div>
									

								</div>

							</div>

						</div>

						<!-- END SAMPLE FORM PORTLET-->

					</div>

				</div>

				<!-- END PAGE CONTENT-->         

			</div>

			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->  

	</div>

	<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014-2015 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>

		<div class="footer-tools">

			<span class="go-top">

			<i class="icon-angle-up"></i>

			</span>

		</div>

	</div>

	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="<?php echo $base_url;?>media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="<?php echo $base_url;?>media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="<?php echo $base_url;?>media/js/excanvas.min.js"></script>

	<script src="<?php echo $base_url;?>media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="<?php echo $base_url;?>media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.js" type="text/javascript"></script>   

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.russia.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.world.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.europe.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.germany.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.usa.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.flot.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.flot.resize.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.pulsate.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/date.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/daterangepicker.js" type="text/javascript"></script>     

	<script src="<?php echo $base_url;?>media/js/jquery.gritter.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/fullcalendar.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.sparkline.min.js" type="text/javascript"></script>  

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="<?php echo $base_url;?>media/js/app.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/index.js" type="text/javascript"></script>    
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/chosen.jquery.min.js"></script>
	<script src="<?php echo $base_url;?>media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="<?php echo $base_url;?>media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.input-ip-address-control-1.0.min.js"></script>
	
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.multi-select.js"></script>  
	<script src="<?php echo $base_url;?>media/js/form-components.js"></script>  
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-colorpicker.js"></script>  
	<script src="<?php echo $base_url;?>media/js/ui-modals.js"></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.toggle.buttons.js"></script>
	
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

		   App.init(); // initlayout and core plugins

		   Index.init();
// 		   Search.init();
		   
		   Index.initJQVMAP(); // init index page's custom scripts

		   Index.initCalendar(); // init index page's custom scripts

		   Index.initCharts(); // init index page's custom scripts

		   Index.initChat();

		   Index.initMiniCharts();

		   Index.initDashboardDaterange();
		   FormComponents.init();
		   UIModals.init();
		   
// 		   Index.initIntro();

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>