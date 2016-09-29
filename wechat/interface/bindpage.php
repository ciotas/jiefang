<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');

class BindPageClass{
    public function getOpenid($code){
        return Wechat_BLLFactory::createInstanceWechatBLL()->getOpenid($code);
    }

    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}

$openid = isset($_GET['openid'])?$_GET['openid']:'';
$menutype = isset($_GET['menutype'])?$_GET['menutype']:'';

//$code = isset($_GET['code'])?$_GET['code']:'';
//$openid = '';
$var = new BindPageClass();
//if (strlen($code) == 0){
//
//} else {
//    $openid = $var->getOpenid($code);
$var->write_logs('[BindPageClass]openid='.$openid);
//}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
		<title>用户登录</title>
		<link rel="stylesheet" type="text/css" href="../media/css/bindpage.css"/>
		<script type="text/javascript" src="../media/js/jquery-2.2.1.js"></script>
		
	</head>
<body>
		<div class="weibo">
			<div class="header">
				<div class="bg_login"></div>
				<div class="login">
					<ul>
						<li class="one">用户登录</li>
					</ul>
					<div class="login_a">
					<form action="./getbindinfo.php" method="POST">
						<input type="tel" class="name" placeholder="请输入手机号" name="phone" />
						<div class="name_tubiao" style="background-image: url('../media/images/user.png')"></div>
						<input type="password" placeholder="请输入密码" class="password" name="passwd"/>
						<div class="password_tubiao" style="background-image: url('../media/images/password.png')"></div>		
						
						<input type="hidden" name="openid" value='<?php echo $openid;?>'>
    					<input type="hidden" name="menutype" value='<?php echo $menutype;?>'>
    					<button type="submit" value="登录" class="login_btn"/>登录</button>
					</form>
					</div>
					<div class="fengexian"></div>
					<span class="other"><em>2016&copy杭州点霸科技</em></span>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("body").width($(window).width()).height($(window).height());
// 			var value = $(".name").val();
			$(".name").blur(function(){
				var number = /^1[3|4|5|8|7]\d{9}$/;
				if(!number.test(this.value)){
					$(this).css({"border":"2px solid red"});
				}else{
					$(this).css({"border-color":"#7a98a2"});
				}
			})

		})
		</script>
</body>
<?php 
if(isset($_GET['status'])){
    if($_GET['status']=="error"){
        echo '<script>alert("用户名或密码错误！");window.location.href="./bindpage.php";</script>';
    }
}
?>
</html>