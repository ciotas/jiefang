<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class IsBindShop{
    public function getShopidByOpenid($openid){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getShopidByOpenid($openid);
    }
}
$isbindshop=new IsBindShop();
if(isset($_POST['openid'])){
    $openid=$_POST['openid'];
    $shopid=$isbindshop->getShopidByOpenid($openid);
    if(!empty($shopid)){
        $code=200;
        $status="1";
        $msg="已绑定";
    }else{
        $code=110;
        $status="0";
        $msg="未绑定";
    }
    echo json_encode(array("code"=>$code,"msg"=>$msg,"data"=>array("status"=>$status)));
}
    
?>