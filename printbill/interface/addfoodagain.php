<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class AddFoodAgain{
    public function updateBillFoodByBillid($billid,$totalmoney,$paymoney,$disacountfoodmoney,$oldfoodarr,$foodarr){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->updateBillFoodByBillid($billid, $totalmoney,$paymoney, $disacountfoodmoney,$oldfoodarr, $foodarr);
    }
    public function getBillData($billid){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->getBillData($billid);
    }
    public function tobeRunner($inputdarr){
        return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
    }
    public function printChuanCaiData($type,$json){
        return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($type,$json);
    }
    public function tobeCusList($inputdarr){
        return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputdarr);
    }
    public function printCuslistData($type,$json){
        return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($type, $json);
    }
    public function orderByprinterid($inputdarr){
        return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
    }
    public function tobePieceList($arr){
        return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
    }
    public function PrintKitchenData($json, $type){
        return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json, $type);
    }
    public function getUrlsArr($json){
        return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
    }
    public function sendFreeMessage($msg) {
        return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
    }
    public function getTokenStatus($shopid){
        return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
    }
    public function updateShopSession($shopid,$session){
        return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
    }
}
$addfoodagain=new AddFoodAgain();
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $billid=$_POST['billid'];
    $food=$_POST['food'];
    /*******************
    food格式
    $food='{"food":[{"foodid":"53a8f20316c109cb5a8b4569","foodname":"水煮肉",
		"foodamount":"1","zonename":"中厨","foodguqing":"0", "fooddisaccount":"1",
		"cooktype":"红烧" ,"foodprice":"22.50","foodunit":"例", "foodrequest":"微辣",
		 "printerid":"5438e04516c1090a058b45cc"},
{"foodid":"53a8f23d16c10919218b4568","foodname":"烤鸭",
		"foodamount":"1","zonename":"点心房", "foodprice":"27.00","foodunit":"例","foodrequest":"少盐",
		"cooktype":"清蒸","foodguqing":"0", "fooddisaccount":"1",
		"printerid":"5438e04516c1090a058b45cc"}]}';
    **************************/
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$addfoodagain->getTokenStatus($shopid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($shopid.$billid.$food.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$addfoodagain->updateShopSession($shopid,$session);break;
            }
            $billarr=$addfoodagain->getBillData($billid);
            $foodarr=json_decode($food,true);
            $inputdarr=array(
                "shopid"=>$shopid,
                "nickname"=>$billarr['nickname'],
                "shopname"=>$billarr['shopname'],
                "branchname"=>"",
                "wait"=>$billarr['wait'],
                "type"=>$billarr['type'],
                "paytype"=>$billarr['paytype'],
                "paystatus"=>$billarr['paystatus'],
                "tabname"=>$billarr['tabname'],
                "cusnum"=>$billarr['cusnum'],
                "discountdesc"=>"",
                "totalmoney"=>"",
                "disacountmoney"=>"",
                "paymoney"=>"",
                "service"=>"",
                "time"=>time(),
                "timestamp"=>$timestamp,
                "tradeno"=>$billarr['tradeno'],
                "food"=>$foodarr,
            );
            $temparr=array();
            //更新bill
            $addfoodagain->updateBillFoodByBillid($billid,$billarr['totalmoney'], $billarr['paymoney'], $billarr['disacountfoodmoney'],$billarr['allfood'], $foodarr);
            ////传菜单
            $foodRunnerArr=$addfoodagain->tobeRunner($inputdarr);
            // print_r($foodRunnerArr);exit;
            $chuancaiarr=$addfoodagain->printChuanCaiData("3", json_encode($foodRunnerArr));
            if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
            // print_r($temparr);exit;
            ////划菜单
            $cusListArr=$addfoodagain->tobeCusList($inputdarr);
            // print_r($cusListArr);exit;
            $cuslistarr=$addfoodagain->printCuslistData("1", json_encode($cusListArr));
            if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
            //厨房单
            $orderfoodarr=$addfoodagain->orderByprinterid($inputdarr);
            // print_r($orderfoodarr);exit;
            $piecelistArr=$addfoodagain->tobePieceList($orderfoodarr);
            // print_r($piecelistArr);exit;
            $kitchenarr=$addfoodagain->PrintKitchenData(json_encode($piecelistArr), "kitchen");
            if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
            //打印
            $urls=$addfoodagain->getUrlsArr(json_encode($temparr));
            // print_r($urls);exit;
            $nullarr=$addfoodagain->sendFreeMessage($urls);
            header('Content-type: application/json');
            echo json_encode(array("token"=>$session));
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
exit;
$food='{"food":[{"foodid":"53a8f20316c109cb5a8b4569","foodname":"水煮肉",
		"foodamount":"1","zonename":"中厨","foodguqing":"0", "fooddisaccount":"1",
		"cooktype":"红烧" ,"foodprice":"22.50","foodunit":"例", "foodrequest":"微辣",
		 "printerid":"54a2714f16c1090a058b4635"},
{"foodid":"53a8f23d16c10919218b4568","foodname":"烤鸭",
		"foodamount":"1","zonename":"点心房", "foodprice":"27.00","foodunit":"例","foodrequest":"少盐",
		"cooktype":"清蒸","foodguqing":"0", "fooddisaccount":"1",
		"printerid":"54a2714f16c1090a058b4635"}]}';
$billid="54abafe116c1090b058b4673";
$shopid="547430f016c10932708b4624";
$billarr=$addfoodagain->getBillData($billid);
// print_r($billarr);exit;
$foodarr=json_decode($food,true);
$inputdarr=array(
    "shopid"=>$shopid,
    "nickname"=>$billarr['nickname'],
    "shopname"=>$billarr['shopname'],
    "branchname"=>"",
    "wait"=>$billarr['wait'],
    "type"=>$billarr['type'],
    "paytype"=>$billarr['paytype'],
    "paystatus"=>$billarr['paystatus'],
    "tabname"=>$billarr['tabname'],
    "cusnum"=>$billarr['cusnum'],
    "discountdesc"=>"",
    "totalmoney"=>"",
    "disacountmoney"=>"",
    "paymoney"=>"",
    "service"=>"",
    "time"=>time(),
    "timestamp"=>time(),
    "tradeno"=>$billarr['tradeno'],
    "food"=>$foodarr,
);
$temparr=array();
// print_r($inputdarr);exit;
// $addfoodagain->updateBillFoodByBillid($billid,$billarr['totalmoney'], $billarr['paymoney'], $billarr['disacountfoodmoney'],$billarr['allfood'], $foodarr);
$foodRunnerArr=$addfoodagain->tobeRunner($inputdarr);
// print_r($foodRunnerArr);exit;
$chuancaiarr=$addfoodagain->printChuanCaiData("3", json_encode($foodRunnerArr));
if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
// print_r($temparr);exit;
////划菜单
$cusListArr=$addfoodagain->tobeCusList($inputdarr);
// print_r($cusListArr);exit;
$cuslistarr=$addfoodagain->printCuslistData("1", json_encode($cusListArr));
if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
// print_r($temparr);exit;
//厨房单
$orderfoodarr=$addfoodagain->orderByprinterid($inputdarr);
// print_r($orderfoodarr);exit;
$piecelistArr=$addfoodagain->tobePieceList($orderfoodarr);
// print_r($piecelistArr);exit;
$kitchenarr=$addfoodagain->PrintKitchenData(json_encode($piecelistArr), "kitchen");
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
// print_r($temparr);exit;
$urls=$addfoodagain->getUrlsArr(json_encode($temparr));
// print_r($urls);exit;
$nullarr=$addfoodagain->sendFreeMessage($urls);
?>