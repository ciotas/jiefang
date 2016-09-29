<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
require_once ('/var/www/html/queue/Factory/InterfaceFactory.php');
//环信
require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class AliPay{
    public function updateAliPayData($billid, $othermoney, $paymoney){
        return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateAliPayData($billid, $othermoney, $paymoney);
    }
    public function getOneBillById($billid){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->getOneBillById($billid);
    }
    public function tobeConsumeList($inputdarr){
        return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr);
    }
    public function printConsumeListData($json,$type){
        return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json, $type);
    }
    public function getUrlsArr($json){
        return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
    }
    public function sendFreeMessage($msg) {
        return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
    }
    public function updateTabStatus($shopid,$tabname, $usestatus){
        PRINT_InterfaceFactory::createInstanceHandleDAL()->updateTabStatus($shopid,$tabname, $usestatus);
    }
    public function judgeTheServer($shopid,$uid){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->judgeTheServer($shopid, $uid);
    }
    public function getPoints($shopid,$uid){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->getPoints($shopid, $uid);
    }
    public function getShopPointSet($shopid){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->getShopPointSet($shopid);
    }
    public function addPointsToUser($uid,$shopid,$paymoney){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->addPointsToUser($uid,$shopid, $paymoney);
    }
    public function changeBillStatus($billid,$paystatus,$paytype){
        return PRINT_InterfaceFactory::createInstanceHandleDAL()->changeBillStatus($billid, $paystatus, $paytype);
    }
    public function getTokenStatus($shopid){
        return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
    }
    public function updateShopSession($shopid,$session){
        return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
    }
}
$alipay=new AliPay();
$easemob=new Easemob($options);
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $billid=$_POST['billid'];
    $uid=$_POST['uid'];
    $othermoney=$_POST['othermoney'];
    $paymoney=$_POST['paymoney'];
    $paymoney=$_POST['paymoney'];
   
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$alipay->getTokenStatus($shopid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($shopid.$billid.$uid.$othermoney.$paymoney.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$alipay->updateShopSession($shopid,$session);break;
            }
            if($paymoney=="0.01"){
            	$paymoney="0";
            }
            $alipay->updateAliPayData($billid, $othermoney, $paymoney);
            //打印出消费清单
            $temparr=array();
            $billarr=$alipay->getOneBillById($billid);
            $consumeListArr=$alipay->tobeConsumeList($billarr);
            // print_r($consumeListArr);exit;//消费清单
            $consumearr=$alipay->printConsumeListData(json_encode($consumeListArr), "2");
            if(!empty($consumearr)){$temparr[]=$consumearr;}
            if(empty($temparr)){
                header('Content-type: application/json');
                echo   json_encode(array("paytype"=>"billdone" ,"token"=>$session));
                $sendshopMsg="温馨提示：您未设置消费清单打印机，无法出单，请设置好后重新输入。";
                $easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
                $alipay->changeBillStatus($billid, "billdone", "alipay");
                return ;
            }
            $urls=$alipay->getUrlsArr(json_encode($temparr));
            // print_r($urls);exit;
            $nullarr=$alipay->sendFreeMessage($urls);//打印
            $alipay->updateTabStatus($shopid, $billarr['tabname'], "0");//买单之后自动清台
            header('Content-type: application/json');
            echo json_encode(array("paytype"=>"alipay", "token"=>$session));
            //商家提醒
            $sendshopMsg=date("Y-m-d H:i:s",time())."买单成功！";
            //客人提醒
            $sendcusMsg="您已成功买单，欢迎下次光临！";
            $easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
            $easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
            
            $serverarr=$alipay->judgeTheServer($shopid, $uid);
            if(empty($serverarr)){//不是本店服务员
                $mypoints=$alipay->getPoints($shopid, $uid);
                $shoppointsetarr=$alipay->getShopPointSet($shopid);
                $thing="";
                $pointtype="";
                $thingnum="0";
                $minuspoints=0;
                if(!empty($shoppointsetarr)){
                    if($shoppointsetarr['type']=="money"){
                        $minuspoints=$mypoints;
                    }else{
                        if($mypoints>=intval($shoppointsetarr['points'])){
                            $thing=$shoppointsetarr['thing'];
                            $pointtype=$shoppointsetarr['type'];
                            $thingnum=$shoppointsetarr['num'];
                            $minuspoints=$shoppointsetarr['points'];
                        }
                    }
                    $mypoints=$alipay->addPointsToUser($uid,$shopid, $paymoney);//线下付款赠送积分
                }
            }
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
?>