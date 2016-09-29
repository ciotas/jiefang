<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneBusinessZone{
	public function delOneBusizoneData($busi_zoneid){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->delOneBusizoneData($busi_zoneid);
	}
}
$delonebusinesszone=new DelOneBusinessZone();
if(isset($_GET['busi_zoneid'])){
	$busi_zoneid=base64_decode($_GET['busi_zoneid']);
	$delonebusinesszone->delOneBusizoneData($busi_zoneid);
	header("location: ../businesszone.php");
}
?>