<?php 
interface IConsumeListDAL{ 
	public function printConsumeListData($json);
	public function createContentHtml($arr,$deviceno,$devicekey);
	public function createSmallContentHtml($arr,$deviceno,$devicekey);
	public function getStableLenStr($str,$len);
	public function getOneCounponType($ctid);
	public function getDepositmoney($shopid);
	public function isInDonateticketTable($shopid);
	public function createDonateticketContentHtml($arr,$deviceno,$devicekey);
	public function createDonateticketSmallContentHtml($arr,$deviceno,$devicekey);
	public function getDonateticketFullmoney($shopid,$foodarr);
	public function getDonateticketFtarr($shopid);
	public function getDonateticketRule($shopid);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getDonateticketTips($shopid);
	public function getBillnumToday($shopid);
	public function getOpenHourByShopid($shopid);
	public function getTheday($shopid);
	public function printPrePaySheet($json,$op);
	public function createPrepayContentHtml($arr,$deviceno,$devicekey,$shopid,$op);
	public function createPrepaySmallContentHtml($arr,$deviceno,$devicekey,$shopid,$op);
	public function getPaySheetPrintnum($shopid);
}
?>