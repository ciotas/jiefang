<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class MyOrders{
	public function getMyOrdersByUid($uid,$op){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getMyOrdersByUid($uid,$op);
	}
	public function getMyBeforeOrdersByUid($uid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getMyBeforeOrdersByUid($uid);
	}
}
$myorders=new MyOrders();
$arr=array();
if(isset($_REQUEST['uid'])){
	$uid=$_REQUEST['uid'];
	$undonearr=$myorders->getMyBeforeOrdersByUid($uid);
	$unpayarr=$myorders->getMyOrdersByUid($uid,"unpay");
	$payarr=$myorders->getMyOrdersByUid($uid,"paid");
}
?>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>我的订单</title>

	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>
	
	<link href="media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>


	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="media/css/timeline.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->


</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body>  

	<div class="page-container row-fluid">
		<!-- BEGIN PAGE -->

		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							我的订单 <small>保留一个月内的有效订单</small>

						</h3>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">
					<div class="span12">
						<div class="tabbable tabbable-custom boxless">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_0" data-toggle="tab"><span style="font-size:18px; ">未下单</span></a></li>
								<li class=""><a href="#tab_1" data-toggle="tab"><span style="font-size:18px; ">未付款</span></a></li>
								<li class=""><a href="#tab_2" data-toggle="tab"><span style="font-size:18px; ">已完成</span></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_0">
									<ul class="timeline">
						<?php if(empty($undonearr)){echo "<span style='color:red;'>客官，您已经很久没来了 ~ </span>";}?>
						<?php foreach ($undonearr as $key=>$val){
								$color="yellow";
								if($val['paystatus']=="paid"){$color="green";}
								if($val['paystatus']=="unpay" && $val['takeout']=="1"){$color="red";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="done"){$color="yellow";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="undone"){$color="red";}
							?>
							<li class="timeline-<?php echo $color;?>" onclick="window.location.href='./onebeforebill.php?billid=<?php echo $val['billid'];?>&takeout=<?php echo $val['takeout'];?>&paystatus=<?php echo $val['paystatus'];?>&billstatus=<?php echo $val['billstatus'];?>&from=myorders&uid=<?php echo $uid;?>' ">
								<div class="timeline-time">
									<span class="time"><?php echo date("Y-m-d H:i",$val['timestamp'])?></span>

								</div>
								<div class="timeline-body">
									<h2><?php echo $val['shopname'];?><span style="float: right"><?php if($val['paystatus']=="paid"){echo "已付款";}elseif ( ($val['takeout']=="1"&&$val["paystatus"]=="unpay") || ($val['takeout']=="0"&&$val['billstatus']=="done" &&$val['paystatus']=="unpay")){echo "未付款";}elseif($val['takeout']=="0"&&$val['billstatus']=="undone" &&$val['paystatus']=="unpay"){echo "未下单";}?></span></h2>
									<div class="timeline-content">
										<img class="timeline-img pull-left" src="<?php echo $val['logo']?>" alt="">
										<p>人数：<?php echo $val['cusnum'];?></p>
										<p>台号：<?php if($val['takeout']=="1"){echo "外卖单";}elseif($val['takeout']=="0"&&empty($val['tabid'])){echo "待定";}else{echo $val['tabname'];}?></p>
										<p>订单金额：￥<?php echo $val['totalmoney'];?></p>
									</div>
								</div>
							</li>
							<?php }?>
						</ul>
									</div>
									
									<div class="tab-pane " id="tab_1">
									<ul class="timeline">
						<?php if(empty($unpayarr)){echo "<span style='color:red;'>客官，您已经很久没来了 ~ </span>";}?>
						<?php foreach ($unpayarr as $key=>$val){
								$color="yellow";
								if($val['paystatus']=="paid"){$color="green";}
								if($val['paystatus']=="unpay" && $val['takeout']=="1"){$color="red";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="done"){$color="yellow";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="undone"){$color="red";}
							?>
							<li class="timeline-<?php echo $color;?>" onclick="window.location.href='./onebill.php?billid=<?php echo $val['billid'];?>&takeout=<?php echo $val['takeout'];?>&paystatus=<?php echo $val['paystatus'];?>&billstatus=<?php echo $val['billstatus'];?>&from=myorders&uid=<?php echo $uid;?>' ">
								<div class="timeline-time">
									<span class="time"><?php echo date("Y-m-d H:i",$val['timestamp'])?></span>

								</div>
								<div class="timeline-body">
									<h2><?php echo $val['shopname'];?><span style="float: right"><?php if($val['paystatus']=="paid"){echo "已付款";}elseif ( ($val['takeout']=="1"&&$val["paystatus"]=="unpay") || ($val['takeout']=="0"&&$val['billstatus']=="done" &&$val['paystatus']=="unpay")){echo "未付款";}elseif($val['takeout']=="0"&&$val['billstatus']=="undone" &&$val['paystatus']=="unpay"){echo "未下单";}?></span></h2>
									<div class="timeline-content">
										<img class="timeline-img pull-left" src="<?php echo $val['logo']?>" alt="">
										<p>人数：<?php echo $val['cusnum'];?></p>
										<p>台号：<?php if($val['takeout']=="1"){echo "外卖单";}elseif($val['takeout']=="0"&&empty($val['tabid'])){echo "待定";}else{echo $val['tabname'];}?></p>
										<p>订单金额：￥<?php echo $val['totalmoney'];?></p>
									</div>
								</div>
							</li>
							<?php }?>
						</ul>
									</div>
									<div class="tab-pane " id="tab_2">
									<ul class="timeline">
						<?php if(empty($payarr)){echo "<span style='color:red;'>客官，您已经很久没来了 ~ </span>";}?>
						<?php foreach ($payarr as $key=>$val){
								$color="yellow";
								if($val['paystatus']=="paid"){$color="green";}
								if($val['paystatus']=="unpay" && $val['takeout']=="1"){$color="red";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="done"){$color="yellow";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="undone"){$color="red";}
							?>
							<li class="timeline-<?php echo $color;?>" onclick="window.location.href='./onebill.php?billid=<?php echo $val['billid'];?>&takeout=<?php echo $val['takeout'];?>&paystatus=<?php echo $val['paystatus'];?>&billstatus=<?php echo $val['billstatus'];?>' ">
								<div class="timeline-time">
									<span class="time"><?php echo date("Y-m-d H:i",$val['timestamp'])?></span>

								</div>
								<div class="timeline-body">
									<h2><?php echo $val['shopname'];?><span style="float: right"><?php if($val['paystatus']=="paid"){echo "已付款";}elseif ( ($val['takeout']=="1"&&$val["paystatus"]=="unpay") || ($val['takeout']=="0"&&$val['billstatus']=="done" &&$val['paystatus']=="unpay")){echo "未付款";}elseif($val['takeout']=="0"&&$val['billstatus']=="undone" &&$val['paystatus']=="unpay"){echo "未下单";}?></span></h2>
									<div class="timeline-content">
										<img class="timeline-img pull-left" src="<?php echo $val['logo']?>" alt="">
										<p>人数：<?php echo $val['cusnum'];?></p>
										<p>台号：<?php if($val['takeout']=="1"){echo "外卖单";}elseif($val['takeout']=="0"&&empty($val['tabid'])){echo "待定";}else{echo $val['tabname'];}?></p>
										<p>订单金额：￥<?php echo $val['totalmoney'];?></p>
									</div>
								</div>
							</li>
							<?php }?>
						</ul>
									</div>
									
									
									
								</div>

							</div>
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


		</div>



	</div>

	<!-- END FOOTER -->

<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="media/js/excanvas.min.js"></script>

	<script src="media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/app.js" type="text/javascript"></script>

	<script src="media/js/index.js" type="text/javascript"></script>    
	<script type="text/javascript" src="media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="media/js/chosen.jquery.min.js"></script>
	<script src="media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="media/js/jquery.input-ip-address-control-1.0.min.js"></script>
	
	<script type="text/javascript" src="media/js/jquery.multi-select.js"></script>  
	<script src="media/js/form-components.js"></script>  
	<script type="text/javascript" src="media/js/bootstrap-colorpicker.js"></script>  
	<script src="media/js/ui-modals.js"></script> 
	<script type="text/javascript" src="media/js/jquery.toggle.buttons.js"></script>
	
	
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
// 		   FormComponents.init();
		   UIModals.init();
		   
// 		   Index.initIntro();

		});

	</script>
	
</body>

<!-- END BODY -->

</html>