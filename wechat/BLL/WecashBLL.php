<?php
require_once ('/var/www/html/global.php');
// require_once (_ROOT.'wechat/Model/User.php');
require_once (_ROOT.'wechat/IBLL/IWecashBLL.php');//接口定义
require_once (_ROOT.'wechat/Factory/DataFactory.php');//引用下层数据

class WecashBLL implements IWecashBLL{
    public function payToMerchant($openid='', $casharr, $user_name=''){
        return Wechat_DataFactory::createInstanceWecashDAL()->payToMerchant($openid, $casharr, $user_name);
    }
    public function getTixianRecord($shopid=''){
        return Wechat_DataFactory::createInstanceWecashDAL()->getTixianRecord($shopid);
    }
    public function getBalance($openid = ''){
        return Wechat_DataFactory::createInstanceWecashDAL()->getBalance($openid);
    }
    public function getOpenidFromCode($code = ''){
        return Wechat_DataFactory::createInstanceWecashDAL()->getOpenidFromCode($code);
    }
    public function isBindShopOpenid($openid=''){
        return Wechat_DataFactory::createInstanceWecashDAL()->isBindShopOpenid($openid);
    }
}

?>
