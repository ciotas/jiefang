<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DoFoodDisacount{
	public function discountOneFood($billid,$foodid, $discountval){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->discountOneFood($billid, $foodid, $discountval);
	}
}
$dofooddicount=new DoFoodDisacount();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$foodid=$_GET['foodid'];
	$discountval=$_GET['discountval'];
	$dofooddicount->discountOneFood($billid, $foodid, $discountval);
	header("location: ../tabmanage.php");
}

?>