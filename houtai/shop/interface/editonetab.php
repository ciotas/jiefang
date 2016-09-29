<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditOneTab{
	public function saveOneTable($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->saveOneTable($inputarr);
	}
	public function updateOneTable($tabid,$inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->updateOneTable($tabid, $inputarr);
	}
}
$editonetab=new EditOneTab();
if (isset($_POST['tabid'])){
	$shopid=$_SESSION['shopid'];
	$tabid=$_POST['tabid'];
	$tabname=$_POST['tabname'];
	$sortno=$_POST['sortno'];
	$seatnum=$_POST['seatnum'];
	$zoneid=$_POST['zoneid'];
	$printerid=$_POST['printerid'];
	$typeno=$_POST['typeno'];
	$inputarr=array(
			"shopid"=>$shopid,
			"tabname"	=>$tabname,
			"sortno"=>$sortno,
			"seatnum"=>$seatnum,
			"zoneid"=>$zoneid,
			"printerid"=>$printerid,
			"tabstatus"=>"empty",
			"tablowest"=>"0",
			"tabswitch"=>"1",
			"addtime"=>time(),
	);
// 	echo $tabid;exit;
// 	print_r($inputarr);exit;
	if(!empty($tabid)){
		$editonetab->updateOneTable($tabid, $inputarr);
	}else{
		$editonetab->saveOneTable($inputarr);
	}
	header("location: ../tables.php?typeno=$typeno");
	
}
?>