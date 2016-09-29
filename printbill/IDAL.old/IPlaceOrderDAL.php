<?php 
interface IPlaceOrderDAL{
	public function getBillById($billid);
	public function rolling_curl($urls, $delay);
	public function getUrlsArr($json);
	public function callback($data, $delay);
	public function getRePrintContent($nullarr,$urls);
	public function updateBillStatus($billid,$billstatus);
	//打印
	public function sendFreeMessage($url);
	public function sendMessage($msgInfo);

	public function getSendMsg($shopid,$uid,$totalmoney,$paytype,$disacountfoodmoney,$discountvalue,$type,$discountzong,$paymoney,$time);//发送消息的内容
	public function getCouponLimit($shopid);
	public function getOwnCouponNum($uid, $shopid,$time);
	public function getShopPoint($shopid);
	public function getOwnPoint($uid);
}
?>