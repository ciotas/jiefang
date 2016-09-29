<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveShopAccount{
	public function saveShopAccount($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->saveShopAccount($inputarr);
	}
	public function updateShopAccount($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateShopAccount($inputarr);
	}
}
$saveshopaccount=new SaveShopAccount();
if(isset($_POST['shopaccountid'])){
	$shopaccountid=$_POST['shopaccountid'];
	$shopkeeper=$_POST['shopkeeper'];
	$bankno=$_POST['bankno'];
	$bankbranch=$_POST['bankbranch'];
	$shopid=$_SESSION['shopid'];
	$inputarr=array(
			"shopid"=>$shopid,
			"shopkeeper"	=>$shopkeeper,
			"bankno"=>$bankno,
			"bankbranch"=>$bankbranch,
	);
// 	print_r($inputarr);exit;
	if(!empty($shopaccountid)){
		$saveshopaccount->updateShopAccount($inputarr);
	}else{
		$saveshopaccount->saveShopAccount($inputarr);
	}
	header("location: ../shopaccount.php");
}
?>