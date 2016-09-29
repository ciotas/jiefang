<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/BLL/WechatBLL.php');
require_once (_ROOT.'phwechat/BLL/WxpayBLL.php');
class Wechat_BLLFactory{
	public static function createInstanceWechatBLL(){
		return new WechatBLL();
	}
	public static function createInstanceWxpayBLL(){
		return new WxpayBLL();
	}
}
?>