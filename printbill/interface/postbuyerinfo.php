<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PostBuyerInfo{
    public function saveMyaddress($inputarr){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->saveMyaddress($inputarr);
    }
    public function getDIstance($inputarr){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDIstance($inputarr);
    }
    public function getDistributeFee($inputarr){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDistributeFee($inputarr);
    }
}
$postbuyerinfo=new PostBuyerInfo();
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $uid=$_POST['uid'];
    $prov=$_POST['prov'];
    $city=$_POST['city'];
    $dist=$_POST['dist'];
    $road=$_POST['road'];
    $contact=$_POST['contact'];
    $phone=$_POST['phone'];
    $inputarr=array(
        "shopid"=>$shopid,
        "uid"=>$uid,
        "prov"=>$prov,
        "city"=>$city,
        "dist"=>$dist,
        "road"=>$road,
        "contact"=>$contact,
        "phone"=>$phone,
    );
    $postbuyerinfo->saveMyaddress($inputarr);
    $distance=$postbuyerinfo->getDIstance($inputarr);
    $distributefee=$postbuyerinfo->getDistributeFee($inputarr);
    echo json_encode(array("dis"=>$distance,"distributefee"=>$distributefee));
}
?>