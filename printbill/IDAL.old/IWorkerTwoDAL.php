<?php 
interface  IWorkerTwoDAL{
	public function getHistoryLists($shopid,$uid);
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
}
?>