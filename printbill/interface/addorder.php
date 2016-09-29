<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
//环信
// require_once ('/var/www/html/emchat-server/Easemob.class.php');
// require_once ('/var/www/html/emchat-server/global.php');
class AddOrder{
	public function getPrinters($shopid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getPrinters($shopid);
	}
	public function intoBillFood($billid,$foodarr){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->intoBillFood($billid, $foodarr);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function tobeRunner($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
	}
	public function tobeCusList($inputarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputarr);
	}
	public function tobeConsumeList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr);
	}
	public function printChuanCaiData($type,$json){
		return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($type, $json);
	}
	public function printCuslistData($type,$json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($type, $json);
	}
	public function printConsumeListData($json,$type){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json,$type);
	}
	public function PrintKitchenData($json, $type){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json, $type);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$addorder=new AddOrder();
// $easemob=new Easemob($options);
if(isset($_POST['uid'])){
	$shopid=$_POST['shopid'];
	$billid=$_POST['billid'];
	$uid=$_POST['uid'];
	$food=$_POST['food'];
	$foodarr=json_decode($food,true);
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$addorder->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$billid.$food.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$addorder->updateShopSession($shopid,$session);break;
			}
			$addorder->intoBillFood($billid, $foodarr);//更新数据库
			//打印
			$billarr=$addorder->getOneBillInfoByBillid($billid);
			$printerarr=$addorder->getPrinters($shopid);
// 			print_r($printerarr);exit;
			$temparr=array();
			foreach ($printerarr as $key=>$val){
				switch ($val['outputtype']){
					case "menu":
						$cusListArr=$addorder->tobeCusList($billarr);//划菜单
						// 			print_r($cusListArr);exit;
						$cuslistarr[]=$addorder->printCuslistData(json_encode($cusListArr));//menu
						if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
						break;
					case "checkout":
						$paymethod=$billarr['paymethod'];
						$paymoney=$billarr['paymoney'];
						$consumeListArr=$addorder->tobeConsumeList($billarr,$paymethod,$paymoney);
						// 			print_r($consumeListArr);exit;//消费清单
						$consumearr=$addorder->printConsumeListData(json_encode($consumeListArr));
						if(!empty($consumearr)){$temparr[]=$consumearr;}
						break;
					case "pass":
						$foodRunnerArr=$addorder->tobeRunner($billarr);//传菜单
						// 			print_r($foodRunnerArr);exit;
						$chuancaiarr=$addorder->printChuanCaiData(json_encode($foodRunnerArr));//pass
						if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
						break;
					default:
						if($val['outputtype']=="single" || $val['outputtype']=="double" || $val['outputtype']=="subtotal"){
							$orderfoodarr=$addorder->orderByprinterid($billarr);
							$piecelistArr=$addorder->tobePieceList($orderfoodarr);
							// 				print_r($piecelistArr);exit;
							$kitchenarr=$addorder->PrintKitchenData(json_encode($piecelistArr));
							if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						}
						break;
				}
			}
			//下单打印
			$urls=$addorder->getUrlsArr(json_encode($temparr));
			$addorder->sendFreeMessage($urls);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$shopid="5514c2c416c1092b2f8b4594";
$billid="5538656c5bc109af098b4568";
$uid="5514fe2c16c109762f8b456f";
$foodarr=array(
	"0"=>array("foodid"=>"55150adb16c1092f2f8b457e","foodnum"=>"2","isfree"=>"1"),
	"1"=>array("foodid"=>"55150adb16c1092f2f8b458c","foodnum"=>"1","isfree"=>"1"),
);
// echo json_encode($foodarr);exit;
// $addorder->intoBillFood($billid, $foodarr);
$printerarr=$addorder->getPrinters($shopid);
$arr=$addorder->getBillById($billid);
// print_r($arr);exit;
$temparr=array();
foreach ($printerarr as $key=>$val){
	switch ($val['outputtype']){
		case "1":
			$cusListArr=$addorder->tobeCusList($arr);//客看单
			$cuslistarr=$addorder->printCuslistData( $val['outputtype'], json_encode($cusListArr));
			if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
			break;
		case "2":
			$arr['discountzong']=$arr['disacountmoney'];
			$consumeListArr=$addorder->tobeConsumeList($arr);
			$consumearr=$addorder->printConsumeListData(json_encode($consumeListArr),$val['outputtype']);
			if(!empty($consumearr)){$temparr[]=$consumearr;}
			break;
		case "3":
			$foodRunnerArr=$addorder->tobeRunner($arr);//传菜单
			$chuancaiarr=$addorder->printChuanCaiData($val['outputtype'], json_encode($foodRunnerArr));
			if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
			break;
		default:
			if($val['outputtype']=="4" || $val['outputtype']=="5" || $val['outputtype']=="6"){
				$orderfoodarr=$addorder->orderByprinterid($arr);
				$piecelistArr=$addorder->tobePieceList($orderfoodarr);
				$kitchenarr=$addorder->PrintKitchenData(json_encode($piecelistArr), "kitchen");
				if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
			}
			break;
	}
}
print_r($temparr);exit;
$urls=$addorder->getUrlsArr(json_encode($temparr));
// $addorder->sendFreeMessage($urls);
?>