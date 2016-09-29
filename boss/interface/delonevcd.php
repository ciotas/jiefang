<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneVcd{
	public function delOneVcd($vcid){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->delOneVcd($vcid);
	}
}
$delonevcd=new DelOneVcd();
if(isset($_GET['vcid'])){
	$vcid=base64_decode($_GET['vcid']);
	$delonevcd->delOneVcd($vcid);
	header("location: ../vipset.php");
}
?>