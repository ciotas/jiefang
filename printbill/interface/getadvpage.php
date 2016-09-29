<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetAdvPage{
	public function getAdvUrlByAdvid($advid){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->getAdvUrlByAdvid($advid);
	}
}
$getadvpage=new GetAdvPage();
if(isset($_GET['advid'])){
	$advid=$_GET['advid'];
	$adurl=$getadvpage->getAdvUrlByAdvid($advid);
	if(!empty($adurl)){
		header("location: ".$adurl);
	}
}
?>