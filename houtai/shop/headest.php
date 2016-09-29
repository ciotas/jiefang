<?php 
require_once ('/var/www/html/houtai/shop/global.php');

?>
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title><?php echo $title;?></title>

<!-- 	<meta content="width=device-width, initial-scale=1.0" name="viewport" /> -->
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=2">
	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="<?php echo $base_url;?>media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/style.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/style-responsive.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

	<link href="<?php echo $base_url;?>media/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo $base_url;?>media/css/blue.css" rel="stylesheet" type="text/css" id="style_color"/>
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES --> 

	<link href="<?php echo $base_url;?>media/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>

 	<link href="<?php echo $base_url;?>media/css/daterangepicker.css" rel="stylesheet" type="text/css" /> 

	<link href="<?php echo $base_url;?>media/css/fullcalendar.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>media/css/select2_metro.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>media/css/jquery.tagsinput.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>media/css/clockface.css" />
	
	<link href="<?php echo $base_url;?>media/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo $base_url;?>media/css/jquery.fileupload-ui.css" rel="stylesheet" />
	<link href="<?php echo $base_url;?>media/css/bootstrap-fileupload.css" rel="stylesheet" type="text/css" />
	
	<link href="<?php echo $base_url;?>media/css/chosen.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo $base_url;?>media/css/profile.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $base_url;?>media/css/blog.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>media/css/multi-select-metro.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>media/css/jquery-ui-1.10.1.custom.min.css/">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>media/css/bootstrap-toggle-buttons.css" />
	
	<link href="<?php echo $base_url;?>media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo $base_url;?>media/css/invoice.css" rel="stylesheet" type="text/css"/>

	<link href="<?php echo $base_url;?>media/css/print.css" rel="stylesheet" type="text/css" media="print"/>

	<!-- END PAGE LEVEL STYLES -->

	<link rel="shortcut icon" href="<?php echo $base_url;?>media/image/toptag.png" />
	<script src="<?php echo $root_url;?>chart.js/Chart.js"></script>
	<script>
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?09030893c9d2b87fd5de3f707ee17beb";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	</script>
	
</head>

<!-- END HEAD -->
