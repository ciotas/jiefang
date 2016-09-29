<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');
class LogOut{
    public function getPostRequest($url='', $data){
        return Wechat_BLLFactory::createInstanceWechatBLL()->getPostRequest($url, $data);
    }
    public function getShopidByOpenid($openid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
    }
}
$logout=new LogOut();
if(isset($_GET['openid'])){
    $openid = isset($_GET['openid'])?$_GET['openid']:'';
    $shopid=$logout->getShopidByOpenid($openid);
    $url = ROOTURL.'printbill/interface/logoutwechat.php?shopid='.$shopid."&openid=";
    file_get_contents($url);
    $url= ROOTURL.'wechat/interface/bindpage.php?openid='.$openid."&menutype=11";
    header("location: ".$url);
}
?>