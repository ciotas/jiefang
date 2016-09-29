<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SetVipCard{
	public function saveVipCard($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->saveVipCard($inputarr);
	}
	public function updateOneVcd($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->updateOneVcd($inputarr);
	}
}
$setvipcard=new SetVipCard();
if(isset($_POST['vcid'])){
	$vcid=$_POST['vcid'];
	$shopid=$_SESSION['shopid'];
	$cardname=$_POST['cardname'];
	$cardrate=$_POST['cardrate'];
	$carddiscount=$_POST['carddiscount'];
	$cardlimit=$_POST['cardlimit'];
	$pointfactor=$_POST['pointfactor'];
	$inputarr=array(
			"vcid"=>$vcid,
			"shopid"=>$shopid,
			"cardname"=>$cardname,
			"cardrate"	=>$cardrate,
			"carddiscount"=>$carddiscount,
			"cardlimit"=>$cardlimit,
			"pointfactor"=>$pointfactor,
	);
	if(!empty($vcid)){
		$setvipcard->updateOneVcd($inputarr);
	}else{
		$setvipcard->saveVipCard($inputarr);
	}
	
	header("location: ../vipset.php");
}
?>