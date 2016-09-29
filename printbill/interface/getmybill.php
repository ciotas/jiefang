<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class getMyBill{
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
	public function getMyBillData($tabid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getMyBillData($tabid, $shopid);
	}
}
$getmybill=new getMyBill();
if(isset($_POST['tabid'])){
	$tabid=$_POST['tabid'];
	$shopid=$_POST['shopid'];
	$tabstatus=$getmybill->getTabStatusByTabid($tabid);
	$arr=array();
	if($tabstatus=="start"){
		$arr=$getmybill->getMyBillData($tabid, $shopid);
	}
	header('Content-type: application/json');
	echo json_encode($arr);
}
exit;
$tabid="5565d1015bc1092b7a8b9687";
$shopid="554ad9615bc109d8518b45d2";
$tabstatus=$getmybill->getTabStatusByTabid($tabid);
// var_dump($tabstatus);exit;
$arr=$getmybill->getMyBillData($tabid, $shopid);
print_r($arr);exit;
?>