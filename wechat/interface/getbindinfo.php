<?php
/**
 * Created by PhpStorm.
 * User: wangjj
 * Date: 5/17/16
 * Time: 19:49
 */
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');

class GetBindInfoClass{
    public function getPostRequest($url, $data){
        return Wechat_BLLFactory::createInstanceWechatBLL()->getPostRequest($url, $data);
    }
    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}

$var = new GetBindInfoClass();


$phone = isset($_POST['phone'])?$_POST['phone']:'';
$passwd = isset($_POST['passwd'])?$_POST['passwd']:'';
$openid = isset($_POST['openid'])?$_POST['openid']:'';
$menutype = isset($_POST['menutype'])?$_POST['menutype']:'';


if($phone){
    $var->write_logs("phone=".$phone);
    $var->write_logs("openid=".$openid);
}

if(strlen($phone)==0 || strlen($passwd)==0 || strlen($openid)==0){
    $var->write_logs("[bind]: input error");
    return;
}

$url = ROOTURL.'houtai/shop/interface/bindshopid.php';
$var->write_logs("[bind][url]=".$url);
$data = array('phone'=>$phone, 'passwd'=>$passwd, 'openid'=>$openid);
$res = $var->getPostRequest($url, $data);
$var->write_logs("res=".json_encode($res));
//绑定成功过
if($res['code']=='ok'){
    $url = ROOTURL.'wechat/interface/choosepage.php?menutype='.$menutype.'&openid='.$openid.'&isbind=1';
    $var->write_logs("bindurl=".$url);
    header("location: ".$url);
} else{
    $url='./bindpage.php?status=error';
    header("location: ".$url);
}



?>
