<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class JudgeCardno{
	public function judgeCardnoStatus($shopid,$cardno){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->judgeCardnoStatus($shopid, $cardno);
	}
}
$judgecardno=new JudgeCardno();
if(isset($_GET['cardno'])){
	$shopid=$_SESSION['shopid'];
	$cardno=$_GET['cardno'];
	$result=$judgecardno->judgeCardnoStatus($shopid, $cardno);
	echo json_encode($result);
}
?>