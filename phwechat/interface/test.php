<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/Factory/BLLFactory.php');
// require_once (_ROOT.'wechat/Model/User.php');
class Test{
	public function getPlus($a,$b){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getPlus($a, $b);
	}
}
$test=new Test();
echo $test->getPlus(1, 2);
?>