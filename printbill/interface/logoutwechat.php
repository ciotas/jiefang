<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class LogoutWechat{
    public function LogOutMyWechat($shopid,$openid){
        PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->LogOutMyWechat($shopid,$openid);
    }
}
$logoutwechat=new LogoutWechat();
if(isset($_REQUEST['shopid'])){
    $shopid=$_REQUEST['shopid'];
    $openid=$_REQUEST['openid'];
    $logoutwechat->LogOutMyWechat($shopid,$openid);
}
?>