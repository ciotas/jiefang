<?php
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once(DOCUMENT_ROOT."function.php");

class ShopRegisterCode{
	public function writeShopCheckCodeToDB($phone,$checkcode,$timestamp){
		InterfaceFactory::createInstanceSendMsgDAL()->writeShopCheckCodeToDB($phone, $checkcode,$timestamp);
	}
}
$shopregcode=new ShopRegisterCode();
// sendTemplateSMS("手机号码","内容数据","模板Id");
if(isset($_REQUEST['phone'])){
	$phone=$_REQUEST['phone'];
	$signature=$_REQUEST['signature'];
	$timestamp=$_REQUEST['timestamp'];
	$time=time()+30*60;
	$checkcode=strval(mt_rand(1000, 9999));//验证码生成方法
	$datas=array($checkcode,$inputtime);
	$serversign=strtoupper(md5($phone.$timestamp.$token));
	if($serversign==$signature){
		$crypt = new CookieCrypt($phonekey);
		$sendphone=$crypt->decrypt($phone);
		$status=sendTemplateSMS($sendphone,$datas,$tempId);	
		echo $status;
		//写入到数据库
		$shopregcode->writeShopCheckCodeToDB($phone, $checkcode,$time);
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
}
exit;
$checkcode=strval(mt_rand(1000, 9999));//验证码生成方法
$datas=array($checkcode,$inputtime);
$status=sendTemplateSMS("13758113612",$datas,$tempId);
echo $status;
// var_dump($tempstr);exit;
exit;
$timestamp=time()+3*60;
$shopregcode->writeShopCheckCodeToDB('13758113612', "567892",$timestamp);
?>
