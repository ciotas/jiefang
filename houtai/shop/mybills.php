<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class MyBills{
	public function getMyBillsDataByUid($uid,$shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getMyBillsDataByUid($uid,$shopid);
	}
	public function getMyBeforeBillsDataByUid($uid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getMyBeforeBillsDataByUid($uid, $shopid);
	}
	public function array_sort($arr, $keys, $type = 'asc'){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->array_sort($arr, $keys,$type);
	}
}
$mybills=new MyBills();
$arr=array();
if(isset($_REQUEST['uid'])){
	$uid=$_REQUEST['uid'];
	$shopid=$_REQUEST['shopid'];
	$donearr=$mybills->getMyBillsDataByUid($uid,$shopid);
	$undonearr=$mybills->getMyBeforeBillsDataByUid($uid, $shopid);
	$arr=array_merge_recursive($donearr,$undonearr);
	$arr=$mybills->array_sort($arr, "timestamp","desc");
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

	<link href="media/css/style-responsive.css" rel="stylesheet" type="text/css"/>


	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="media/css/timeline.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->


</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- BEGIN HEADER -->

	<div class="header navbar navbar-inverse navbar-fixed-top">

	</div>

	<!-- END HEADER -->

	<!-- BEGIN CONTAINER -->   

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

							<a href="<?php echo $root_url."weshop/shopindex.php?shopid=$shopid";?>" style="text-decoration:none;">首页</a>&nbsp;<&nbsp;我的订单 <small>保留一个月内的有效订单</small>

						</h3>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">
					<div class="span12">
						<ul class="timeline">
						<?php if(empty($arr)){echo "<span style='color:red;'>客官，您已经很久没来了 ~ </span>";}?>
						<?php foreach ($arr as $key=>$val){
								$color="yellow";
								if($val['paystatus']=="paid"){$color="green";}
								if($val['paystatus']=="unpay" && $val['takeout']=="1"){$color="red";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="done"){$color="yellow";}
								if($val['paystatus']=="unpay" && $val['billstatus']=="undone"){$color="red";}
							?>
							<?php if($val['from']=="beforebill"){?>
							<li class="timeline-<?php echo $color;?>" onclick="window.location.href='./onebeforebill.php?billid=<?php echo $val['billid'];?>&takeout=<?php echo $val['takeout'];?>&paystatus=<?php echo $val['paystatus'];?>&billstatus=<?php echo $val['billstatus'];?>&from=mybills&uid=<?php echo $uid;?>' ">
								<?php }else{ ?>
								<li class="timeline-<?php echo $color;?>" onclick="window.location.href='./onebill.php?billid=<?php echo $val['billid'];?>&takeout=<?php echo $val['takeout'];?>&paystatus=<?php echo $val['paystatus'];?>&billstatus=<?php echo $val['billstatus'];?>&from=mybills&uid=<?php echo $uid;?>' ">
								<?php }?>
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


</body>

<!-- END BODY -->

</html>