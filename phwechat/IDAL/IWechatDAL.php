<?php 
// require_once (_ROOT.'wechat/Model/User.php');
interface IWechatDAL{
	public function getPlus($a,$b);
	public function authorizeCode();
	public function getGetRequest($url = '');
	public function write_logs($content='');
	public function getShopinfo($shopid);
	public function getFoodCookData($foodid);
	public function getFoodsCookData($shopid);
	public function addPlatfromData($uid,$platform);
	public function getWechatUserinfo($uid);
	public function getPaySwitch($shopid);
	public function getUnscoredShopinfo($uid);
	public function judgeBillIsByScored($shopid,$uid,$billid);
	public function getShopinfoByShopid($shopid);
	public function addScoreData($inputarr);
    public function createMenu();
}
?>
