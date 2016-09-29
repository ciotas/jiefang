<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class DoPreDiscount{
	public function addPreConsumeBill($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->addPreConsumeBill($inputarr);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printPrediscountHtml($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printPrediscountHtml($json);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
}
$doprediscount=new DoPreDiscount();
if(isset($_POST['billid'])){
	$billid=$_POST['billid'];
	$shopid=$_SESSION['shopid'];
	$ticketway=$_POST['ticketway'];
	$ticketval=$_POST['ticketval'];
	$ticketnum=$_POST['ticketnum'];
	$discountval=$_POST['discountval'];
	if(empty($discountval)){
		$discountval=100;
	}
	$allcount=$_POST['allcount']['0'];
	if($allcount=="on"){
		$allcount="1";
	}else{
		$allcount="0";
	}
	$fooddisaccountmoney=$_POST['fooddisaccountmoney'];
	$serverfee=0;
	if(isset($_POST['serverfee'])){
	    $serverfee=$_POST['serverfee'];
	    if(empty($serverfee)){
	        $serverfee="0";
	    }
	}
	$servermoney=$fooddisaccountmoney*($serverfee/100);
	$returndepositmoney=0;
	if(!empty($_POST['returndepositmoney'])){
		$returndepositmoney=$_POST['returndepositmoney'];
	}
	
	$clearmoney=$_POST['clearmoney'];
	$shouldpay=$_POST['shouldpay'];
	$inputarr=array(
			"billid"	=>$billid,
			"shopid"=>$shopid,
			"ticketway"=>$ticketway,
			"ticketval"=>$ticketval,
			"ticketnum"=>$ticketnum,
			"discountval"=>$discountval,
			"allcount"=>$allcount,
	        "serverfee"=>$serverfee,
	        "servermoney"=>$servermoney,
			"returndepositmoney"=>$returndepositmoney,
			"clearmoney"=>$clearmoney,
			"shouldpay"=>$shouldpay,
	);
// 	print_r($inputarr);exit;
	$doprediscount->addPreConsumeBill($inputarr);
	//打印预结单
	$paytype="none";
	$cuspay=$shouldpay;
	$cashmoney="0";
	$unionmoney="0";
	$vipmoney="0";
	$alipay="0";
	$wechatpay="0";
	$meituanpay="0";
	$dazhongpay="0";
	$otherpay="0";
	
	$cashierman="";
	$temparr=array();
	$paymethod="commonpay";
	$totalmoney=0;
	$billarr=$doprediscount->getOneBillInfoByBillid($billid);
	foreach ($billarr['food'] as $key=>$val){
		if(empty($val['present'])){
			$totalmoney+=$val['foodamount']*$val['foodprice'];
		}
	}
	$paymoney=$shouldpay;
	$billarr=$doprediscount->getOneBillInfoByBillid($billid);//新数据
	$billarr['serverfee']=$serverfee;
	$billarr['servermoney']=$servermoney;
	$billarr['discountval']=$discountval;
	$billarr['ticketway']=$ticketway;
	$billarr['ticketval']=$ticketval;
	$billarr['ticketnum']=$ticketnum;
	$billarr['allcount']=$allcount;
	$billarr['returndepositmoney']=$returndepositmoney;
	$billarr['clearmoney']=$clearmoney;	
	$consumeListArr=$doprediscount->tobeConsumeList($billarr,$paymethod,$paymoney);
// 	print_r($consumeListArr);exit;//消费清单
	$consumearr=$doprediscount->printPrediscountHtml(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
// 		print_r($temparr);exit;
	$urls=$doprediscount->getUrlsArr(json_encode($temparr));
	// 	print_r($urls);exit;
	$doprediscount->sendFreeMessage($urls);//打印
	header("location: ../tabmanage.php");
}
?>