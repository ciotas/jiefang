<?php 
interface IAdminOneDAL{
	public function DoLogin($useremail, $password);
	public function isShopphoneReg($shopphone);
	public function addToDonateAccount($inputarr);
	public function getShopuseaccountEndtime($shopid);
	public function getAllOnLineShop($mobile = NULL);
	public function updateShopSwitch($shopid,$type,$status);
	public function getStaticsData($starttime);
	public function getOnlineData();
	public function getBillCountByDay($shopid,$theday,$openhour);
	public function getOpenHourByShopid($shopid);
	public function getTheday($shopid);
	public function getPrintersStatus();
	public function queryPrinterStatus($device_no,$device_key);
	public function getShopinfo($shopid);
	public function getCusFlowData($datearr);
	public function getMoneyFlowData($datearr);
	public function getBillnumFlowData($datearr);
	public function getBusinessZoneData();
	public function addBusizoneData($inputarr);
	public function updateBusiZoneData($inputarr,$busi_zoneid);
	public function getOneBusizoneData($busi_zoneid);
	public function delOneBusizoneData($busi_zoneid);
	public function addShopToBusiZoneid($shopid,$busi_zoneid);
	public function addOneGoodsTypeData($inputarr);
	public function updateOneGoodsTypeData($goodstypeid,$inputarr);
	public function getOneGoodsTypeData($goodstypeid);
	public function getGoodsTypeData();
	public function delOneGoodsTypeData($goodstypeid);
	public function getOneGoodsData($goodsid);
	public function getGoodsData();
	public function addGoodsData($inputarr);
	public function updateGoodsData($goodsid,$inputarr);
	public function delOneGoodsData($goodsid);
	public function getGoodsPicUpTime($goodsid);
	public function updateGoodsImgData($goodsid, $newgoodspic,$timestamp);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getQuestionData();
	public function updateQuestion($id,$arr);
	public function delOneQuestion($id);
	//随机获得一个题目
	public function getOneQuestion($id=NULL);
	public function addOneQuestion($arr);
	//获取中奖数据
	public function getPrizeData();
	//tansfer转账记录操作
	public function addOneTransferLog($arr);
	public function getTransferLog($where = null);
	public function delOneTransferLog($id);
	public function getCodeLog($mobile = null);
	//获取用户列表
	public function getUserList();
	//获取微信点餐商家日账单
	public function getDayReport($day=NULL);
	//获取商家日销售额
	public function getDayReportByDay($shopid, $theday,$openhour);
	//通过商家ID获取商家的信息
	public function getShopInfoById($shopid);
	//获取转账状态
	public function getTransferState($shopid,$day);
	public function getOnWechatShop();
	public function getShopaccountByShopid($shopid);
	public function initShopAccontMoney($inputarr);
	//打印机监控
	public function controlPrinter($shopinfo);
	//菜单监控
	public function controlFood($shopinfo);
	//获取所有的店铺信息数组以店铺shopid为键
	public function getAllShopinfo();
	//分类监控
	public function controlFoodType($shopinfo);
	
	//获取微信点餐营业走势图
	public function getReport();
	//根据订单号获取信息
	public function getShopInfoByOrderno($orderno);
}
?>
