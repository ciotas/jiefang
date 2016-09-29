<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneCoupon{
	public function delOneCoupon($cpid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delOneCoupon($cpid);
	}
}
$delonecoupon=new DelOneCoupon();
if(isset($_GET['cpid'])){
	$cpid=base64_decode($_GET['cpid']);
	$delonecoupon->delOneCoupon($cpid);
	header("location: ../coupontype.php");
}
?>