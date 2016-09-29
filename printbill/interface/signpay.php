<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
// require_once ('/var/www/html/queue/Factory/InterfaceFactory.php');
// //环信
// require_once ('/var/www/html/emchat-server/Easemob.class.php');
// require_once ('/var/www/html/emchat-server/global.php');
class SignPay{
    public function updateSignPayData($inputarr){
        return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateSignPayData($inputarr);
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
$signpay=new SignPay();
// $easemob=new Easemob($options);
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$shopid=$_POST['shopid'];//新增
    $billid=$_POST['billid'];
    $signername=$_POST['signername'];
    $signerunit=$_POST['signerunit'];
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$signpay->getCusTokenStatus($uid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($uid.$shopid.$billid.$signername.$signerunit.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$signpay->updateCusSession($uid,$session);break;
            }
            //收银员
            $cusinfoarr=$signpay->getCusinfo($uid,$shopid);
            $cashierman=$cusinfoarr['nickname'];
            $foodmoney=$signpay->getTotalmoneyAndFoodDiscountmoney($billid);
            $paymoney=$foodmoney['totalmoney'];
            $paymethod="signpay";
            $inputarr=array(
            		"billid"=>$billid,
            		"paymethod"=>$paymethod,
            		"signername"=>$signername,
            		"signerunit"=>$signerunit,
            		"signmoney"=>$paymoney,
            		"cashierman"=>$cashierman,
            );
            $signpay->updateSignPayData($inputarr);
            $billarr=$signpay->getOneBillInfoByBillid($billid);
            $consumeListArr=$signpay->tobeConsumeList($billarr,$paymethod,$paymoney);
            // print_r($consumeListArr);exit;//消费清单
            $consumearr=$signpay->printConsumeListData(json_encode($consumeListArr));
            if(!empty($consumearr)){$temparr[]=$consumearr;}
            // print_r($consumearr);exit;
            $urls=$signpay->getUrlsArr(json_encode($temparr));
            // print_r($urls);exit;
            $signpay->sendFreeMessage($urls);//打印
            $signpay->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
            header('Content-type: application/json');
            echo json_encode(array("token"=>$session));
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
exit;
$foodmoney=$signpay->getTotalmoneyAndFoodDiscountmoney("5535f9875bc109bb038b4567");
print_r($foodmoney);exit;
$paymethod="signpay";
$signername="张林梓";
$signerunit="杭州街坊科技有限公司";
$billid="5531ea1e5bc1096d228b4568";
//             $signpay->updateSignPayData($billid,$paymethod, $signername, $signerunit);
            $totalmoney=0;
            $billarr=$signpay->getOneBillInfoByBillid($billid);
            foreach ($billarr['food'] as $key=>$val){
            	if(empty($val['present'])){
            		$totalmoney+=$val['foodamount']*$val['foodprice'];
            	}
            }
            $paymoney=$totalmoney;
            $consumeListArr=$signpay->tobeConsumeList($billarr,$paymethod,$paymoney);
//             print_r($consumeListArr);exit;//消费清单
            $consumearr=$signpay->printConsumeListData(json_encode($consumeListArr));
            if(!empty($consumearr)){$temparr[]=$consumearr;}
//             print_r($consumearr);exit;
            $urls=$signpay->getUrlsArr(json_encode($temparr));
            // print_r($urls);exit;
            $signpay->sendFreeMessage($urls);//打印
?>