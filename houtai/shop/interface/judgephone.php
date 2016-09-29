<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class JudgePhone{
	public function getUidByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getUidByphone($phone);
	}
}
$judgephone=new JudgePhone();
if(isset($_GET['phone'])){
	$userphone=$_GET['phone'];
	$phonecrypt = new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$uid=$judgephone->getUidByphone($userphone);
	if(!empty($uid)){
		echo true;
	}else{
		echo false;
	}
}

?>