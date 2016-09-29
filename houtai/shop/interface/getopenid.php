<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOpenId{
    public function getShopidByOpenid($openid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
    }
    public function getRealOpenidByShopid($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getRealOpenidByShopid($shopid);
    }
}
$getopenid=new GetOpenId();
if(isset($_POST['openid'])){
    $openid=$_POST['openid'];
    $shopid=$getopenid->getShopidByOpenid($openid);
    $realopenid=$getopenid->getRealOpenidByShopid($shopid);
    if(!empty($realopenid)){
        $code="200";
        $msg="openid获取正常";
    }else{
        $code="110";
        $msg="openid为空";
    }
    echo json_encode(array("code"=>$code,"msg"=>$msg,"data"=>array("openid"=>$realopenid)));
}
exit;
$openid="o1HJqt4r0hiILjIJ2dg5ifgSa0jY";
$shopid=$getopenid->getShopidByOpenid($openid);
echo $shopid;exit;
$realopenid=$getopenid->getRealOpenidByShopid($shopid);
echo $realopenid;
?>