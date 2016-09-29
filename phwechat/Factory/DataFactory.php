<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/DAL/WechatDAL.php');
require_once (_ROOT.'phwechat/DAL/WxpayDAL.php');
class Wechat_DataFactory{
	public static function createInstanceWechatDAL(){
		return new WechatDAL();
	}
	public static function createInstanceWxpayDAL(){
		return new WxpayDAL();
	}
}
?>