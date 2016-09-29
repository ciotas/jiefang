<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveStockNum{
	public function saveStockNumData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->saveStockNumData($inputarr);
	}
}
$savestocknum=new SaveStockNum();
if(isset($_POST['foodid'])){
	$foodid=$_POST['foodid'];
	$shopid=$_SESSION['shopid'];
	$num=$_POST['num'];
	if(empty($num)){$num="0";}
	$returnurl=$_POST['returnurl'];
	$inputarr=array(
			"foodid"=>$foodid,
			"shopid"=>$shopid,
			"num"=>$num,
	);
// 	print_r($inputarr);exit;
	$savestocknum->saveStockNumData($inputarr);
	header("location: ".$base_url."stock/$returnurl.php");
}
?>