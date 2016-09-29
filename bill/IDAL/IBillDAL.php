<?php 
interface IBillDAL{
	public function getOneBillInfoByBillid($billid);
	public function getDaySheetData($shopid, $theday,$token);
	public function getTotalmoneyAndFoodDiscountmoney($billid);
	public function judgeTheFoodDisaccount($foodid);
	public function getFoodSheetData($shopid,$theday,$starttime,$endtime);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getFoodtypeSheetData($shopid,$theday,$starttime,$endtime);
	public function getFoodTypInfoByFtid($foodid);
	public function getFoodTypInfoByFoodid($ftid);
	public function getTodayOnlineData($shopid,$theday,$token);
	public function getPreArray($inpuarr, $n);
	public function getTabNum($shopid);
	public function getOpenHourByShopid($shopid);
	public function getFoodinfoByFoodid($foodid);
	public function getOnePrinterInfoByPid($pid);
	public function generPrintContent($deviceno,$devicekey,$datarr,$theday);
	function sendSelfFormatMessage($msgInfo);
	public function getStableLenStr($str, $len);
	public function getTabnameByTabid($tabid);
	public function getDepositmoney($shopid);
	public function getTabStatusByTabid($tabid);
	public function getTheday($shopid);
	public function hasBillBeforeTheTab($shopid,$tabid, $timestamp);
	public function getTablenameByTabid($tabid);
	public function getTakeoutData($shopid);
	public function getUserinfo($uid);
	public function getBillsData($shopid,$searchday);
	public function getBillNo($billid);
}

?>