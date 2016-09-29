<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class updateBill{
	public function updateBillShopInfo($billid,$info){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->updateBillShopInfoByBillid($billid, $info);
	}
}
$model = new updateBill;
$billid = $_POST['billid'];
$info = array(
//     "prov"=>$_POST['prov'],
//     "city"=>$_POST['city'],
//     "dist"=>$_POST['dist'],
//     "road"=>$_POST['road'],
//     "carno"=>$_POST['carno'],
    "shopname"=>$_POST['shopname'],
    "contact"=>$_POST['contact'],
    "phone"=>$_POST['phone'],
);
$theday = $_POST['theday'];
$model->updateBillShopInfo($billid, $info);
header("location: ../flowinnersheet.php?theday={$theday}");
?>