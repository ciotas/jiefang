<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddOneFoodType{
	public function addOneFoodTypeData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->addOneFoodTypeData($inputarr);
	}
}
$addoneftpe=new AddOneFoodType();
if(isset($_SESSION['shopid'])){
	$shopid=$_SESSION['shopid'];
	$ftname=$_POST['ftname'];
	$ftcode=$_POST['ftcode'];
	$sortno=$_POST['sortno'];
	$printerid=$_POST['printerid'];
	$inputarr=array(
			"shopid"	=>$shopid,
			"foodtypename"=>$ftname,
			"foodtypecode"=>$ftcode,
			"sortno"=>$sortno,
			"printerid"=>$printerid,
	);
// 	print_r($inputarr);exit;
	$onetype=$addoneftpe->addOneFoodTypeData($inputarr);
	file_get_contents("./syncfood.php?backurl=foodtype");
	header("location: ../foodtype.php");
}
?>