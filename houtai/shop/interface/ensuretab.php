<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EnsureTab{
	public function ensureBookTab($bookid, $tabid,$op){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->ensureBookTab($bookid, $tabid, $op);
	}
	public function getOneBookinfo($bookid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getOneBookinfo($bookid);
	}
	public function sendBookMsg($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendBookMsg($inputarr);
	}
}
$ensuretab=new EnsureTab();
if(isset($_POST['bookid'])){
	$bookid=$_POST['bookid'];
	$tabid=$_POST['tabid'];
	$theday=$_POST['theday'];
	$op="accept";
	$ensuretab->ensureBookTab($bookid, $tabid, $op);
	$onebook=$ensuretab->getOneBookinfo($bookid);
	$inputarr=array(
			"bookid"=>$onebook['bookid'],
			"shopid"=>$onebook['shopid'],
			"uid"=>$onebook['uid'],
			"cusname"=>$onebook['cusname'],
			"cusnum"	=>$onebook['cusnum'],
			"cusphone"=>$onebook['cusphone'],
			"tabid"=>$tabid,
			"bookdate"=>$onebook['bookdate'],
			"booktime"=>$onebook['booktime'],
			"timestamp"=>$onebook['timestamp'],
	);
	$result=$ensuretab->sendBookMsg($inputarr);
	$result=trim($result);
	switch($result){
		case "ok": header("location: ../booklist.php?theday=$theday");break;
		case "error": header("location: ../booklist.php?theday=$theday&status=error");break;
	}
}
exit;
$str='{"bookid":"56445a7f5bc10976138b45b1","shopid":"554ad9615bc109d8518b45d2","uid":"560ffb637cc10967058b4578","cusname":"\u5f20\u5148\u751f","cusnum":"5","cusphone":"13071870889","tabid":"5565d1015bc1092b7a8b9687","bookdate":"2015-11-12","booktime":null,"timestamp":1447320191}';
$inputarr=json_decode($str,true);
$result=$ensuretab->sendBookMsg($inputarr);
?>