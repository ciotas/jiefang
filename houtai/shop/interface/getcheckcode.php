<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class GetCheckCode{
	public function getCheckCodeByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckCodeByphone($phone);
	}
}
$getcheckcode=new GetCheckCode();
if(isset($_GET['userphone'])){
	$userphone=$_GET['userphone'];
	$phonecrypt= new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$checkcode=$getcheckcode->getCheckCodeByphone($userphone);
	echo $checkcode;
}
?>