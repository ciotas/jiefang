<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class VipReg{
	public function getChargeVipCard($bossid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getChargeVipCard($bossid);
	}
	public function getBossidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getBossidByShopid($shopid);
	}
	public function getViptagsData($bossid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getViptagsData($bossid);
	}
}
$vipreg=new VipReg();
$title="会员注册";
$menu="vip";
$clicktag="vipreg";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$bossid=$vipreg->getBossidByShopid($shopid);
$vipcardarr=$vipreg->getChargeVipCard($bossid);
$tagarr=$vipreg->getViptagsData($bossid);
?>
<script src="./media/js/vip.js"></script>

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							会员注册
							 <small></small>
						</h3>
					</div>
				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">会员注册</span>

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
											<form action="./interface/doregvip.php" class="form-horizontal" method="post">
												<div class="control-group">
													<label class="control-label">姓名</label>
													<div class="controls">
														<input type="tel" placeholder="必填" name="realname" id="realname" onblur="checkBtnsave()" class="m-wrap large" />
														<span class="help-inline" id="nametip"  style="color:red"></span>
													</div>
												</div>
												<!-- <div class="control-group">
													<label class="control-label">会员卡号NO.</label>
													<div class="controls">
														<input type="number" placeholder="必填，数字" name="cardno" id="cardno" onblur="checkBtnsave()" class="m-wrap large" />
														<span class="help-inline" id="idsixtip" style="color:red">用于找回账户</span>
													</div>
												</div> -->
												<div class="control-group ">
													<label class="control-label">类型</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="cardid"  id="cardid" onblur="checktype(this.value)">
														<?php foreach ($vipcardarr as $cdkey=>$cdval){?>
														<option value="<?php echo $cdval['cardid'];?>"><?php echo $cdval['cardname'];?></option>
														<?php }?>
														</select>
														<span class="help-inline" id="cardidtip"></span>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">顾客标签</label>
												<div class="controls">
													<select data-placeholder="请选择标签" class="chosen m-wrap large" tabindex="-1" multiple id="selS0V" name="tagid[]">							
													<?php foreach ($tagarr as $tkey=>$tval){
														echo '<option value="'.$tval['tagid'].'" >'.$tval['tagname'].'</option>';
													}?>
													</select>
												</div>
											</div>
												<div class="control-group">
													<label class="control-label">手机号</label>
													<div class="controls">
														<input type="tel" placeholder="必填" name="userphone" id="userphone" onblur="checkreversephone(this.value,'<?php echo $shopid;?>')" class="m-wrap large" />
														<span class="help-inline" id="phonetip"  style="color:red"></span>
													</div>
												</div>
												
													<div class="control-group">
													<label class="control-label">验证码</label>
														<div class="controls">
														<div class="input-icon left">
														<i class=" icon-list-ol"></i>
														<input type="text" placeholder="4位数字验证码" class="m-wrap small"  id="phonecode" name="checkcode">
															<a class="btn purple"  onclick="showHint();">发送验证码 </a>
															</div>
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
			case "ok": $userphone=$_GET['userphone'];echo "<script>if(confirm('注册成功！是否去充值？')){window.location.href='./recharge.php?userphone=".$userphone." ';}</script>";break;
			case "sorry":echo "<script>alert('注册失败，请重试！')</script>";break;
		}
	}
	?>
