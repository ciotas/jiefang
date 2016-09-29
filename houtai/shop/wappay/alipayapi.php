<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
	 <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no,email=no"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
	<title>支付宝支付买单</title>
</head>
<?php
/* *
 * 功能：手机网站支付接口接入页
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
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");
class AlipayApi{
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
}
$alipayapi=new AlipayApi();

/**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = $base_url."wappay/notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = $base_url."wappay/payreturn.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //商户订单号
        $out_trade_no = $_POST['WIDout_trade_no'];
        //商户网站订单系统中唯一订单号，必填
                //订单名称
        $subject = $_POST['WIDsubject'];
        //必填
        //付款金额
        $total_fee = $_POST['WIDtotal_fee'];
        //必填
        $show_url="";
        //订单描述（这里传递的是billid）
        $body = $_POST['WIDbody'];
        //选填        $bodyarr=explode("|*|*|*|*|*|*|", $body);
        $billid=$bodyarr[0];
        //超时时间
        $it_b_pay = $_POST['WIDit_b_pay'];
        //选填
        //钱包token
        $extern_token = $_POST['WIDextern_token'];
        //选填
		
/************************************************************/
       
//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => "alipay.wap.create.direct.pay.by.user",
		"partner" => trim($alipay_config['partner']),
		"seller_id" => trim($alipay_config['seller_id']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"show_url"	=> $show_url,
		"body"	=> $body,
		"it_b_pay"	=> $it_b_pay,
		"extern_token"	=> $extern_token,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);
// print_r($parameter);exit;
//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "正在跳转，请稍后...");
$path="/var/www/html/houtai/shop/wappay/paydata/".$billid."/";
if(!file_exists($path)){
	$cmd1="mkdir -p ".$path;
	shell_exec($cmd1);
	$cmd2="chmod -R 777 ".$path;
	shell_exec($cmd2);
	file_put_contents($path."submit.html", $html_text);
}else{
	file_put_contents($path."submit.html", $html_text);
}

// echo $html_text;
?>
<iframe frameborder=0 width="100%" height="600"  style="margin:0;padding:0" MARGINWIDTH=0 MARGINHEIGHT=0 scrolling=no src="<?php echo $base_url;?>wappay/paydata/<?php echo $billid;?>/submit.html"></iframe>
</body>
</html>