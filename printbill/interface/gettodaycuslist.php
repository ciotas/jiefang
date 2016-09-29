<?php
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTodayCusList{
	public function getData($shopid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getTodayCuslist($shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$gettodaycuslist=new GetTodayCusList();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettodaycuslist->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettodaycuslist->updateShopSession($shopid,$session);break;
			}
			$result=$gettodaycuslist->getData($shopid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
// exit;
$result= $gettodaycuslist->getData("5514c2c416c1092b2f8b4594");
print_r($result);exit;
echo json_encode(array("token"=>"","data"=>$result));
// file_put_contents(LASTTIME, time());
?>
