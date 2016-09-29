<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=2">
	<title>支付宝即时到账交易接口接口</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'startsession.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');

require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");
class Purchase{

}
$shopid=$_SESSION['shopid'];
$purchase=new Purchase();


/**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = "return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
        $out_trade_no = md5(time().rand(1000, 9999));
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $_POST['goodsname'];
        //必填
        $goodsid=$_POST['goodsid'];
        $goodsprice=$_POST['goodsprice'];
        $goodsnum=$_POST['goodsnum'];
        //付款金额
        $total_fee = $goodsprice*$goodsnum;
        //必填
        //订单描述
        $body = array("goodsid"=>$goodsid);
        //商品展示地址
        $show_url ="";
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
        		"service" => "create_direct_pay_by_user",
        		"partner" => trim($alipay_config['partner']),
        		"seller_email" => trim($alipay_config['seller_email']),
        		"payment_type"	=> $payment_type,
        		"notify_url"	=> $notify_url,
        		"return_url"	=> $return_url,
        		"out_trade_no"	=> $out_trade_no,
        		"subject"	=> $subject,
        		"total_fee"	=> $total_fee,
        		"body"	=> $body,
        		"show_url"	=> $show_url,
        		"anti_phishing_key"	=> $anti_phishing_key,
        		"exter_invoke_ip"	=> $exter_invoke_ip,
        		"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
        
);

// print_r($parameter);exit;
//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "正在请求...");
echo $html_text;

?>
</body>
</html>