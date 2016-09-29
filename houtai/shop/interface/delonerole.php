<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneRole{
	public function delOneRoleByRoleid($roleid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delOneRoleByRoleid($roleid);
	}
}
$delonerole=new DelOneRole();
if(isset($_GET['roleid'])){
	$roleid=base64_decode($_GET['roleid']);
	$delonerole->delOneRoleByRoleid($roleid);
	header("location: ../jobset.php");
}
?>