<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ReCharge{
	public function getChargeVipCard($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getChargeVipCard($shopid);
	}
}
$recharge=new ReCharge();
$title="充值中心";
$menu="vip";
$clicktag="recharge";
$shopid=$_SESSION['shopid'];
$cardno="";
if(isset($_GET['userphone'])){
	$userphone=$_GET['userphone'];
}
require_once ('header.php');
$vipcardarr=$recharge->getChargeVipCard($shopid);
?>
<script src="./media/js/vip.js"></script>

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							充值中心
							 <small>充得多送得多</small>

						</h3>
	

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box green tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">充值中心</span>

								</div>

							</div>

							<div class="portlet-body form">

								<div class="tabbable portlet-tabs">

									<ul class="nav nav-tabs">
										<li>&nbsp;</li>
									</ul>
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/dorecharge.php" class="form-horizontal" method="post">
											<br>
												<div class="control-group">
													<label class="control-label">会员手机号</label>
													<div class="controls">
														<input type="tel" placeholder="必填，数字" name="userphone" id="userphone" onblur="checkUserphone(this.value)" value="<?php if(!empty($userphone)){echo $userphone;}?>" class="m-wrap large" />
														<span class="help-inline" id="cardnotip" style="color: red"></span>
													</div>
												</div>
											
												<div class="control-group" >
													<label class="control-label">充</label>
													<div class="controls">
														<input type="number" placeholder="必填，请输入数字" name="chargemoney" onblur="checkcharge(this.value)" class="m-wrap large" />
														<span class="help-inline" id="chargemoneytip" style="color: red"></span>
													</div>
												</div>
									
												<div class="form-actions">
													<button type="submit" class="btn blue" id="btnsave"><i class="icon-ok"></i> 保存</button>
												</div>
											</form>
											<!-- END FORM-->  
										</div>
									</div>
								</div>

							</div>

						</div>

						<!-- END SAMPLE FORM PORTLET-->

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
	if(isset($_GET['status'])){
		$status=$_GET['status'];
		switch ($status){
			case "phone_empty": echo "<script>alert('手机号不能为空！')</script>";break;
			case "cardid_empty":echo "<script>alert('请选择卡类型！')</script>";break;
			case "chargemoney_empty":echo "<script>alert('充值金额不能为空！')</script>";break;
			case "phone_unreg":echo "<script>alert('请先让顾客在“食趣点餐”公众账号内领取会员卡后再充值！')</script>";break;
		}
	}
	?>
