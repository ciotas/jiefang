<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneRule{
	public function getOneDonateTicketRule($ruleid){
		return QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->getOneDonateTicketRule($ruleid);
	}
}
$getonerule=new GetOneRule();
if(isset($_GET['ruleid'])){
	$ruleid=$_GET['ruleid'];
	$result=$getonerule->getOneDonateTicketRule($ruleid);
	echo json_encode($result);
}
?>