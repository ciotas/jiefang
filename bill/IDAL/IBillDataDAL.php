<?php 
interface IBillDataDAL{
	public function getBillViewData($uid);
	public function getBillData($billid);
	public function getShopInfo($shopid);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getBillInfoById($billid);
}
?>