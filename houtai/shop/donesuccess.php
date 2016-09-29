<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class DoneSuccess{
	
}
$donesuccess=new DoneSuccess();
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$type=$_GET['type'];
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<title>下单成功！</title>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="./media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="./media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="./media/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="./media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="./media/css/profile.css" rel="stylesheet" type="text/css" />

</head>
<body>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

			<div class="container-fluid">
				<div class="span12">
						<button class="btn green big btn-block">下单成功，请等待服务员确认，谢谢使用~</button>
					</div>
				<div class="row-fluid profile">

					<div class="span12">

						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->
							
								 <br>
								<h4 style="color: #32CD32;">&nbsp;&nbsp;您还可以返回首页到我的订单里，直接买单！</h4>
								<div style="text-align:center"><img src="http://shop.meijiemall.com/weshop/shopmedia/img/kfl.jpg" width="70%"></div>
								<br><br>
							<a class="btn yellow big btn-block" target="_blank" href="<?php echo $root_url."weshop/shopindex.php?shopid=$shopid";?>&type=">返回首页</a> 
								<!--end tab-pane-->
						</div>

						<!--END TABS-->

					</div>

				</div>

				<!-- END PAGE CONTENT-->

			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->

	<div class="footer">


	</div>

</body>

<!-- END BODY -->

</html>	