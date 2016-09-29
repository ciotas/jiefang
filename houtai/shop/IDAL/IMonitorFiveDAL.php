<?php 
interface IMonitorFiveDAL{
	public function getAllowinbalanceValue($shopid);
	public function clearOneTableStatus($tabid,$tabstatus);
	public function saveUpdateTabStatus($inputarr);
	public function getPayRecordData($inputarr);
	public function getTablenameByTabid($tabid);
	public function updateShopinfoData($inputarr);
	public function getMyShopinfoData($shopid);
	public function getShopImgUpTime($shopid,$op);
	public function updateShopimgData($shopid, $newshopimgpic,$timestamp,$op);
	public function upArticleData($shopid,$htmlData);
	public function getArticleByshopid($shopid);
	public function getFoodinfoByFoodid($foodid);
	public function updateTakeoutData($billid,$uid,$cusphone,$cusaddress);
	public function getTakeoutInfo($uid);
	public function getMyBillsDataByUid($uid,$shopid);
	public function getLogoUpTime($shopid);
	public function updateLogoData($shopid, $logourl,$timestamp);
	public function getPageviewDataByDay($shopid, $datearr);
	public function getTotalPageviewnum($shopid,$startdate,$enddate);
	public function getTabstatusByTabid($tabid);
	public function getTakeoutsheetData($shopid,$theday,$openhour,$op);
	public function updateTakeoutSheet($billid,$op);
	public function getMyOrdersByUid($uid,$op);
	public function switchBookFlag($tabid,$status);
	public function postBookData($inputarr);
	public function updateCusinfo($inputarr);
	public function getCusinfoByuid($uid);
	public function generPrintBookOrderContent($deviceno, $devicekey, $inputarr);
	public function getBooklistSheet($shopid,$theday,$op);
	public function updateBookStatusData($bookid,$op);
	public function getTodayBookData($shopid);
	public function getBookids($shopid,$theday);
	public function getAvilableTabs($shopid);
	public function ensureBookTab($bookid,$tabid,$op);
	public function getOneBookinfo($bookid);
	public function getMyBeforeOrdersByUid($uid);
	public function getMyBeforeBillsDataByUid($uid,$shopid);
	public function updateShopSwitch($shopid,$op, $status);
	public function getShopSetData($shopid);
	public function updatePicAddress($foodid,$foodpic);
	
	//获取商家营业时间段
	public function shopTimeSlot($shopid);
	//商家营业时间段增删改操作
	public function editShopTimeSlot($shopid,$id=NULL,$name=NULL,$starttime=NULL,$overtime=NULL);
	public function getOneTimeSlot($id);
	public function delOneTimeSlot($id);
	//获取外卖订单信息
	public function getShopTakeout($shopid,$date);
	public function getBillReceivedStatus($billid);
	//卖家接单拒单操作
	public function changeBillReceivedStatus($billid,$status);
	//获取卖家优惠设置
	public function getDiscount($shopid);
	//卖家优惠设置
	
	
	public function addDiscount($shopid,$data);
	public function delDiscount($discountid);
	//卖家配送费用设置
	public function getFare($shopid);
	public function addFare($shopid,$data);
	public function delFare($fareid);
}
?>