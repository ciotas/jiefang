<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneGoodsType{
	public function addOneGoodsTypeData($inputarr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->addOneGoodsTypeData($inputarr);
	}
	public function updateOneGoodsTypeData($goodstypeid,$inputarr){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->updateOneGoodsTypeData($goodstypeid, $inputarr);
	}
}
$saveonegoodstype=new SaveOneGoodsType();
if(isset($_POST['goodstypeid'])){
	$goodstypeid=$_POST['goodstypeid'];
	$goodstypename=$_POST['goodstypename'];
	$sortno=$_POST['sortno'];
	$inputarr=array(
			"goodstypename"=>$goodstypename,
			"sortno"=>$sortno,
	);
	if(!empty($goodstypeid)){
		$saveonegoodstype->updateOneGoodsTypeData($goodstypeid, $inputarr);
	}else{
		$saveonegoodstype->addOneGoodsTypeData($inputarr);
	}
	header("location: ../goodstype.php");
}
?>