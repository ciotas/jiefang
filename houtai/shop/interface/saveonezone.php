<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneZone{
	public function addOneZone($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->addOneZone($inputarr);
	}
	public function updateOneZone($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateOneZone($inputarr);
	}
}
$saveonezone=new SaveOneZone();
if(isset($_POST['zonename'])){
	$zoneid=$_POST['zoneid'];
	$shopid=$_SESSION['shopid'];
	$zonename=$_POST['zonename'];
	$inputarr=array(
			"zoneid"	=>$zoneid,
			"zonename"=>$zonename,
			"shopid"=>$shopid
	);
	if(!empty($zoneid)){
		$saveonezone->updateOneZone($inputarr);
	}else{
		$saveonezone->addOneZone($inputarr);
	}
	header("location: ../zone.php");
}
?>