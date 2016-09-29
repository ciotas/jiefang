<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SwitchPayer{
	public function getSeversByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getSeversByShopid($shopid);
	}
	public function checkPayerPwd($uid, $shopid,$passwd){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->checkPayerPwd($uid, $shopid, $passwd);
	}
	public function updateCashierMan($shopid, $uid){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->updateCashierMan($shopid, $uid);
	}
	public function getCashierMan($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCashierMan($shopid);
	}
	public function doCashiermanLogout($shopid){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->doCashiermanLogout($shopid);
	}
}
$switchpayer=new SwitchPayer();
$title="收银交班";
$menu="table";
$clicktag="switchpayer";
require_once ('header.php');
$shopid=$_SESSION['shopid'];
// echo $shopid;exit;
$serverarr=$switchpayer->getSeversByShopid($shopid);
// print_r($serverarr);exit;
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$passwd=$_POST['passwd'];
	$result=$switchpayer->checkPayerPwd($uid, $shopid, $passwd);
	switch ($result['status']){
		case "ok":echo '<script>alert("收银交班成功！")</script>';
			$switchpayer->updateCashierMan($shopid, $uid);
			break;
		case "error_pwd":echo '<script>alert("密码错误，请重试！")</script>';break;
		case "none":echo '<script>alert("此收银员未注册！")</script>';break;
	}
}
if(isset($_GET['op'])){
	$switchpayer->doCashiermanLogout($shopid);
}
$nowcashierman=$switchpayer->getCashierMan($shopid);
if(empty($nowcashierman)){
	$nowcashierman="未设置";
}

?>

				<div class="row-fluid">

					<div class="span12">
					<h3 class="page-title">
							收银交班
						</h3>
						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">首页</a> 

								<span class="icon-angle-right"></span>

							</li>

							<li>

								<a href="#">桌台</a>

								<span class="icon-angle-right"></span>

							</li>

							<li><a href="switchpayer.php">收银交班</a></li>

						</ul>

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-reorder"></i>选择收银员</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>
								</div>

							</div>

							<div class="portlet-body form">

								<!-- BEGIN FORM-->

								<form action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" method="post" autocomplete="off">

								
								<div class="control-group">

										<label class="control-label">当前收银员</label>

										<div class="controls">

										<label class="help-inline" style="color: red"><?php echo $nowcashierman;?></label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="./switchpayer.php?op=cashierlogout" class="btn middle red" ><i class="icon-key"></i> 退出</a>
										</div>

									</div>
									
									<div class="control-group">
										<label class="control-label">交接收银员</label>
										<div class="controls">
											<select class="medium m-wrap" tabindex="1" name="uid">
											<option value="0">---请选择---</option>
												<?php foreach ($serverarr as $skey=>$sval){?>
															<option value="<?php echo $sval['uid'];?>"><?php echo $sval['payer'];?></option>
											<?php }?>
														</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">此营业员密码</label>
										<div class="controls">
											<input type="text" onfocus="this.type='password'" placeholder="小跑堂登录密码"  name="passwd" class="m-wrap medium" autocomplete="off">
										</div>
									</div>
									
																		
									<div class="form-actions">

										<button type="submit" class="btn blue">确定</button>


									</div>

								</form>

								<!-- END FORM-->       

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
	?>