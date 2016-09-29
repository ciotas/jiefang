<?php 
// require_once (_ROOT.'wechat/Model/User.php');
interface IWechatBLL{
	public function getPlus($a,$b);
	public function authorizeCode();
	public function getGetRequest($url = '');
	public function write_logs($content='');
	public function getShopinfo($shopid);
	public function getFoodCookData($foodid);
	public function getFoodsCookData($shopid);
	public function addPlatfromData($uid, $platform);
	public function getWechatUserinfo($uid);
	public function getPaySwitch($shopid);
	public function isShowScorePage($uid);
	public function getShopinfoByShopid($shopid);
	public function addScoreData($inputarr);
	public function createMenu();
    public function getPostRequest($url='', $data);
	// 获取桌台号码
	public function getTabNum($tabid);
	public function getByCusnum($shopid);
}
?>
