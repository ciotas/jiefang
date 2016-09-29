<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class GetFoodCook{
	public function getFoodCookData($foodid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getFoodCookData($foodid);
	}
}
$getfoodcook=new GetFoodCook();
if(isset($_GET['foodid'])){
	$foodid=$_GET['foodid'];
	$result=$getfoodcook->getFoodCookData($foodid);
	echo json_encode($result);
}
exit;
$result=$getfoodcook->getFoodCookData("554b05355bc109d5518b45eb");
print_r($result);
?>