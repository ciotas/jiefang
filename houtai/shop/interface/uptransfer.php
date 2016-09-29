<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpTransfer{
    public function addTransferLog($inputarr){
        QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->addTransferLog($inputarr);
    }
    public function getShopidByOpenid($openid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
    }
}
$uptransfer=new UpTransfer();
if(isset($_POST['user_openid'])){
    $user_openid=$_POST['user_openid'];//申请提现的用户openid
    $realcash=$_POST['realcash']; //提现金额
    $origincash=$_POST['origincash'];
    $amount=$_POST['amount'];
    $getcash_time=$_POST['getcash_time']; //提现时间
    $shopid=$uptransfer->getShopidByOpenid($user_openid);
//     $mchid=$_POST['mchid']; //商家shopid
    $is_ok=$_POST['is_ok']; //提现成功与否
    $inputarr=array(
        "user_openid"=>$user_openid,
        "amount"=>$amount/100,
        "origincash"=>$origincash/100,
        "realcash"=>$realcash/100,
        "getcash_time"=>$getcash_time,
        "shopid"=>$shopid,
        "is_ok"=>$is_ok,
    );
    $uptransfer->addTransferLog($inputarr);
}
?>