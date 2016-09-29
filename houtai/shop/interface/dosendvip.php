<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoReCharge{
	public function chargeForPeople($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->chargeForPeople($inputarr);
	}
	public function isRegByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->isRegByphone($phone);
	}
	public function getUidByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getUidByphone($phone);
	}
	public function isSendToTheUser($shopid, $uid, $cardid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->isSendToTheUser($shopid, $uid, $cardid);
	}
}
$dorecharge=new DoReCharge();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$userphone=$_POST['userphone'];
	if(empty($userphone)){
		header("location: ../recharge.php?status=phone_empty");exit;
	}
	$phonecrypt = new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$isreg=$dorecharge->isRegByphone($userphone);
	$cardid=$_POST['cardid'];
	if(empty($cardid)){
		header("location: ../sendvip.php?status=cardid_empty");exit;
	}
	$chargemoney="0";
	$sendmoney="0";
	if($isreg){
		//查询uid
		$uid=$dorecharge->getUidByphone($userphone);
		$issended=$dorecharge->isSendToTheUser($shopid, $uid, $cardid);
		if($issended){//已赠送
			header("location: ../sendvip.php?status=sended");exit;
		}else{
			$inputarr=array(
					"shopid"=>$shopid,
					"uid"=>$uid,
					"userphone"=>$userphone,
					"cardid"=>$cardid,
					"chargemoney"=>$chargemoney,
					"sendmoney"=>$sendmoney,
			);
			$recordid=$dorecharge->chargeForPeople($inputarr);
			header("location: ../chargeresult.php?recordid=".$recordid."&type=send");exit;//跳转到新的页面
		}
		
	}else{
		header("location: ../sendvip.php?status=phone_unreg");exit;
	}

}
?>