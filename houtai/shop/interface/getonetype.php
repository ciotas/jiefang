<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneFoodType{
	public function getOneFoodTypeByFtid($ftid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getOneFoodTypeByFtid($ftid);
	}
}
$getoneftpe=new GetOneFoodType();
if(isset($_GET['ftid'])){
	$ftid=$_GET['ftid'];
	$onetype=$getoneftpe->getOneFoodTypeByFtid($ftid);
	echo json_encode($onetype);
}
?>