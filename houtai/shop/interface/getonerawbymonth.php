<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneRawByMonth{
	public function getOneRawinfoByMonth($rawid,$theyear,$themonth){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOneRawinfoByMonth($rawid,$theyear,$themonth);
	}
}
$getonerawbymonth=new GetOneRawByMonth();
if(isset($_GET['rawid'])){
	$rawid=$_GET['rawid'];
	$typeno=$_GET['typeno'];
	$theyear=$_GET['theyear'];
	$themonth=$_GET['themonth'];
	$oneraw=$getonerawbymonth->getOneRawinfoByMonth($rawid,$theyear,$themonth);
	$oneraw['typeno']=$typeno;
	echo json_encode($oneraw);
}
?>