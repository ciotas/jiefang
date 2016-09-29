<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintRawStock{
	public function generRawStockPrintContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->generRawStockPrintContent($deviceno, $devicekey, $inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printrawstock=new PrintRawStock();
if(isset($_POST['theyear'])){
	$theyear=$_POST['theyear'];
	$themonth=$_POST['themonth'];
	$shopid=$_SESSION['shopid'];
	$T_rawmoney=$_POST['T_rawmoney'];
	$T_rawpaymoney=$_POST['T_rawpaymoney'];
	$T_rawusemoney=$_POST['T_rawusemoney'];
	$T_rawleftmoney=$_POST['T_rawleftmoney'];
	
	$data=json_decode($_POST['data'],true);
	$inputarr=array(
			"theyear"	=>$theyear,
			"themonth"=>$themonth,
			"T_rawmoney"=>$T_rawmoney,
			"T_rawpaymoney"=>$T_rawpaymoney,
			"T_rawusemoney"=>$T_rawusemoney,
			"T_rawleftmoney"=>$T_rawleftmoney,
			"data"=>$data,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printrawstock->getCheckBillidByShopid($shopid);
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printcontent=$printrawstock->generRawStockPrintContent($deviceno, $devicekey, $inputarr);
// 		print_r($printcontent);exit;
		$printrawstock->sendSelfFormatMessage($printcontent);
	}
	header("location: ../stock/raw.php?theyear=$theyear&themonth=$themonth");
}
?>