<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneVcd{
	public function delOneVcd($vcid){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->delOneVcd($vcid);
	}
}
$delonevcd=new DelOneVcd();
if(isset($_GET['vcid'])){
	$vcid=base64_decode($_GET['vcid']);
	$delonevcd->delOneVcd($vcid);
	header("location: ../vipset.php");
}
?>