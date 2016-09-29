<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOnePrinter{
	public function delOnePrinterData($pid){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->delOnePrinterData($pid);
	}
}
$deloneprinter=new DelOnePrinter();
if(isset($_GET['printerid'])){
	$printerid=base64_decode($_GET['printerid']);
	$deloneprinter->delOnePrinterData($printerid);
	header("location: ../printers.php");
}
?>