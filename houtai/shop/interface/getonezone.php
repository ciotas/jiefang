<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneZone{
	public function getOnezoneByZoneid($zoneid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getOnezoneByZoneid($zoneid);
	}
}
$getonezone=new GetOneZone();
if(isset($_GET['zoneid'])){
	$zoneid=$_GET['zoneid'];
	$result=$getonezone->getOnezoneByZoneid($zoneid);
	echo json_encode($result);
}
?>