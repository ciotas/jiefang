<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');

class AuthorizeClass{
    public function authorizeCode(){
       return Wechat_BLLFactory::createInstanceWechatBLL()->authorizeCode();
    }
    private function getGetRequest($url = ''){
    	return Wechat_BLLFactory::createInstanceWechatBLL()->getGetRequest();
    }
    public function write_logs($content=''){
    	return Wechat_BLLFactory::createInstanceWechatBLL()->write_logs();
    }
}
//
#echo "hello world";
//
#$appid = "wxc5b83fb82bad0b65";
$inst = new AuthorizeClass();
$inst->authorizeCode();
// $redirecturl=$inst->authorizeCode();//index();
// $redirecturl=urlencode($redirecturl);
//$redirecturl=urlencode("http://test.meijiemall.com/wechat/interface/index.html");
// echo $redirecturl;exit;
//$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirecturl."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";

// echo $redirecturl;exit;
$url = "http://test.meijiemall.com/wechat/interface/index.html";
header("location: ".$url);
?>
