<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SyncData{
	
}
$syncdata=new SyncData();
file_get_contents($clearcache_url."delCache.php?shopid=$shopid&");
file_get_contents($clearfoodcache_url."delCache.php?shopid=$shopid&");
file_get_contents($syncwechat_url."syncfood.php?shopid=$shopid&");
file_get_contents($syncphwechat_url."syncfood.php?shopid=$shopid&");

?>
