<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Fare{
	public function addFare($shopid,$data){
	    QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->addFare($shopid, $data);
	}
	public function changeFare($fareid,$data,$shopid){
	    return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->changeFare($fareid, $data, $shopid);
	}
	public function delFare($shopid,$fareid)
	{
	    QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->delFare($shopid, $fareid);
	}
}

$fare=new Fare();
$openid=$_POST['openid'];
$op=$_POST['op'];
$shopid=$_POST['shopid'];
$data['area'] = $_POST['area'];
$data['fare'] = $_POST['fare'];
$fare->addFare($shopid, $data);

if($op=="notwechat"){
	header("location: ../handle.php");
}else{
	header("location: ../wechatservice/handle.php?openid=".$openid);
}
