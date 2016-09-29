<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneStock{
	public function getOneStockData($shopid, $foodid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOneStockData($shopid, $foodid);
	}
}
$getonestock=new GetOneStock();
if(isset($_GET['foodid'])){
	$foodid=$_GET['foodid'];
	$shopid=$_SESSION['shopid'];
	$result=$getonestock->getOneStockData($shopid, $foodid);
	echo json_encode($result);
}
?>