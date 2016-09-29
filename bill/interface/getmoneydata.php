<?php 
require_once ('/var/www/html/bill/global.php');
require_once (DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetMoneyData{
    public function getBillInfoById($billid){
        return InterfaceFactory::createInstanceBillDataDAL()->getBillInfoById($billid);
    }
    public function getTokenStatus($shopid){
        return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
    }
    public function updateShopSession($shopid,$session){
        return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
    }
}
$getmoneydata=new GetMoneyData();
if(isset($_POST['billid'])){
    $billid=$_POST['billid'];
    $shopid=$_POST['shopid'];
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$getmoneydata->getTokenStatus($shopid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($shopid.$billid.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$getmoneydata->updateShopSession($shopid,$session);break;
            }
            $result=$getmoneydata->getBillInfoById($billid);
            header('Content-type: application/json');
            echo json_encode(array("token"=>$session,"totalmoney"=>$result['totalmoney'],"disacountfoodmoney"=>$result['disacountfoodmoney'],"paymoney"=>$result['paymoney'],"discountdesc"=>$result['discountdesc']));
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
exit;
$result=$getmoneydata->getBillInfoById("5498e5ad16c109d40f8b45e3");
print_r($result);
echo json_encode(array("token"=>"","totalmoney"=>$result['totalmoney'],"disacountfoodmoney"=>$result['disacountfoodmoney'],"paymoney"=>$result['paymoney'],"discountdesc"=>$result['discountdesc']));
?>