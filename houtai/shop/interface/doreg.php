<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DoReg{
	public function regShopData($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->regShopData($inputarr);
	}
}
$doreg=new DoReg();
if(isset($_POST['phone'])){
	$phone=$_POST['phone'];
	if(empty($phone)){return ;}
	$checkcode=$_POST['checkcode'];
	if(empty($checkcode)){return ;}
	$password=$_POST['password'];
	if(empty($password)){return ;}
	$rpassword=$_POST['rpassword'];
	$shopname=$_POST['shopname'];
	if(empty($shopname)){return ;}
	if($password==$rpassword){
		$inputarr=array(
				"mobilphone"	=>$phone,
				"checkcode"=>$checkcode,
				"passwd"=>$password,
				"shopname"=>$shopname,
				"shopstatus"=>"1",
				"addtime"=>time()
		);
// 		print_r($inputarr);exit;
		$result=$doreg->regShopData($inputarr);
		header("location: ../login.php?status=".$result['status']);
	}else{
		header("location: ../login.php?status=notequal");
	}
// 	header("location: ../login.php");
}
?>