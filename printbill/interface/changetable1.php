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
	public function ChangeTableSheetContent($inputarr){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->ChangeTableSheetContent($inputarr);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getTablenameByTabid($tabid){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
	public function updateTabStatus($newtabid,$oldtabid){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateTabStatus($newtabid, $oldtabid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
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
			$oldtabname=$changetable->getTablenameByTabid($oldtabid);
			$newtabname=$changetable->getTablenameByTabid($newtabid);
			$changetable->swithTable($billid,$newtabid, $newtabname);//换台
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
			$changetable->updateTabStatus($newtabid, $oldtabid);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
// exit;
$billid="552b3b0f5bc109cf318b4567";
$oldtabid="5513702216c1094b628b4573";
$newtabid="552501e65bc10928568b456a";
$newtabname="A108";

// $changetable->swithTable($billid,$newtabid, $newtabname);//换台
$changetable->updateTabStatus($newtabid, $oldtabid);
?>