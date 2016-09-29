<?php 
interface IMonitorFourDAL{
	public function getAutoStockFoods($shopid,$theday);
	public function saveAutoStockFood($inputarr);
	public function changeStockRecord($inputarr);
	public function getChangeStockRecord($shopid,$theday);
	public function getFoodInfoByFoodid($foodid);
	public function getOneStockData($shopid,$foodid);
	public function saveStockNumData($inputarr);
	public function getConsumeListData($shopid,$theday);
	public function getOpenHourByShopid($shopid);
	public function judgeAutoStock($foodid);
	public function getDayStockData($shopid,$theday,$thehour);
	public function getTotalStockAmountByDay($foodid,$thetime);
	public function getConsumeFoodAmountByFoodid($shopid,$foodid,$starttime,$endtime);
	public function generPrintAutostockContent($deviceno, $devicekey, $inputarr);
	public function generPrintAutostockSamllContent($deviceno, $devicekey, $inputarr);
	public function getStableLenStr($str, $len);
	public function addOneRawtype($inputarr);
	public function updateOneRawtype($rtnid,$inputarr);
	public function getRawtypeData($shopid);
	public function getOneRawtypenameByid($rtnid);
	public function delOneRawtypenameById($rtnid);
	public function getBeforeStarttimeSoldamount($shopid,$foodid,$starttime);
	public function addOneRawinfo($inputarr);
	public function updateOneRawinfo($rawid,$inputarr);
	public function getOneRawinfo($rawid);
	public function getRawsOrderByRawtype($shopid);
	public function getRawDataByRawtypeid($rawtypeid);
	public function delOnerawByRawid($rawid);
	public function getRawUpTime($rawid);
	public function updateRawData($rawid, $newrawpic,$timestamp);
	public function addRawamountRecord($inputarr);
	public function getRawRecordData($shopid,$theday);
	public function getRawsOrderByTime($shopid,$theyear,$themonth);
	public function getRawDataByRawtypeidAndTime($rawtypeid,$theyear,$themonth);
	public function getRawamountBytime($rawid,$theyear,$themonth);
	public function addRawleftamountData($inputarr);
	public function getRawleftData($rawid,$rawpackrate,$theyear,$themonth);
	public function getTotalmoneyBymonth($shopid,$theyear,$themonth);
	public function getRawLastInputprice($shopid,$rawid);
	public function getDayRawDetail($shopid,$theday);
	public function getRawsByRawtypeidAndTheday($rawtypeid,$theday);
	public function getRawsByRawidAndTheday($rawid,$theday);
	public function saveRawStorage($inputarr);
	public function getRawDataBymonth($rawid,$rawpackrate, $theyear,$themonth);
	public function generDayRawinPrintContent($deviceno, $devicekey, $inputarr);
	public function generRawStockPrintContent($deviceno, $devicekey, $inputarr);
	public function getOneRawinfoByday($rawid,$theday);
	public function getOneRawinfoByMonth($rawid,$theyear,$themonth);
	public function getOneStockInfo($foodid);
	public function getFisrtStockTime($shopid);
	public function getStockamountBeforeThetime($shopid,$foodid,$packrate,$thetime);
	public function getSumrawData($shopid,$startdate,$endate);
	public function generPrintCalcRawContent($deviceno,$devicekey,$inputarr);
	public function getStockCalcData($shopid, $startdate, $endate);
	public function generPrintStockCalcContent($deviceno,$devicekey,$inputarr);
	public function getAutostockFoodsoldnumByfoodid($shopid,$theday,$foodid);
	//酒水出库记录
	public function getOutgoing($shopid,$ym);
	//添加入库记录
	public function addStockin($inputarr);
	public function getStockin($shopid,$theday);
	public function getServerBill($shopid,$datestart,$dateover,$uid = NULL);
	public function getServers($shopid);
	public function dealBillToServer($bill,$food,$uid=NULL);
	public function addMonthStock($shopid,$foodid,$month,$num);
	//根据shopid获取店内全部商品
	public function getFoodByShopid($shopid);
	public function getMonthStock($foodid);
	public function getOneMonth($foodid,$month);
	//根据billid 修改订单的收货信息
	public function updateBillShopInfoByBillid($billid,$info);
}
?>