<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (_ROOT.'boss/Factory/InterfaceFactory.php');
class MyPurchaseBill{
	public function getBuyGoodsRecord($id,$type,$theday){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getBuyGoodsRecord($id,$type,$theday);
	}
}
$mypurchasebill=new MyPurchaseBill();
$title="我的订单";
$menu="profile";
$clicktag="mypurchasebill";
require_once ('header.php');
$shopid=$_SESSION['shopid'];
$arr=array();
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$mypurchasebill->getBuyGoodsRecord($shopid,"shopid", $theday);
?>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./mypurchasebill.php?theday="+theday;
}
</script>
	<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid invoice">

					<div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-reorder"></i>我的订单 </div>

								<div class="tools">
							
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
							
								</tr>
								</table>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">单号</th>
											<th class="numeric">支付宝流水号</th>
											<th class="numeric">商品</th>
											<th class="numeric">购买数量</th>
											<th class="numeric">支付方式</th>
											<th class="numeric">交易金额</th>
											<th class="numeric">付款时间</th>									
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									switch ($val['paytype']){
										case "alipay_wap":$paytype="支付宝网页";
									}
										?>
										<tr>
										    <td class="numeric"><?php echo $val['out_trade_no'];?></td>
											<td class="numeric"><?php echo $val['trade_no'];?></td>
											<td class="numeric"><?php echo $val['goodsname'];?></td>
											<td class="numeric"><?php echo $val['soldamount'].$val['soldunit'];?></td>
											<td class="numeric"><?php echo $paytype;?></td>
											<td class="numeric"><?php echo $val['total_fee'];?></td>
											<td class="numeric"><?php echo $val['buytime'];?></td>
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
	require_once ('footer.php');
	?>