<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpdateGuqing{
	public function updateGuqingStatus($foodid, $guqingstatus){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->updateGuqingStatus($foodid, $guqingstatus);
	}
}
$updateguqing=new UpdateGuqing();
if(isset($_GET['foodid'])){
	$foodid=$_GET['foodid'];
	$shopid=$_SESSION['shopid'];
	$guqingstatus="0";
	$updateguqing->updateGuqingStatus($foodid, $guqingstatus);
// 	file_get_contents("http://shop.meijiemall.com/shophome/interface/delCache.php?shopid=$shopid&");
	header("location: ../guqinglist.php");
}
?>