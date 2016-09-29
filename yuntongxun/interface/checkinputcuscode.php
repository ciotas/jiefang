<?php 
require_once ('/var/www/html/yuntongxun/global.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
class CheckInputCusCode{
	public function checkCusCode($phone, $checkcode, $timestamp) {
		return InterfaceFactory::createInstanceSendMsgDAL()->checkCusCode($phone, $checkcode, $timestamp);
	}
}
$checkinputcuscode=new CheckInputCusCode();
if(isset($_POST['phone'])&&isset($_POST['checkcode'])){
	$phone=$_POST['phone'];
	$checkcode=$_POST['checkcode'];
	$timestamp=time();
	$time=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$serversign=strtoupper(md5($phone.$time.$token));
	if($serversign==$signature){
		$status=$checkinputcuscode->checkCusCode($phone, $checkcode, $timestamp);
		header('Content-type: application/json');
		echo json_encode(array("status"=>$status));
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
}
exit;
echo $checkinputcuscode->checkCusCode('K5ujz21uBkfrtcVDWcKhRQ==', '5352', time());
?>