<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneServer{
	public function getOneServerByServerid($serverid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getOneServerByServerid($serverid);
	}
}
$getoneserver=new GetOneServer();
if(isset($_GET['serverid'])){
	$serverid=$_GET['serverid'];
	$result=$getoneserver->getOneServerByServerid($serverid);
	echo json_encode($result);
}
?>