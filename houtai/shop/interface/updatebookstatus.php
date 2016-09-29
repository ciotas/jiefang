<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpdateBookStatus{
	public function updateBookStatusData($bookid, $op){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateBookStatusData($bookid, $op);
	}
}
$updatebookstatus=new UpdateBookStatus();
if(isset($_REQUEST['bookid'])){
	$bookid=$_REQUEST['bookid'];
	$op=$_REQUEST['op'];
	$theday=$_REQUEST['theday'];
	$updatebookstatus->updateBookStatusData($bookid, $op);
	header("location: ../booklist.php?theday=".$theday);
}
?>