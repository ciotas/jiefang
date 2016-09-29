<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddBusiZoneid{
	public function addShopToBusiZoneid($shopid,$busi_zoneid){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->addShopToBusiZoneid($shopid, $busi_zoneid);
	}
}
$addbusizoneid=new AddBusiZoneid();
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$busi_zoneid=$_GET['busi_zoneid'];
	$addbusizoneid->addShopToBusiZoneid($shopid, $busi_zoneid);
	echo "";
}
?>