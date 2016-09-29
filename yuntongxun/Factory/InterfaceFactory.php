<?php 
require_once ('/var/www/html/yuntongxun/global.php');
require_once (DOCUMENT_ROOT.'DAL/SendMsgDAL.php');
class InterfaceFactory{
	public static function createInstanceSendMsgDAL(){
		return new SendMsgDAL(); 
	}
}
?>