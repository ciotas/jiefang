<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintConsumeFood{
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
	public function generConsumeFoodPrintContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generConsumeFoodPrintContent($deviceno, $devicekey, $inputarr);
	}
	public function generConsumeFoodPrintSmallContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generConsumeFoodPrintSmallContent($deviceno, $devicekey, $inputarr);
	}
}
$printconsumefood=new PrintConsumeFood();
if(isset($_POST['theday'])){
	$theday=$_POST['theday'];
	$data=json_decode($_POST['data'],true);
	$shopid=$_SESSION['shopid'];
	$consumemoney=$_POST['consumemoney'];
	$inputarr=array(
			"shopid"=>$shopid,
			"theday"=>$theday,
			"consumemoney"=>$consumemoney,
			"data"=>$data,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printconsumefood->getCheckBillidByShopid($shopid);
// 			print_r($oneprintarr);exit;
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		if(printertype=="58"){
			$printcontent=$printconsumefood->generConsumeFoodPrintSmallContent($deviceno, $devicekey, $inputarr);
		}else{
			$printcontent=$printconsumefood->generConsumeFoodPrintContent($deviceno, $devicekey, $inputarr);
		}
			
// 		print_r($printcontent);exit;
		$printconsumefood->sendSelfFormatMessage($printcontent);
	}
	header("location: ../stock/dailyconsume.php?theday=$theday");
}
?>