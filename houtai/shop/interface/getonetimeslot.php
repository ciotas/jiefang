<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOne{
	
	public function GetOneTimeSlot($id)
	{
	    return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getOneTimeSlot($id);
	}
}
$getone=new GetOne;
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$result=$getone->GetOneTimeSlot($id);
	echo json_encode($result);
}
?>