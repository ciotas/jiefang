<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
//环信
require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class UpdateTab{
	public function swithTable($billid, $tabname){
		PRINT_InterfaceFactory::createInstanceHandleDAL()->switchTable($billid, $tabname);
	}
	public function getSureTableSheetContent($inputarr){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getSureTableSheetContent($inputarr);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function updateTabStatus($shopid,$tabname, $usestatus){
		PRINT_InterfaceFactory::createInstanceHandleDAL()->updateTabStatus($shopid,$tabname, $usestatus);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$updatetab=new UpdateTab();
$easemob=new Easemob($options);
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$nickname=$_POST['nickname'];
	$billid=$_POST['billid'];
	$uid=$_POST['uid'];
	$newtabname=$_POST['newtabname'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$updatetab->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$billid.$uid.$nickname.$newtabname.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$updatetab->updateShopSession($shopid,$session);break;
			}
			$inputarr=array(
					"shopid"=>$shopid	,
					"nickname"=>$nickname,
					"newtabname"=>$newtabname,
					"timestamp"=>time(),
			);
			$updatetab->swithTable($billid, $newtabname);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
			
			$printarr=$updatetab->getSureTableSheetContent($inputarr);
			$updatetab->sendFreeMessage($printarr);
			
			$updatetab->updateTabStatus($shopid, $newtabname, "1");
			
			$sendcusMsg="顾客您好，已通知厨房上菜，请稍等。";
			$easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$updatetab->swithTable("5474595d16c109ef258b45c2", "A12");exit;
$inputarr=array(
					"shopid"=>"547430f016c10932708b4624"	,
					"nickname"=>"lindy",
					"newtabname"=>"A12",
					"timestamp"=>time(),
			);
// $updatetab->swithTable("547a7f1e16c109ef258b45ca", "A12");exit;
$printarr=$updatetab->getSureTableSheetContent($inputarr);
print_r($printarr);exit;
$updatetab->sendFreeMessage($printarr);
?>