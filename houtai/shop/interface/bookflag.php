<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BookFlag{
	public function switchBookFlag($tabid, $status){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->switchBookFlag($tabid, $status);
	}
}
$bookflag=new BookFlag();
if(isset($_GET['tabid'])){
	$tabid=$_GET['tabid'];
	$status=$_GET['status'];
	$bookflag->switchBookFlag($tabid, $status);
	echo "";
}
?>