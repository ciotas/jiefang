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
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>接单页面</title>

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
<style type="text/css">
.zhutio{
	width:80%;
	height:600px;
	border:1px solid #333;
	padding:20px;
	position:relative;
	left:10%;
	font-size:16px;
}
.zhutio label{
	font-size:16px;
}
.biaogeo{
	height:120px;
	overflow-y:scroll;
	padding-bottom:20px;
	border:1px solid #DAD4D4;
}
.jiedano{
	padding-left:40%;

}
.jiedanos{
	padding-left:40%;
	position:absolute;
	bottom:10px;
}
.dan{
	border:1px solid #DAD4D4;
	margin-top:10px;
	position:relative;
	padding:0 6px;
}
</style>

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
				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480">接单页面</span>

								</div>

							</div>
						</div>
					</div>
				</div>
</div>
<!-- 主体部分 -->
<div class="zhutio">
	<div>
		<label>日期</label>
		<input type="date" name="日期" class="m-wrap span5"/>
		<button class="btn blue">查询</button>
	</div>
	<div>
		<div class="jiedano">
			<div class="btn yellow">未接单</div>
			<div class="btn yellow">已接单</div>
		</div>
		<div class="weijie tab-pane dan">
    		<div class="control-group">
    			<label class="control-label">单号：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
    		<div class="control-group">
    			<label class="control-label">昵称：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
    		<div class="control-group">
    			<label class="control-label">地址：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
    		<div class="control-group">
    			<label class="control-label">电话：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
				<div class="portlet-body biaogeo">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>菜品</th>
								<th>数量</th>
								<th>总价</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>小白菜</td>
								<td>3</td>
								<td>23</td>
							</tr>
							<tr>
								<td>小白菜</td>
								<td>3</td>
								<td>23</td>
							</tr>
							<tr>
								<td>小白菜</td>
								<td>3</td>
								<td>23</td>
							</tr>
							<tr>
								<td>小白菜</td>
								<td>3</td>
								<td>23</td>
							</tr>
						</tbody>
					</table>
				</div>
			<div class="jiedanos">
    			<button  class="btn ju"> 拒单 </button>
    			<button  class="btn green"> 接单 </button>
			</div>
		</div>
		
	<div>
		<div class="weijie tab-pane dan">
    		<div class="control-group">
    			<label class="control-label">单号：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
    		<div class="control-group">
    			<label class="control-label">昵称：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
    		<div class="control-group">
    			<label class="control-label">地址：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
    		<div class="control-group">
    			<label class="control-label">电话：</label>
    			<div class="controls">
    				<input type="text" name="" class="m-wrap span5">
    			</div>
    		</div>
				<div class="portlet-body biaogeo">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>菜品</th>
								<th>数量</th>
								<th>总价</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>小白菜</td>
								<td>3</td>
								<td>23</td>
							</tr>
						</tbody>
					</table>
				</div>
			<div class="jiedanos">
    			<button  class="btn ju"> 拒单 </button>
    			<button  class="btn green"> 接单 </button>
			</div>
		</div>

<!-- 		<div class="yijie"> -->
<!-- 			<label>单号：</label> -->
<!-- 			<input type="text" name=""> -->
<!-- 			<label>昵称：</label> -->
<!-- 			<input type="text" name=""> -->
<!-- 			<label>地址：</label> -->
<!-- 			<input type="text" name=""> -->
<!-- 			<label>电话：</label> -->
<!-- 			<input type="text" name=""> -->
<!-- 				<div class="portlet-body"> -->
<!-- 					<table class="table table-hover"> -->
<!-- 						<thead> -->
<!-- 							<tr> -->
<!-- 								<th>菜品</th> -->
<!-- 								<th>数量</th> -->
<!-- 								<th>单价</th> -->
<!-- 							</tr> -->
<!-- 						</thead> -->
<!-- 						<tbody> -->
<!-- 							<tr> -->
<!-- 								<td>小白菜</td> -->
<!-- 								<td>3</td> -->
<!-- 								<td>23</td> -->
<!-- 							</tr> -->
<!-- 						</tbody> -->
<!-- 					</table> -->
<!-- 				</div> -->
<!-- 		</div> -->
	</div>
</div>

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