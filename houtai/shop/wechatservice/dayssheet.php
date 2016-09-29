<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DaysSheet{
	public function getDaysSheetData($shopid,  $startdate, $enddate){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDaysSheetData($shopid,  $startdate, $enddate);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getAllTickets($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getAllTickets($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$dayssheet=new DaysSheet();
$openid=$_REQUEST['openid'];

$shopid=$dayssheet->getShopidByOpenid($openid);
$startdate=date("Y-m-01",time());
$enddate=$dayssheet->getTheday($shopid);
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$totalticket=array();
$tiketallarr=$dayssheet->getAllTickets($shopid);
// print_r($tiketallarr);exit;
$daysarr=$dayssheet->getDaysSheetData($shopid, $startdate, $enddate);
// print_r($daysarr);exit;
$totalmoney=0;
$billnum=0;
$cusnum=0;
$receivablemoney=0;
$avgmoney=0;
$cashmoney=0;
$unionmoney=0;
$vipmoney=0;
$meituanpay=0;
$dazhongpay=0;
$nuomipay=0;
$otherpay=0;
$alipay=0;
$wechatpay=0;
$othermoney=0;
$clearmoney=0;
$signmoney=0;
$freemoney=0;
$discountmoney=0;
$ticketmoney=0;
$tdepositmoney=0;
$treturndepositmoney=0;
$totalticket=array();
foreach ($daysarr as $rkey=>$rval){
	$totalmoney+=$rval['totalmoney'];
	$billnum+=$rval['billnum'];
	$cusnum+=$rval['cusnum'];
	$receivablemoney+=$rval['receivablemoney'];
	$avgmoney+=$rval['avgmoney'];
	$cashmoney+=$rval['cashmoney'];
	$unionmoney+=$rval['unionmoney'];
	$vipmoney+=$rval['vipmoney'];
	$meituanpay+=$rval['meituanpay'];
	$dazhongpay+=$rval['dazhongpay'];
	$nuomipay+=$rval['nuomipay'];
	$otherpay+=$rval['otherpay'];
	$alipay+=$rval['alipay'];
	$wechatpay+=$rval['wechatpay'];
	$othermoney+=$rval['othermoney'];
	$clearmoney+=$rval['clearmoney'];
	$signmoney+=$rval['signmoney'];
	$freemoney+=$rval['freemoney'];
	$discountmoney+=$rval['discountmoney'];
	$ticketmoney+=$rval['ticketmoney'];
	$tdepositmoney+=$rval['depositmoney'];
	$treturndepositmoney+=$rval['returndepositmoney'];
}
$totalarr=array(
		"totalmoney"=>$totalmoney,
		"billnum"=>$billnum,
		"cusnum"=>$cusnum,
		"receivablemoney"=>$receivablemoney,
		"avgmoney"=>$avgmoney,
		"cashmoney"=>($cashmoney),
		"unionmoney"=>($unionmoney),
		"vipmoney"=>$vipmoney,
		"meituanpay"=>$meituanpay,
		"dazhongpay"=>$dazhongpay,
		"nuomipay"=>($nuomipay),
		"otherpay"=>$otherpay,
		"alipay"=>$alipay,
		"wechatpay"=>$wechatpay,
		"ticket"=>$totalticket,
		"othermoney"=>$othermoney,
		"clearmoney"=>$clearmoney,
		"signmoney"=>$signmoney,
		"freemoney"=>$freemoney,
		"discountmoney"=>$discountmoney,
		"ticketmoney"=>$ticketmoney,
		"depositmoney"=>$tdepositmoney,
		"returndepositmoney"=>$treturndepositmoney,
);
// print_r($totalarr);exit;
?>

<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>月汇总</title>

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
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>月汇总</div>
								<div class="tools">
							<!-- 	<form action="./interface/printtotalcalc.php" method="post"  style="margin: 0;padding:0">
								<input type="hidden" name="startdate" value="<?php echo $startdate;?>" >
								<input type="hidden" name="enddate" value="<?php echo $enddate;?>" >
								<input type="hidden" name="data" value='<?php echo json_encode($totalarr);?>'>
								<button  type="submit"  class="btn blue middle hidden-print" >小票打印 <i class="icon-print icon-big"></i></button>
								</form> -->
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<form action="./dayssheet.php" method="post">
							<input type="hidden" name="openid" value="<?php echo $openid;?>">
								<table style="margin:0">
								<tr>
								<td style="float: left">
								<span class="inline">起始日期</span>
									<input type="date"  style="height:35px;" name="startdate"  value="<?php echo $startdate;?>" >
								
								<!-- <div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
								<input class="m-wrap m-ctrl-medium Wdate"  style="width: 100px;" name="startdate" onClick="WdatePicker()" size="8" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div> -->
							</td>
							<td style="float: left">
								<span class="inline">结束日期</span>
								<input type="date"  style="height:35px;" name="enddate"  value="<?php echo $enddate;?>" >
								<!-- <div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" name="enddate" style="width: 100px;" onClick="WdatePicker()" size="8" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div> -->
							</td>
								<td style="float: left"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
								</tr>
								</table>
								</form>
							<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
											<th class="numeric"><h4>总人数</h4></th>
											<th class="numeric"><h4>下单总数</h4></th>
											<th class="numeric"><h4>销售总额</h4></th>
<!-- 											<th class="numeric"><h4>其他收入</h4></th> -->
											<th class="numeric"><h4>应收款</h4></th>
											<th class="numeric"><h4>现金</h4></th>
											<th class="numeric"><h4>银联卡</h4></th>
											<th class="numeric"><h4>会员卡</h4></th>
											<th class="numeric"><h4>美团账户</h4></th>
											<th class="numeric"><h4>大众账户</h4></th>
											<th class="numeric"><h4>糯米账户</h4></th>
											<th class="numeric"><h4>其他</h4></th>
											<th class="numeric"><h4>支付宝</h4></th>
											<th class="numeric"><h4>微信支付</h4></th>
											<?php foreach ($tiketallarr as $tkey=>$tkname){?>
												<th class="numeric"><h4><?php echo $tkname;?></h4></th> 
											<?php }?>
											<th class="numeric"><h4>折扣额</h4></th>
											<th class="numeric"><h4>抹零</h4></th>
											<th class="numeric"><h4>签单</h4></th>
											<th class="numeric"><h4>免单</h4></th>
											<th class="numeric"><h4>收押金</h4></th>
											<th class="numeric"><h4>退押金</h4></th>
									</tr>
									</thead>
									<tbody>
										<tr>
											<td class="numeric"><h4><?php echo $cusnum;?></h4></td>
											<td class="numeric"><h4><?php echo $billnum;?></h4></td>
											<td class="numeric"><h4><?php echo $totalmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $receivablemoney;?></h4></td>
											<td class="numeric"><h4><?php echo $cashmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $unionmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $vipmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $meituanpay;?></h4></td>
											<td class="numeric"><h4><?php echo $dazhongpay;?></h4></td>
											<td class="numeric"><h4><?php echo $nuomipay;?></h4></td>
											<td class="numeric"><h4><?php echo $otherpay;?></h4></td>
											<td class="numeric"><h4><?php echo $alipay;?></h4></td>
											<td class="numeric"><h4><?php echo $wechatpay;?></h4></td>
											<?php foreach ($daysarr as $dkey=>$dval){ 
												foreach ($dval['ticket']  as $ticketkey=>$ticketval){
													if(array_key_exists($ticketkey, $totalticket)){
														$totalticket[$ticketkey]['ticketnum']+=$ticketval['ticketnum'];
														$totalticket[$ticketkey]['ticketmoney']+=$ticketval['ticketmoney'];
													}else{
														$totalticket[$ticketkey]=array(
															"ticketname"=>$ticketval['ticketname'],
															"ticketnum"=>$ticketval['ticketnum'],
															"ticketmoney"=>$ticketval['ticketmoney'],
														);
													}
												}}
// 												print_r($totalticket);exit;
												foreach ($totalticket as $ttkey=>$ttval){
													echo '<td class="numeric"><h4>'.$ttval['ticketnum'].'/'.$ttval['ticketmoney'].' </h4></td>';
												}
												?>
											
											<td class="numeric"><h4><?php echo $discountmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $clearmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $signmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $freemoney?></h4></td>
											<td class="numeric"><h4><?php echo $tdepositmoney?></h4></td>
											<td class="numeric"><h4><?php echo $treturndepositmoney?></h4></td>
										</tr>
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
require ('../footer.php');
?>
