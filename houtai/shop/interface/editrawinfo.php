<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditRawInfo{
	public function addOneRawinfo($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->addOneRawinfo($inputarr);
	}
	public function updateOneRawinfo($rawid, $inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->updateOneRawinfo($rawid, $inputarr);
	}
}
$editrawinfo=new EditRawInfo();
if(isset($_POST['rawname'])){
	$shopid=$_SESSION['shopid'];
	$rawid=$_POST['rawid'];
	$rawtypeid=$_POST['rawtypeid'];
	$rawname=$_POST['rawname'];
	$rawcode=$_POST['rawcode'];
	$rawformat=$_POST['rawformat'];
// 	$rawpackunit=$_POST['rawpackunit'];
	$rawunit=$_POST['rawunit'];
	$rawtinyunit=$_POST['rawtinyunit'];
	$rawpackrate=$_POST['rawpackrate'];
	if(empty($rawpackrate)){$rawpackrate="1";}
	$typeno=$_POST['typeno'];
// 	var_dump($typeno);exit;
	$inputarr=array(
			"shopid"=>$shopid,
			"rawname"=>$rawname,
			"rawcode"=>$rawcode,
			"rawformat"=>$rawformat,
// 			"rawpackunit"=>$rawpackunit,
			"rawunit"=>$rawunit,
			"rawtypeid"=>$rawtypeid,
			"rawtinyunit"=>$rawtinyunit,
			"rawpackrate"=>$rawpackrate,
	);
// 	print_r($inputarr);exit;
	if(!empty($rawid)){
		$editrawinfo->updateOneRawinfo($rawid, $inputarr);
	}else{//add
		$editrawinfo->addOneRawinfo($inputarr);
	}
	header("location: ../stock/rawinfo.php?typeno=".$typeno);
}
?>