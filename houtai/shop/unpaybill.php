<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class UnpayBill{
	public function getDailySheetData($shopid, $starttime, $endtime,$type){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDailySheetData($shopid, $starttime, $endtime,$type);
	}
	public function getFuncRole($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFuncRole($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}

}
$unpaybill=new UnpayBill();
$title="未结账单";
$menu="datasheet";
$clicktag="unpaybill";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$theday=$unpaybill->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}

$dailyarr=$unpaybill->getDailySheetData($shopid, $theday, "","unpay");
// print_r($dailyarr);exit;
$rolearr=$unpaybill->getFuncRole($shopid);
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
$receivablemoney+=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney+$tdepositmoney;

?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./unpaybill.php?theday="+theday;
}

function returnNum(billid,foodid,foodnum,cooktype,theday){
	var returnnum=prompt("请输入退菜量（只能为数字）","");
	if(isInteger(returnnum)){
		  if(returnnum){
			  if(returnnum<=0 || returnnum>foodnum){
				  alert("请输入折扣值在1~"+foodnum+"之间的正整数！");
			  }else{
				  	url="./interface/returnonefood.php?billid="+billid+"&foodid="+foodid+"&foodnum="+foodnum+"&cooktype="+cooktype+"&returnnum="+returnnum+"&theday="+theday;
					window.location.href=url;
			  }
		  }
	  }
}
function isInteger(number){
	return number > 0 && String(number).split('.')[1] == undefined
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
						
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>未结账单</div>
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
											
											<td class="numeric"><h4><?php echo $ticketmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $discountmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $clearmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $signmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $freemoney?></h4></td>
											<td class="numeric"><h4><?php echo $tdepositmoney;?></h4></td>
											<td class="numeric"><h4><?php echo $treturndepositmoney;?></h4></td>
										</tr>
										</tbody>
									
								</table>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">#</th>
											<th class="numeric">台号</th>
											<th class="numeric">人数</th>
											<th class="numeric">销售额</th>
<!-- 										<th class="numeric">其他收入</th> -->
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
											<th class="numeric">详单</th>
											<?php if($_SESSION['role']=="manager"){?>
											 <th class="numeric">操作</th> 
											<?php }?>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($dailyarr as $key=>$val){?>
										<tr>
										    <td class="numeric"><?php echo ++$key;?></td>
										    <?php if(!empty($val['tabname'])){?>
										    <td class="numeric"><?php echo $val['tabname'];?></td>
										    <?php }else{?>
										     <td class="numeric">外卖</td>
										    <?php }?>
										<!--   <td class="numeric"><?php echo $val['billid'];?></td> -->  
											<td class="numeric"><?php echo $val['cusnum'];?></td>
											<td class="numeric"><?php echo $val['totalmoney'];?></td>
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
											
											<td class="numeric"><?php echo $val['ticketmoney'];?></td>
											<td class="numeric"><?php echo $val['ticketname'];?></td>
											<td class="numeric"><?php echo $val['discountval'];?></td>
											<td class="numeric"><?php echo $val['discountmoney'];?></td>
											<td class="numeric"><?php echo $val['clearmoney'];?></td>
											<td class="numeric"><?php echo $val['signmoney'];?></td>
											<td class="numeric"><?php echo $val['freemoney'];?></td>
											<td class="numeric"><?php echo $val['depositmoney'];?></td>
											<td class="numeric"><?php echo $val['returndepositmoney'];?></td>
											<td class="numeric"><?php echo $val['nickname'];?></td>
											<td class="numeric"><?php echo $val['cashierman'];?></td>
											<td class="numeric"><?php echo date("m-d H:i",$val['timestamp']);?></td>
											<td class="numeric"><?php echo $val['buytime']?></td>
											<td class="numeric"><a href="#static_<?php echo $val['billid'];?>"  class="btn mini blue" data-toggle="modal" ><i class="icon-table"></i> </a></td>
										<?php if($_SESSION['role']=="manager"){?>
											<td class="numeric"><a href="./interface/delonebill.php?billid=<?php echo $val['billid'];?>&theday=<?php echo $theday;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a></td> 
										<?php }?>
										</tr>
										<?php }?>
									</tbody>

								</table>
							</div>
						<?php foreach ($dailyarr as $key1=>$val1){?>
							<div id="static_<?php echo $val1['billid'];?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
							<div class="modal-body span12">
								<button type="button"  class="btn green"  onclick="window.location.href='./paypage.php?billid=<?php echo $val1['billid'];?>&type=repay ' ">收银</button>
									<button type="button" data-dismiss="modal" class="btn">取消</button>
								<div class="tab-pane  active" id="portlet_tab3">
												<h4>点菜详细 <span style="color: red;"><?php if($_SESSION['role']=="manager"){echo "（在这里退菜后，请重新收银）"; }?></span></h4>
												<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
												<thead class="flip-content">
												<tr>
												<th>菜品</th>
												<th>价格</th>
												<th>数量</th>
												<th>单位</th>
												<th>金额</th>
												<th>赠送</th>
												<?php if($_SESSION['role']=="manager"){?>
												<th>操作</th>
												<?php }?>
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
												<td><?php echo $fval['foodnum'];?></td>
												<td><?php echo $fval['orderunit'];?></td>
												<td><?php echo $fval['foodmoney'];?></td>
												<td><?php echo $donatestr;?></td>
												<?php if($_SESSION['role']=="manager"){?>
												<td><button onclick="returnNum('<?php echo $val1['billid'];?>','<?php echo $fval['foodid']?>','<?php echo $fval['foodnum']?>','<?php echo $fval['cooktype'];?>','<?php echo $theday;?>')" class="btn mini red"><i class="icon-trash"></i> 退菜</button></td>
												<?php }?>
												</tr>
												<?php }?>
												</tbody>
												</table>
											</div><br>
																						
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
require ('footer.php');
?>
