<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
require_once ('/var/www/html/houtai/shop/startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
class NotifyUrl{
	public function getDaliAccount($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDaliAccount($shopid);
	}
	public function addToDaliAccount($shopid, $income){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->addToDaliAccount($shopid, $income);
	}
	public function buyAccountRecord($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->buyAccountRecord($inputarr);
	}
	public function addShopUseAccount($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->addShopUseAccount($inputarr);
	}
	public function getShopuseaccountEndtime($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getShopuseaccountEndtime($shopid);
	}
	public function getYearfeeMoney($shopid){
		return QuDian_InterfaceFactory::createInstancePayDAL()->getYearfeeMoney($shopid);
	}
}
$notifyurl=new NotifyUrl();
$shopid=$_SESSION['shopid'];
// $dalidata=$returnurl->getDaliAccount($shopid);
// $agentaccount=$dalidata['agentaccount'];
$agentaccount="";
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
$tradeno = $_GET['out_trade_no'];//商户订单号
$alipaytradeno = $_GET['trade_no'];//支付宝交易号
$tradedesc=$_GET['body'];
$buyer_email=$_GET['buyer_email'];
$buyer_id=$_GET['buyer_id'];
$paytime=strtotime($_GET['notify_time']);
$ordername=$_GET['subject'];
$paymoney=$_GET['total_fee'];

$yearfee=$notifyurl->getYearfeeMoney($shopid);
$onemonthfee=sprintf("%.0f",$yearfee/12);
$halfyearfee=sprintf("%.0f",$yearfee*0.95/2);
$yearfee=sprintf("%.0f",0.9*$yearfee);

switch (strval($paymoney)){
	case $onemonthfee:$buytype="permonth";break;
	case $halfyearfee:$buytype="perhalfyear";break;
	case $yearfee:$buytype="peryear";break;
}
$income=0;
if($verify_result) {//验证成功
	//请在这里加上商户的业务逻辑程序代码
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	if(!empty($agentaccount)){
		$income=sprintf("%.2f",0.8*$paymoney);
		$notifyurl->addToDaliAccount($shopid, $income);//添加到代理商账号
	}
	$trade_status = $_GET['trade_status'];//交易状态
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
    }else {
//       echo "trade_status=".$_GET['trade_status'];
    }
    $paystatus="ok";
    
// 	echo "验证成功<br />";
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
//     echo "验证失败";
    $paystatus="fail";
}
$endtime=$notifyurl->getShopuseaccountEndtime($shopid);
switch ($buytype){
	case "permonth":
		$endtime=$endtime+30*24*3600;
		break;
	case "perhalfyear":
		$endtime=$endtime+6*30*24*3600;
		break;
	case "peryear":
		$endtime=$endtime+365*24*3600;
		break;
}
$inputarr=array(
		"shopid"=>$shopid,
		"tradeno"=>$tradeno,
		"buyer_email"=>$buyer_email,
		"alipaytradeno"	=>$alipaytradeno,
		"paytime"=>$paytime,
		"buytype"=>$buytype,
		"paymoney"=>$paymoney,
		"dalimoney"=>$income,
		"paystatus"=>$paystatus,
		"endtime"=>$endtime,
);
//商家账户

$shopaccountarr=array(
		"shopid"=>$shopid,
		"buytime"=>$paytime,
		"endtime"	=>$endtime,
		"accounttype"=>"standard",
);
//购买记录
$buyid=$notifyurl->buyAccountRecord($inputarr);
$notifyurl->addShopUseAccount($shopaccountarr);
header("location: ../buaccountresult.php?paystatus=".$paystatus."&buyid=".$buyid);
?>