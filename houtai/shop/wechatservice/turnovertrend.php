<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TurnOverTrend{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getTurnfoodTrendData($shopid, $startdate, $enddate,$datearr, $thehour){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getTurnfoodTrendData($shopid, $startdate, $enddate, $datearr, $thehour);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$turnovertrend=new TurnOverTrend();
$openid=$_REQUEST['openid'];

$shopid=$turnovertrend->getShopidByOpenid($openid);
$startdate=date('Y-m-01', strtotime(date("Y-m-d")));//获取本月1号
$enddate=$turnovertrend->getTheday($shopid);
$thehour=$turnovertrend->getOpenHourByShopid($shopid);
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$datearr=array();
for ($day=strtotime($startdate);$day<=strtotime($enddate);$day=$day+86400){
	$datearr[]=date("Y-m-d",$day);
}
$data=$turnovertrend->getTurnfoodTrendData($shopid, $startdate, $enddate, $datearr, $thehour);
?>

<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>营业额走势图</title>

	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="../media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style.css" rel="stylesheet" type="text/css"/>
	
	<link href="../media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="../media/css/timeline.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->
	<script src="<?php echo $root_url;?>chart.js/Chart.js"></script>

</head>

<!-- END HEAD -->
<body>
<div class="page-container row-fluid">
		<!-- BEGIN PAGE -->

		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
	
				<div class="row-fluid invoice">
					<div class="span12">
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>营业额走势图</div>
								<div class="tools">
									
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<form action="./turnovertrend.php" method="post">
							<input type="hidden" name="openid" value="<?php echo $openid;?>">
								<table style="margin:0">
								<tr>
								<td style="float: left">
								<span class="inline">起始日期</span>
								<input type="date"  style="height:35px;" name="startdate"  value="<?php echo $startdate;?>" >
								
							</td>
							<td style="float: left">
								<span class="inline">结束日期</span>
								<input type="date"  style="height:35px;" name="enddate"  value="<?php echo $enddate;?>" >
							</td>
								<td style="float: left"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
								</tr>
								</table>
								</form>
							
								<canvas id="lineChart" ></canvas>
								<?php 
									$jsondata=json_encode($data);
									echo '<script>window.onload = function(){';
									echo 'var lineChartData='.$jsondata.';';
									echo 'var ctx = document.getElementById("lineChart").getContext("2d");';
									echo 'window.myLine = new Chart(ctx).Line(lineChartData, {responsive: true});';
									echo '}</script>';
								?>
					
							</div>

						</div>

						<!-- END SAMPLE TABLE PORTLET-->

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
	<!-- BEGIN FOOTER -->

	<!-- BEGIN FOOTER -->

	<?php 
	require_once '../footer.php';
	?>
