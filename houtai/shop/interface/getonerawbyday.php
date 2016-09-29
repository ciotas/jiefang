<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneRawByDay{
	public function getOneRawinfoByday($rawid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOneRawinfoByday($rawid,$theday);
	}
}
$getonerawbyday=new GetOneRawByDay();
if(isset($_GET['rawid'])){
	$rawid=$_GET['rawid'];
	$typeno=$_GET['typeno'];
	$theday=$_GET['theday'];
	$oneraw=$getonerawbyday->getOneRawinfoByday($rawid,$theday);
	$oneraw['typeno']=$typeno;
	echo json_encode($oneraw);
}
?>