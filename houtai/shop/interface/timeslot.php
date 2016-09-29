<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TimeSlot{
	public function editShopTimeSlot($shopid,$id=NULL,$name=NULL,$starttime=NULL,$overtime=NULL){
	    return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->editShopTimeSlot($shopid,$id,$name,$starttime,$overtime);
	}
}
$timeslot = new TimeSlot();
$shopid = $_REQUEST['shopid'];
$openid=$_REQUEST['openid'];
$id= isset($_REQUEST['id'])?$_REQUEST['id'] : NULL;
$name = isset($_POST['name'])?$_POST['name'] : NULL;
$starttime = isset($_POST['starttime'])?$_POST['starttime'] : NULL;
$overtime = isset($_POST['overtime'])?$_POST['overtime'] : NULL;
$op=$_REQUEST['op'];
$res = $timeslot->editShopTimeSlot($shopid,$id,$name,$starttime,$overtime);
if($op=="notwechat"){
	header("location: ../handle.php");
}else{
	header("location: ../wechatservice/handle.php?openid=".$openid);
}


?>