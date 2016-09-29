<?php 
interface IMonitorCusDAL{
	public function getCusConsumeInfo($shopid,$theday);
	public function getWechatUserinfo($uid);
	public function getSexPercentRate($inputarr);
	public function getPlatformPercent($inputarr);
	public function getCityPercet($inputarr);
	public function getFoodpicsData($searchfoodname,$p,$pagenum);
}
?>