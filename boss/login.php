<?php 
if(!empty($_COOKIE['bossid'])){
	header("location: index.php");exit;
}
$loginerror="";
if(isset($_GET['status'])){
	if($_GET['status']=="error"){
		$loginerror="用户名或密码错误！";
	}elseif($_GET['status']=="ok"){
		$result=json_decode($_GET['result'],true);
		$bossid=$result['bossid'];
		$bossname=$result['bossname'];
		$bosslogo=$result['bosslogo'];
		$_SESSION['bossid'] = $bossid;
		$_SESSION['bossname'] = $bossname;
		$_SESSION['bosslogo'] = $bosslogo;
		setcookie('bossid', $bossid, time() + (60 * 30));    // expires in 30 mins
		setcookie('bossname', $bossname, time() + (60 * 30));
		setcookie('bosslogo', $bosslogo, time() + (60 * 30));
		header("location: ./index.php");
	}
}
?>
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>总店登录</title>

	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=2">
	
	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-responsive.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

	<link href="media/css/uniform.default.css" rel="stylesheet" type="text/css"/>

	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="media/css/login.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->

	<script src="./media/js/boss.js"></script> 
</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="login">

	<!-- BEGIN LOGO -->

	<div class="logo">
		
	</div>

	<!-- END LOGO -->

	<!-- BEGIN LOGIN -->

	<div class="content">

		<!-- BEGIN LOGIN FORM -->

		<form class="form-vertical login-form" action="./interface/dologin.php" method="post">

			<h3 class="form-title">登录您的总店后台</h3>

			<div class="alert alert-error hide">

				<button class="close" data-dismiss="alert"></button>

				<span>请输入账户名或密码.</span>

			</div>
			
			<?php if(!empty($loginerror)){?>
			<div class="alert alert-error ">

				<button class="close" data-dismiss="alert"></button>

				<span><?php echo $loginerror;?></span>

			</div><?php }?>
			<div class="control-group">

				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

				<label class="control-label visible-ie8 visible-ie9">账号</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="text" placeholder="手机号" name="bossphone"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">密码</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-lock"></i>

						<input class="m-wrap placeholder-no-fix" type="password" placeholder="密码" name="password"/>

					</div>

				</div>

			</div>

			<div class="form-actions">

				<button type="submit" class="btn green pull-right">

				登录 <i class="m-icon-swapright m-icon-white"></i>

				</button>            

			</div>
<!-- 
			<div class="forget-password">

				<h4>忘记密码 ?</h4>

				<p>

					别着急, 点击 <a href="javascript:;" class="" id="forget-password">这里</a>

					重置密码.

				</p>

			</div>
 -->
			<div class="create-account">

				<p>

					还没有账户?&nbsp; 

					<a href="javascript:;" id="register-btn" class="">创建你的账户</a>

				</p>

			</div>

		</form>

		<!-- END LOGIN FORM -->        

		<!-- BEGIN FORGOT PASSWORD FORM -->

		<form class="form-vertical forget-form" action="index.html">

			<h3 class="">忘记密码 ?</h3>

			<p>请输入您的账号.</p>

			<div class="control-group">

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-envelope"></i>

						<input class="m-wrap placeholder-no-fix" type="text" placeholder="手机号" name="bossphone" />

					</div>

				</div>

			</div>

			<div class="form-actions">

				<button type="button" id="back-btn" class="btn">

				<i class="m-icon-swapleft"></i> 返回

				</button>

				<button type="submit" class="btn green pull-right">

				提交 <i class="m-icon-swapright m-icon-white"></i>

				</button>            

			</div>

		</form>

		<!-- END FORGOT PASSWORD FORM -->

		<!-- BEGIN REGISTRATION FORM -->

		<form class="form-vertical register-form" action="./interface/doreg.php" method="post">

			<h3 class="">注册</h3>

			<p></p>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">账号</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="text" placeholder="手机号" name="bossphone"   id="telphone" onblur="validatemobile();"/>

					</div>

				</div>

			</div>
			
			<div class="control-group">
			<div class="controls">
			<div class="input-icon left">
			<i class=" icon-list-ol"></i>
			<input type="text" placeholder="4位数字验证码" class="m-wrap small"  id="phonecode" name="checkcode">
				<button class="btn purple"  onclick="showHint();">发送验证码 <i class="m-icon-swapright m-icon-white"></i></button>
				</div>
			</div>
				
			</div>
			
			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">密码</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-lock"></i>

						<input class="m-wrap placeholder-no-fix" type="password"  id="pwd1" onblur="checkpwd1();"placeholder="密码" name="password"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">再次输入</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-ok"></i>

						<input class="m-wrap placeholder-no-fix" type="password" placeholder="再次输入密码"  id="pwd2" onblur="checkpwd2();" name="rpassword"/>

					</div>

				</div>

			</div>
			
			<div class="control-group">

				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

				<label class="control-label visible-ie8 visible-ie9">商家名</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-globe"></i>

						<input class="m-wrap placeholder-no-fix" type="text" id="bossname"  onblur="checkbossname();" placeholder="商家总店名" name="bossname"/>

					</div>

				</div>

			</div>

			<div class="form-actions">

				<button id="register-back-btn" type="button" class="btn">

				<i class="m-icon-swapleft"></i>  返回

				</button>

				<button type="submit" id="register-submit-btn" class="btn green pull-right">

				注册 <i class="m-icon-swapright m-icon-white"></i>

				</button>            

			</div>

		</form>

		<!-- END REGISTRATION FORM -->

	</div>

	<!-- END LOGIN -->

	<!-- BEGIN COPYRIGHT -->

	<div class="copyright">

		2014 &copy; 杭州街坊科技有限公司

	</div>

	<!-- END COPYRIGHT -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

	<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="media/js/excanvas.min.js"></script>

	<script src="media/js/respond.min.js"></script>  

	<![endif]-->   

	<!-- END PAGE LEVEL PLUGINS -->
	<script src="media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="media/js/jquery.validate.min.js" type="text/javascript"></script>

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/app.js" type="text/javascript"></script>

	<script src="media/js/login.js" type="text/javascript"></script>      

	<!-- END PAGE LEVEL SCRIPTS --> 

	<script>

		jQuery(document).ready(function() {     

		  App.init();

		  Login.init();

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>