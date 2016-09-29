<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."function.php");
class SendMsg{
	
}
$sendmsg=new SendMsg();
if(isset($_POST['phone'])){
	$phone=$_POST['phone'];
	$msg=$_POST['msg'];
	$time=$_POST['timestamp'];
	$signature=$_POST['signature'];	
	$datas=json_decode($msg,true);
	$serversign=strtoupper(md5($phone.$msg.$time.$token));
	if($serversign==$signature){
		$status=sendTemplateSMS($phone,$datas,$msgtempId);
		echo $status;
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
}
exit;
$phone="13071870889";
$datas=array("张先生","8色家","A108","2015-11-11 11:00","0571-888888");
$status=sendTemplateSMS($phone,$datas,$msgtempId);
?>