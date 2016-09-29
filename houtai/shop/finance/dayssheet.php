<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DaysSheet{
	public function getDaysSheetData($shopid,  $startdate, $enddate){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDaysSheetData($shopid,  $startdate, $enddate);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getDecreasenum($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getDecreasenum($shopid);
	}
	public function getAllTickets($shopid){
	    return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getAllTickets($shopid);
	}
}
$dayssheet=new DaysSheet();
$menu="datasheet";
$clicktag="days";
$shopid=$_SESSION['shopid'];
require_once ('./finance_header.php');
$tiketallarr=$dayssheet->getAllTickets($shopid);
$startdate=$dayssheet->getTheday($shopid);
$enddate=$dayssheet->getTheday($shopid);
if(isset($_POST['startdate'])){
	$startdate=$_POST['startdate'];
	$enddate=$_POST['enddate'];
}
$decreasenum=$dayssheet->getDecreasenum($shopid);
$daysarr=$dayssheet->getDaysSheetData($shopid, $startdate, $enddate);
$totalmoney=0;
$billnum=0;
$cusnum=0;
$receivablemoney=0;
$avgmoney=0;
$cashmoney=0;
$unionmoney=0;
$vipmoney=0;
$meituanpay=0;
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
$tticketmoney=0;
 foreach ($daysarr as $dkey=>$dval){
    foreach ($dval['ticket']  as $ticketkey=>$ticketval){
        $tticketmoney+=$ticketval['ticketmoney'];
    }}
  
?>

<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>


				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							日报表 <small>班次统计</small>

						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">主页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">报表</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="timesheet.php">按日期</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
	
				<div class="row-fluid invoice">
					<div class="span12">
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>详细</div>
								<div class="tools">
								<a class="btn purple middle hidden-print" onclick="javascript:window.print();">打印 <i class="icon-print icon-big"></i></a>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<form action="./dayssheet.php" method="post">
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
							<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
											<th class="numeric"><h4>时间</h4></th>
											<th class="numeric"><h4>总人数</h4></th>
											<th class="numeric"><h4>下单总数</h4></th>
											<th class="numeric"><h4>销售总额</h4></th>
											<th class="numeric"><h4>其他收入</h4></th>
<!-- 											<th class="numeric"><h4>应收款</h4></th> -->
											<th class="numeric"><h4>现金</h4></th>
											<th class="numeric"><h4>银联卡</h4></th>
											<th class="numeric"><h4>会员卡</h4></th>
											<th class="numeric"><h4>美团账户</h4></th>
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
											<td class="numeric"><h4>/</h4></td>
											<td class="numeric"><h4><?php echo $cusnum;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$billnum;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$totalmoney-($decreasenum-1)*($tticketmoney+$meituanpay);?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$othermoney;?></h4></td>
										<!-- 	<td class="numeric"><h4><?php echo $decreasenum*$receivablemoney;?></h4></td> -->
											<td class="numeric"><h4><?php echo $decreasenum*$cashmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$unionmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$vipmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $meituanpay;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$alipay;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$wechatpay;?></h4></td>
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
											<td class="numeric"><h4><?php echo $decreasenum*$discountmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$clearmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$signmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$freemoney?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$tdepositmoney?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$treturndepositmoney?></h4></td>
										</tr>
										</tbody>
									
								</table>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">时间</th>
											<th class="numeric">人数</th>
											<th class="numeric">下单数</th>
											<th class="numeric">销售额</th>
											<th class="numeric">其他收入</th>
<!-- 											<th class="numeric">应收款</th> -->
											<th class="numeric">现金</th>
											<th class="numeric">银联卡</th>
											<th class="numeric">会员卡</th>
											<th class="numeric">美团账户</th>
											<th class="numeric">支付宝</th>
											<th class="numeric">微信支付</th>
											<?php foreach ($tiketallarr as $tkey=>$tkname){?>
											<th class="numeric"><?php echo $tkname."(张数/金额)";?></th> 
											<?php }?>
											<th class="numeric">折扣额</th>
											<th class="numeric">抹零</th>
											<th class="numeric">签单</th>
											<th class="numeric">免单</th>
											<th class="numeric">收押金</th>
											<th class="numeric">退押金</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($daysarr as $key=>$val){?>
										<tr>
										    <td class="numeric"><?php echo $key;?></td>
											<td class="numeric"><?php echo $decreasenum*$val['cusnum'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['billnum'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['totalmoney']-($decreasenum-1)*($meituanpay+$ticketval['ticketmoney']);?></td>
											<td class="numeric"><?php echo $decreasenum*$val['othermoney'];?></td>
										<!-- 	<td class="numeric"><?php echo $decreasenum*$val['receivablemoney'];?></td> -->
											<td class="numeric"><?php echo $decreasenum*$val['cashmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['unionmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['vipmoney'];?></td>
											<td class="numeric"><?php echo $val['meituanpay'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['alipay'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['wechatpay'];?></td>
											
											<?php foreach ($val['ticket']  as $ticketkey=>$ticketval){?>
												<td class="numeric"><?php echo $ticketval['ticketnum']."/".$ticketval['ticketmoney'];?></td>
											<?php }?>
											<td class="numeric"><?php echo $decreasenum*$val['discountmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['clearmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['signmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$decreasenum*$val['freemoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['depositmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['returndepositmoney'];?></td>
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
require ('../footer.php');
?>
