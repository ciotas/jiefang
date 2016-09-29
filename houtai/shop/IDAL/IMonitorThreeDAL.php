<?php 
interface IMonitorThreeDAL{
	public function updateFoodtypeDonateticket($ftid,$donateticket);
	public function getFoodtypesByShopid($shopid);
	public function array_sort($arr, $keys, $type = 'asc');
	public function saveRuleData($inputarr);
	public function getAllDonateticketData($shopid);
	public function getOneDonateTicketRule($ruleid);
	public function updateDonateticketData($ruleid,$inputarr);
	public function delOneDonateticketData($ruleid);
	public function isInDonateticketTable($shopid);
	public function saveDonateticketContent($inputarr);
	public function getDonateticketTips($shopid);
	public function delDonateticketTips($inputarr);
	
}
?>