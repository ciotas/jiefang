<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class CusnumRealTrend{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getCusnumRealTrendData($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getCusnumRealTrendData($inputarr);
	}
}
$cusnumrealtrend=new CusnumRealTrend();
$menu="datasheet";
$clicktag="cusnumrealtrend";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$theday=$cusnumrealtrend->getTheday($shopid);
$openhour=$cusnumrealtrend->getOpenHourByShopid($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$inputarr=array(	
		"shopid"	=>$shopid,
		"theday"=>$theday,
		"openhour"=>$openhour,
);
// print_r($inputarr);exit;
$data=$cusnumrealtrend->getCusnumRealTrendData($inputarr);
// print_r($data);
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./cusnumrealtrend.php?theday="+theday;
}
//-->
</script>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							顾客流量实时图<small></small>

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
								<div class="caption"><i class="icon-table"></i>顾客流量（实时）</div>
								<div class="tools">
									
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
									<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
								</div>
							
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
