<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/BLL/WechatBLL.php');
require_once (_ROOT.'takeoutwechat/BLL/WxpayBLL.php');
class Wechat_BLLFactory{
	public static function createInstanceWechatBLL(){
		return new WechatBLL();
	}
	public static function createInstanceWxpayBLL(){
		return new WxpayBLL();
	}
}
?>