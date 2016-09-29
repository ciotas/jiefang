<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ClearOrder{
	public function getOnePrinterByPid($pid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getOnePrinterByPid($pid);
	}
}
$clearorder=new ClearOrder();
if(isset($_GET['printerid'])){
	$printerid=base64_decode($_GET['printerid']);
	$printerarr=$clearorder->getOnePrinterByPid($printerid);
	$deviceno="";
	if(!empty($printerarr)){
		$deviceno=$printerarr['device_no'];
	}
	$url="http://printer.meijiemall.com:8080/WPServer/clearorder?sn=".$deviceno;
	$data=file_get_contents($url);
	header("location: ../printers.php");
}

?>