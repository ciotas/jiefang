<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');

class FreePay{
    public function updateFreePayData($inputarr){
        return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateFreePayData($inputarr);
    }
    public function getOneBillInfoByBillid($billid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
    }
    public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
    	return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
    }
    public function printConsumeListData($json){
    	return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
    }
    public function getUrlsArr($json){
    	return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
    }
    public function sendFreeMessage($msg) {
    	return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
    }
    public function updateOneTabStatus($tabid,$tabstatus){
    	PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
    }
    public function getTotalmoneyAndFoodDiscountmoney($billid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTotalmoneyAndFoodDiscountmoney($billid);
    }
    public function getCusinfo($uid,$shopid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getCusInfo($uid,$shopid);
    }
    public function getCusTokenStatus($uid){
    	return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
    }
    public function updateCusSession($uid,$session){
    	return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
    }
}
$freepay=new FreePay();
if(isset($_POST['uid'])){
    $uid=$_POST['uid'];
    $shopid=$_POST['shopid'];//新增
    $billid=$_POST['billid'];
    $freename=$_POST['freename'];
    $freereason=$_POST['freereason'];
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$freepay->getCusTokenStatus($uid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($uid.$shopid.$billid.$freename.$freereason.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$freepay->updateCusSession($uid,$session);break;
            }
            //收银员
            $cusinfoarr=$freepay->getCusinfo($uid,$shopid);
            $cashierman=$cusinfoarr['nickname'];
            $foodmoney=$freepay->getTotalmoneyAndFoodDiscountmoney($billid);
            $paymoney=$foodmoney['totalmoney'];
            $paymethod="freepay";
            $inputarr=array(
            		"billid"=>$billid,
            		"paymethod"=>$paymethod,
            		"freename"=>$freename,
            		"freereason"=>$freereason,
            		"freemoney"=>$paymoney,	
            		"cashierman"=>$cashierman,
            );
            $freepay->updateFreePayData($inputarr); 
            $billarr=$freepay->getOneBillInfoByBillid($billid);
            
            $consumeListArr=$freepay->tobeConsumeList($billarr,$paymethod,$paymoney);
            // print_r($consumeListArr);exit;//消费清单
            $consumearr=$freepay->printConsumeListData(json_encode($consumeListArr));
            if(!empty($consumearr)){$temparr[]=$consumearr;}
            // print_r($consumearr);exit;
            $urls=$freepay->getUrlsArr(json_encode($temparr));
            // print_r($urls);exit;
            $freepay->sendFreeMessage($urls);//打印
            $freepay->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
            header('Content-type: application/json');
            echo json_encode(array("token"=>$session));
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
exit;
$inputarr=array(
		"billid"=>"5535f9875bc109bb038b4567",
		"paymethod"=>"freepay",
		"freename"=>"lindy",
		"freereason"=>"",
		"freemoney"=>"199",
);
$freepay->updateFreePayData($inputarr);
?>