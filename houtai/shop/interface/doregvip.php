<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DoRegVip{
	public function regCusinfo($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->regCusinfo($inputarr);
	}
}
$doregvip=new DoRegVip();
if(isset($_POST['realname'])){
	$shopid=$_SESSION['shopid'];
	$realname=$_POST['realname'];
// 	$cardno=$_POST['cardno'];
	$tagid=$_POST['tagid'];
	$cardid=$_POST['cardid'];
	$userphone=$_POST['userphone'];
	$checkcode=$_POST['checkcode'];
	
	$inputarr=array(
			"shopid"=>$shopid,
			"realname"=>$realname,
// 			"cardno"=>$cardno,
			"cardid"=>$cardid,
			"tagid"=>$tagid,
			"userphone"	=>$userphone,
			"checkcode"=>$checkcode,
			"addtime"=>time()
	);
// 	print_r($inputarr);exit;
	$result=$doregvip->regCusinfo($inputarr);
	header("location: ../vipreg.php?status=".$result['status']."&userphone=$userphone");
}
?>