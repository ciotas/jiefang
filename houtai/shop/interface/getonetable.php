<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneTable{
	public function getOneTableData($tabid,$typeno){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOneTableData($tabid,$typeno);
	}
}
$getonetable=new GetOneTable();
if(isset($_GET['tabid'])){
	$tabid=$_GET['tabid'];
	$typeno=$_GET['typeno'];
	$result=$getonetable->getOneTableData($tabid,$typeno);
	echo json_encode($result);
}
?>