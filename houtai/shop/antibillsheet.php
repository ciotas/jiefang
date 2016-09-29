<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AntiBillSheet{
	public function getAntiBillAndBill($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getAntiBillAndBill($shopid, $theday);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
}
$antibillsheet=new AntiBillSheet();
$menu="business";
$title="反结表";
$clicktag="antibill";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=$antibillsheet->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$antibillsheet->getAntiBillAndBill($shopid, $theday);
// print_r($arr);exit;
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
// 	alert(theday)
	window.location.href="./antibillsheet.php?theday="+theday;
}
</script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							反结表 <small></small>

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
								<div class="caption"><i class="icon-table"></i>反结表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<div class="control-group pull-left margin-right-20">
								<div class="controls">
									<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</div>
							</div>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th>#</th>
											<th>台号</th>
											<th>类型</th>
											<th>销售额</th>
											<th>现金</th>
											<th>银联卡</th>
											<th>会员卡</th>
											<th>美团账户</th>
											<th>支付宝</th>
											<th>微信支付</th>
											<th>券</th>
											<th>券种</th>
											<th>折扣</th>
											<th>抹零</th>
											<th>收押金</th>
											<th>退押金</th>
											<th>收银员</th>
											<th>买单时间</th>
											<th>反结时间</th>
										</tr>
										
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
									<tr>
											<td rowspan="3"><?php echo ++$key;?></td>
											<td rowspan="3"><?php echo $val['tabname'];?></td>
											<td>原单</td>
											<td><?php echo $val['old']['totalmoney'];?></td>
											<td><?php echo $val['old']['cashmoney'];?></td>
											<td><?php echo $val['old']['unionmoney'];?></td>
											<td><?php echo $val['old']['vipmoney'];?></td>
											<td><?php echo $val['old']['meituanpay'];?></td>
											<td><?php echo $val['old']['alipay'];?></td>
											<td><?php echo $val['old']['wechatpay'];?></td>
											<td><?php echo $val['old']['ticket'];?></td>
											<td><?php echo $val['old']['ticketname'];?></td>
											<td><?php echo $val['old']['discountval'];?></td>
											<td><?php echo $val['old']['clearmoney'];?></td>
											<td><?php echo $val['old']['depositmoney'];?></td>
											<td><?php echo $val['old']['returndepositmoney'];?></td>
											<td><?php echo $val['old']['cashierman'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['old']['buytime']);?></td>
											<td rowspan="3"><?php echo date("Y-m-d H:i:s",$val['antitime']);?></td>
										</tr>
										
										<tr>
											<td>新单</td>
											<td><?php echo $val['new']['totalmoney'];?></td>
											<td><?php echo $val['new']['cashmoney'];?></td>
											<td><?php echo $val['new']['unionmoney'];?></td>
											<td><?php echo $val['new']['vipmoney'];?></td>
											<td><?php echo $val['new']['meituanpay'];?></td>
											<td><?php echo $val['new']['alipay'];?></td>
											<td><?php echo $val['new']['wechatpay'];?></td>
											<td><?php echo $val['new']['ticket'];?></td>
											<td><?php echo $val['new']['ticketname'];?></td>
											<td><?php echo $val['new']['discountval'];?></td>
											<td><?php echo $val['new']['clearmoney'];?></td>
											<td><?php echo $val['new']['depositmoney'];?></td>
											<td><?php echo $val['new']['returndepositmoney'];?></td>
											<td><?php echo $val['new']['cashierman'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['new']['buytime']);?></td>
										</tr>
										<tr style="height: 5">
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
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
