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
	$shopid=$_GET['shopid'];
	$uid=$_GET['uid'];
	$from=$_GET['from'];
	$delonebill->delOneBillByBillid($billid);
	if($from=="mybills"){
		header("location: ../mybills.php?shopid=$shopid&uid=$uid");
	}elseif($from=="myorders"){
		header("location: ../myorders.php?uid=$uid");
	}else{
		header("location: ".$root_url."weshop/shopindex.php?shopid=$shopid");
	}
	
}
?>