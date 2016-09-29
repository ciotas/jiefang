<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneDiscout{
    public function delDiscount($discountid)
	{
	    QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->delDiscount($discountid);
	}
}
$delonediscount=new DelOneDiscout();
if(isset($_REQUEST['id'])){
    $id=$_REQUEST['id'];
    $op=$_REQUEST['op'];
    $openid=$_REQUEST['openid'];
    $delonediscount->delDiscount($id);
    if($op=="notwechat"){
	    header("location: ../handle.php");
    }else{
	    header("location: ../wechatservice/handle.php?openid=".$openid);
    }
    
}
?>