<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveShopinfo{
	public function updateShopinfoData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateShopinfoData($inputarr);
	}
}
$saveshopinfo=new SaveShopinfo();
if(isset($_SESSION['shopid'])){
	$shopid=$_SESSION['shopid'];
	$briefinfo=$_POST['briefinfo'];
	$province=$_POST['province'];
	$city=$_POST['city'];
	$district=$_POST['district'];
	$road=$_POST['road'];
	$lon=floatval($_POST['lon']);
	$lat=floatval($_POST['lat']);
	$avgpay=$_POST['avgpay'];
	$opentime=$_POST['opentime'];
	$takeoutswitch=$_POST['takeoutswitch'];
	$servicephone=$_POST['servicephone'];
	$manager=$_POST['manager'];
	$alipayaccount=$_POST['alipayaccount'];
	$storetag=$_POST['storetag'];
	$favfoodid=$_POST['favfoodid'];
	$inputarr=array(
			"shopid"=>$shopid,
			"briefinfo"=>$briefinfo,
			"province"=>$province,
			"city"=>$city,
			"district"=>$district,
			"road"=>$road,
			"lon"=>$lon,
			"lat"=>$lat,
			"loc"=>array("type"=>"Point","coordinates"=>array($lon,$lat)),
			"avgpay"=>$avgpay,
			"opentime"=>$opentime,
			"servicephone"=>$servicephone,
			"manager"=>$manager,
			"alipayaccount"=>$alipayaccount,
			"takeoutswitch"=>$takeoutswitch,
			"storetag"=>$storetag,
			"favfoodid"=>$favfoodid,
	);
// 	print_r($inputarr);exit;
	$saveshopinfo->updateShopinfoData($inputarr);
	header("location: ../shopinfo.php");
}
?>