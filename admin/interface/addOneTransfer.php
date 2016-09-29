<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TransferLog{
	public function addTransferLog($arr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->addOneTransferLog($arr);
	}
	public function delTransferLog($id)
	{
	    Admin_InterfaceFactory::createInstanceAdminOneDAL()->delOneTransferLog($id);
	}
}
$transferLog=new TransferLog();

if(isset($_GET['shopid'])){
    $arr['addtime']=time();
    $arr['shopid'] = $_GET['shopid'];
    $arr['day'] = $_GET['day'];
    $arr['money'] = $_GET['money'];
	$transferLog->addTransferLog($arr);
	header("location: ../dayreport.php");
}
if(isset($_GET['id']))
{
    $transferLog->delTransferLog($_GET['id']);
    echo "删除成功";
    header("location: ../transfernote.php");
}
?>