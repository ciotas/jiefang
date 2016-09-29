<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ChangeOneTab{
	public function clearOneTableStatus($tabid, $tabstatus){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->clearOneTableStatus($tabid, $tabstatus);
	}
	public function saveUpdateTabStatus($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->saveUpdateTabStatus($inputarr);
	}
}
$changeonetab=new ChangeOneTab();
if(isset($_GET['tabid'])){
	$tabid=$_GET['tabid'];
	$shopid=$_SESSION['shopid'];
	$status=$_GET['status'];
	$uid=$_GET['uid'];
	$changeonetab->clearOneTableStatus($tabid, $status);
	
	$inputarr=array(
			"shopid"=>$shopid,
			"uid"=>$uid,
			"tabid"=>$tabid,
			"tabstatus"	=>$status,
			"timestamp"=>time(),
	);
	$changeonetab->saveUpdateTabStatus($inputarr);
	header("location: ../tabmanage.php");
}
?>