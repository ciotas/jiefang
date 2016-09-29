<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class ReturnOneFood{
	public function updateBillFood($foodarr,$billid, $returnnum,$foodid,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype);
	}
	public function getFoodsByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodsByBillid($billid);
	}
	public function getOneFoodInBill($billid, $foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneFoodInBill($billid, $foodid);
	}
	public function addToReturnBill($inputarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addToReturnBill($inputarr);
	}
}
$returnonefood=new ReturnOneFood();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$foodarr=$returnonefood->getFoodsByBillid($billid);
	$returnnum=$_GET['returnnum'];
	$foodid=$_GET['foodid'];
	$foodnum=$_GET['foodnum'];
	$cooktype=$_GET['cooktype'];
	$theday=$_GET['theday'];
	$op=$_GET['op'];
	if(isset($_GET['from'])){
	    $from=$_GET['from'];
	    $openid=$_GET['openid'];
	}
	
	$billarr=$returnonefood->getOneFoodInBill($billid,$foodid);
	$returnonefood->updateBillFood($foodarr, $billid, $returnnum, $foodid, $foodnum, $cooktype);
	$inputarr=array(
			"uid"=>$billarr['uid'],
			"nickname"=>$billarr['nickname'],
			"tabname"=>$billarr['tabname'],
			"billid"=>$billid,
			"foodid"=>$foodid,
			"cusnum"=>$billarr['cusnum'],
			"foodnum"=>$billarr['foodnum'],
			"orderunit"=>$billarr['orderunit'],
			"foodname"=>$billarr['foodname'],
			"returnnum"=>$returnnum,
			"timestamp"=>time(),
	);
	$returnonefood->addToReturnBill($inputarr);//添加退菜记录
	if($from=="wechatservice"){
	    header("location: ../wechatservice/$op.php?theday=$theday&openid=$openid");
	}else{
	    header("location: ../$op.php?theday=$theday");
	}
	
}
?>