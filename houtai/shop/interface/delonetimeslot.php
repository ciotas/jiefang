<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneTimeslot{
    public function delOneTimeSlot($id){
        QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->delOneTimeSlot($id);
    }
}
$delonetimeslot=new DelOneTimeslot();
if(isset($_REQUEST['id'])){
    $id=$_REQUEST['id'];
    $op=$_REQUEST['op'];
    $openid=$_REQUEST['openid'];
    $delonetimeslot->delOneTimeSlot($id);
    if($op=="notwechat"){
	    header("location: ../handle.php");
    }else{
	    header("location: ../wechatservice/handle.php?openid=".$openid);
    }
    
}
?>