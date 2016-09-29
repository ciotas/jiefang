<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOnePrinter{
	public function addOnePrinter($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->addOnePrinter($inputarr);
	}
	public function updateOnePrinter($inputarr, $printerid){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->updateOnePrinter($inputarr, $printerid);
	}
}
$saveoneprinter=new SaveOnePrinter();
if(isset($_POST['deviceno'])){
	$printerid=$_POST['printerid'];
	$shopid=$_SESSION['shopid'];
	$deviceno=$_POST['deviceno'];
	$devicekey=$_POST['devicekey'];
	$workphone=$_POST['workphone'];
	$printername=$_POST['printername'];
	$outputtype=$_POST['outputtype'];
	$printertype=$_POST['printertype'];
	$zoneid=$_POST['zoneid'];
	$inputarr=array(
			"shopid"	=>$shopid,
			"deviceno"=>$deviceno,
			"devicekey"=>$devicekey,
			"workphone"=>$workphone,
			"printername"=>$printername,
			"outputtype"=>$outputtype,
			"zoneid"=>$zoneid,
			"printertype"=>$printertype,
	);
	if(!empty($printerid)){
		$saveoneprinter->updateOnePrinter($inputarr, $printerid);
	}else{
		$saveoneprinter->addOnePrinter($inputarr);
	}
	header("location: ../printers.php");
}
?>