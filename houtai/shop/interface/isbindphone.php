<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class IsBindPhone{
	public function isMyBindPhone($uid,$shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->isMyBindPhone($uid,$shopid);
	}
}
$isbindphone=new IsBindPhone();
if(isset($_GET['uid'])){
	$uid=$_GET['uid'];
	$shopid=$_GET['shopid'];
	$result=$isbindphone->isMyBindPhone($uid,$shopid);
	echo json_encode($result);
}
?>