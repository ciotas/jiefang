<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SetVipCard{
	public function saveVipCard($inputarr){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->saveVipCard($inputarr);
	}
	public function updateOneVcd($inputarr){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->updateOneVcd($inputarr);
	}
}
$setvipcard=new SetVipCard();
if(isset($_POST['vcid'])){
	$vcid=$_POST['vcid'];
	$bossid=$_SESSION['bossid'];
	$cardname=$_POST['cardname'];
	$cardrate=$_POST['cardrate'];
// 	$carddiscount=$_POST['carddiscount'];
	$cardlimit=$_POST['cardlimit'];
	$pointfactor=$_POST['pointfactor'];
	$inputarr=array(
			"vcid"=>$vcid,
			"bossid"=>$bossid,
			"cardname"=>$cardname,
			"cardrate"	=>$cardrate,
// 			"carddiscount"=>$carddiscount,
			"cardlimit"=>$cardlimit,
			"pointfactor"=>$pointfactor,
	);
// 	print_r($inputarr);exit;
	if(!empty($vcid)){
		$setvipcard->updateOneVcd($inputarr);
	}else{
		$setvipcard->saveVipCard($inputarr);
	}
	
	header("location: ../vipset.php");
}
?>