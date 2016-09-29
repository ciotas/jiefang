<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneGoods{
	public function delOneGoodsData($goodsid){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->delOneGoodsData($goodsid);
	}
}
$delonegoods=new DelOneGoods();
if(isset($_GET['goodsid'])){
	$goodsid=base64_decode($_GET['goodsid']);
	$typeno=$_GET['typeno'];
	$delonegoods->delOneGoodsData($goodsid);
	header("location: ../goods.php?typeno=$typeno");
}
?>