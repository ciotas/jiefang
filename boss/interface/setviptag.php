<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveVipTag{
	public function saveOneTag($inputarr){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->saveOneTag($inputarr);
	}
	public function updateOneTag($viptagid,$inputarr){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->updateOneTag($viptagid, $inputarr);
	}
}
$saveviptag=new SaveVipTag();
if(isset($_POST['viptaggid'])){
	$viptaggid=$_POST['viptaggid'];
	$bossid=$_SESSION['bossid'];
	$tagname=$_POST['tagname'];
	
	$inputarr=array(
			"viptaggid"=>$viptaggid,
			"bossid"=>$bossid,
			"tagname"=>$tagname,
	);
	if(!empty($viptaggid)){
		$saveviptag->updateOneTag($viptaggid,$inputarr);
	}else{
		$saveviptag->saveOneTag($inputarr);
	}
	header("location: ../viptag.php");
}
?>