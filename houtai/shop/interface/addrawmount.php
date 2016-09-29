<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddOneRawamount{
	public function addRawamountRecord($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->addRawamountRecord($inputarr);
	}
	public function saveRawStorage($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->saveRawStorage($inputarr);
	}
}
$addonerawamount=new AddOneRawamount();
if(isset($_POST['rawid'])){
	$rawid=$_POST['rawid'];
	$shopid=$_SESSION['shopid'];
	$rawamount=doubleval($_POST['rawamount']);
	if(empty($rawamount)){$rawamount=0;}
	$rawtinyamount=doubleval($_POST['rawtinyamount']);
	if(empty($rawtinyamount)){$rawtinyamount=0;}
	$rawprice=doubleval($_POST['rawprice']);//大单位价格
	$typeno=$_POST['typeno'];
	$theday=$_POST['theday'];
	$rawpaymoney=$_POST['rawpaymoney'];
	$manager=$_SESSION['manager_name'];
	$addtime=time();
	$inputarr=array(
			"shopid"=>$shopid,
			"rawid"=>$rawid,
			"rawprice"=>$rawprice,
			"rawamount"=>$rawamount,
			"rawtinyamount"=>$rawtinyamount,
			"rawpaymoney"=>$rawpaymoney,
			"manager"=>$manager,
			"theday"=>$theday,
			"addtime"=>$addtime,
	);
// 	print_r($inputarr);exit;
	$addonerawamount->saveRawStorage($inputarr);
	$addonerawamount->addRawamountRecord($inputarr);
	header("location: ../stock/addraw.php?typeno=$typeno");
}
?>