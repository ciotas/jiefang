<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Statics{
	public function getStaticsData(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getStaticsData();
	}
}
$statics =new Statics();
$result=$statics->getStaticsData();
print_r($result);
?>
