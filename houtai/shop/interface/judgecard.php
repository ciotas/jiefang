<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class JudgeCardno{
	public function judgeCardnoStatus($shopid,$phone){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->judgeCardnoStatus($shopid, $phone);
	}
}
$judgecardno=new JudgeCardno();
if(isset($_GET['phone'])){
	$shopid=$_SESSION['shopid'];
	$phone=$_GET['phone'];
	$phonecrypt = new CookieCrypt($cusphonekey);
	$phone=$phonecrypt->encrypt($phone);
	$result=$judgecardno->judgeCardnoStatus($shopid, $phone);
	echo json_encode($result);
}
?>