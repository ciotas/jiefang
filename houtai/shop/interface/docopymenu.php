<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DoCopyMenu{
	public function DoCopyMenuData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->DoCopyMenuData($inputarr);
	}
}
$docopymenu=new DoCopyMenu();
if(isset($_POST['toshopid'])){
	$toshopid=$_POST['toshopid'];
	$fromftid=$_POST['fromftid'];
	$toftid=$_POST['toftid'];
	$tozoneid=$_POST['tozoneid'];
	$fromshopid=$_SESSION['shopid'];
	if(!empty($toshopid) && !empty($fromftid) && !empty($toftid) && !empty($tozoneid)){
		$inputarr=array(
				"toshopid"	=>$toshopid,
				"fromshopid"=>$fromshopid,
				"fromftid"=>$fromftid,
				"toftid"=>$toftid,
				"tozoneid"=>$tozoneid,
		);
// 		print_r($inputarr);exit;
		$docopymenu->DoCopyMenuData($inputarr);
	}
	file_get_contents($clearcache_url."delCache.php?shopid=$toshopid&");
	file_get_contents($clearfoodcache_url."delCache.php?shopid=$toshopid&");
	file_get_contents($syncwechat_url."syncfood.php?shopid=$toshopid&");
	file_get_contents($syncphwechat_url."syncfood.php?shopid=$toshopid&");
	
	header("location: ../menucopy.php");
}
?>
