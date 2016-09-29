<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintFoodCalc{
	public function generFoodCalcPrintContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generFoodCalcPrintContent($deviceno, $devicekey, $inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printfoodcalc=new PrintFoodCalc();
if(isset($_POST['tabnum'])){
	$shopid=$_SESSION['shopid'];
// 	echo $shopid;exit;
	$tabnum=$_POST['tabnum'];
	$foodtotalmoney=$_POST['foodtotalmoney'];
	$ftnamearr=json_decode($_POST['ftname'],true);
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
	$data=json_decode($_POST['data'],true);
	$inputarr=array(
			"tabnum"=>$tabnum,
			"foodtotalmoney"	=>$foodtotalmoney,
			"ftnamearr"=>$ftnamearr,
			"startdate"=>$startdate,
			"starthour"=>$starthour,
			"enddate"=>$enddate,
			"endhour"=>$endhour,
			"data"=>$data,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printfoodcalc->getCheckBillidByShopid($shopid);
	print_r($oneprintarr);
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printcontent=$printfoodcalc->generFoodCalcPrintContent($deviceno, $devicekey, $inputarr);
// 		print_r($printcontent);exit;
		$printfoodcalc->sendSelfFormatMessage($printcontent);
	}
	header("location: ../foodcalc.php");
}
?>