<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneRule{
	public function delOneDonateticketData($ruleid){
		QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->delOneDonateticketData($ruleid);
	}
}
$delonerule=new DelOneRule();
if(isset($_GET['ruleid'])){
	$ruleid=base64_decode($_GET['ruleid']);
	$delonerule->delOneDonateticketData($ruleid);
	header("location: ".$base_url."activity/donateticket/rule.php");
}
?>