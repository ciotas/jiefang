<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneAdv{
	public function delOneAdvByAdvid($advid){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->delOneAdvByAdvid($advid);
	}
}
$deloneadv=new DelOneAdv();
if(isset($_GET['advid'])){
	$advid=base64_decode($_GET['advid']);
	
	$deloneadv->delOneAdvByAdvid($advid);
	header("location: ../cussheetadv.php");
}
?>