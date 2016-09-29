<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
class AddScore{
	public function addScoreData($inputarr){
		Wechat_BLLFactory::createInstanceWechatBLL()->addScoreData($inputarr);
	}
}
$addscore=new AddScore();
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$uid=$_GET['uid'];
	$billid=$_GET['billid'];
	$score=$_GET['score'];
	$inputarr=array(
			"shopid"=>$shopid,
			"uid"=>$uid,
			"billid"=>$billid,
			"score"=>$score,	
	);
	$addscore->addScoreData($inputarr);
	echo "";
}
?>