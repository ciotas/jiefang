<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
class NotifyUrl{
	public function getPreBillByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getPreBillByBillid($billid);
	}
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
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function delPrebillByBillid($billid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delPrebillByBillid($billid);
	}
	public function addPayRecord($inputarr){
		QuDian_InterfaceFactory::createInstancePayDAL()->addPayRecord($inputarr);
	}
}
$notifyurl=new NotifyUrl();
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];
	$gmt_payment=$_POST['gmt_payment'];//交易时间
	$notify_time=$_POST['notify_time'];//通知时间
	$buyer_email=$_POST['buyer_email'];//买家支付宝
	$body=$_POST['body'];
	
	$bodyarr=explode("|*|*|*|*|*|*|", $body);
	$billid=$bodyarr[0];
	$nickname=$bodyarr[1];
	$cuspay=$_POST['total_fee'];
	
    if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    	$cuspay=$_POST['total_fee'];
    	$prearr=$notifyurl->getPreBillByBillid($billid);
    	$clearmoney="0";
    	$ticketval="0";
    	$ticketnum="0";
    	$ticketway="";
    	$discountval="100";
    	$discountmode="part";
    	$returndepositmoney="0";
    	if(!empty($prearr)){
    		$clearmoney=$prearr['clearmoney'];
    		if($prearr['allcount']=="1"){
    			$discountmode="all";
    		}else{
    			$discountmode="part";
    		}
    		$ticketval=$prearr['ticketval'];
    		$ticketnum=$prearr['ticketnum'];
    		$ticketway=$prearr['ticketway'];
    		$returndepositmoney=$prearr['returndepositmoney'];
    		$discountval=$prearr['discountval'];
    	}
    	$paymethod="alipay";
    	$paymoney=$cuspay;
    	$inputarr=array(
    			"tradeno"=>$trade_no,
    			"orderno"=>$out_trade_no,
    			"billid"=>$billid,
    			"cuspay"=>$cuspay,
    			"clearmoney"=>$clearmoney,
    			"othermoney"=>"0",
    			"discountval"=>$discountval,
    			"cashmoney"=>"0",
    			"unionmoney"=>"0",
    			"vipmoney"=>"0",
    			"discountmode"=>$discountmode,
    			"ticketval"=>$ticketval,
    			"ticketnum"	=>$ticketnum,
    			"ticketway"=>$ticketway,
    			"meituanpay"=>"0",
    			"dazhongpay"=>"0",
    			"nuomipay"=>"0",
    			"alipay"=>$cuspay,
    			"wechatpay"=>"0",
    			"paytype"=>"alipay",
    			"returndepositmoney"=>$returndepositmoney,
    			"paymethod"=>$paymethod,
    			"cashierman"=>$nickname,
    	);
    	// 	print_r($inputarr);exit;
//     	file_put_contents("/var/www/html/houtai/shop/stock/a.txt", json_encode($inputarr));
    	$notifyurl->updateCommonPayData($inputarr);
    	$billarr=$notifyurl->getOneBillInfoByBillid($billid);//新数据
    	
    	$consumeListArr=$notifyurl->tobeConsumeList($billarr,$paymethod,$paymoney);
    	
    	// 	print_r($consumeListArr);exit;//消费清单
    	$consumearr=$notifyurl->printConsumeListData(json_encode($consumeListArr));
//     	file_put_contents("/var/www/html/houtai/shop/wappay/1.txt", json_encode($consumearr));
    	if(!empty($consumearr)){$temparr[]=$consumearr;}
    	$urls=$notifyurl->getUrlsArr(json_encode($temparr));
    	// 	print_r($urls);exit;
    	
    	$notifyurl->sendFreeMessage($urls);//打印
    	if(!empty($billarr['tabid'])){
    		$notifyurl->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
    	}
    	$notifyurl->delPrebillByBillid($billid);
    	
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	echo "success";		//请不要修改或删除
	//支付记录
	$payrecord=array(
			"out_trade_no"=>$out_trade_no,
			"trade_no"=>$trade_no,
			"billid"	=>$billid,
			"shopid"=>$billarr['shopid'],
			"uid"=>$billarr['uid'],
			"buyer"=>$nickname,
			"tabid"=>$billarr['tabid'],
			"paymoney"=>$paymoney,
			"paytype"=>"alipay",
			"downtime"=>$billarr['timestamp'],
			"buyemail"=>$buyer_email,
			"buytime"=>strtotime($notify_time),
			
	);
	$notifyurl->addPayRecord($payrecord);
	
}
else {
    //验证失败
    echo "fail";
    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}


?>