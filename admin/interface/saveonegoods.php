<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneGoods{
	public function addGoodsData($inputarr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->addGoodsData($inputarr);
	}
	public function updateGoodsData($goodsid, $inputarr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->updateGoodsData($goodsid, $inputarr);
	}
}
$saveonegoods=new SaveOneGoods();
if(isset($_POST['goodsid'])){
	$goodsid=$_POST['goodsid'];
	$goodsname=$_POST['goodsname'];
	$typeno=$_POST['typeno'];
	$goodsdesc=$_POST['goodsdesc'];
	$otherprice=$_POST['otherprice'];
	$ourprice=$_POST['ourprice'];
	$goodsunit=$_POST['goodsunit'];
	$goodssoldunit=$_POST['goodssoldunit'];	
	$goodstypeid=$_POST['goodstypeid'];
	$online=$_POST['online'][0];
	if($online=="on"){
		$online="1";
	}else{
		$online="0";
	}
	$goodsformat=$_POST['goodsformat'];
	$timestamp=time();
	$inputarr=array(
			"goodsname"=>$goodsname,
			"otherprice"=>$otherprice,
			"ourprice"=>$ourprice,
			"goodsunit"=>$goodsunit,
			"goodssoldunit"=>$goodssoldunit,
			"goodstypeid"=>$goodstypeid,
			"goodsdesc"=>$goodsdesc,
			"online"=>$online,
			"goodsformat"=>$goodsformat,
			"timestamp"=>$timestamp,
	);
// 	print_r($inputarr);exit;
	if(!empty($goodsid)){
		$saveonegoods->updateGoodsData($goodsid, $inputarr);
	}else{
		$saveonegoods->addGoodsData($inputarr);
	}
	header("location: ../goods.php?typeno=$typeno");
}
?>