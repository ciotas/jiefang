<?php
/**
 * Created by PhpStorm.
 * User: wangjj
 * Date: 6/1/16
 * Time: 20:32
 */

require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
class GetCashClass{
    public function payToMerchant($openid, $casharr){
        return Wechat_BLLFactory::createInstanceWecashBLL()->payToMerchant($openid, $casharr, '');
    }
    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}

$cashvalue = isset($_POST['cashvalue'])?$_POST['cashvalue']:'';
$openid = isset($_POST['openid'])?$_POST['openid']:'';
$openid=trim($openid);
$var = new GetCashClass();
$var->write_logs('[Getcash][GetCashClass]: '.$openid.' ;cashvalue = '.$cashvalue);
if($cashvalue=='' || $openid==''){
    return;
}
$origincash = (int)(floatval($cashvalue)*100);
$cashvalue = sprintf("%.2f",$cashvalue*(1-0.006));
$cash = (int)(floatval($cashvalue)*100);
$casharr=array("origincash"=>$origincash,"realcash"=>$cash);
$var->write_logs('[Getcash][GetCashClass]: cashvalue = '.strval($cash));
$flg = $var->payToMerchant($openid, $casharr);
if($flg == 0){
    $url = ROOTURL.'/wechat/interface/getcashpage.php?openid='.$openid;
    header("location: ".$url);
} else {
    $url = ROOTURL.'/wechat/interface/getcasherror.php';
    header("location: ".$url);
}

?>
