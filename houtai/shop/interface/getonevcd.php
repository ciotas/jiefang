<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneVcd{
	public function getOneVcdData($vcid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOneVcdData($vcid);
	}
}
$getonevcd=new GetOneVcd();
if(isset($_GET['vcid'])){
	$vcid=$_GET['vcid'];
	$onevcd=$getonevcd->getOneVcdData($vcid);
	echo json_encode($onevcd);
}
?>