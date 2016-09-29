<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintCalcRaw{
	public function generPrintCalcRawContent($deviceno,$devicekey,$inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->generPrintCalcRawContent($deviceno, $devicekey, $inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printcalcraw=new PrintCalcRaw();
if(isset($_POST['startdate'])){
	$shopid=$_SESSION['shopid'];
	$startdate=$_POST['startdate'];
	$enddate=$_POST['enddate'];
	$printsdata=json_decode($_POST['data'],true);
	$inputarr=array(
			"startdate"	=>$startdate,
			"enddate"=>$enddate,
			"data"=>$printsdata,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printcalcraw->getCheckBillidByShopid($shopid);
// 		print_r($oneprintarr);exit;
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printcontent=$printcalcraw->generPrintCalcRawContent($deviceno, $devicekey, $inputarr);
// 				print_r($printcontent);exit;
		$printcalcraw->sendSelfFormatMessage($printcontent);
	}
	header("location: ../stock/sumraw.php?startdate=$startdate&enddate=$enddate");
}
?>