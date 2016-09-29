<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Discount{
	public function addDiscount($shopid,$data){
	    QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->addDiscount($shopid, $data);
	}
	
}

$discount=new Discount();
$shopid = $_POST['shopid'];
$openid=$_POST['openid'];
$op=$_POST['op'];
$data['money'] = $_POST['money'];
$data['discount'] = $_POST['discount'];
$discount->addDiscount($shopid, $data);
if($op=="notwechat"){
	header("location: ../handle.php");
}else{
	header("location: ../wechatservice/handle.php?openid=".$openid);
}

?>