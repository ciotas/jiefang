<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class UnLock{
	public function checkPwd($shopid, $pwd){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->checkPwd($shopid, $pwd);
	}
}
$unlock=new UnLock();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$passwd=$_POST['passwd'];
	$pwdcrypt= new CookieCrypt($pwdkey);
	$passwd=$pwdcrypt->encrypt($passwd);
	$result=$unlock->checkPwd($shopid, $passwd);
	if($result){
		header("location: ../index.php");
	}else{
		header("location: ../extra_lock.php?status=error");
	}
}
?>
