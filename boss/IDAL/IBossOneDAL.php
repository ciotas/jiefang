<?php 
interface IBossOneDAL{
	public function DoLoginData($bossphone, $password);
	public function regBossAccount($bossphone,$checkcode,$bossname,$passwd,$addtime);
	public function isPhoneUse($phone);
	public function isShopPhonereg($shopphone);
	public function bindShopPhoneData($inputarr);
	public function post_curl($url, $params);
	public function getShopidbyPhone($phone);
	public function delOneShopData($shopid);
	public function getMyShoplistData($bossid);
	public function getShopinfoByShopid($shopid);
	public function getOpenHourByShopid($shopid);
	public function getOneShopRundata($shopid, $startdate, $enddate, $datearr, $thehour);
	public function getShopPercentData($shoparr,$op);
	public function getTheday($shopid);
	public function getTableRunStatus($shoparr);
	public function saveVipCard($inputarr);
	public function updateOneVcd($inputarr);
	public function getVipcardList($bossid);
	public function delOneVcd($vcid);
	public function getOneVcdData($vcid);
	public function getViptagsData($bossid);
	public function saveOneTag($inputarr);
	public function updateOneTag($viptagid,$inputarr);
	public function getOneTagByTagid($viptagid);
	public function delOneTagByTagid($viptagid);
	public function getBuyGoodsRecord($id,$type,$theday);
	public function getOneGoodsInfo($goodsid);
	public function saveBossFtype($inputarr);
	public function updateBossFtype($ftid,$inputarr);
	public function getBossFtypes($bossid);
	public function delOneBOssFtype($ftid,$ftcode,$bossid);
	public function addBossFtypeToShopFtype($inputarr);
	public function updateToShopFtype($inputarr);
	public function getSubShopIds($bossid);
	public function getShopPrinter($shopid);
	public function delSubshopFtype($ftcode,$bossid);
	public function getPuhongShopZone($bossid);
	public function getFoodSoldnumByDist($shopidarr,$startdate,$enddate);
}
?>
