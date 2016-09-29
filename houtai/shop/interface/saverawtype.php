<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveRawtype{
	public function addOneRawtype($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->addOneRawtype($inputarr);
	}
	public function updateOneRawtype($rtnid, $inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->updateOneRawtype($rtnid, $inputarr);
	}
}
$saverawtype=new SaveRawtype();
if(isset($_POST['rawtypename'])){
	$rawtypename=$_POST['rawtypename'];
	$rtnid=$_POST['rtnid'];
	$shopid=$_SESSION['shopid'];
	$inputarr=array(
			"shopid"=>$shopid,
			"rawtypename"	=>$rawtypename,
			"timestamp"=>time(),
	);
// 	print_r($inputarr);exit;
	if(!empty($rtnid)){
		$saverawtype->updateOneRawtype($rtnid, $inputarr);
	}else{
		$saverawtype->addOneRawtype($inputarr);
	}
	header("location: ../stock/rawtype.php");
}
?>