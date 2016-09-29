<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneBusiZone{
	public function addBusizoneData($inputarr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->addBusizoneData($inputarr);
	}
	public function updateBusiZoneData($inputarr,$busi_zoneid){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->updateBusiZoneData($inputarr, $busi_zoneid);
	}
}
$saveonebusizone=new SaveOneBusiZone();
if(isset($_POST['city'])){
	$busi_zoneid=$_POST['busi_zoneid'];
	$city=$_POST['city'];
	$busi_zonename=$_POST['busi_zonename'];
	$timestamp=time();
	$inputarr=array(
			"city"=>$city,
			"busi_zonename"=>$busi_zonename,
			"timestamp"=>$timestamp,
	);
// 	print_r($inputarr);exit;
	if(!empty($busi_zoneid)){
		$saveonebusizone->updateBusiZoneData($inputarr, $busi_zoneid);
	}else{
		$saveonebusizone->addBusizoneData($inputarr);
	}
	header("location: ../businesszone.php");
}
?>