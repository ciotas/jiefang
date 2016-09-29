<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveStockAmount{
	public function saveAutoStockFood($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->saveAutoStockFood($inputarr);
	}
	
}
$savestockamount=new SaveStockAmount();
if(isset($_POST['foodid'])){
	$shopid=$_SESSION['shopid'];
	$foodid=$_POST['foodid'];
	$format=$_POST['format'];
	$packunit=$_POST['packunit'];
	$packnum=0;
	$retailnum=0;
	$packrate=$_POST['packrate'];
	$backurl=$_POST['backurl'];
	if(empty($packrate)){$packrate=1;}
	$inputarr=array(
			"foodid"	=>$foodid,
			"shopid"=>$shopid,
			"format"=>$format,
			"packunit"=>$packunit,
			"packnum"=>intval($packnum),
			"retailnum"=>intval($retailnum),
			"packrate"=>$packrate,
			"timestamp"=>time(),
	);
// 	print_r($inputarr);exit;
	$savestockamount->saveAutoStockFood($inputarr);
	header("location: ".$base_url."stock/$backurl.php");
}
?>