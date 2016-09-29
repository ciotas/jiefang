<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class CusnumTrend{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getCusnumTrendData($shopid, $startdate, $enddate, $datearr, $thehour){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getCusnumTrendData($shopid, $startdate, $enddate, $datearr, $thehour);
	}
}
$cusnumtrend=new CusnumTrend();
$menu="datasheet";
$clicktag="cusnumtrend";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$startdate=date('Y-m-01', strtotime(date("Y-m-d")));//获取本月1号
$enddate=$cusnumtrend->getTheday($shopid);
$thehour=$cusnumtrend->getOpenHourByShopid($shopid);
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$datearr=array();
for ($day=strtotime($startdate);$day<=strtotime($enddate);$day=$day+86400){
	$datearr[]=date("Y-m-d",$day);
}
$data=$cusnumtrend->getCusnumTrendData($shopid, $startdate, $enddate, $datearr, $thehour);
?>


<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							顾客流量走势图<small></small>

						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
	
				<div class="row-fluid invoice">
					<div class="span12">
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>顾客流量走势图</div>
								<div class="tools">
									
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<form action="./cusnumtrend.php" method="post">
								<table style="margin:0">
								<tr>
								<td style="float: left">
								<span class="inline">起始日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
								<input class="m-wrap m-ctrl-medium Wdate"  style="width: 100px;" name="startdate" onClick="WdatePicker()" size="8" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
							</td>
							<td style="float: left">
								<span class="inline">结束日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" name="enddate" style="width: 100px;" onClick="WdatePicker()" size="8" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
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
	require_once 'footer.php';
	?>
