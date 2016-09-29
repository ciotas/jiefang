<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Dailysheet{
	public function getDailySheetData($shopid, $starttime, $endtime,$type){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDailySheetData($shopid, $starttime, $endtime,$type);
	}
	public function getFuncRole($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFuncRole($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getDecreasenum($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getDecreasenum($shopid);
	}
}
$dailysheet=new Dailysheet();
$title="日流水";
$menu="datasheet";
$clicktag="daily";
$shopid=$_SESSION['shopid'];
require_once ('./finance_header.php');
$theday=$dailysheet->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$decreasenum=$dailysheet->getDecreasenum($shopid);
$dailyarr=$dailysheet->getDailySheetData($shopid, $theday, "","real");
// print_r($dailyarr);exit;
$rolearr=$dailysheet->getFuncRole($shopid);
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
$clearmoney=0;
$othermoney=0;
$clearmoney=0;
$signmoney=0;
$freemoney=0;
$discountmoney=0;
$ticketmoney=0;
$tdepositmoney=0;
$treturndepositmoney=0;
foreach ($dailyarr as $rkey=>$rval){
	$totalmoney+=$rval['totalmoney'];
	$billnum++;
	$cusnum+=$rval['cusnum'];	
	$cashmoney+=$rval['cashmoney'];
	$unionmoney+=$rval['unionmoney'];
	$vipmoney+=$rval['vipmoney'];
	$meituanpay+=$rval['meituanpay'];
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
$receivablemoney+=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$alipay+$wechatpay+$ticketmoney+$tdepositmoney;

?>

<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
// 	alert(theday)
	window.location.href="./dailysheet.php?theday="+theday;
}
</script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid invoice">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid invoice">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>每日流水</div>
								<div class="tools">
								<a class="btn purple middle hidden-print" onclick="javascript:window.print();">打印 <i class="icon-print icon-big"></i></a>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
							<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
											<th class="numeric"><h4>总人数</h4></th>
											<th class="numeric"><h4>下单总数</h4></th>
											<th class="numeric"><h4>销售总额</h4></th>
											<th class="numeric"><h4>其他收入</h4></th>
											<th class="numeric"><h4>应收款</h4></th>
											<th class="numeric"><h4>现金</h4></th>
											<th class="numeric"><h4>银联卡</h4></th>
											<th class="numeric"><h4>会员卡</h4></th>
											<th class="numeric"><h4>美团账户</h4></th>
											<th class="numeric"><h4>支付宝</h4></th>
											<th class="numeric"><h4>微信支付</h4></th>
											
											<th class="numeric"><h4>券</h4></th>
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
											<td class="numeric"><h4><?php echo $decreasenum*$billnum;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$totalmoney?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$othermoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$receivablemoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$cashmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$unionmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$vipmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$meituanpay;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$alipay;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$wechatpay;?></h4></td>
											
											<td class="numeric"><h4><?php echo $decreasenum*$ticketmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$discountmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$clearmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$signmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$freemoney?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$tdepositmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $decreasenum*$treturndepositmoney;?></h4></td>
										</tr>
										</tbody>
									
								</table>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">序号</th>
											<th class="numeric">台号</th>
											<th class="numeric">人数</th>
											<th class="numeric">销售额</th>
											<th class="numeric">其他收入</th>
											<th class="numeric">应收款</th>
											<th class="numeric">现金</th>
											<th class="numeric">银联卡</th>
											<th class="numeric">会员卡</th>
											<th class="numeric">美团账户</th>
											<th class="numeric">支付宝</th>
											<th class="numeric">微信支付</th>
											
											<th class="numeric">券</th>
											<th class="numeric">券种</th>
											<th class="numeric">折扣值</th>
											<th class="numeric">折扣额</th>
											<th class="numeric">抹零</th>
											<th class="numeric">签单</th>
											<th class="numeric">免单</th>
											<th class="numeric">收押金</th>
											<th class="numeric">退押金</th>
											<th class="numeric">下单人</th>
											<th class="numeric">收银员</th>
											<th class="numeric">下单时间</th>
											<th class="numeric">买单时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($dailyarr as $key=>$val){?>
										<tr>
										    <td class="numeric"><?php echo ++$key;?></td>
										    <td class="numeric"><?php echo $val['tabname'];?></td>
										     <!-- <td class="numeric"><?php echo $val['billid'];?></td> -->
											<td class="numeric"><?php echo $decreasenum*$val['cusnum'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['totalmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['othermoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['receivablemoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['cashmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['unionmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['vipmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['meituanpay'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['alipay'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['wechatpay'];?></td>
											
											<td class="numeric"><?php echo $decreasenum*$val['ticketmoney'];?></td>
											<td class="numeric"><?php echo $val['ticketname'];?></td>
											<td class="numeric"><?php echo $val['discountval'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['discountmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['clearmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['signmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['freemoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['depositmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['returndepositmoney'];?></td>
											<td class="numeric"><?php echo $val['nickname'];?></td>
											<td class="numeric"><?php echo $val['cashierman'];?></td>
											<td class="numeric"><?php echo date("m-d H:i",$val['timestamp']);?></td>
											<td class="numeric"><?php echo $val['buytime']?></td>
										</tr>
										<?php }?>
									</tbody>

								</table>

							</div>
						<?php foreach ($dailyarr as $key1=>$val1){?>
							<div id="static_<?php echo $val1['billid'];?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
							<div class="modal-body span12">
								<form method="post" action="../interface/cashier.php" >
									<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
									<input type="hidden" name="billid"  value="<?php echo $val1['billid'];?>">
									<input type="hidden" name="clearmoney"  value="<?php echo $val1['clearmoney'];?>">
									<input type="hidden" name="reprint" value="yes">
									<button type="submit"  class="btn blue" >重打结账单</button>
									<?php if($rolearr['repay']=="1"){?>
									<button type="button"  class="btn red"  onclick="window.location.href='../paypage.php?billid=<?php echo $val1['billid'];?>&type=repay ' ">重新收银</button>
									<?php }?>
								</form>
								<div class="tab-pane  active" id="portlet_tab3">
												<h4>点菜详细</h4>
												<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
												<thead class="flip-content">
												<tr>
												<th>菜品</th>
												<th>价格</th>
												<th>数量</th>
												<th>单位</th>
												<th>金额</th>
												<th>赠送</th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($val1['food'] as $fkey=>$fval){
													$donatestr="/";
													if($fval['present']=="1"){
														$donatestr='<span class="label label-success">赠</span>';
													}
													?>
												<tr>
												<td><?php echo $fval['foodname'];?></td>
												<td><?php echo $fval['foodprice'];?></td>
												<td><?php echo $decreasenum*$fval['foodnum'];?></td>
												<td><?php echo $fval['orderunit'];?></td>
												<td><?php echo $decreasenum*$fval['foodmoney'];?></td>
												<td><?php echo $donatestr;?></td>
												</tr>
												<?php }?>
												</tbody>
												</table>
											</div><br>
											<button type="button" data-dismiss="modal" class="btn">取消</button>
											
											
									</div>
								</div>	
					<?php }?>
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
