<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintTotalCalc{
	public function generTotalcalcPrintContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generTotalcalcPrintContent($deviceno, $devicekey, $inputarr);
	}
	public function generTotalcalcPrintSmallContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generTotalcalcPrintSmallContent($deviceno, $devicekey, $inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printtotalcalc=new PrintTotalCalc();
if(isset($_POST['startdate'])){
	$shopid=$_SESSION['shopid'];
	$startdate=$_POST['startdate'];
	$enddate=$_POST['enddate'];
	$data=json_decode($_POST['data'],true);
	$inputarr=array(
			"startdate"=>$startdate,
			"enddate"=>$enddate,
			"data"=>$data,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printtotalcalc->getCheckBillidByShopid($shopid);
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printertype=$oneprintarr['printertype'];
		if($printertype=="80"){
			$printcontent=$printtotalcalc->generTotalcalcPrintContent($deviceno, $devicekey, $inputarr);
		}elseif ($printertype=="58"){
			$printcontent=$printtotalcalc->generTotalcalcPrintSmallContent($deviceno, $devicekey, $inputarr);
		}
// 				print_r($printcontent);exit;
		$printtotalcalc->sendSelfFormatMessage($printcontent);
	}
	header("location: ../dayssheet.php?startdate=$startdate&enddate=$enddate");
}
?>