<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
// require_once ('/var/www/html/queue/Factory/InterfaceFactory.php');
class CommonPay{
    public function updateCommonPayData($inputarr){
        return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateCommonPayData($inputarr);
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
$commonpay=new CommonPay();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$shopid=$_POST['shopid'];//新增
    $billid=$_POST['billid'];
    $cuspay=$_POST['cuspay'];
    $clearmoney=$_POST['clearmoney'];
    $discountval=$_POST['discountval'];
    $othermoney=$_POST['othermoney'];
    $cashmoney=$_POST['cashmoney'];
    $unionmoney=$_POST['unionmoney'];
    $vipmoney=$_POST['vipmoney'];
    $ticketval=$_POST['ticketval'];
    $ticketnum=$_POST['ticketnum'];
    $ticketway=$_POST['ticketway'];
    $meituanpay=$_POST['meituanpay'];
    $alipay=$_POST['alipay'];
    $wechatpay=$_POST['wechatpay'];
    $returndepositmoney=$_POST['returndepositmoney'];
    $timestamp=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $sessionresult=$commonpay->getCusTokenStatus($uid);
    if(is_array($sessionresult)&&!empty($sessionresult)){
        $token=$sessionresult['token'];
        $serversign=strtoupper(md5($uid.$shopid.$billid.$clearmoney.$othermoney.$cashmoney.$unionmoney.$vipmoney.$discountval.$ticketval.$ticketnum.$ticketway.$timestamp.$token));
        if($serversign==$signature){//验证通过
            switch ($sessionresult['status']){
                case "valid":$session="";break;
                case "invalid":$session=session_id();$commonpay->updateCusSession($uid,$session);break;
            }
            //打印出消费清单
            //收银员
            $cusinfoarr=$commonpay->getCusinfo($uid,$shopid);
            $cashierman=$cusinfoarr['nickname'];
            
            $temparr=array();
            $paymethod="commonpay";
            $totalmoney=0;
            $billarr=$commonpay->getOneBillInfoByBillid($billid);
            foreach ($billarr['food'] as $key=>$val){
		    	 if(empty($val['present'])){
		         	$totalmoney+=$val['foodamount']*$val['foodprice'];
			     }
			}
			$paymoney=$totalmoney-$clearmoney;
			$paymethod="commonpay";
			$inputarr=array(
					"billid"=>$billid,
					"cuspay"=>$cuspay,
					"clearmoney"=>$clearmoney,
					"othermoney"=>$othermoney,
					"discountval"=>$discountval,
					"cashmoney"=>$cashmoney,
					"unionmoney"=>$unionmoney,
					"vipmoney"=>$vipmoney,
					"ticketval"=>$ticketval,
					"ticketnum"	=>$ticketnum,
					"ticketway"=>$ticketway,
					"meituanpay"=>$meituanpay,
					"alipay"=>$alipay,
					"wechatpay"=>$wechatpay,
					"returndepositmoney"=>$returndepositmoney,
					"paymethod"=>$paymethod,
					"cashierman"=>$cashierman,
			);
			$commonpay->updateCommonPayData($inputarr);
			$billarr=$commonpay->getOneBillInfoByBillid($billid);//新数据
			$consumeListArr=$commonpay->tobeConsumeList($billarr,$paymethod,$paymoney);
			// print_r($consumeListArr);exit;//消费清单
			$consumearr=$commonpay->printConsumeListData(json_encode($consumeListArr));
			if(!empty($consumearr)){$temparr[]=$consumearr;}
			// print_r($consumearr);exit;
			$urls=$commonpay->getUrlsArr(json_encode($temparr));
			// print_r($urls);exit;
			$commonpay->sendFreeMessage($urls);//打印
            $commonpay->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
            header('Content-type: application/json');
            echo json_encode(array("token"=>$session));
        }else{
            header('Content-type: application/json');
            echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
        }
    }
}
exit;
$billid="5531c8185bc109221b8b4567";
$totalmoney=0;
$clearmoney=1;
$cashmoney="170";
$unionmoney="8";
$vipmoney="0";
$inputarr=array(
		"billid"=>$billid,
		"clearmoney"=>$clearmoney,
		"othermoney"=>"0",
		"discountval"=>"90",
		"cashmoney"=>$cashmoney,
		"unionmoney"=>$unionmoney,
		"vipmoney"=>$vipmoney,
		"ticketval"=>"0",
		"ticketnum"	=>"0",
		"ticketway"=>"",
		"paymethod"=>"commonpay",
);
$commonpay->updateCommonPayData($inputarr);
?>