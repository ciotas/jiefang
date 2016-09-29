<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneVcd{
	public function getOneVcdData($vcid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getOneVcdData($vcid);
	}
}
$getonevcd=new GetOneVcd();
if(isset($_GET['vcid'])){
	$vcid=$_GET['vcid'];
	$onevcd=$getonevcd->getOneVcdData($vcid);
	echo json_encode($onevcd);
}
?>