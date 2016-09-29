<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class FoodSoldByType{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getFoodTypes($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodTypes($shopid);
	}
	public function getFoodSoldByTypeData($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getFoodSoldByTypeData($inputarr);
	}
}
$foodsoldbytype=new FoodSoldByType();
$menu="datasheet";
$clicktag="foodsoldbytype";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$startdate=date('Y-m-01', strtotime(date("Y-m-d")));//获取本月1号
$enddate=$foodsoldbytype->getTheday($shopid);
$thehour=$foodsoldbytype->getOpenHourByShopid($shopid);
$ftarr=$foodsoldbytype->getFoodTypes($shopid);
$ftidarr=array();
if(isset($_REQUEST['startdate'])){
	$ftidarr=$_POST['ftid'];
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$inputarr=array(
		"shopid"=>$shopid,
		"ftid"=>$ftidarr,
		"startdate"=>$startdate,
		"enddate"	=>$enddate,
		"thehour"=>$thehour,
);
$data=$foodsoldbytype->getFoodSoldByTypeData($inputarr);
// print_r($data);
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							类别美食饼状图<small></small>

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
								<div class="caption"><i class="icon-table"></i>类别美食饼状图</div>
								<div class="tools">
									
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<form action="./foodsoldbytype.php" method="post">
								<table style="margin:0">
								<tr>
								<td>
								类别 <select class="middle m-wrap" multiple="multiple" size=5  tabindex="1" name="ftid[]">
								<option value="0">---全部---</option>
								<?php foreach ($ftarr as $ftkey=>$ftval){?>
									<option value="<?php echo $ftval['ftid'];?>" <?php if(in_array($ftval['ftid'], $ftidarr)){echo "selected";}?>><?php echo $ftval['ftname'];?></option>
									<?php }?>
								</select>
							</td>
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
							
								<canvas id="chart-area" width="300" height="300" style=" text-align:center;margin:0 auto"/></canvas>
								<?php 
									$jsondata=json_encode($data);
									echo '<script>window.onload = function(){';
									echo 'var pieData='.$jsondata.';';
									echo 'var ctx = document.getElementById("chart-area").getContext("2d");';
									echo 'window.myPie = new Chart(ctx).Pie(pieData);';
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
