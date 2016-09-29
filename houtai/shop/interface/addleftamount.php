<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddLeftAmount{
	public function addRawleftamountData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->addRawleftamountData($inputarr);
	}
	public function getRawLastInputprice($shopid, $rawid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawLastInputprice($shopid, $rawid);
	}
}
$addleftamount=new AddLeftAmount();
if(isset($_POST['rawid'])){
	$rawid=$_POST['rawid'];
	$typeno=$_POST['typeno'];
	$rawleftamount=$_POST['rawleftamount'];
	if(empty($rawleftamount)){$rawleftamount=0;}
	$rawlefttinyamount=$_POST['rawlefttinyamount'];
	if(empty($rawlefttinyamount)){$rawlefttinyamount=0;}
	$rawpackrate=$_POST['rawpackrate'];
	if(empty($rawpackrate)){$rawpackrate=1;}
	$theyear=$_POST['theyear'];
	$themonth=$_POST['themonth'];
	$shopid=$_SESSION['shopid'];
	$addtime=time();
	$newrawprice=$addleftamount->getRawLastInputprice($shopid, $rawid);
	$inputarr=array(
			"shopid"	=>$shopid,
			"rawid"=>$rawid,
			"rawleftamount"=>$rawleftamount,
			"rawlefttinyamount"=>$rawlefttinyamount,
			"newrawprice"=>$newrawprice,
			"rawpackrate"=>$rawpackrate,
			"theyear"=>$theyear,
			"themonth"=>$themonth,
			"addtime"=>$addtime,
	);
// 	print_r($inputarr);exit;
	$addleftamount->addRawleftamountData($inputarr);
	header("location: ../stock/raw.php?typeno=$typeno");
}
?>
