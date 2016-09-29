<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetOneGoodsType{
	public function getOneGoodsTypeData($goodstypeid){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getOneGoodsTypeData($goodstypeid);
	}
}
$getonegoodstype=new GetOneGoodsType();
if(isset($_GET['goodstypeid'])){
	$goodstypeid=$_GET['goodstypeid'];
	$result=$getonegoodstype->getOneGoodsTypeData($goodstypeid);
	echo json_encode($result);
}
?>