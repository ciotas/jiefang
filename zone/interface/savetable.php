<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class SaveTable{
	public function addData($inputarr){
		Zone_InterfaceFactory::createInstanceTabDAL()->addData($inputarr);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$savetab=new SaveTable();
if(isset($_POST['shopid'])){//$_POST
	$shopid=$_POST['shopid'];
	$tabname=$_POST['tabname'];//桌台名称
	$seatnum=$_POST['seatnum'];//叫号用
	$tabstatus=$_POST['tabstatus'];//桌台状态
	$tabswitch=$_POST['tabswitch'];
	$tablowest=$_POST['tablowest'];
	$zoneid=$_POST['zoneid'];
	$printerid=$_POST['printerid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$savetab->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$tabname.$seatnum.$tabstatus.$tabswitch.$tablowest.$zoneid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$savetab->updateShopSession($shopid,$session);break;
			}
			$inputarr=array(
					"shopid"=>$shopid,
					"tabname"=>$tabname,
					"seatnum"=>$seatnum,
					"tabstatus"=>$tabstatus,
					"tabswitch"=>$tabswitch,
					"tablowest"=>$tablowest,
					"zoneid"=>$zoneid,
					"printerid"=>$printerid,
					"addtime"=>time(),
			);
			$savetab->addData($inputarr);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$inputarr=array(
		"shopid"=>"547430f016c10932708b4624",
		"tabname"=>"A33",
		"seatnum"=>"4",
		"tabstatus"=>"empty",
		"tabswitch"=>"1",
		"tablowest"=>"20",
);
$savetab->addData($inputarr);
?>