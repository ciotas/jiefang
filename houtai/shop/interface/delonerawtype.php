<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneRawtype{
	public function delOneRawtypenameById($rtnid){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->delOneRawtypenameById($rtnid);
	}
}
$delonerawtype=new DelOneRawtype();
if(isset($_GET['rtnid'])){
	$rtnid=base64_decode($_GET['rtnid']);
	$delonerawtype->delOneRawtypenameById($rtnid);
	header("location: ../stock/rawtype.php");
}
?>