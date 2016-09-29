<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneTag{
	public function getOneTagByTagid($viptagid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getOneTagByTagid($viptagid);
	}
}
$getonetag=new GetOneTag();
if(isset($_GET['tagid'])){
	$bossid=$_SESSION['bossid'];
	$tagid=$_GET['tagid'];
	$result=$getonetag->getOneTagByTagid($tagid);
	echo json_encode($result);
}
?>