<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');

class WxPayClass{
    public function payHandle($openid = '', $orderno = '', $orderfee = '', $attach = ''){
        $this->write_logs('==========START===============');
        $this->write_logs($code);
        return Wechat_BLLFactory::createInstanceWxpayBLL()->jsApiCall($openid, $orderno, $orderfee, $attach); 
        $this->write_logs('=======END==================');
    }
    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}
$inst = new WxPayClass();
$inst->write_logs('xxxxxxbeginxxxxxxxxx');
if (isset($_POST['openid'])){
    $inst = new WxPayClass();
    $inst->write_logs('begin!');
    //$code="041c9d3464e2f503d10e1451a277789S";
    //$code = $_GET['code'];
    //**********************这里接收传递过来的参数
    $openid =$_POST['openid'];// "oCTvts4JllU3lZH6JTDIgx-LWXik";//"o1HJqt4r0hiILjIJ2dg5ifgSa0jY";//$_POST['openid'];
    $orderno = $_POST['orderno'];
    $tabid = $_POST['tabid'];
    $billid = $_POST['billid'];
    $uid = $_POST['uid'];
    $shopid = $_POST['shopid'];
    $orderfee =$_POST['paymoney'];
    $orderrequest = $_POST['orderrequest'];
    $attach = json_encode(array('openid'=>$openid,  'billid'=>$billid, 'orderfee'=>$orderfee));
    $inst->write_logs('t='.$attach);
    //**********************这里接参数结束
    $result = $inst->payHandle($openid, $orderno, $orderfee, $attach);
    //var_dump($result);
    $orderno = $result['orderno'];
    $jsApiParameters = $result['jsApiParameters'];
    $res = $result['res'];
    //$inst->write_logs('jsapiparams='.jsone_encode($jsApiParameters));
    $inst->write_logs('end!');
}

//exit;
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title>微信安全支付</title>

<script type="text/javascript">

function jsApiCall()
{
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
		<?php echo $jsApiParameters; ?>,
		function(res){
		    WeixinJSBridge.log(res.err_msg);
		   // alert(res.err_code+"="+res.err_desc+"="+res.err_msg);
		    if(res.err_msg=="get_brand_wcpay_request:cancel"){
		        window.location.href='./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>'
		    }else if(res.err_msg=="get_brand_wcpay_request:ok"){
		    	window.location.href='./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>&paystatus=paid'
		    }
		}
	);
}

function callpay()
{
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    }else{
        jsApiCall();
    }
}

callpay();

</script>
</head>
<body>
</body>
</html>
