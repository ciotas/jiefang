<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoReCharge{
	public function chargeForPeople($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->chargeForPeople($inputarr);
	}
	public function judgeCardnoStatus($shopid,$userphone){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->judgeCardnoStatus($shopid, $userphone);
	}
}
$dorecharge=new DoReCharge();
if(isset($_POST['userphone'])){
	$userphone=$_POST['userphone'];
	$shopid=$_SESSION['shopid'];
	$chargemoney=intval($_POST['chargemoney']);
	if(empty($chargemoney)){
		header("location: ../recharge.php?status=chargemoney_empty");exit;
	}
	$cardstatus=$dorecharge->judgeCardnoStatus($shopid, $userphone);
	if($cardstatus){
		$inputarr=array(
				"shopid"=>$shopid,
				"userphone"=>$userphone,
				"chargemoney"=>$chargemoney,
		);
// 		print_r($inputarr);exit;
		$recordid=$dorecharge->chargeForPeople($inputarr);
		header("location: ../chargeresult.php?recordid=$recordid&type=charge");exit;//跳转到新的页面
	}else{
		header("location: ../recharge.php?status=phone_unreg");exit;
	}

}
?>