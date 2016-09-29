<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneBusiZone{
	public function getOneBusizoneData($busi_zoneid){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getOneBusizoneData($busi_zoneid);
	}
}
$getonebusizone=new GetOneBusiZone();
if(isset($_GET['busi_zoneid'])){
	$busi_zoneid=$_GET['busi_zoneid'];
	$result=$getonebusizone->getOneBusizoneData($busi_zoneid);
	echo json_encode($result);
}
?>