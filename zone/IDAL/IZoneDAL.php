<?php  
interface IZoneDAL{ 
	public function getZoneByShopid($shopid);
	public function delZone($zoneid);
	public function saveZone($inputarr);
	public function getPrinterByPid($printerid);
	public function updateZoneData($zoneid,$op,$newval);
	public function getOneZoneData($zoneid,$token);
	public function changeZoneSort($zoneno);
	public function array_sort($arr, $keys, $type = 'asc');
}
?>