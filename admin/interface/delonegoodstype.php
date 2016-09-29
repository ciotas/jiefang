<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneGoodsType{
	public function delOneGoodsTypeData($goodstypeid){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->delOneGoodsTypeData($goodstypeid);
	}
}
$delonegoodstype=new DelOneGoodsType();
if(isset($_GET['goodstypeid'])){
	$goodstypeid=base64_decode($_GET['goodstypeid']);
	$delonegoodstype->delOneGoodsTypeData($goodstypeid);
	header("location: ../goodstype.php");
}
?>