<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
require_once (_ROOT.'des.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class AddPlatform{
	public function addPlatfromData($uid, $platform){
		return Wechat_BLLFactory::createInstanceWechatBLL()->addPlatfromData($uid, $platform);
	}
}
$addplatform=new AddPlatform();
if(isset($_GET['uid'])){
	$uid=$_GET['uid'];
	$platform=$_GET['platform'];
	$addplatform->addPlatfromData($uid, $platform);
	echo "";
}
?>