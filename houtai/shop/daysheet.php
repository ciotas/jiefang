<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class DaySheet{
	public function getDaySheetData($shopid, $theday,$cashierman){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDaySheetData($shopid, $theday,$cashierman);
	}
	public function getSeversByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getSeversByShopid($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getShopServers($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopServers($shopid);
	}
}
$daysheet=new DaySheet();
$title="单日报表";
$menu="datasheet";
$clicktag="day";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$theday=$daysheet->getTheday($shopid);
$cashiermanarr=$daysheet->getShopServers($shopid);
// var_dump($cashiermanarr);exit;
$cashierman="all";
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
	$cashierman=$_GET['cashierman'];
}
$dayarr=$daysheet->getDaySheetData($shopid, $theday,$cashierman);
// print_r($dayarr);exit;
?>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./daysheet.php?theday="+theday+"&cashierman=all";
}

function getTheCashierman(){
	theday=document.getElementById("daydatepicker").value;
	thecashinerman=document.getElementById("cashierman").value;
	window.location.href="./daysheet.php?theday="+theday+"&cashierman="+thecashinerman;
}
</script>


				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">
						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid invoice">

					<div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-reorder"></i>单日汇总表 <?php if($cashierman!="all" && !empty($dayarr['switchtime'])){echo "【交接班时间：".date("Y-m-d H:i:s",$dayarr['switchtime'])."】";}?></div>

								<div class="tools">
								<form action="./interface/printcalc.php" method="post"  style="margin: 0;padding:0">
								<input type="hidden" name="theday" value="<?php echo $theday;?>" >
								<input type="hidden" name="data" value='<?php echo json_encode($dayarr);?>'>
								<input type="hidden" name="cashierman" value='<?php echo $cashierman;?>'>
								<button  type="submit"  class="btn green middle hidden-print" >小票打印 <i class="icon-print icon-big"></i></button>
								<a class="btn purple middle hidden-print" onclick="javascript:window.print();">A4打印 <i class="icon-print icon-big"></i></a>
								</form>
							
								</div>
							</div>
							<div class="portlet-body">
							<table style="margin:0">
								<tr>
								<td style="float: left">
								日期：<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
							</td>
								<td style="float: left">
									<label class="help-inline">收银员：</label>
										<select class="medium m-wrap" tabindex="1" id="cashierman" onchange="getTheCashierman();">
											<option value="all">---全部---</option>
											<?php foreach ($cashiermanarr as $onecashier){?>
											<option value="<?php echo $onecashier['serverid'];?>" <?php if($cashierman==$onecashier['serverid']){echo "selected";}?>><?php echo $onecashier['servername'];?></option>
											<?php }?>
											<option value="notpay" style="color:red" <?php if($cashierman=="notpay"){echo "selected";}?>>待收银</option>
											<option value="boss" style="color:red" <?php if($cashierman=="boss"){echo "selected";}?>>老板收银</option>
											<option value="customer" style="color:red" <?php if($cashierman=="customer"){echo "selected";}?>>线上支付</option>
										</select>
								</td>
								</tr>
								</table>
								<table class="table"  style="margin:0">
									<tr>
											<td><?php if(!empty($dayarr)){echo "销售总额："."￥".$dayarr['totalmoney'];}?></td>
											<td>
												<?php if($cashierman=="notpay"){?>
												<?php if(!empty($dayarr['cash_unpay'])){echo " 现金：".$dayarr['cash_unpay'];}?>
													<?php if(!empty($dayarr['mt_unpay'])){echo " 美团：".$dayarr['mt_unpay'];}?>
													<?php if(!empty($dayarr['dz_unpay'])){echo " 大众：".$dayarr['dz_unpay'];}?>
													<?php if(!empty($dayarr['nm_unpay'])){echo " 糯米：".$dayarr['nm_unpay'];}?>
													<?php if(!empty($dayarr['depositmoney'])){echo " 押金：".$dayarr['depositmoney'];}?>
													<?php if(!empty($dayarr['the100minus5'])){echo " 美团立减：".$dayarr['the100minus5'];}?>
												<?php }?>
											</td>
										</tr>
										<tr>
											<td>人数</td>
											<td>
												<?php if(!empty($dayarr)){echo $dayarr['cusnum'];}?>人
											</td>
										</tr>
										<tr>
											<td>下单数</td>
											<td>
												<?php if(!empty($dayarr)){echo $dayarr['billnum'];}?>单
											</td>
										</tr>

										<tr>
											<td>翻台率</td>
											<td>
												<?php if(!empty($dayarr)){echo $dayarr['changerate'];}?>
											</td>
										</tr>
										<tr>
											<td>人均消费</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['avgmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>应收款</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['receivablemoney'];}?>
											</td>
										</tr>
										<tr>
											<td>现金</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['cashmoney'];}?>
											</td>
										</tr>
										
										<tr>
											<td>银联卡</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['unionmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>会员卡</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['vipmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>美团账户</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['meituanpay'];}?>
											</td>
										</tr>
										<tr>
											<td>大众账户</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['dazhongpay'];}?>
											</td>
										</tr>
										<tr>
											<td>糯米账户</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['nuomipay'];}?>
											</td>
										</tr>
											<tr>
											<td>其他</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['otherpay'];}?>
											</td>
										</tr>
										<tr>
											<td>支付宝</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['alipay'];}?>
											</td>
										</tr>
										<tr>
											<td>微信支付</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['wechatpay'];}?>
											</td>
										</tr>
										<!-- <tr>
											<td>其他收入</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['othermoney'];}?>
											</td>
										</tr> -->
										<?php foreach ($dayarr['ticket'] as $ticketway=>$ticketval){?>
										<tr>
											<td><?php echo $ticketval['ticketname'];?></td>
											<td>
												￥<?php echo $ticketval['ticketmoney'];?>
											</td>
										</tr>
										<?php }?>
										<tr>
											<td>折扣额</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['discountmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>抹零</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['clearmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>签单</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['signmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>免单</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['freemoney'];}?>
											</td>
										</tr>
										<tr>
											<td>收押金</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['depositmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>退押金</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['returndepositmoney'];}?>
											</td>
										</tr>
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
	require_once ('footer.php');
	?>