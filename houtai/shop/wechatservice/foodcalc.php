<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
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
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$foodcalc=new FoodCalc();
$openid=$_REQUEST['openid'];

$shopid=$foodcalc->getShopidByOpenid($openid);
$ftid="0";
$startdate=$foodcalc->getTheday($shopid);
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
}
$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
$daynum=(strtotime($enddate)-strtotime($startdate))/86400+1;
$ftarr=$foodcalc->getFoodTypes($shopid);
$ftnamearr=array();
if(!empty($ftidarr[0])){
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

<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>商品统计</title>

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
	<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
	<script src="<?php echo ROOTURL;?>chart.js/Chart.js"></script>
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
								<form action="./foodcalc.php" method="post">
								<input type="hidden" name="openid" value="<?php echo $openid;?>">
								<table>
								<tr>
							<td>
							<span class="inline">起始日期</span>
							<input type="date"  style="height:35px;" name="startdate"  value="<?php echo $startdate;?>" >
							
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
								<input type="date"  style="height:35px;" name="enddate"  value="<?php echo $enddate;?>" >
								
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
								类别 （可多选）<select class="middle m-wrap" multiple="multiple" size=5  tabindex="1" name="ftid[]">
								<option value="0" <?php if(empty($ftidarr[0])){echo "selected";}?>>---全部---</option>
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
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>查询结果 <?php if(!empty($ftnamearr)){echo '【查询商品类别：'. implode("、", $ftnamearr).'】';}else{echo '【查询商品类别：所有】';}?></div>
								<div class="tools">
								
								</div>
							</div>
							<div class="portlet-body flip-scroll">
								<canvas id="chart-area" width="300" height="300" style=" text-align:center;margin:0 auto"/></canvas>
								<?php 
									$jsondata=json_encode($data);
									echo '<script>window.onload = function(){';
									echo 'var pieData='.$jsondata.';';
									echo 'var ctx = document.getElementById("chart-area").getContext("2d");';
									echo 'window.myPie = new Chart(ctx).Pie(pieData);';
									echo '}</script>';
								?>
								<div class="responsive span6" data-tablet="span6" data-desktop="span3">

							<div class="dashboard-stat yellow">

								<div class="visual">

									<i class="icon-bar-chart"></i>

								</div>

								<div class="details">

									<div class="number">¥<?php echo $foodtotalmoney;?></div>

									<div class="desc">销售总额</div>
								</div>

							</div>

						</div>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
									<th class="numeric">排名</th>
									<th class="numeric">商品名</th>
									<th class="numeric">数量</th>
									<!-- <th class="numeric">日均销售</th> -->
									<th class="numeric">数量比重</th>
									<th class="numeric">计算总额(元)</th>			
									<!-- <th class="numeric">日均销售额(元)</th>						
									<th class="numeric">金额比重</th> -->	
									</tr>
									</thead>
									<tbody>
									<?php foreach ($arr['data'] as $key=>$val){?>
										<tr>
											<td class="numeric"><?php echo ++$key;?></td>
											<td class="numeric"><?php echo $val['foodname'];?></td>
											<td class="numeric"><?php echo $val['foodamount'].$val['foodunit'];?></td>
											<!-- <td class="numeric"><?php if($val['foodcycle']>=$daynum){echo sprintf("%.0f",$val['foodamount']/$daynum);}else{echo sprintf("%.0f",$val['foodamount']/$val['foodcycle']);} ;?><?php echo $val['foodunit'];?></td> -->
											<td class="numeric"><?php if(!empty($soldtotalamount)){echo sprintf("%.1f",100*$val['foodamount']/$soldtotalamount)."%";}else{echo "/";}?></td>
											
											<td class="numeric"><?php echo $val['foodmoney'];?></td>
										<!-- 	<td class="numeric"><?php if($val['foodcycle']>=$daynum){echo sprintf("%.0f",$val['foodmoney']/$daynum);}else{echo sprintf("%.0f",$val['foodmoney']/$val['foodcycle']);} ;?></td> 
											<td class="numeric"><?php if(!empty($foodtotalmoney)){echo sprintf("%.1f",100*$val['foodmoney']/$foodtotalmoney)."%";}else{echo "/";}?></td>-->
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
