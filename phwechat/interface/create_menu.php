<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/Factory/BLLFactory.php');

class createMenuClass{
    public function createMenu(){
       echo "==123";
       return Wechat_BLLFactory::createInstanceWechatBLL()->createMenu();
       echo "==123";
    }
    public function write_logs($content=''){
        echo $content;
    	return Wechat_BLLFactory::createInstanceWechatBLL()->write_logs($content);
    }
}
//
echo "hello world";
//
$inst = new createMenuClass();
$inst->write_logs("123asfasf");
$inst->createMenu();

?>
