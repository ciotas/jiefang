<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneTable{
	public function delOneTableData($tabid){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->delOneTableData($tabid);
	}
}
$delonetable=new DelOneTable();
if(isset($_GET['tabid'])){
	$tabid=base64_decode($_GET['tabid']);
	$typeno=$_GET['typeno'];
	$delonetable->delOneTableData($tabid);
	header("location: ../tables.php?typeno=$typeno");
}
?>