<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddWechatFtype{
    public function addOneFoodTypeData($inputarr){
        QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->addOneFoodTypeData($inputarr);
    }
    public function updateOneFoodtypeData($ftid, $inputarr){
        QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->updateOneFoodtypeData($ftid, $inputarr);
    }
    public function syncData($shopid){
        QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
    }
}
$addwechatftype=new AddWechatFtype();
if(isset($_POST['openid'])){
        $openid=$_POST['openid'];
        $shopid=$_POST['shopid'];
        $ftname=$_POST['ftname'];
        $ftcode=$_POST['ftcode'];
        $sortno=$_POST['sortno'];
        $printerid=$_POST['printerid'];
        $ftid=$_POST['ftid'];
        $openid=$_POST['openid'];
        $inputarr=array(
            "shopid"	=>$shopid,
            "foodtypename"=>$ftname,
            "foodtypecode"=>$ftcode,
            "sortno"=>$sortno,
            "printerid"=>$printerid,
        );
        if(!empty($ftid)){
            $addwechatftype->updateOneFoodtypeData($ftid, $inputarr);
        }else{
            $onetype=$addwechatftype->addOneFoodTypeData($inputarr);
        }
        $addwechatftype->syncData($shopid);
        header("location: ../wechatservice/foodtype.php?openid=$openid");
}
?>