<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneCoupon{
	public function getOneCouponByCpid($cpid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getOneCouponByCpid($cpid);
	}
}
$getonecoupon=new GetOneCoupon();
if(isset($_GET['cpid'])){
	$cpid=$_GET['cpid'];
	$onecoupon=$getonecoupon->getOneCouponByCpid($cpid);
	echo json_encode($onecoupon);
}
?>