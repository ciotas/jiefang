<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DoBindPhone{
	public function bindMyPhone($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->bindMyPhone($inputarr);
	}
}
$dobindphone=new DoBindPhone();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	if(empty($uid)){
		header("location: ../myphone.php");
	}
	$telphone=$_POST['phone'];
	$checkcode=$_POST['checkcode'];
	$inputarr=array(
			"uid"=>$uid,
			"telphone"=>$telphone,
			"checkcode"=>$checkcode,
			"addtime"=>time(),
	);
// 	print_r($inputarr);exit;
	$result=$dobindphone->bindMyPhone($inputarr);
	switch($result['status']){
		case "ok": header("location: ../myphone.php?uid=$uid");break;
		case "codeerror": header("location: ../bindphone.php?status=codeerror");break;
	}
}
?>