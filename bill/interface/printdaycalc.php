<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class PrintDayCalc{
	public function generPrintContent($deviceno,$devicekey,$datarr,$theday){
		return Bill_InterfaceFactory::createInstanceBillDAL()->generPrintContent($deviceno, $devicekey, $datarr,$theday);
	}
	public function getOnePrinterInfoByPid($pid){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getOnePrinterInfoByPid($pid);
	}
	public function sendSelfFormatMessage($msgInfo){
		Bill_InterfaceFactory::createInstanceBillDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$printdaycalc=new PrintDayCalc();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$printerid=$_POST['printerid'];
	$theday=$_POST['theday'];
	$printdata=$_POST['printdata'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$printdaycalc->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$printerid.$theday.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$printdaycalc->updateShopSession($shopid,$session);break;
			}
			$oneprintarr=$printdaycalc->getOnePrinterInfoByPid($printerid);
			if(!empty($oneprintarr)){
				$deviceno=$oneprintarr['deviceno'];
				$devicekey=$oneprintarr['devicekey'];
				$datarr=json_decode($printdata,true);
				$printcontent=$printdaycalc->generPrintContent($deviceno, $devicekey, $printdata,$theday);
				$printdaycalc->sendSelfFormatMessage($printcontent);
			}
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$oneprintarr=$printdaycalc->getOnePrinterInfoByPid("554adc825bc109dd518b45c0");
// print_r($oneprintarr);exit;
$theday="2015-5-13";
$printdata=array(
		"totalmoney"=>strval(sprintf("%.0f","12")),
		"billnum"=>strval(2),
		"cusnum"=>strval(3),
		"receivablemoney"=>strval(3),
		"avgmoney"=>strval(3),
		"changerate"=>strval(43),
		"cashmoney"=>strval(54),
		"unionmoney"=>strval(32),
		"vipmoney"=>strval(54),
		"meituanpay"=>strval(65),
		"alipay"=>strval(67),
		"wechatpay"=>strval(89),
		"clearmoney"=>strval(9),
		"othermoney"=>strval(0),
		"signmoney"=>strval(0),
		"freemoney"=>strval(0),
		"discountmoney"=>strval(45),
		"ticketmoney"=>strval(8),
);
if(!empty($oneprintarr)){
	$deviceno=$oneprintarr['deviceno'];
	$devicekey=$oneprintarr['devicekey'];
	$printcontent=$printdaycalc->generPrintContent($deviceno, $devicekey, $printdata,$theday);
// 	print_r($printcontent);exit;
	$printdaycalc->sendSelfFormatMessage($printcontent);
}
?>