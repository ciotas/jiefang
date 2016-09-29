<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddOneFood{
	public function upFoodToDB($foodarr){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->upFoodToDB($foodarr);
	}
	public function updateFoodByFid($foodid, $inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->updateFoodByFid($foodid, $inputarr);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$addonefood=new AddOneFood();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$openid=$_POST['openid'];
	$foodid=$_POST['foodid'];
	$sortno=$_POST['sortno'];
	$foodname=$_POST['foodname'];
	$foodengname=$_POST['foodengname'];
	$foodprice=$_POST['foodprice'];
	$orderunit=$_POST['orderunit'];
	$foodcode=$_POST['foodcode'];
	$foodunit=$_POST['foodunit'];
	$foodcooktype=$_POST['foodcooktype'];
	$zoneid=$_POST['zoneid'];
	$foodtypeid=$_POST['ftid'];
	$fooddisaccount=$_POST['fooddisaccount'][0];
	$mustorder=$_POST['mustorder'][0];
	$orderbynum=$_POST['orderbynum'][0];
	if($fooddisaccount=="on"){
		$fooddisaccount="1";
	}else{
		$fooddisaccount="0";
	}
	$isweight=$_POST['isweight'][0];
	if($isweight=="on"){
		$isweight="1";
	}else{
		$isweight="0";
	}
	$ishot=$_POST['ishot'][0];
	if($ishot=="on"){
		$ishot="1";
	}else{
		$ishot="0";
	}
	$ispack=$_POST['ispack'][0];
	if($ispack=="on"){
		$ispack="1";
	}else{
		$ispack="0";
	}
	$foodguqing=$_POST['foodguqing'][0];
	if($foodguqing=="on"){
		$foodguqing="1";
	}else{
		$foodguqing="0";
	}
	$autostock=$_POST['autostock'][0];
	if($autostock=="on"){
		$autostock="1";
	}else{
		$autostock="0";
	}
	
	$showout=$_POST['showout'][0];
	if($showout=="on"){
		$showout="1";
	}else{
		$showout="0";
	}
	
	$showserver=$_POST['showserver'][0];
	if($showserver=="on"){
		$showserver="1";
	}else{
		$showserver="0";
	}
	
	if($mustorder=="on"){
		$mustorder="1";
	}else{
		$mustorder="0";
	}
	if($orderbynum=="on"){
		$orderbynum="1";
	}else{
		$orderbynum="0";
	}
	//必点菜开启，消费者可见关闭
	if($mustorder=="1"){$showout="0";}
	//必点菜关闭，按人数为0
	if($mustorder=="0"){$orderbynum="0";}
	$foodintro=$_POST['foodintro'];
	$typeno=$_POST['typeno'];
	$inputarr=array(
			"shopid"=>$shopid,
			"foodname"=>$foodname,
			"foodengname"=>$foodengname,
			"foodcode"=>$foodcode,
			"foodprice"=>$foodprice,
			"orderunit"=>$orderunit,
			"foodunit"=>$foodunit,
			"foodcooktype"=>$foodcooktype,
			"zoneid"=>$zoneid,
			"foodtypeid"=>$foodtypeid,
			"fooddisaccount"=>$fooddisaccount,
			"isweight"=>$isweight,
			"ishot"=>$ishot,
			"ispack"=>$ispack,
			"foodguqing"=>$foodguqing,
			"autostock"=>$autostock,
			"showout"=>$showout,
			"showserver"=>$showserver,
			"mustorder"=>$mustorder,
			"orderbynum"=>$orderbynum,
			"sortno"=>$sortno,
			"foodintro"=>$foodintro,
			
	);
// 	print_r($inputarr);exit;
	if(!empty($foodid)){
		$addonefood->updateFoodByFid($foodid, $inputarr);
	}else{
		$addonefood->upFoodToDB($inputarr);
	}
	$addonefood->syncData($shopid);
	header("location: ../wechatservice/foodmanage.php?typeno=".$typeno."&openid=$openid");
}
?>
