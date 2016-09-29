<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class TimeSheet{
	public function getTimeSheetData($shopid, $starttime, $endtime){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTimeSheetData($shopid, $starttime, $endtime);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getAllTickets($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getAllTickets($shopid);
	}
}
$timesheet=new TimeSheet();
$menu="datasheet";
$clicktag="time";
$shopid=$_SESSION['shopid'];
$totalticket=array();
require_once ('finance_header.php');
$startdate=$timesheet->getTheday($shopid);
// $enddate=$timesheet->getTheday($shopid);
$enddate=date("Y-m-d",strtotime($startdate)+86400);
$starthour=$timesheet->getOpenHourByShopid($shopid);
$endhour=$timesheet->getOpenHourByShopid($shopid);
$startminute="0";
$endminute="0";
if(isset($_POST['startdate'])){
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
	$startminute=$_POST['startminute'];
	$endminute=$_POST['endminute'];
}
$starttime=$startdate." ".$starthour.":".$startminute.":0";
$endtime=$enddate." ".$endhour.":".$endminute.":0";
// echo $starttime;exit;
$tiketallarr=$timesheet->getAllTickets($shopid);
$timearr=$timesheet->getTimeSheetData($shopid, $starttime, $endtime);
// print_r($timearr);exit;
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
foreach ($timearr as $rkey=>$rval){
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
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>


				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							按时段查询 <small>班次统计</small>

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

								<div class="caption"><i class="icon-table"></i>选择时间间隔</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./timesheet.php" method="post">
								<table style="margin:0">
								<tr>
								<td>
								<span class="inline">起始日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
								<input class="m-wrap m-ctrl-medium Wdate"  style="width: 100px;" name="startdate" onClick="WdatePicker()" size="8" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
							</td>
							<td>
							<span class="inline"></span>
								<select class="small m-wrap" tabindex="1" name="starthour">
								<?php for ($i=0;$i<24;$i++){?>
									<option value="<?php echo $i;?>" <?php if($starthour==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>时
								</td>
								<td>
							<span class="inline"></span>
								<select class="small m-wrap" tabindex="1" name="startminute">
								<?php for ($i=0;$i<60;$i++){?>
									<option value="<?php echo $i;?>" <?php if($startminute==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>分
								</td>
								</tr>
								<tr>
								<td>
								<span class="inline">结束日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" name="enddate" style="width: 100px;" onClick="WdatePicker()" size="8" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
							</td>
							<td>
							<span class="inline"></span>
								<select class="small m-wrap" tabindex="1" name="endhour">
								<?php for ($i=0;$i<24;$i++){?>
									<option value="<?php echo $i;?>" <?php if($endhour==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>点
								</td>
								<td>
							<span class="inline"></span>
								<select class="small m-wrap" tabindex="1" name="endminute">
								<?php for ($i=0;$i<60;$i++){?>
									<option value="<?php echo $i;?>" <?php if($endminute==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>分
								</td>
								</tr>
								<tr>
								
								<td>
									
								</td>
								<td></td>
								<td style="float: right"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
								</tr>
								</table>
								</form>
							</div>
						</div>
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>详细</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
											<th class="numeric"><h4>时间</h4></th>
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
											<td class="numeric"><h4>/</h4></td>
											<td class="numeric"><h4><?php echo $cusnum;?></h4></td>
											<td class="numeric"><h4><?php echo $billnum;?></h4></td>
											<td class="numeric"><h4><?php echo $totalmoney?></h4></td>
										<!-- 	<td class="numeric"><h4><?php echo $othermoney;?></h4></td> -->
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
											
											<?php foreach ($timearr as $dkey=>$dval){ 
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
											<td class="numeric"><h4><?php echo $treturndepositmoney;?></h4></td>
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
<!-- 											<th class="numeric">其他收入</th> -->
											<th class="numeric">应收款</th>
											<th class="numeric">现金</th>
											<th class="numeric">银联卡</th>
											<th class="numeric">会员卡</th>
											<th class="numeric">美团账户</th>
											<th class="numeric">大众账户</th>
											<th class="numeric">糯米账户</th>
											<th class="numeric">其他</th>
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
									<?php foreach ($timearr as $key=>$val){?>
										<tr>
										    <td class="numeric"><?php echo $key;?></td>
											<td class="numeric"><?php echo $val['cusnum'];?></td>
											<td class="numeric"><?php echo $val['billnum'];?></td>
											<td class="numeric"><?php echo $val['totalmoney'];?></td>
										<!-- 	<td class="numeric"><?php echo $val['othermoney'];?></td>-->
											<td class="numeric"><?php echo $val['receivablemoney'];?></td>
											<td class="numeric"><?php echo $val['cashmoney'];?></td>
											<td class="numeric"><?php echo $val['unionmoney'];?></td>
											<td class="numeric"><?php echo $val['vipmoney'];?></td>
											<td class="numeric"><?php echo $val['meituanpay'];?></td>
											<td class="numeric"><?php echo $val['dazhongpay'];?></td>
											<td class="numeric"><?php echo $val['nuomipay'];?></td>
											<td class="numeric"><?php echo $val['otherpay'];?></td>
											<td class="numeric"><?php echo $val['alipay'];?></td>
											<td class="numeric"><?php echo $val['wechatpay'];?></td>
											
											<?php foreach ($val['ticket']  as $ticketkey=>$ticketval){?>
											<td class="numeric"><?php echo $ticketval['ticketnum']."/".$ticketval['ticketmoney'];?></td>
											<?php }?>
											<td class="numeric"><?php echo $val['discountmoney'];?></td>
											<td class="numeric"><?php echo $val['clearmoney'];?></td>
											<td class="numeric"><?php echo $val['signmoney'];?></td>
											<td class="numeric"><?php echo $val['freemoney'];?></td>
											<td class="numeric"><?php echo $val['depositmoney'];?></td>
											<td class="numeric"><?php echo $val['returndepositmoney'];?></td>
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
