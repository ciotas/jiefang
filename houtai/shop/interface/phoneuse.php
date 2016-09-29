<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PhoneUse{
	public function isShopphoneUse($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->isShopphoneUse($phone);
	}
}
$phoneuse=new PhoneUse();
if(isset($_GET['phone'])){
	$phone=$_GET['phone'];
	echo $phoneuse->isShopphoneUse($phone);
}
?>