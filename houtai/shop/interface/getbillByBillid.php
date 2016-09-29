<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetBillByBillid{
	public function getOneBillDataByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOneBillDataByBillid($billid);
	}
}
$getbillbybillid=new GetBillByBillid();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$result=$getbillbybillid->getOneBillDataByBillid($billid);
	echo json_encode($result);
}
?>