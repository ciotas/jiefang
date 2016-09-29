<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddOneFoodType{
	public function addOneFoodTypeData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->addOneFoodTypeData($inputarr);
	}
	public function updateOneFoodtypeData($ftid, $inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->updateOneFoodtypeData($ftid, $inputarr);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$addoneftpe=new AddOneFoodType();
if(isset($_SESSION['shopid'])){
	$shopid=$_SESSION['shopid'];
	$ftname=$_POST['ftname'];
	$ftcode=$_POST['ftcode'];
	$sortno=$_POST['sortno'];
	$printerid=$_POST['printerid'];
	$ftid=$_POST['ftid'];
	$inputarr=array(
			"shopid"	=>$shopid,
			"foodtypename"=>$ftname,
			"foodtypecode"=>$ftcode,
			"sortno"=>$sortno,
			"printerid"=>$printerid,
	);
	if(!empty($ftid)){
		$addoneftpe->updateOneFoodtypeData($ftid, $inputarr);
	}else{
		$onetype=$addoneftpe->addOneFoodTypeData($inputarr);
	}
	$addoneftpe->syncData($shopid);
    header("location: ../foodtype.php");
	
}
?>