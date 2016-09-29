<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
//环信
// require_once ('/var/www/html/emchat-server/Easemob.class.php');
// require_once ('/var/www/html/emchat-server/global.php');
class ReturnOneFood{
	public function updateBillFood($foodarr,$billid, $returnnum,$foodid,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype);
	}
	public function printReturnOrder($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->printReturnOrder($inputarr);
	}
	public function getOneFoodInBill($billid, $foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneFoodInBill($billid, $foodid);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function addToReturnBill($inputarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addToReturnBill($inputarr);
	}
	public function updateSelfStockByReturnFood($foodid,$returnnum){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateSelfStockByReturnFood($foodid, $returnnum);
	}
	public function updateMoneyWhenReturnfood($billid,$returnmoney){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateMoneyWhenReturnfood($billid, $returnmoney);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$returnonefood=new ReturnOneFood();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$foodid=$_POST['foodid'];
	$returnnum=$_POST['returnnum'];
	$foodnum =$_POST['foodnum'];
	$cooktype=$_POST['cooktype'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$returnonefood->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$foodid.$returnnum.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$returnonefood->updateCusSession($uid,$session);break;
			}
			$billarr=$returnonefood->getOneFoodInBill($billid,$foodid);
			$returnonefood->updateBillFood($billarr['food'], $billid, $returnnum, $foodid,$foodnum,$cooktype);
			//打印退菜单
			$inputarr=array(
					"uid"=>$uid,
					"nickname"=>$billarr['nickname'],
					"tabname"=>$billarr['tabname'],
					"billid"=>$billid,
					"foodid"=>$foodid,
					"cusnum"=>$billarr['cusnum'],
					"foodnum"=>$billarr['foodnum'],
					"orderunit"=>$billarr['orderunit'],
					"foodname"=>$billarr['foodname'],
					"returnnum"=>$returnnum,
					"timestamp"=>time(),
			);
			if($billarr['paystatus']=="unpay"){
				$printarr=$returnonefood->printReturnOrder($inputarr);
				$returnonefood->sendFreeMessage($printarr);//打印
			}elseif($billarr['paystatus']=="paid" && !empty($billarr['billnum'])){
				//变动结账
				$returnmoney=$returnnum*$billarr['foodprice'];
				$returnonefood->updateMoneyWhenReturnfood($billid, $returnmoney);
			}
			
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
			$returnonefood->addToReturnBill($inputarr);//添加退菜记录
			//自动库存盘点（专门针对已买单退菜）
			if($billarr['paystatus']=="paid"){
				$returnonefood->updateSelfStockByReturnFood($foodid, $returnnum);
			}
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="5647012a5bc1094d3e8b4d0d";
$foodid="554b0c6b5bc109d8518b45e1";
$billarr=$returnonefood->getOneFoodInBill($billid, $foodid);
// print_r($billarr);exit;
// $returnonefood->updateBillFood($billarr['food'], "552b3b0f5bc109cf318b4567", "1", "54748bbe16c1090b058b462e");
$inputarr=array(
		"uid"=>"560fcf6b7cc1096c058b4575",
		"nickname"=>$billarr['nickname'],
		"tabname"=>$billarr['tabname'],
		"billid"=>$billid,
		"foodid"=>$foodid,
		"cusnum"=>$billarr['cusnum'],
		"foodnum"=>$billarr['foodnum'],
		"orderunit"=>$billarr['orderunit'],
		"foodname"=>$billarr['foodname'],
		"returnnum"=>"1",
		"timestamp"=>time(),
);
$printarr=$returnonefood->printReturnOrder($inputarr);
print_r($printarr);exit;
$returnonefood->sendFreeMessage($printarr);//打印
// print_r($printarr);exit;

?>