<?php 
// require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BindPhone{
	
}
$bindphone=new BindPhone();
$title="我的手机号";
$uid=$_REQUEST['uid'];

?>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>绑定手机号</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>

	<script src="./media/js/cus.js"></script> 
</head>

<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- BEGIN HEADER -->

	<!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
<!-- BEGIN SIDEBAR -->

		<div class="page-content">

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							绑定手机号

						</h3>
	
					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<div class="portlet-body form">

								<div class="tabbable portlet-tabs">

									<ul class="nav nav-tabs">
										<li>&nbsp;</li>
									</ul>
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/dobindphone.php" class="form-horizontal" method="post">
												<input type="hidden" name="uid" value="<?php echo $uid;?>">
											
												<div class="control-group">
													<label class="control-label">手机号</label>
													<div class="controls">
													<input class="m-wrap span12" type="tel" placeholder="手机号" name="phone"  id="telphone" onblur="validatemobile();" />
													</div>
												</div>
												
												<div class="control-group">
												<div class="controls">
												<div class="input-icon left">
												<i class=" icon-list-ol"></i>
												<input type="text" placeholder="4位数字验证码" class="m-wrap small"  id="phonecode" name="checkcode">
													<a class="btn green"  onclick="sendcode();">发送验证码 </a>
													</div>
												</div>
													
												</div>
												<a class="btn red" href="./myphone.php?uid=<?php echo $uid;?>"><i class="icon-remove"></i> 返回</a>
												<button type="submit" class="btn blue"><i class="icon-ok"></i> 提交</button>
												
											</form>
											<!-- END FORM-->  
										</div>
									</div>
								</div>

							</div>
					</div>

				</div>

				<!-- END PAGE CONTENT-->         

			</div>

			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->  

	</div>

	<!-- END CONTAINER -->

	<div class="footer">

		<div class="footer-inner">


		</div>

	</div>

	<!-- END FOOTER -->


	<!-- END JAVASCRIPTS -->
<?php 
if(isset($_GET['status'])){
	$status=$_GET['status'];
	if($status=="codeerror"){
		echo '<script>alert("验证码输入错误！")</script>';
	}
}
?>
</body>

<!-- END BODY -->

</html>
