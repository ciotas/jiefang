<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneBill{
	public function delOneBillByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->delOneBillByBillid($billid);
	}
}
$delonebill=new DelOneBill();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$theday=$_GET['theday'];
	$op=$_GET['op'];
	if(isset($_GET['from'])){
	    $from=$_GET['from'];
	    $openid=$_GET['openid'];
	}
	$delonebill->delOneBillByBillid($billid);
	if($from=="wechatservice"){
	    header("location: ../wechatservice/$op.php?theday=$theday&openid=$openid");
	}else{
	    header("location: ../$op.php?theday=$theday");
	}
	
}
?>