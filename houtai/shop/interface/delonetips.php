<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneTips{
	public function delDonateticketTips($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->delDonateticketTips($inputarr);
	}
}
$delonetips=new DelOneTips();
if(isset($_GET['tipcontent'])){
	$tipcontent=$_GET['tipcontent'];
	$shopid=$_SESSION['shopid'];
	$tipswitch=$_GET['tipswitch'];
	$sortno=$_GET['sortno'];
	$inputarr=array(
			"shopid"=>$shopid,
			"tipcontent"=>$tipcontent,
			"tipswitch"=>$tipswitch,
			"sortno"=>$sortno,
	);
// 	print_r($inputarr);exit;
	$delonetips->delDonateticketTips($inputarr);
	header("location: ".$base_url."activity/donateticket/tips.php");
}
?>