<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Model/User.php');
require_once (_ROOT.'wechat/IBLL/IWxpayBLL.php');//接口定义
require_once (_ROOT.'wechat/Factory/DataFactory.php');//引用下层数据

class WxpayBLL implements IWxpayBLL{
  
    public function jsApiCall($openid = '', $orderno = '', $orderfee = '', $attach = ''){
        return Wechat_DataFactory::createInstanceWxpayDAL()->jsApiCall($openid, $orderno, $orderfee, $attach);
    }
    public function notifyUrl(){
        return Wechat_DataFactory::createInstanceWxpayDAL()->notifyUrl();
    }
    public function write_logs($content = ''){
        return Wechat_DataFactory::createInstanceWxpayDAL()->write_logs($content = '');
    }
}

?>
