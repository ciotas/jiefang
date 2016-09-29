<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SendVip{
	public function getChargeVipCard($shopid,$type){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getChargeVipCard($shopid,$type);
	}
}
$sendvip=new SendVip();
$title="送会员";
$menu="vip";
$clicktag="sendvip";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$vipcardarr=$sendvip->getChargeVipCard($shopid, "nostoreflag");

?>
<script src="./media/js/vip.js"></script>

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							送会员
							 <small>本店福利多多</small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">首页</a> 

								<span class="icon-angle-right"></span>

							</li>

							<li>

								<a href="#">我的会员</a>

								<span class="icon-angle-right"></span>

							</li>

							<li><a href="sendvip.php">送会员</a></li>

						</ul>

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box red tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">填写信息</span>

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
											<form action="./interface/dosendvip.php" class="form-horizontal" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
											
												<div class="control-group error">
													<label class="control-label">食趣账号</label>
													<div class="controls">
														<input type="tel" placeholder="必填，手机号码" name="userphone" id="userphone" onblur="checkphone(this.value)" class="m-wrap large" />
														<span class="help-inline" id="phonetip"></span>
													</div>
												</div>
												<div class="control-group error">
													<label class="control-label">类型</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="cardid"  id="cardid" onblur="checksendtype(this.value)">
														<option value="0">--未选择--</option>
														<?php foreach ($vipcardarr as $cdkey=>$cdval){?>
														<option value="<?php echo $cdval['cardid'];?>"><?php echo $cdval['cardname'];?></option>
														<?php }?>
														</select>
														<span class="help-inline" id="cardidtip"></span>
													</div>
												</div>
											
												<div class="form-actions">
													<button type="submit" class="btn blue"><i class="icon-ok"></i> 保存</button>
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
			case "sended":echo "<script>alert('此用户已赠送此卡，无需重复赠送！')</script>";break;
			case "phone_unreg":echo "<script>alert('此手机号未注册开饭啦app！')</script>";break;
		}
	}
	?>