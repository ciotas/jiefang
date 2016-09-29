<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneRaw{
	public function delOnerawByRawid($rawid){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->delOnerawByRawid($rawid);
	}
}
$deloneraw=new DelOneRaw();
if(isset($_GET['rawid'])){
	$typeno=$_GET['typeno'];
	$rawid=base64_decode($_GET['rawid']);
	$deloneraw->delOnerawByRawid($rawid);
	header("location: ../stock/rawinfo.php?typeno=$typeno");
}
?>