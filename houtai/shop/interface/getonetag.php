<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneTag{
	public function getOneTagByTagid($viptagid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getOneTagByTagid($viptagid);
	}
}
$getonetag=new GetOneTag();
if(isset($_GET['tagid'])){
	$shopid=$_SESSION['shopid'];
	$tagid=$_GET['tagid'];
	$result=$getonetag->getOneTagByTagid($tagid);
	echo json_encode($result);
}
?>