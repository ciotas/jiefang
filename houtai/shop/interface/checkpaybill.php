<?php
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class CheckPayBill{
	public function setPaidBillData($inputarr){
	 	QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->setPaidBillData($inputarr);
	}
}
$checkpaybill=new CheckPayBill();
if(isset($_POST['billid'])){
	$billid=$_POST['billid'];
	$totalmoney=$_POST['totalmoney'];
	$paidmoney=$_POST['paidmoney'];
	$shopid=$_SESSION['shopid'];
	$theday=$_POST['theday'];
	$op=$_POST['op'];
	$inputarr=array(
		"billid"=>$billid,
		"shopid"=>$shopid,
		"totalmoney"=>$totalmoney,
		"paidmoney"=>$paidmoney,
	);
	$checkpaybill->setPaidBillData($inputarr);
	header("location: ../$op.php?theday=$theday");
}

?>