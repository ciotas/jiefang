<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneCoupon{
	public function addOneCoupon($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->addOneCoupon($inputarr);
	}
	public function updateOneCoupon($cpid, $inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateOneCoupon($cpid, $inputarr);	
	}
}
$saveonecoupon=new SaveOneCoupon();
if(isset($_POST['couponname'])){
	$cpid=$_POST['cpid'];
	$shopid=$_SESSION['shopid'];
	$couponname=$_POST['couponname'];
	$inputarr=array(
			"cpid"=>$cpid,
			"shopid"=>$shopid,	
			"couponname"=>$couponname,
	);
// 	print_r($inputarr);exit;
	if(empty($cpid)){
		$saveonecoupon->addOneCoupon($inputarr);
	}else{
		$saveonecoupon->updateOneCoupon($cpid, $inputarr);
	}
	header("location: ../coupontype.php");
	
}
?>