<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ShowType{
	public function changeShowtypeStatus($ftid,$status){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->changeShowtypeStatus($ftid, $status);
	}
}
$showtype=new ShowType();
if(isset($_GET['ftid'])){
	$ftid=$_GET['ftid'];
	$status=$_GET['status'];
	$showtype->changeShowtypeStatus($ftid, $status);
	echo "1";
}
?>