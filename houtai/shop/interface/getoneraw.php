<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneRaw{
	public function getOneRawinfo($rawid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOneRawinfo($rawid);
	}
}
$getoneraw=new GetOneRaw();
if(isset($_GET['rawid'])){
	$rawid=$_GET['rawid'];
	$typeno=$_GET['typeno'];
	$oneraw=$getoneraw->getOneRawinfo($rawid);
	$oneraw['typeno']=$typeno;
	echo json_encode($oneraw);
}
?>