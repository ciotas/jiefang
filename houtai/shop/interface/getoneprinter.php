<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOnePrinter{
	public function getOnePrinterByPid($pid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getOnePrinterByPid($pid);
	}
}
$getoneprinter=new GetOnePrinter();
if(isset($_GET['printerid'])){
	$printerid=$_GET['printerid'];
	$oneprinter=$getoneprinter->getOnePrinterByPid($printerid);
	echo json_encode($oneprinter);
}
?>