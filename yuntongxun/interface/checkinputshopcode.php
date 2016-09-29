<?php 
require_once ('/var/www/html/yuntongxun/global.php');
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once(DOCUMENT_ROOT."function.php");
class CheckInputShopCode{
	public function checkShopCode($phone, $checkcode, $timestamp) {
		return InterfaceFactory::createInstanceSendMsgDAL()->checkShopCode($phone, $checkcode, $timestamp);
	}
}
$checkinputshopcode=new CheckInputShopCode();
if(isset($_POST['phone'])){
	$phone=$_POST['phone'];
	$checkcode=$_POST['checkcode'];
	$timestamp=$_POST['timestamp'];
	$time=time();
	$signature=$_POST['signature'];
	$serversign=strtoupper(md5($phone.$timestamp.$token));
// 	header('Content-type: application/json');
// 	echo json_encode(array("phone"=>$phone,"timestamp"=>$timestamp,"token"=>$token,"sign"=>$serversign));exit;
	if($serversign==$signature){
		$status=$checkinputshopcode->checkShopCode($phone, $checkcode, $time);
		header('Content-type: application/json');
		echo json_encode(array("status"=>$status));
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}

}
exit;
echo $checkinputshopcode->checkShopCode('13758113612', '567892', time());
?>