<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneVipTag{
	public function delOneTagByTagid($viptagid){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->delOneTagByTagid($viptagid);
	}
}
$deloneviptag=new DelOneVipTag();
if(isset($_GET['viptagid'])){
	$viptagid=base64_decode($_GET['viptagid']);
	$deloneviptag->delOneTagByTagid($viptagid);
	header("location: ../viptag.php");
}
?>