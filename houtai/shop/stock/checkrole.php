<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SwitchPayer{
	public function getSeversByShopid($shopid,$role="server"){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getSeversByShopid($shopid,$role);
	}
	public function checkPayerPwd($uid, $shopid,$passwd){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->checkPayerPwd($uid, $shopid, $passwd);
	}
}
$switchpayer=new SwitchPayer();
$title="管理员输入";
$menu="stock";
$clicktag="checkrole";
require_once ('../header.php');
$shopid=$_SESSION['shopid'];
$serverarr=$switchpayer->getSeversByShopid($shopid,"manager");
if(isset($_GET['page'])){
	$page=$_GET['page'];
}
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$page=$_POST['page'];
	$passwd=$_POST['passwd'];
	$result=$switchpayer->checkPayerPwd($uid, $shopid, $passwd);
	switch ($result['status']){
		case "ok": 
			$_SESSION['manager_id']=$uid;
			$_SESSION['manager_name']=$result['payer'];
			echo '<script>window.location.href="./'.$page.'.php";</script>';
			break;
		case "error_pwd":echo '<script>alert("密码错误，请重试！")</script>';break;
		case "none":echo '<script>alert("此管理员未注册！")</script>';break;
	}
}
?>

				<div class="row-fluid">

					<div class="span12">
					<h3 class="page-title">
							管理员权限验证
						</h3>
						

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-reorder"></i>选择管理员</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>
								</div>

							</div>

							<div class="portlet-body form">

								<!-- BEGIN FORM-->

								<form action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" method="post" autocomplete="off">									
									<input type="hidden" name="page" value="<?php echo $page;?>">
									<div class="control-group">
										<label class="control-label">交接管理员</label>
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
										<label class="control-label">管理员密码</label>
										<div class="controls">
											<input type="text" placeholder="小跑堂登录密码" onfocus="this.type='password'"  name="passwd" class="m-wrap medium" autocomplete="off">
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
	require_once ('../footer.php');
	?>