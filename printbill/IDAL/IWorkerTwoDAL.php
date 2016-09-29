<?php 
interface  IWorkerTwoDAL{
	public function getHistoryLists($shopid,$uid,$date=NULL);
	public function array_sort($arr, $keys, $type = 'asc');
	public function intoShop_infoData($inputarr);
	public function getOneShop_infoData($shopid,$uid);
	public function intoBillShopinfo($inputarr,$tab);
	public function getOneBillShopinfo($tab,$billid);
	public function getVipdiscount($shopid);
	public function addBillNum($shopid,$billid,$theday);
	public function getBillNum($billid);
	public function isBindShopByOpenid($openid);
	public function intoInnerShop_infoData($inputarr);
	public function intoBillInnerShopinfo($inputarr,$tab); 
	public function getShopidByOpenid($openid);
	public function getDIstance($inputarr);
	public function getDistanceByLatLon($lat1,$lng1, $lat2, $lng2);
	public function getDistanceLimit($shopid);
	public function getTakeoutSwitch($shopid);
	public function sendDownbillMsg($shopid,$billid,$paymoney);
	public function getDiscountByOnline($shopid,$paymoney);
	public function getDistributeFee($inputarr);
	public function LogOutMyWechat($shopid,$openid);
	public function sendTixianErrorMsg($msg);
	public function isReDownbill($shopid,$uid,$foodjson);
	public function saveMyaddress($inputarr);
	public function getMyAddress($uid);
	public function getStartmoney($shopid);
}
?>