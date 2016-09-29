<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveTips{
	public function saveDonateticketContent($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->saveDonateticketContent($inputarr);
	}
}
$savetips=new SaveTips();
if(isset($_POST['content'])){
	$content=$_POST['content'];
	$tipswitch=$_POST['tipswitch'];
	if($tipswitch){
		$tipswitch="1";
	}else{
		$tipswitch="0";
	}
	$shopid=$_SESSION['shopid'];
	$sortno=$_POST['sortno'];
	$inputarr=array(
			"shopid"=>$shopid,
			"tipcontent"	=>$content,
			"tipswitch"=>$tipswitch,
			"sortno"=>$sortno,
	);
// 	print_r($inputarr);exit;
	$savetips->saveDonateticketContent($inputarr);
	header("location: ".$base_url."activity/donateticket/tips.php");
}
?>