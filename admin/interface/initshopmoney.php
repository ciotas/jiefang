<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class InitShopMoney{
    public function initShopAccontMoney($inputarr){
        Admin_InterfaceFactory::createInstanceAdminOneDAL()->initShopAccontMoney($inputarr);
    }
}
$initshopmoney=new InitShopMoney();
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $money=$_POST['money'];
    $inputarr=array(
        "shopid"=>$shopid,
        "money"=>$money,
    );
    $initshopmoney->initShopAccontMoney($inputarr);
    header("location: ../shoperaccount.php");
}
?>