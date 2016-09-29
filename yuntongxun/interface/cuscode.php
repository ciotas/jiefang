<?php
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."function.php");
class CusCode{
	public function writeCusCheckCodeToDB($phone,$checkcode,$timestamp){
		InterfaceFactory::createInstanceSendMsgDAL()->writeCusCheckCodeToDB($phone, $checkcode,$timestamp);
	}
}
$cuscode=new CusCode();

// sendTemplateSMS("手机号码","内容数据","模板Id");
if(isset($_POST['phone'])&&isset($_POST['signature'])){
	$phone=$_POST['phone'];
	$time=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$timestamp=time()+30*60;
	$checkcode=strval(mt_rand(1000, 9999));//验证码
	$datas=array($checkcode,$inputtime);
	$serversign=strtoupper(md5($phone.$time.$token));
	if($serversign==$signature){    
		$crypt = new CookieCrypt($cusphonekey);
		$sendphone=$crypt->decrypt($phone);
		$status=sendTemplateSMS($sendphone,$datas,$tempId);
		echo $status;
		//写入到数据库
		$cuscode->writeCusCheckCodeToDB($phone, $checkcode, $timestamp);
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
	
}
exit;
$timestamp=time()+3*60;
$cuscode->writeCusCheckCodeToDB('13758113612', "567892",$timestamp);
?>
