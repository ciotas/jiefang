<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneServer{
	public function addOneServer($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->addOneServer($inputarr);
	}
	public function updateOneServer($serverid,$inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateOneServer($serverid, $inputarr);
	}
}
$saveoneserver=new SaveOneServer();
if(isset($_POST['serverphone'])){
    $openid=$_POST['openid'];
	$servername=$_POST['servername'];
	$serverno=$_POST['serverno'];
	$serverphone=$_POST['serverphone'];
	$serverpwd=$_POST['serverpwd'];
	$serverid=$_POST['serverid'];
	$roleid=$_POST['roleid'];
	$shopid=$_POST['shopid'];
	$inputarr=array(
			"serverid"	=>$serverid,
			"shopid"=>$shopid,
			"servername"=>$servername,
			"serverno"=>$serverno,
			"serverphone"=>$serverphone,
			"serverpwd"=>$serverpwd,
			"roleid"=>$roleid,
	);
// 	print_r($inputarr);exit;
	if(empty($serverid)){
		$saveoneserver->addOneServer($inputarr);
	}else{
		$saveoneserver->updateOneServer($serverid, $inputarr);
	}
	header("location: ../wechatservice/servers.php?openid=$openid");
}
?>