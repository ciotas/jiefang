<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoDonateAccount{
	public function isShopphoneReg($shopphone){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->isShopphoneReg($shopphone);
	}
	public function addToDonateAccount($inputarr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->addToDonateAccount($inputarr);
	}
}
$dodonateaccount=new DoDonateAccount();
if(isset($_POST['shopphone'])){
	$shopphone=$_POST['shopphone'];
	if(empty($shopphone)){
		header("location: ../donateacccount.php?status=phone_empty");exit;
	}
	$shoparr=$dodonateaccount->isShopphoneReg($shopphone);
	if($shoparr['status']){
		$shopid=$shoparr['shopid'];
		$donatemonth=$_POST['donatemonth'];
		$donatereason=$_POST['donatereason'];
		$addtime=time();
		$donatefrom="街坊科技";
		$inputarr=array(
				"shopid"	=>$shopid,
				"donatemonth"=>$donatemonth,
				"donatereason"=>$donatereason,
				"donatefrom"=>$donatefrom,
				"addtime"=>$addtime,
		);
// 		print_r($inputarr);exit;
		$dodonateaccount->addToDonateAccount($inputarr);
		header("location: ../donateacccount.php?status=ok");exit;
	}else{
		header("location: ../donateacccount.php?status=phone_unreg");exit;
	}
	
}
?>