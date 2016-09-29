<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneRawtype{
	public function getOneRawtypenameByid($rtnid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOneRawtypenameByid($rtnid);
	}
}
$getonerawtype=new GetOneRawtype();
if(isset($_GET['rtnid'])){
	$rtnid=$_GET['rtnid'];
	$result=$getonerawtype->getOneRawtypenameByid($rtnid);
	echo json_encode($result);
}
?>