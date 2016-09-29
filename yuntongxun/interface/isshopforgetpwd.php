<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."function.php");
class IsShopForgetPwd{
	public function isRegisteredPhone($mobilphone){
		return InterfaceFactory::createInstanceSendMsgDAL()->isRegisteredPhone($mobilphone);
	}
	public function writeShopCheckCodeToDB($phone,$checkcode,$timestamp){
		InterfaceFactory::createInstanceSendMsgDAL()->writeShopCheckCodeToDB($phone, $checkcode,$timestamp);
	}
}
$isshopforgetpwd=new IsShopForgetPwd();
if(isset($_POST['phone'])){
	$phone=$_POST['phone'];
	$signature=$_POST['signature'];
	$timestamp=$_POST['timestamp'];
	$time=time()+10*60;
	$checkcode=strval(mt_rand(1000, 9999));//验证码生成方法
	$datas=array($checkcode,$inputtime);
	$serversign=strtoupper(md5($phone.$timestamp.$token));
	if($serversign==$signature){
		$result=$isshopforgetpwd->isRegisteredPhone($phone);
		if($result=="0"){
			echo "notregistered";exit;
		}
		$crypt = new CookieCrypt($phonekey);
		$sendphone=$crypt->decrypt($phone);
		$status=sendTemplateSMS($sendphone,$datas,$tempId);	
		echo $status;
		//写入到数据库
		$isshopforgetpwd->writeShopCheckCodeToDB($phone, $checkcode,$time);
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
}
exit;
echo $isshopforgetpwd->isRegisteredPhone("k8a3fyEC5VXeh08N3JNRng==");
?>