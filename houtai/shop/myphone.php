<?php 
// require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class MyPhpne{
	public function getUserphoneByuid($uid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getUserphoneByuid($uid);
	}
}
$myphone=new MyPhpne();
$uid=$_REQUEST['uid'];
$arr=$myphone->getUserphoneByuid($uid);
$myphoneno="";
$buttontip="立即绑定";
if($arr['status']=="exist"){
	$myphoneno=$arr['telphone'];
	$buttontip="绑定新手机";
}
?>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>绑定手机</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>


	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>


	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES --> 
	<script>
	window.onload=function(){
	}
	
	</script>
	
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
							绑定手机
							 <small></small>

						</h3>
	

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">
						<h2><?php if(!empty($myphoneno)){echo $myphoneno;}else{echo "未绑定";}?></h2>
						<br><br>
						<a href="./bindphone.php?uid=<?php echo $uid;?>" class="btn blue btn-block"><?php echo $buttontip;?> <i class="m-icon-swapright m-icon-white"></i></a><br>
						
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

</body>

<!-- END BODY -->

</html>
