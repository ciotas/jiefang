<?php 
interface IHandleDAL{
	public function getType($json);
	public function intoConsumeRecord($inputdarr);
	public function intoBillRecord($inputdarr,$billstatus);
	public function getTodayCuslist($shopid);
	public function getCusinfo($uid,$shopid);
	public function getVipname($shopid,$uid);
	public function getBillData($billid);
	public function getBillDataByView($uid,$shopid);
// 	public function getOldPrice($foodid);
	public function switchTable($billid, $tabname);
	public function ChangeTableSheetContent($inputarr);
	public function getSureTableSheetContent($inputarr);
	public function getApiCodeAry($shopid);
	public function getPrintContent($inputarr,$arr);
	public function getSureTabPrintContent($inputarr,$arr);
	public function getPrinterZoneName($shopid,$deviceno);
	public function getLastBillData($uid);
	public function returnCoupon($uid,$shopid,$couponvalue,$couponnum);
	public function getthumbLogo($shopid);
	public function reOrderFoodList($arr);
	public function reOrderService($arr);
	public function getShopBriefInfo($shopid);
	public function getShopAllPrinters($shopid);
	public function getShopAddress($shopid);
	public function addPointsToUser($uid,$shopid,$paymoney);
	public function getNameRemark($uid, $shopid);
	public function getAllShopId();
	public function finishPayStatus($billid,$paymoney,$paytype,$coupontype,$coupontypevalue,$coupontypenum,$status);
	public function RmOneBillData($billid);
	public function updateBillPayStatus($billid,$status,$paystatus);
	public function getOneBillById($billid);
	public function judgeTheServer($shopid,$uid);
	public function updateTabStatus($shopid,$tabname,$usestatus);
	public function getPointSetByShopid($shopid);
	public function getShopPointSet($shopid);
	public function getPoints($shopid,$uid);//获取积分
	public function MinusMyPoint($uid,$shopid,$minuspoint);
	public function getFoodnameByFid($foodid);
	public function comBineBill($billid,$uid);
	public function comBineBillTogether($billid,$oldbillid);
	public function IntoTheOldBill($billid,$oldbillid,$dataarr);
	public function calcPayMoney($foodarr,$servicearr,$discountmoney);
	public function changeBillStatus($billid,$paystatus,$paytype);
	public function getPayMoneySet($shopid);
	public function updateBillFoodByBillid($billid,$totalmoney,$paymoney,$disacountfoodmoney,$oldfoodarr,$foodarr);
	public function intoBillFood($billid,$foodarr);
	public function getZoneNameByZid($zid);
	public function updateFoodBill($billid,$arr);
	public function getPrinters($shopid);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getTwoBillidByTabname($tabname,$shopid);
}
?>