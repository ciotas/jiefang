<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SyncFood{
	
}
$syncfood=new SyncFood();
if(isset($_GET['backurl'])){
	$backurl=$_GET['backurl'];
	$shopid=$_SESSION['shopid'];
	file_get_contents($clearcache_url."delCache.php?shopid=$shopid&");
	file_get_contents($clearfoodcache_url."delCache.php?shopid=$shopid&");
	file_get_contents($syncwechat_url."syncfood.php?shopid=$shopid&");
	file_get_contents($syncphwechat_url."syncfood.php?shopid=$shopid&");
	//同步微信点餐数据
	header("location: ../$backurl.php");
}

?>
