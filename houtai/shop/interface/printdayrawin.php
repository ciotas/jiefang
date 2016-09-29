<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintDayRawin{
	public function generDayRawinPrintContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->generDayRawinPrintContent($deviceno, $devicekey, $inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printrawin=new PrintDayRawin();
if(isset($_POST['theday'])){
	$theday=$_POST['theday'];
	$shopid=$_SESSION['shopid'];
	$manager_name=$_POST['manager_name'];
	$data=json_decode($_POST['data'],true);
	$inputarr=array(
			"theday"	=>$theday,
			"manager_name"=>$manager_name,
			"data"=>$data,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printrawin->getCheckBillidByShopid($shopid);
// 	print_r($oneprintarr);exit;
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printcontent=$printrawin->generDayRawinPrintContent($deviceno, $devicekey, $inputarr);
// 		print_r($printcontent);exit;
		$printrawin->sendSelfFormatMessage($printcontent);
	}
	header("location: ../stock/addraw.php?theday=$theday");
}
?>