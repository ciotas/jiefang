<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneServer{
	public function delOneServerByServerid($serverid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delOneServerByServerid($serverid);
	}
}
$deloneserver=new DelOneServer();
if(isset($_GET['serverid'])){
	$serverid=base64_decode($_GET['serverid']);
	$deloneserver->delOneServerByServerid($serverid);
	header("location: ../servers.php");
}
?>