<?php
require_once ('/var/www/html/global.php');
class test{
    public function write_logs($content=''){
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}

$inst = new test();

$shopid=isset($_GET['shopid'])?$_GET['shopid']:'';
$deskno=isset($_GET['deskno'])?$_GET['deskno']:'';
$encodeurl = ROOTURL."wechat/interface/author.php?shopid=$shopid&deskno=$deskno";
$inst->write_logs("encodeurl=".$encodeurl);
#$encodeurl = "http://test.meijiemall.com/wechat/interface/index.php";
$encodeurl = urlencode($encodeurl);
#$inst->write_logs($encodeurl);
$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$encodeurl."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
$inst->write_logs("header before!");
header("location: ".$url);
$inst->write_logs("header after!");
?>
