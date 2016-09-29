<?php 
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
require_once (_ROOT.'des.php');
class Test{
    public function payHandle($openid = '', $orderno = '', $orderfee = '', $attach = ''){
        return Wechat_BLLFactory::createInstanceWxpayBLL()->jsApiCall($openid, $orderno, $orderfee, $attach);
    }
}
$test=new Test();
$openid="o3CVYtxXGv58el3ADrZXTsJ_7Leo";
$attach = json_encode(array('openid'=>$openid,  'billid'=>"57525d307cc109117c8b4569", 'orderfee'=>"0.01"));
$result = $test->payHandle($openid, "111111", 0.01, $attach);
print_r($result);
// $jsApiParametersarr=json_decode($result['jsApiParameters'],true);
?>