<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddOneRole{
	public function addOneRoleData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->addOneRoleData($inputarr);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$addonerole=new AddOneRole();
if(isset($_POST['rolename'])){
	$rolename=$_POST['rolename'];
	$shopid=$_SESSION['shopid'];
	$detail=$_POST['detail'][0];
	if($detail=="on"){
		$detail="1";
	}else{
		$detail="0";
	}
	$donate=$_POST['donate'][0];
	if($donate=="on"){
		$donate="1";
	}else{
		$donate="0";
	}
	$weight=$_POST['weight'][0];
	if($weight=="on"){
		$weight="1";
	}else{
		$weight="0";
	}
	$returnfood=$_POST['returnfood'][0];
	if($returnfood=="on"){
		$returnfood="1";
	}else{
		$returnfood="0";
	}
	$outsheet=$_POST['outsheet'][0];
	if($outsheet=="on"){
		$outsheet="1";
	}else{
		$outsheet="0";
	}
	$empty=$_POST['empty'][0];
	if($empty=="on"){
		$empty="1";
	}else{
		$empty="0";
	}
	$book=$_POST['book'][0];
	if($book=="on"){
		$book="1";
	}else{
		$book="0";
	}
	$start=$_POST['start'][0];
	if($start=="on"){
		$start="1";
	}else{
		$start="0";
	}
	$online=$_POST['online'][0];
	if($online=="on"){
		$online="1";
	}else{
		$online="0";
	}
	$changetab=$_POST['changetab'][0];
	if($changetab=="on"){
		$changetab="1";
	}else{
		$changetab="0";
	}
	$changeprice=$_POST['changeprice'][0];
	if($changeprice=="on"){
		$changeprice="1";
	}else{
		$changeprice="0";
	}
	
	$pay=$_POST['pay'][0];
	if($pay=="on"){
		$pay="1";
	}else{
		$pay="0";
	}
	$repay=$_POST['repay'][0];
	if($repay=="on"){
		$repay="1";
	}else{
		$repay="0";
	}
	$deposit=$_POST['deposit'][0];
	if($deposit=="on"){
		$deposit="1";
	}else{
		$deposit="0";
	}
	$inputarr=array(
			"shopid"=>$shopid,
			"rolename"=>$rolename,
			"detail"=>$detail,
			"donate"=>$donate,
			"weight"=>$weight,
			"returnfood"=>$returnfood,
			"outsheet"=>$outsheet,
			"empty"=>$empty,
			"book"=>$book,
			"start"=>$start,
			"online"=>$online,
			"changetab"=>$changetab,
			"changeprice"=>$changeprice,
			"pay"=>$pay,
			"repay"=>$repay,
			"deposit"=>$deposit,
	);
// 	print_r($inputarr);exit;
	$addonerole->addOneRoleData($inputarr);
	$addonerole->syncData($shopid);
	header("location: ../jobset.php");
}
?>