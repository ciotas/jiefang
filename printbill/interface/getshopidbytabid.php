<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetShopidByTabid{
	public function getShopidByTabidData($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getShopidByTabidData($tabid);
	}
}
$getshopidbytabid=new GetShopidByTabid();
if(isset($_POST['tabid'])){
	$tabid=$_POST['tabid'];
	$shopid=$getshopidbytabid->getShopidByTabidData($tabid);
	header('Content-type: application/json');
	echo json_encode(array("shopid"=>$shopid));
}
?>