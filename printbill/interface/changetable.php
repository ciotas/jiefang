<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
//环信
// require_once ('/var/www/html/emchat-server/Easemob.class.php');
// require_once ('/var/www/html/emchat-server/global.php');
class ChangeTable{
	public function swithTable($billid, $tabid,$newtabname){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->swithTable($billid, $tabid, $newtabname);
	}
	public function ChangeTableSheetContent($shopid,$oldtabname,$newtabname){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->ChangeTableSheetContent($shopid,$oldtabname,$newtabname);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
	public function updateTabStatus($newtabid,$oldtabid,$oldtabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateTabStatus($newtabid,$oldtabid, $oldtabstatus);
	}
	public function getShopidByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getShopidByBillid($billid);
	}
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
	public function getBillPaystatusByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillPaystatusByBillid($billid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
	public function intoChangeTabRecord($record){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoChangeTabRecord($record);
	}
}
$changetable=new ChangeTable();
// $easemob=new Easemob($options);
if(isset($_POST['billid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$oldtabid=$_POST['oldtabid'];
	$newtabid=$_POST['newtabid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$changetable->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$newtabid.$oldtabid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$changetable->updateCusSession($uid,$session);break;
			}
			$inputarr=array(
					"uid"=>$uid,
					"billid"=>$billid,
					"oldtabid"	=>$oldtabid,
					"newtabid"=>$newtabid,
					"timestamp"=>$timestamp,
			);
			$oldtabname=$changetable->getTablenameByTabid($oldtabid);
			$oldtabstatus=$changetable->getTabStatusByTabid($oldtabid);
			$newtabname=$changetable->getTablenameByTabid($newtabid);
			$newtabstatus=$changetable->getTabStatusByTabid($newtabid);
			if(($newtabstatus=="empty" || $newtabstatus=="book") && ($oldtabstatus=="start" || $oldtabstatus=="online")){ 
				$changetable->updateTabStatus($newtabid,$oldtabid, $oldtabstatus);
				$changetable->swithTable($billid,$newtabid, $newtabname);//换台
				$shopid=$changetable->getShopidByBillid($billid);
				$printarr=$changetable->ChangeTableSheetContent($shopid,$oldtabname,$newtabname);
				$changetable->sendFreeMessage($printarr);
				//添加换台记录
				$record=array(
						"billid"=>$billid,
						"shopid"=>$shopid,
						"newtabname"=>$newtabname,
						"oldtabname"=>$oldtabname,
						"uid"=>$uid,	
						"addtime"=>time(),
				);
				$changetable->intoChangeTabRecord($record);
			}
			header('Content-type: application/json');
			echo json_encode(array("tabstatus"=>$newtabstatus, "token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$oldtabid="5552ce3a5bc1092b7a8b4e36";
$newtabid="5552ce0f5bc109d5518b5104";
$oldtabname=$changetable->getTablenameByTabid($oldtabid);

$oldtabstatus=$changetable->getTabStatusByTabid($oldtabid);

$newtabname=$changetable->getTablenameByTabid($newtabid);

$newtabstatus=$changetable->getTabStatusByTabid($newtabid);
$billid="55e87bfc7cc109622c8b45a7";
if(($newtabstatus=="empty" || $newtabstatus=="book") && ($oldtabstatus=="start" || $oldtabstatus=="online")){
// 	$changetable->updateTabStatus($newtabid,$oldtabid, $oldtabstatus);
	$changetable->swithTable($billid,$newtabid, $newtabname);//换台
	$shopid=$changetable->getShopidByBillid($billid);
	
	$printarr=$changetable->ChangeTableSheetContent($shopid,$oldtabname,$newtabname);
	$changetable->sendFreeMessage($printarr);
}else{
	echo 22;exit;
}
?>