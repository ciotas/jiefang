<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/BLL/WechatBLL.php');
require_once (_ROOT.'wechat/BLL/WxpayBLL.php');
require_once (_ROOT.'wechat/BLL/WecashBLL.php');
class Wechat_BLLFactory{
	public static function createInstanceWechatBLL(){
		return new WechatBLL();
	}
	public static function createInstanceWxpayBLL(){
		return new WxpayBLL();
	}
	public static function createInstanceWecashBLL(){
        file_put_contents('/data/www/logs/123.log', 'asdfasdfasdfasdf123adv', FILE_APPEND);
		return new WecashBLL();
	}
}
?>
