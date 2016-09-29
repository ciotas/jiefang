<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintCalc{
	public function generPrintContent($deviceno,$devicekey,$datarr,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generPrintContent($deviceno, $devicekey, $datarr, $theday);
	}
	public function generPrintSmallContent($deviceno, $devicekey, $datarr, $theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generPrintSmallContent($deviceno, $devicekey, $datarr, $theday);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printcalc=new PrintCalc();
if(isset($_POST['theday'])){
	$theday=$_POST['theday'];
	$shopid=$_SESSION['shopid'];
	$data=$_POST['data'];
	$cashierman=$_POST['cashierman'];
	$printsdata=json_decode($data,true);
// 	print_r($printsdata);exit;
	$printsdata['cashierman']=$cashierman;
	$oneprintarr=$printcalc->getCheckBillidByShopid($shopid);
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printertype=$oneprintarr['printertype'];
		if($printertype=="80"){
			$printcontent=$printcalc->generPrintContent($deviceno, $devicekey, $printsdata,$theday);
		}elseif($printertype=="58"){
			$printcontent=$printcalc->generPrintSmallContent($deviceno, $devicekey, $printsdata,$theday);
		}
// 		print_r($printcontent);exit;
		$printcalc->sendSelfFormatMessage($printcontent);
	}
	header("location: ../daysheet.php?theday=$theday");
}
?>