<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/houtai/shop/Factory/InterfaceFactory.php');
class FoodCalc{
	public function getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime);
	}
	public function getFoodTypes($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodTypes($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getFoodtypenameByFtidarr($ftidarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodtypenameByFtidarr($ftidarr);
	}
	public function getFoodSoldByTypeData($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getFoodSoldByTypeData($inputarr);
	}
}
$foodcalc=new FoodCalc();
$shopid="";
$shopname="";
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$shopname=$_GET['shopname'];
	$menu="shop";
	$clicktag="shoplist_".$shopid;
	$firstclick=$_GET['dist'];
}
$bossid=$_SESSION['bossid'];
require_once ('header.php');

$ftid="0";
$startdate=date("Y-m-01");
$enddate=$foodcalc->getTheday($shopid);
$enddate=date("Y-m-d",strtotime($enddate)+86400);
$starthour=$foodcalc->getOpenHourByShopid($shopid);
$endhour=$starthour;
if(isset($_POST['startdate'])){
	$ftidarr=$_POST['ftid'];
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
	$firstclick=$_GET['dist'];
}
$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
$daynum=(strtotime($enddate)-strtotime($startdate))/86400+1;
$ftarr=$foodcalc->getFoodTypes($shopid);
$ftnamearr=array();
if(!empty($ftidarr)){
	$ftnamearr=$foodcalc->getFoodtypenameByFtidarr($ftidarr);
}
$arr=$foodcalc->getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime);
// print_r($arr);exit;
$foodtotalmoney=0;
$soldtotalamount=0;
if(!empty($arr['data'])){
	foreach ($arr['data'] as $fkey=>$fval){
		$foodtotalmoney+=$fval['foodmoney'];
		$soldtotalamount+=$fval['foodamount'];
	}
}
$inputarr=array(
		"shopid"=>$shopid,
		"ftid"=>$ftidarr,
		"startdate"=>$startdate,
		"enddate"	=>$enddate,
		"thehour"=>$starthour,
);
$data=$foodcalc->getFoodSoldByTypeData($inputarr);
?>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							<?php echo $shopname;?>的统计 <small></small>
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

								<div class="caption"><i class="icon-table"></i>选择时间</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./shops.php?shopid=<?php echo $shopid;?>&shopname=<?php echo $shopname;?>&dist=<?php echo $firstclick;?>" method="post">
								<table>
								<tr>
							<td>
							<span class="inline">起始日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" style="width: 100px;" name="startdate" onClick="WdatePicker()" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
								</td>
								<td>
								<span class="inline">起始时间点</span>
								<select class="small m-wrap" tabindex="1" name="starthour">
								<?php for ($i=0;$i<24;$i++){?>
									<option value="<?php echo $i;?>" <?php if($starthour==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>
								</td>
								</tr>
								<tr>
								<td>
								<span class="inline">结束日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" style="width: 100px;" name="enddate"  onClick="WdatePicker()" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
								 </td>
								 <td>
							<span class="inline">结束时间点</span>
								<select class="small m-wrap" tabindex="1" name="endhour">
								<?php for ($i=0;$i<24;$i++){?>
									<option value="<?php echo $i;?>" <?php if($endhour==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>
								</td>
								</tr>
								<tr>
								<td>
								类别 <select class="middle m-wrap" multiple="multiple" size=5  tabindex="1" name="ftid[]">
								<option value="0" <?php if(empty($ftidarr['0'])){echo "selected";}?>>---全部---</option>
								<?php foreach ($ftarr as $ftkey=>$ftval){?>
									<option value="<?php echo $ftval['ftid'];?>" <?php if(in_array($ftval['ftid'], $ftidarr)){echo "selected";}?>><?php echo $ftval['ftname'];?></option>
									<?php }?>
								</select>
							</td>
							<td style="float: right"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
							<tr>
								</table>
								</form>
							</div>
						</div>
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-body">
							<canvas id="chart-area" width="260" height="260" style=" text-align:center;margin:0 auto"/></canvas>
								<?php 
									$jsondata=json_encode($data);
									echo '<script>window.onload = function(){';
									echo 'var pieData='.$jsondata.';';
									echo 'var ctx = document.getElementById("chart-area").getContext("2d");';
									echo 'window.myPie = new Chart(ctx).Pie(pieData);';
									echo '}</script>';
								?>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>总量</th><td><?php echo $soldtotalamount;?>份</td>
											<th>总额</th><td>￥<?php echo $foodtotalmoney;?></td>
											<th> 未结款</th><td>￥<?php echo $arr['totalmoney'];?></td>
											<th>应收款</th><td>￥<?php echo $arr['unpaymoney'];?></td>
										</tr>
									</thead>
									
								</table>
								<table class="table table-hover">
									<thead>
										<tr>
											<th >#</th>
											<th >商品名</th>
											<th >数量</th>
											<th>总额</th>
											<th>商家数</th>	
										</tr>
									</thead>
									<tbody>
										<?php foreach ($arr['data'] as $key=>$val){?>
										<tr>
											<td ><?php echo ++$key;?></td>
											<td ><?php echo $val['foodname'];?></td>
											<td><?php echo $val['foodamount'].$val['foodunit'];?></td>
											<td>￥<?php echo $val['foodmoney'];?></td>
											<td><?php echo count($val['shopname']);?></td>
										</tr>
										<?php }?>
									</tbody>
								</table>
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

<?php 
require ('footer.php');
?>
