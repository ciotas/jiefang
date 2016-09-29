<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BindShopID{
    public function bindShopidAndOpenid($shopid, $openid){
        QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->bindShopidAndOpenid($shopid, $openid);
    }
    public function getShopidByPhoneAndPwd($phone, $passwd){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByPhoneAndPwd($phone, $passwd);
    }
}
$bindshopid=new BindShopID();
if(isset($_POST['phone'])){
    $phone=$_POST['phone'];
    $passwd=$_POST['passwd'];
    $openid=$_POST['openid'];
    $shopid=$bindshopid->getShopidByPhoneAndPwd($phone, $passwd);
    if(!empty($shopid)){
        $bindshopid->bindShopidAndOpenid($shopid, $openid);
        $code="ok";
    }else{
        $code="no";
    }
    echo json_encode(array("code"=>$code));
}
?>