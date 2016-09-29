<?php
/**
 * Created by PhpStorm.
 * User: wangjj
 * Date: 6/1/16
 * Time: 20:33
 */


require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');

class GetBalanceClass{
    public function getShopBalance($openid){
        return Wechat_BLLFactory::createInstanceWecashBLL()->getBalance($openid);
    }
    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}

$var = new GetBalanceClass();
$openid = isset($_GET['openid'])?$_GET['openid']:'';
$var->write_logs('[openid] = '.$openid);

$amount = $var->getShopBalance($openid);

var_dump($amount);

?>
