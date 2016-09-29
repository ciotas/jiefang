<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrintFoodtypeCalc{
	public function generTotalFoodcalcPrintContent($deviceno, $devicekey, $inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->generTotalFoodcalcPrintContent($deviceno, $devicekey, $inputarr);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
}
$printfoodtypecalc=new PrintFoodtypeCalc();
if(isset($_POST['soldtotalmoney'])){
	$soldtotalmoney=$_POST['soldtotalmoney'];
	$soldtotalnum=$_POST['soldtotalnum'];
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
	$data=json_decode($_POST['data'],true);
	$shopid=$_SESSION['shopid'];
	$inputarr=array(
			"soldtotalmoney"=>$soldtotalmoney,
			"soldtotalnum"=>$soldtotalnum,
			"starthour"=>$starthour,
			"startdate"=>$startdate,
			"enddate"=>$enddate,
			"endhour"=>$endhour,
			"data"=>$data,
	);
// 	print_r($inputarr);exit;
	$oneprintarr=$printfoodtypecalc->getCheckBillidByShopid($shopid);
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$printcontent=$printfoodtypecalc->generTotalFoodcalcPrintContent($deviceno, $devicekey, $inputarr);
// 						print_r($printcontent);exit;
		$printfoodtypecalc->sendSelfFormatMessage($printcontent);
	}
	header("location: ../typecalc.php?startdate=$startdate&enddate=$enddate&starthour=$starthour&endhour=$endhour");
	
}
?>