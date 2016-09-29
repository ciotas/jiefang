<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintAutoStock{
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
	public function generPrintAutostockContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->generPrintAutostockContent($deviceno, $devicekey, $inputarr);
	}
	public function generPrintAutostockSamllContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->generPrintAutostockSamllContent($deviceno, $devicekey, $inputarr);
	}
}
$printautostock=new PrintAutoStock();
if(isset($_POST['data'])){
	$shopid=$_SESSION['shopid'];
	$datarr=json_decode($_POST['data'],true);
	$inputarr=array(
			"shopid"=>$shopid,
			"data"=>$datarr,
	);
	$oneprintarr=$printautostock->getCheckBillidByShopid($shopid);
// 	print_r($inputarr);exit;
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printertype=$oneprintarr['printertype'];
		if($printertype=="80"){
			$printcontent=$printautostock->generPrintAutostockContent($deviceno, $devicekey,$inputarr);
		}elseif($printertype=="58"){
			$printcontent=$printautostock->generPrintAutostockSamllContent($deviceno, $devicekey,$inputarr);
		}
		
// 		print_r($printcontent);exit;
		$printautostock->sendSelfFormatMessage($printcontent);
	}
	header("location: ../stock/autostockfood.php");
}


?>