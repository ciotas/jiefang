<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelFare{
    public function delFare($fareid)
	{
	    QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->delFare($fareid);
	}
}
$delfare=new DelFare();
if(isset($_REQUEST['id'])){
    $id=$_REQUEST['id'];
    $op=$_REQUEST['op'];
    $openid=$_REQUEST['openid'];
    $delfare->delFare($id);
    if($op=="notwechat"){
	    header("location: ../handle.php");
    }else{
	    header("location: ../wechatservice/handle.php?openid=".$openid);
    }
    
}
?>