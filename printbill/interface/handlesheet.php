<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class HandleSheet{
	public function getPrintersInfoByPrinterarr($printerarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPrintersInfoByPrinterarr($printerarr);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function PrintKitchenData($json){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json);
	}
	public function tobeRunner($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
	}
	public function printChuanCaiData($json){
		return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($json);
	}
	public function tobeCusList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputdarr);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printConsumeListData($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
	}
	public function printCuslistData($json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($json);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function getOldTabstatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOldTabstatusByTabid($tabid);
	}
	public function getBillTypeData($billid, $outputtype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillTypeData($billid, $outputtype);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$handlesheet=new HandleSheet();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$tabid=$_POST['tabid'];
	$printers=$_POST['printers'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$handlesheet->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$tabid.$printers.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$handlesheet->updateCusSession($uid,$session);break;
			}
			$printerarr=json_decode($printers,true);
			$inputarr=$handlesheet->getOneBillInfoByBillid($billid);
			$parr=$handlesheet->getPrintersInfoByPrinterarr($printerarr);
			$temparr=array();
			foreach ($parr as $key=>$val){
				switch ($val['outputtype']){
					case "menu":
					$cusListArr=$handlesheet->tobeCusList($inputarr);//划菜单
					$cuslistarr=$handlesheet->printCuslistData(json_encode($cusListArr));//menu
					if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
						break;
					case "checkout":
						$paymethod=$inputarr['paymethod'];
						$paymoney=$inputarr['paymoney'];
						$consumeListArr=$handlesheet->tobeConsumeList($inputarr,$paymethod,$paymoney);
						// 			print_r($consumeListArr);exit;//消费清单
						$consumearr=$handlesheet->printConsumeListData(json_encode($consumeListArr));
						if(!empty($consumearr)){$temparr[]=$consumearr;}
						break;
					case "pass":
						$foodRunnerArr=$handlesheet->tobeRunner($inputarr);//传菜单
						//print_r($foodRunnerArr);exit;
						$chuancaiarr=$handlesheet->printChuanCaiData(json_encode($foodRunnerArr));//pass
						if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
						break;
					case "single":
						$billarr=$handlesheet->getBillTypeData($billid, "single");
						$billarr['printerid']=$val['printerid'];
						$orderfoodarr=$handlesheet->orderByprinterid($billarr);
						$piecelistArr=$handlesheet->tobePieceList($orderfoodarr);
						$kitchenarr=$handlesheet->PrintKitchenData(json_encode($piecelistArr));
						if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						break;
					case "double":
						$billarr=$handlesheet->getBillTypeData($billid, "double");
						$billarr['printerid']=$val['printerid'];
						$orderfoodarr=$handlesheet->orderByprinterid($billarr);
						$piecelistArr=$handlesheet->tobePieceList($orderfoodarr);
						$kitchenarr=$handlesheet->PrintKitchenData(json_encode($piecelistArr));
						if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						break;
					case "subtotal":
						$billarr=$handlesheet->getBillTypeData($billid, "subtotal");
						$billarr['printerid']=$val['printerid'];
						$orderfoodarr=$handlesheet->orderByprinterid($billarr);
						$piecelistArr=$handlesheet->tobePieceList($orderfoodarr);
						$kitchenarr=$handlesheet->PrintKitchenData(json_encode($piecelistArr));
						if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						break;
					case "total":
						$billarr=$handlesheet->getBillTypeData($billid, "total");
						$billarr['printerid']=$val['printerid'];
						$orderfoodarr=$handlesheet->orderByprinterid($billarr);
						$piecelistArr=$handlesheet->tobePieceList($orderfoodarr);
						$kitchenarr=$handlesheet->PrintKitchenData(json_encode($piecelistArr));
						if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						break;
					default:
						$inputarr['printerid']=$val['printerid'];
						$orderfoodarr=$handlesheet->orderByprinterid($inputarr);
						$piecelistArr=$handlesheet->tobePieceList($orderfoodarr);
						$kitchenarr=$handlesheet->PrintKitchenData(json_encode($piecelistArr));
						if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						break;
				}
			}
			//下单打印
			$urls=$handlesheet->getUrlsArr(json_encode($temparr));
			$nullarr=$handlesheet->sendFreeMessage($urls);
			//更新台号状态
			$tabstatus=$handlesheet->getOldTabstatusByTabid($tabid);
			if($tabstatus=="book"){
				$handlesheet->updateOneTabStatus($tabid, "start");
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
$printerarr=array("554adbcb5bc1092b7a8b4574");
$inputarr=$handlesheet->getOneBillInfoByBillid("554f26f65bc1097b528b45d5");
// print_r($inputarr);exit;
$parr=$handlesheet->getPrintersInfoByPrinterarr($printerarr);
// print_r($parr);exit;
$temparr=array();
foreach ($parr as $key=>$val){
	switch ($val['outputtype']){
		case "menu":
			$cusListArr=$handlesheet->tobeCusList($inputarr);//划菜单
			print_r($cusListArr);exit;
			$cuslistarr=$handlesheet->printCuslistData(json_encode($cusListArr));//menu
// 			print_r($cuslistarr);exit;
			if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
			break;
		case "checkout":
			$paymethod=$inputarr['paymethod'];
			$paymoney=$inputarr['paymoney'];
			$consumeListArr=$handlesheet->tobeConsumeList($inputarr,$paymethod,$paymoney);
// 			print_r($consumeListArr);exit;//消费清单
			$consumearr=$handlesheet->printConsumeListData(json_encode($consumeListArr));
			if(!empty($consumearr)){$temparr[]=$consumearr;}
			break;
		case "pass":
			$foodRunnerArr=$handlesheet->tobeRunner($inputarr);//传菜单
// 			print_r($foodRunnerArr);exit;
			$chuancaiarr=$handlesheet->printChuanCaiData(json_encode($foodRunnerArr));//pass
			if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
			break;
		default:
			if($val['outputtype']=="single" || $val['outputtype']=="double" || $val['outputtype']=="subtotal" || $val['outputtype']=="total" ){
				$inputarr['printerid']=$val['printerid'];
				$orderfoodarr=$handlesheet->orderByprinterid($inputarr);
// 				print_r($orderfoodarr);exit;
				$piecelistArr=$handlesheet->tobePieceList($orderfoodarr);
// 				print_r($piecelistArr);exit;
				$kitchenarr=$handlesheet->PrintKitchenData(json_encode($piecelistArr));
				if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
			}
			break;
	}
}
// print_r($temparr);exit;
//下单打印
$urls=$handlesheet->getUrlsArr(json_encode($temparr));
$nullarr=$handlesheet->sendFreeMessage($urls);
?>