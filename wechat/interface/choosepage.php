<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');

class ChoosePageClass{
    public function getOpenid($code){
        return Wechat_BLLFactory::createInstanceWecashBLL()->getOpenidFromCode($code);
    }
    public function isBind($openid){
            return Wechat_BLLFactory::createInstanceWecashBLL()->isBindShopOpenid($openid);
    }
    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
    public function get_menu_url($menutype, $openid=''){
        $url = '';
        if ($menutype === '0'){
            $url = ROOTURL.'houtai/shop/wechatservice/daysheet.php';
        } else if ($menutype === '1'){
            $url = ROOTURL.'houtai/shop/wechatservice/dailysheet.php';
        } else if ($menutype === '2'){
            //$url = ROOTURL.'houtai/shop/wechatservice/flowsheet.php';
            $url = ROOTURL.'houtai/shop/wechatservice/turnovertrend.php';
        } else if ($menutype === '3'){
            $url = ROOTURL.'houtai/shop/wechatservice/dayssheet.php';
        } else if ($menutype === '4'){
            $url = ROOTURL.'houtai/shop/wechatservice/foodcalc.php';
        } else if ($menutype === '5'){
            $url = ROOTURL.'houtai/shop/wechatservice/foodtype.php';
        } else if ($menutype === '6'){
            $url = ROOTURL.'houtai/shop/wechatservice/foodmanage.php';
        } else if ($menutype === '7'){
            $url = ROOTURL.'houtai/shop/wechatservice/upfoodpic.php';
        } else if ($menutype === '8'){
            $url = ROOTURL.'houtai/shop/wechatservice/servers.php';
        } else if ($menutype === '11'){
            $url = ROOTURL.'wechat/interface/getcashpage.php';
        } else if ($menutype === '10'){
            //$url = ROOTURL.'wechat/interface/getcashbalance.php';
            //$url = ROOTURL.'houtai/shop/wechatservice/orderpage.php';
            $url = ROOTURL.'wechat/interface/logout.php';
            //http://shop.meijiemall.com/wechat/interface/logout.php?openid=
        } else if ($menutype === '9'){
            $url = ROOTURL.'wechat/interface/getcashrecord.php';
        } else if ($menutype === '12'){
            $url = ROOTURL.'houtai/shop/wechatservice/handle.php';
        } else{
            $url = "没有菜单";
        }
        return $url;
    }
}

$var = new ChoosePageClass();

//如果刚绑定则直接跳转
$isbindflg = isset($_GET['isbind'])?$_GET['isbind']:'';
if($isbindflg == 1){
    $var->write_logs('already bind: '.$isbindflg);
    $openid = isset($_GET['openid'])?$_GET['openid']:'';
    $menutype = isset($_GET['menutype'])?$_GET['menutype']:'';
    $url = $var->get_menu_url($menutype, $openid);
    $url = $url.'?openid='.$openid;
    header("location: ".$url);
    return;
}

$code = isset($_GET['code'])?$_GET['code']:'';
$var->write_logs('[ChoosePageClass]: code='.$code);
$menutype = isset($_GET['menutype'])?$_GET['menutype']:'';
$var->write_logs('[ChoosePageClass]: type='.$menutype);
$openid = $var->getOpenid($code);
if($openid == ''){
    $var->write_logs('[ChoosePageClass]: get no openid');
    return;
}

$var->write_logs('[ChoosePageClass]: openid='.$openid);
$flg = $var->isBind($openid);
$var->write_logs('[ChoosePageClass]: flag='.$flg);
if ($flg==0){
    $url = ROOTURL.'wechat/interface/bindpage.php';
    $url = $url.'?openid='.$openid.'&menutype='.$menutype;
    header("location: ".$url);
    return;
}
$url = $var->get_menu_url($menutype, $openid);
$var->write_logs($url);
$url = $url.'?openid='.$openid;
header("location: ".$url);

?>
