<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."function.php");
class isCusRegistered{
	public function isCusRegisteredPhone($telphone){
		return InterfaceFactory::createInstanceSendMsgDAL()->isCusRegisteredPhone($telphone);
	}
	public function writeCusCheckCodeToDB($phone,$checkcode,$timestamp){
		InterfaceFactory::createInstanceSendMsgDAL()->writeCusCheckCodeToDB($phone, $checkcode,$timestamp);
	}
}
$iscusregistered=new isCusRegistered();
if(isset($_POST['phone'])){
	$phone=$_POST['phone'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$time=time()+30*60;
	$checkcode=strval(mt_rand(1000, 9999));//验证码
	$datas=array($checkcode,$inputtime);
	$serversign=strtoupper(md5($phone.$timestamp.$token));
	if($serversign==$signature){
		$result=$iscusregistered->isCusRegisteredPhone($phone);
		if($result=="1"){
			echo "registered";exit;
		}
		$crypt = new CookieCrypt($cusphonekey);
		$sendphone=$crypt->decrypt($phone);
		$status=sendTemplateSMS($sendphone,$datas,$tempId);
		echo $status;
		//写入到数据库
		$iscusregistered->writeCusCheckCodeToDB($phone, $checkcode, $time);
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
}
exit;
// echo $iscusregistered->isCusRegisteredPhone("aduqAm9GdpiHHILiJ3QuTw==");
?>