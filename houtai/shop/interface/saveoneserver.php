<?php 
require_once ('../startsession.php');
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
	$servername=trim($_POST['servername']);
	$serverno=trim($_POST['serverno']);
	$serverphone=trim($_POST['serverphone']);
	$serverpwd=trim($_POST['serverpwd']);
	$serverid=$_POST['serverid'];
	$roleid=trim($_POST['roleid']);
	$openid=trim($_POST['openid']);
	$shopid=$_SESSION['shopid'];
	$inputarr=array(
			"serverid"	=>$serverid,
			"shopid"=>$shopid,
			"servername"=>$servername,
			"serverno"=>$serverno,
			"serverphone"=>$serverphone,
			"serverpwd"=>$serverpwd,
			"roleid"=>$roleid,
			"openid"=>$openid,
	);
// 	print_r($inputarr);exit;
	if(empty($serverid)){
		$saveoneserver->addOneServer($inputarr);
	}else{
		$saveoneserver->updateOneServer($serverid, $inputarr);
	}
	header("location: ../servers.php");
}
?>