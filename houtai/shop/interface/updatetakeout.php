<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpdateTakeOut{
	public function updateTakeoutData($billid, $uid, $cusphone, $cusaddress){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateTakeoutData($billid, $uid, $cusphone, $cusaddress);
	}
}
$updatetakeout=new UpdateTakeOut();
if(isset($_GET['uid'])){
	$uid=$_GET['uid'];
	$billid=$_GET['billid'];
	$cusphone=$_GET['cusphone'];
	$cusaddress=$_GET['cusaddress'];
	$updatetakeout->updateTakeoutData($billid, $uid, $cusphone, $cusaddress);
	echo "";
}
?>