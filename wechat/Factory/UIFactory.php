<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/BLL/WechatBLL.php');
//这里扩展require_once
class Wechat_UIFactory{
	public static function createIntanceWechatBLL(){
		return new WechatBLL();
	}
	//下面扩展静态函数
}
?>