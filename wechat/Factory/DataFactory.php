<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/DAL/WechatDAL.php');
require_once (_ROOT.'wechat/DAL/WxpayDAL.php');
require_once (_ROOT.'wechat/DAL/WecashDAL.php');
class Wechat_DataFactory{
	public static function createInstanceWechatDAL(){
		return new WechatDAL();
	}
	public static function createInstanceWxpayDAL(){
		return new WxpayDAL();
	}
	public static function createInstanceWecashDAL(){
		return new WecashDAL();
	}
}
?>
