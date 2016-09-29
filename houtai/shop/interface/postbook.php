<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class PostBook{
	public function postBookData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->postBookData($inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
	public function generPrintBookOrderContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->generPrintBookOrderContent($deviceno, $devicekey, $inputarr);
	}
}
$postbook=new PostBook();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$shopid=$_POST['shopid'];
	$cusname=$_POST['cusname'];
	
	$cusnum=$_POST['cusnum'];
	$cusphone=$_POST['cusphone'];
	$bookdate=$_POST['bookdate'];
	$booktime=$_POST['booktime'];
	if(empty($cusname)){
		header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=empty_name");exit;
	}elseif(empty($cusnum)){
		header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=empty_num");exit;
	}elseif(empty($cusphone)){
		header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=empty_phone");exit;
	}elseif(empty($bookdate)){
		header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=empty_date");exit;
	}elseif(empty($booktime)){
		header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=empty_time");exit;
	}elseif ($bookdate<date("Y-m-d",time())){
		header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=date_error");exit;
	}
	$inputarr=array(
			"shopid"=>$shopid,
			"uid"=>$uid,
			"cusname"=>$cusname,
			"cusnum"	=>$cusnum,
			"cusphone"=>$cusphone,
			"bookdate"=>$bookdate,
			"booktime"=>$booktime,
			"bookstatus"=>"ready",
			"timestamp"=>time(),
	);
// 	print_r($inputarr);exit;
	$postbook->postBookData($inputarr);
	$oneprintarr=$postbook->getCheckBillidByShopid($shopid);
// 			print_r($oneprintarr);exit;
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printcontent=$postbook->generPrintBookOrderContent($deviceno, $devicekey, $inputarr);
// 		print_r($printcontent);exit;
		$postbook->sendSelfFormatMessage($printcontent);
	}
	header("location: ../bookinfo.php?shopid=$shopid&uid=$uid&cusnum=$cusnum&status=ok");exit;
	
}
?>