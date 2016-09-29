<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ChangeRole{
	public function changeOneRole($roleid, $type, $status){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->changeOneRole($roleid, $type, $status);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$changerole=new ChangeRole();
if(isset($_GET['roleid'])){
	$shopid=$_SESSION['shopid'];
	$type=$_GET['type'];
	$status=$_GET['status'];
	$roleid=$_GET['roleid'];
	$changerole->changeOneRole($roleid, $type, $status);
	$changerole->syncData($shopid);
	echo "";
}
?>