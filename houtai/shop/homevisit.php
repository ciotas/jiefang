<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class HomeVisit{
	public function getPageviewDataByDay($shopid, $datearr){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getPageviewDataByDay($shopid, $datearr);
	}
	public function getTotalPageviewnum($shopid, $startdate,$enddate){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getTotalPageviewnum($shopid, $startdate, $enddate);
	}
}
$homevisit=new HomeVisit();
$title="访问量";
$menu="profile";
$clicktag="homevisit";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$startdate=date('Y-m-01', strtotime(date("Y-m-d")));//获取本月1号
$enddate=date("Y-m-d",time());
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$datearr=array();
for ($day=strtotime($startdate);$day<=strtotime($enddate);$day=$day+86400){
	$datearr[]=date("Y-m-d",$day);
}
$data=$homevisit->getPageviewDataByDay($shopid, $datearr);
$totalviewnum=$homevisit->getTotalPageviewnum($shopid, $startdate, $enddate);
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							访问量<small></small>

						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
	
				<div class="row-fluid invoice">
					<div class="span12">
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>访问量，共<span style="color: red"><?php echo $totalviewnum;?></span>次</div>
								<div class="tools">
									
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<form action="./homevisit.php" method="post">
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