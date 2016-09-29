<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');

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
//if (isset($_POST['openid'])){
    $inst = new WxPayClass();
    $inst->write_logs('begin!');
    //$code="041c9d3464e2f503d10e1451a277789S";
    //$code = $_GET['code'];
    //**********************这里接收传递过来的参数
    $openid = "o1HJqt4r0hiILjIJ2dg5ifgSa0jY";//$_POST['openid'];
    $orderno = "1234567890";//$_POST['orderno'];
    $tabid = 12;//$_POST['tabid'];
    $billid = 1234;//$_POST['billid'];
    $uid = 858869606;//$_POST['uid'];
    $shopid = 123321;//$_POST['shopid'];
    $orderfee = 0.01;//$_POST['paymoney'];
    $orderrequest = "asdffsdaff";//$_POST['orderrequest'];
    $attach = json_encode(array('openid'=>$openid,  'billid'=>$billid, 'orderfee'=>$orderfee));
    $inst->write_logs('t='.$attach);
    //**********************这里接参数结束
    $result = $inst->payHandle($openid, $orderno, $orderfee, $attach);
    var_dump($result);
    $orderno = $result['orderno'];
    $jsApiParameters = $result['jsApiParameters'];
    $res = $result['res'];
    //$inst->write_logs('jsapiparams='.jsone_encode($jsApiParameters));
    $inst->write_logs('end!');
//} else {
  //  $inst = new WxPayClass();
    //$inst->write_logs('no code!');
//}

// $code="041c9d3464e2f503d10e1451a277789S";
// $inst = new WxPayClass();
// $inst->write_logs('begin!');
// $code = $_GET['code'];
//$inst->payHandle($code);
// $inst->write_logs('end!');
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
    alert(res.err_code+"="+res.err_desc+"="+res.err_msg);
    //var order_no= <?php echo $orderno?>;
    //location.href="&order_sn="+order_no;
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
</script>
</head>
<body>
<div class="flowpay" style="vertical-align: middle;text-align: center;margin-top:30%;font-size: 300%;">
<div style="font-size: 200%;margin-left: 10%"></div>

<div style="margin-top:5%;">
订单号：<span><?php echo $orderno?></span>
</div>
<div style="margin-top:5%">
本次订单需支付：<strong class="price"><?php echo $res['order_amount']?></strong>&nbsp;元
</div>
<div style="margin-top:10%">
<button type="button" class="btn btn-danger" style="width: 40%;height: 20%;font-size: 200%;" onclick="callpay();"   >
支付
</button>
</div>

</div>
</body>
</html>
