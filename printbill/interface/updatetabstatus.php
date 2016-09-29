<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class UpdateTabStatus{
	public function updateOneTabStatus($tabid, $tabstatus){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function saveUpdateTabStatus($inputarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->saveUpdateTabStatus($inputarr);
	}
	public function getShopidByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getShopidByTabid($tabid);
	}
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
	public function isTheShopServerRoleidByUid($shopid,$uid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->isTheShopServerRoleidByUid($shopid, $uid);
	}
	public function getRoleFunsByRoleid($roleid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getRoleFunsByRoleid($roleid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$updatetabstatus=new UpdateTabStatus();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$tabid=$_POST['tabid'];
	$tabstatus=$_POST['tabstatus'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$updatetabstatus->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$tabid.$tabstatus.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$updatetabstatus->updateCusSession($uid,$session);break;
			}
			$shopid=$updatetabstatus->getShopidByTabid($tabid);
			$roleid=$updatetabstatus->isTheShopServerRoleidByUid($shopid, $uid);
			$oldtabstatus=$updatetabstatus->getTabStatusByTabid($tabid);
			if(!empty($roleid)){
				$rolesarr=$updatetabstatus->getRoleFunsByRoleid($roleid);				
				if(!empty($rolesarr)){
					if($rolesarr['pay']=="1"){//经理
						$updatetabstatus->updateOneTabStatus($tabid, $tabstatus);
					}else{//服务员
						if($rolesarr['start']=="1" && $tabstatus=="start"){
							$updatetabstatus->updateOneTabStatus($tabid, $tabstatus);
						}
						if($rolesarr['book']=="1" && $tabstatus=="book"){
							$updatetabstatus->updateOneTabStatus($tabid, $tabstatus);
						}
						if($rolesarr['online']=="1" && $tabstatus=="online"){
							$updatetabstatus->updateOneTabStatus($tabid, $tabstatus);
						}
						if($rolesarr['empty']=="1" && $tabstatus=="empty" && ($oldtabstatus=="online"||$oldtabstatus=="start")  ){
							$updatetabstatus->updateOneTabStatus($tabid, $tabstatus);
						}
					}
					$inputarr=array(
							"shopid"=>$shopid,
							"uid"=>$uid,
							"tabid"=>$tabid,
							"tabstatus"	=>$tabstatus,
							"timestamp"=>time(),
					);
					$updatetabstatus->saveUpdateTabStatus($inputarr);
				}
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
$roleid=$updatetabstatus->isTheShopServerRoleidByUid("554ad9615bc109d8518b45d2", "554ad8cc5bc109d7518b45b5");
$tabid="554addff5bc109d7518b45b8";
$oldtabstatus=$updatetabstatus->getTabStatusByTabid($tabid);
// echo $oldtabstatus;exit;
$rolesarr=$updatetabstatus->getRoleFunsByRoleid($roleid);
print_r($rolesarr);exit;
if($rolesarr['pay']=="1"){//经理
	$updatetabstatus->updateOneTabStatus($tabid, $tabstatus);
}
exit;
$shopid=$updatetabstatus->getShopidByTabid("5565d1015bc1092b7a8b9687");
$inputarr=array(
		"shopid"=>$shopid,
		"tabid"=>"5565d1015bc1092b7a8b9687",
		"tabstatus"	=>"empty",
		"timestamp"=>time(),
);
$updatetabstatus->saveUpdateTabStatus($inputarr);
// $updatetabstatus->updateOneTabStatus("5513702216c1094b628b4573", "start");
?>