<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');

$appid = "wxc5b83fb82bad0b65";
$encodeurl = "http://test.meijiemall.com/wechat/interface/payhand.php";
$encodeurl = urlencode($encodeurl);
$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$encodeurl."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
header("location: ".$url);
?>
