<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DonateAccount{
	
}
$donateaccount=new DonateAccount();
$title="赠送账户";
$menu="manage";
$clicktag="donateacccount";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
?>

<script src="./media/js/vip.js"></script>
			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							赠送账户
							 <small>给商家优惠</small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">首页</a> 

								<span class="icon-angle-right"></span>

							</li>

							<li>

								<a href="#">商家服务</a>

								<span class="icon-angle-right"></span>

							</li>

							<li><a href="donateaccount.php">赠送账户</a></li>

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

									<span class="hidden-480">赠送账户</span>

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
											<form action="./interface/dodonateaccount.php" class="form-horizontal" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
											
												<div class="control-group">
													<label class="control-label">商家账号</label>
													<div class="controls">
														<input type="tel" placeholder="必填，手机号码" name="shopphone" id="shopphone" class="m-wrap large" />
														<span class="help-inline" id="phonetip"></span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">赠送时长</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="donatemonth" >
														<option value="0">--未选择--</option>
														<?php for($i=1 ;$i<=24;$i++){?>
														<option value="<?php echo $i;?>"><?php echo $i;?></option>
														<?php }?>
														</select>
														<span class="help-inline" id="cardidtip"></span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">赠送原因</label>
													<div class="controls">
														<textarea  placeholder="选填"   name="donatereason" class="m-wrap large" /></textarea>
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
			case "ok": echo "<script>alert('赠送成功！')</script>";break;
			case "phone_unreg":echo "<script>alert('此手机号未注册！')</script>";break;
		}
	}
	?>
