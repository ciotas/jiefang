<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetShopMoney{
    public function getShopidByOpenid($openid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
    }
    public function getShopmoneyByShopid($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopmoneyByShopid($shopid);
    }
    public function getTodayMoney($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getTodayMoney($shopid);
    }
}
$getshopmoney=new GetShopMoney();
if(isset($_POST['openid'])){
    $openid=$_POST['openid'];
    $shopid=$getshopmoney->getShopidByOpenid($openid);
    $account=$getshopmoney->getShopmoneyByShopid($shopid);
    $todaymoney=$getshopmoney->getTodayMoney($shopid);
    echo json_encode(array("code"=>"200","msg"=>"获取数据正确","data"=>array("account"=>$account,"todaymoney"=>$todaymoney)));
}
?>