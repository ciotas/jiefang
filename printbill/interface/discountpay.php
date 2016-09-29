<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
require_once ('/var/www/html/queue/Factory/InterfaceFactory.php');
//环信
require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class DisCountPay{
    public function updateDiscountPayData($billid,$discountzong, $discountval,$clearmoney,$paymoney,$paytype){
        return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateDiscountPayData($billid, $discountzong,$discountval, $clearmoney, $paymoney,$paytype);
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
    public function getDiscountZong($clearmoney,$discountval,$arr){
    	return PRINT_InterfaceFactory::createInstanceHandleDAL()->getDiscountZong($clearmoney, $discountval, $arr);
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
$discountpay=new DisCountPay();
$easemob=new Easemob($options);
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $billid=$_POST['billid'];
    $uid=$_POST['uid'];
    $discountval=$_POST['discountval'];
    $clearmoney=$_POST['clearmoney'];
    $paymoney=$_POST['paymoney'];
	
    $paytype=$_POST['paytype'];
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$discountpay->getTokenStatus($shopid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($shopid.$billid.$uid.$discountval.$clearmoney.$paymoney.$paytype.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$discountpay->updateShopSession($shopid,$session);break;
            }
            if($paymoney=="0.01"){
            	$paymoney="0";
            }
            
            //打印出消费清单
            $temparr=array();
            $billarr=$discountpay->getOneBillById($billid);
            $discountzong=$discountpay->getDiscountZong($clearmoney, $discountval, $billarr);
            $inputarr=array(
            		"billid"=>$billid,
            );
//             $discountpay->updateDiscountPayData($billid,$discountzong, $discountval, $clearmoney, $paymoney,$paytype);
            $consumeListArr=$discountpay->tobeConsumeList($billarr);
            // print_r($consumeListArr);exit;//消费清单
            $consumearr=$discountpay->printConsumeListData(json_encode($consumeListArr), "2");
            if(!empty($consumearr)){$temparr[]=$consumearr;}
            
            $urls=$discountpay->getUrlsArr(json_encode($temparr));
            // print_r($urls);exit;
            $nullarr=$discountpay->sendFreeMessage($urls);//打印
            $discountpay->updateTabStatus($shopid, $billarr['tabname'], "0");//买单之后自动清台
            header('Content-type: application/json');
            echo json_encode(array("paytype"=>"finish_offlinepay", "token"=>$session));
            if(empty($temparr)){
//             	header('Content-type: application/json');
//             	echo   json_encode(array("paytype"=>"billdone" ,"token"=>$session));
//             	$sendshopMsg="温馨提示：您未设置消费清单打印机，无法出单，请设置好后重新输入。";
//             	$easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
//             	$discountpay->changeBillStatus($billid, "billdone", $paytype);
            }else{
            	$sendshopMsg=date("Y-m-d H:i:s",time())."买单成功！";
            	//客人提醒
            	$sendcusMsg="您已成功买单，欢迎下次光临！";
            	$easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
            	$easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
            }
            
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
?>