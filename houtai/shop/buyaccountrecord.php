<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BuyAccountRecord{
	public function getBuyAccountRecords($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getBuyAccountRecords($shopid);
	}
}
$buyaccountrecord=new BuyAccountRecord();
$title="购买账户记录";
$menu="profile";
$clicktag="buyaccountrecord";
$vcid="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$buyaccountrecord->getBuyAccountRecords($shopid);
// print_r($arr);exit;
?>

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							购买账户记录 <small> </small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">商家中心</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="buyaccountrecord.php">购买账户记录</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>交易记录</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>支付宝交易号</th>
											<th>所用支付宝账户</th>
											<th>购买账户类型</th>
											<th>付款金额</th>
											<th>账户到期时间</th>
											<th>付款时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									switch ($val['buytype']){
										case "permonth":$buytype="月账户"; break;
										case "perhalfyear":$buytype="半年账户"; break;
										case "peryear":$buytype="年账户"; break;
										}
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['alipaytradeno'];?></td>
											<td><?php echo $val['buyer_email'];?></td>
											<td><?php echo $buytype;?></td>
											<td><?php echo $val['paymoney'];?></td>
											<td><?php echo date("Y-m-d H:i:s", $val['endtime']);?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['paytime']);?></td>
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