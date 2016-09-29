<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PostAdv{
	public function addCusSheetAdvData($shopid, $content,$advurl){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->addCusSheetAdvData($shopid, $content,$advurl);
	}
}
$postadv=new PostAdv();
if(isset($_POST['bottomadv'])){
	$shopid=$_SESSION['shopid'];
	$bottomadv=$_POST['bottomadv'];
	$advurl=$_POST['advurl'];
	$postadv->addCusSheetAdvData($shopid, $bottomadv,$advurl);
	header("location: ../cussheetadv.php");
}
?>