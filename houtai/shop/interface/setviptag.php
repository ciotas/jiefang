<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveVipTag{
	public function saveOneTag($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->saveOneTag($inputarr);
	}
	public function updateOneTag($viptagid,$inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateOneTag($viptagid, $inputarr);
	}
}
$saveviptag=new SaveVipTag();
if(isset($_POST['viptaggid'])){
	$viptaggid=$_POST['viptaggid'];
	$shopid=$_SESSION['shopid'];
	$tagname=$_POST['tagname'];
	
	$inputarr=array(
			"viptaggid"=>$viptaggid,
			"shopid"=>$shopid,
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