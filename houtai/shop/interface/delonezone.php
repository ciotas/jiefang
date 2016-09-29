<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneZone{
	public function delOneZoneByZoneid($zoneid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delOneZoneByZoneid($zoneid);
	}
}
$delonezone=new DelOneZone();
if(isset($_GET['zoneid'])){
	$zoneid=base64_decode($_GET['zoneid']);
	$delonezone->delOneZoneByZoneid($zoneid);
	header("location: ../zone.php");
}
?>