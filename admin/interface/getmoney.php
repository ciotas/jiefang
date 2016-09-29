<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetMoney{
    public function getShopaccountByShopid($shopid){
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getShopaccountByShopid($shopid);
    }
    public function getShopinfo($shopid){
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getShopinfo($shopid);
    }
}
$getmoney=new GetMoney();
if(isset($_GET['shopid'])){
    $shopid=$_GET['shopid'];
    $money=$getmoney->getShopaccountByShopid($shopid);
    $shoparr=$getmoney->getShopinfo($shopid);
    echo json_encode(array("shopid"=>$shopid,"shopname"=>$shoparr['shopname'], "money"=>$money));
}
?>