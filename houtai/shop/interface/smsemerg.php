<?php
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SmSEmerg{
	public function sendSmsEmerg($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->sendSmsEmerg($billid);
	}
}

$smsemerg=new SmSEmerg();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$smsemerg->sendSmsEmerg($billid);
	header("location: ../unpaycheck.php");
}
?>