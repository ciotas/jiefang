<?php 
if(!empty($_COOKIE['shopid'])){
	header("location: index.php");
}
?>
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>登录</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="趣店后台" name="description" />

	<meta content="趣店，杭州街坊科技有限公司" name="author" />

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

	<link href="media/css/login-soft.css" rel="stylesheet" type="text/css"/>
	

	<!-- END PAGE LEVEL STYLES -->

	<link rel="shortcut icon" href="media/image/toptag.png" />
	<script src="./media/js/boss.js"></script> 
</head>

<!-- END HEAD -->

<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');

class Login{
	public function getShopInfo($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getShopInfo($shopid);
	}
}
$login=new Login();
$loginerror="";
if(isset($_GET['loginerr'])){
	$loginerr=$_GET['loginerr'];
	switch ($loginerr){
		case "emptyphone":$loginerror="手机号不能为空！";break;
		case "emptypwd":$loginerror="密码不能为空！";break;
		case "error":$loginerror="账号或密码错误！";break;
		case "none":$loginerror="此账号未注册，请先在注册趣店账户后再登录";break;
		case "none_my_server":$loginerror="您不是本店服务员，无法登陆！";break;
		case "none_server_reg":$loginerror="您尚未注册小跑堂账号";break;
		case "ok":
			$shopid=base64_decode($_GET['shopid']);
			$shopname=$_GET['shopname'];
			$logo=base64_decode($_GET['logo']);
			$role=base64_decode($_GET['role']);
			$servername=$_GET['servername'];
			$_SESSION['shopid'] = $shopid;
			$_SESSION['shopname'] = $shopname;
			$_SESSION['roleid']=$_GET['roleid'];
			$_SESSION['serverid']=$_GET['serverid'];
			$_SESSION['logo']=$logo;
			$_SESSION['role']=$role;
			$_SESSION['servername']=$servername;
			setcookie('shopid', $shopid, time() + (60 * 60 * 3));   
			setcookie('shopname', $shopname, time() + (60 * 60 * 3));  
			setcookie('logo', $logo, time() + (60 * 60 * 3)); 
			setcookie('roleid', $_GET['roleid'], time() + (60 * 60 * 3));
			setcookie('serverid', $_GET['serverid'], time() + (60 * 60 * 3));
			setcookie('role', $role, time() + (60 * 60 * 3));
			setcookie('servername', $servername, time() + (60 * 60 * 3));
			header("location: ./index.php");
	}
}

if(isset($_GET['status'])){
	
}
?>

<!-- BEGIN BODY -->

<body class="login">

	<!-- BEGIN LOGO -->

	<div class="logo">
		<!-- img -->

	</div>

	<!-- END LOGO -->

	<!-- BEGIN LOGIN -->

	<div class="content">

		<!-- BEGIN LOGIN FORM -->

		<form class="form-vertical login-form" method="post" action="./interface/dologin.php">
			<h3 class="form-title">登录您的商家账户</h3>
		<?php if(!empty($loginerror)){?>
			<div class="alert">
				<span><?php echo $loginerror;?></span>
			</div>
		<?php }?>
			
			<div class="control-group">

				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

				<label class="control-label visible-ie8 visible-ie9">商家账号</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="tel" placeholder="商家手机号" name="telphone" value=""/>

					</div>

				</div>

			</div>
			<div class="control-group">

				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

				<label class="control-label visible-ie8 visible-ie9">我的账号</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="tel" placeholder="我的手机号" name="serverphone" value=""/>

					</div>

				</div>

			</div>
			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">我的密码</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-lock"></i>

						<input class="m-wrap placeholder-no-fix" type="password" placeholder="我的密码" name="password" value=""/>

					</div>

				</div>

			</div>

			<div class="form-actions">

				<label class="checkbox">


				</label>

				<button type="submit" class="btn blue pull-right">

				登陆 <i class="m-icon-swapright m-icon-white"></i>

				</button>

			</div> 
		
			<div class="forget-password">

				
				<p>
					老板登陆 ? 请点击 <a href="javascript:;" class="" id="forget-password">这里</a>
				</p>

			</div>

			<div class="create-account">

				<p>

					尚未开店 ?&nbsp; 

					<a href="javascript:;" id="register-btn" class="">创建商家账户</a>

				</p>

			</div>

		</form>

		<!-- END LOGIN FORM -->        

		<!-- BEGIN FORGOT PASSWORD FORM -->

		<form class="form-vertical forget-form" action="./interface/dologin.php" method="post">

			<h3 class="">老板登陆</h3>

			<p></p>

			<div class="control-group">

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="text" name="mobilphone" placeholder="手机号" />

					</div>

				</div>

			</div>
		<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">密码</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-lock"></i>

						<input class="m-wrap placeholder-no-fix" type="password"  placeholder="密码"   name="password"/>

					</div>

				</div>

			</div>
			<div class="form-actions">

				<button type="button" id="back-btn" class="btn">

				<i class="m-icon-swapleft"></i> 返回

				</button>

				<button type="submit" class="btn blue pull-right">

				登陆 <i class="m-icon-swapright m-icon-white"></i>

				</button>            

			</div>

		</form>

		<!-- END FORGOT PASSWORD FORM -->

		<!-- BEGIN REGISTRATION FORM -->

		<form class="form-vertical register-form" method="post" action="./interface/doreg.php">

			<h3 class="">注册</h3>

			<p></p>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">手机号</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="text" placeholder="手机号" name="phone"  id="telphone" onblur="validatemobile();" />

					</div>

				</div>

			</div>
			
			<div class="control-group">
			<div class="controls">
			<div class="input-icon left">
			<i class=" icon-list-ol"></i>
			<input type="text" placeholder="4位数字验证码" class="m-wrap small"  id="phonecode" name="checkcode">
				<a class="btn purple"  onclick="showHint();">发送验证码 </a>
				</div>
			</div>
				
			</div>
			
			
			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">密码</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-lock"></i>

						<input class="m-wrap placeholder-no-fix" type="password" id="pwd1" placeholder="密码" onblur="checkpwd1();"  name="password"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">确认密码</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-ok"></i>

						<input class="m-wrap placeholder-no-fix" type="password" id="pwd2"  onblur="checkpwd2();" placeholder="确认密码" name="rpassword"/>

					</div>

				</div>

			</div>
			
			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">店名</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-ok"></i>

						<input class="m-wrap placeholder-no-fix" type="text"  id="shopname"  onblur="checkshopname();" placeholder="必填，店名" name="shopname" />

					</div>

				</div>

			</div>

<!-- 
			<div class="control-group">

				<div class="controls">

					<label class="checkbox">

					<input type="checkbox" name="tnc"/> 我同意 <a href="#">服务条款</a>

					</label>  

					<div id="register_tnc_error"></div>

				</div>

			</div>
 -->
			<div class="form-actions">

				<button id="register-back-btn" type="button" class="btn">

				<i class="m-icon-swapleft"></i>  返回

				</button>

				<button type="submit" id="register-submit-btn" class="btn blue pull-right">

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
<?php 
if(isset($_GET['status'])){
	$status=$_GET['status'];
	switch ($status){
		case "registered":echo '<script>alert("此号码已注册，请直接登录！")</script>'; break;
		case "codeerror":echo '<script>alert("验证码输入有误，请重新注册！")</script>';break;
		case "ok":echo '<script>alert("注册注册成功，请直接登录！")</script>';break;
		case "notequal":echo '<script>alert("两次输入的密码不一致，请重新注册！")</script>';break;
	}
}
?>
	<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="media/js/excanvas.min.js"></script>

	<script src="media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="media/js/jquery.validate.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.backstretch.min.js" type="text/javascript"></script>

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/app.js" type="text/javascript"></script>

 	<script src="media/js/login-soft.js" type="text/javascript"></script>       

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