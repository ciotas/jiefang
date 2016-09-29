<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
require_once ('/var/www/html/process_lock.php');

require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class ReChargeTips{
	public function getAllShopId(){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getAllShopId();
	}
}
$rechargetips=new ReChargeTips();
$easemob=new Easemob($options);
if ($argc > 1){
	if($argv[1]=="recharge"){
		$shopidarr=$rechargetips->getAllShopId();
		$content="您好，今天是".date("Y-m-d",time())."，请别忘记给打印机SIM卡充值套餐，以保证正常使用。（新购入的打印机可免费使用六个月，超过六个月请自行充值）。";
		$easemob->yy_hxSend("admin",$shopidarr, $content, "users",array(""=>""));
	}
}

?>