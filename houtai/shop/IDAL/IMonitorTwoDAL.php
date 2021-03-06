<?php 
interface IMonitorTwoDAL{
	public function getZoneTabByShopid($shopid);
	public function getTabsByZoneid($zoneid);
	public function hasTabsInZone($zoneid);
	public function combineTwoTab($tabid1,$tabid2);
	public function getStartBillidByTabid($tabid);
	public function updateTabStatus($tabid,$tabstatus);
	public function getTabStatusByTabid($tabid);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getPrintersByShopid($shopid);
	public function getFoodtypesByShopid($shopid);
	public function getOnePrinterByPid($pid);
	public function queryPrinterStatus($device_no,$device_key);
	public function getZoneNameByPos($posid);
	public function getOneFoodTypeByFtid($ftid);
	public function getPrinterNameByPid($pid);
	public function addOneFoodTypeData($inputarr);
	public function delOneFoodTypeData($ftid);
	public function upFoodToDB($foodarr);
	public function updateFoodByFid($foodid,$inputarr);
	public function getZonesByShopid($shopid);
	public function getOneFoodData($foodid);
	public function getZoneNameByZoneId($zoneid);
	public function delOneFoodData($foodid);
	public function updateOneFoodtypeData($ftid,$inputarr);
	public function getGuqingFoodData($shopid);
	public function getFtnameByFtid($ftid);
	public function updateGuqingStatus($foodid,$guqingstatus);
	public function getFuncRole($shopid);
	public function addOnePrinter($inputarr);
	public function updateOnePrinter($inputarr,$printerid);
	public function delOnePrinterData($pid);
	public function getReturnFoodData($shopid,$theday);
	public function getOpenHourByShopid($shopid);
	public function getFoodnameByFoodid($foodid);
	public function getFoodDonateData($shopid,$theday);
	public function getTablenameByTabid($tabid);
	public function getFoodSwitchRecord($shopid,$theday);
	public function isShopphoneUse($phone);
	public function post_curl($url, $params);
	public function regShopData($inputarr);
	public function getSeversByShopid($shopid,$role="server");
	public function checkPayerPwd($uid,$shopid,$passwd);
	public function getServername($shopid,$uid);
	public function updateCashierMan($shopid,$uid);
	public function getCashierMan($shopid);
	public function getAntiBillAndBill($shopid,$theday);
	public function getOneBillDataByBillid($billid);
	public function getOneCounponType($ctid);
	public function getDepositmoney($shopid);
	public function doCashiermanLogout($shopid);
	public function getTabChangedData($shopid,$theday);
	public function getCheckBillidByShopid($shopid);
	public function generPrintContent($deviceno, $devicekey, $datarr,$theday);
	public function generPrintSmallContent($deviceno, $devicekey, $datarr,$theday);
	public function getStableLenStr($str, $len);
	public function sendSelfFormatMessage($msgInfo);
	public function getAddFoodRecordData($shopid,$theday);
	public function getFoodtypenameByFtidarr($ftidarr);
	public function generFoodCalcPrintContent($deviceno, $devicekey, $inputarr);
	public function getUpdatestatusRecord($shopid,$theday);
	public function getRoletypeByRoleid($roleid);
	public function getDecreasenum($shopid);
	public function generTotalcalcPrintContent($deviceno, $devicekey, $inputarr);
	public function generTotalcalcPrintSmallContent($deviceno, $devicekey, $inputarr);
	public function generTotalFoodcalcPrintContent($deviceno, $devicekey, $inputarr);
	public function bindMyPhone($inputarr);
	public function getShopinfoData($shopid);
	public function changeTwoTable($tabid1,$tabid2,$shopid);
	public function getBillinfoByTabid($tabid,$shopid);
	public function intoChangeTabRecord($record);
	public function sendBookMsg($inputarr);
	public function generConsumeFoodPrintContent($deviceno, $devicekey, $inputarr);
	public function generConsumeFoodPrintSmallContent($deviceno, $devicekey, $inputarr);
	public function regCusinfo($inputarr);
	public function regCusinfoData($inputarr);
	public function regMyVipinfo($inputarr);
	public function getCheckCodeByphone($phone);
	public function judgeCardnoStatus($shopid,$phone);
	public function syncData($shopid);
	public function syncAllShopData();
	
}
?>