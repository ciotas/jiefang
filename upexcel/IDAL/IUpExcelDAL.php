<?php 
interface IUpExcelDAL{
	public function getFoodInfo($shopid);
	public function getFoodTypeList($shopid);
	public function getPrinterList($shopid);
	public function getZoneList($shopid);
	public function xlsDataToDb($shopid, $foodarr);
	public function getPrinterIdByZoneId($zoneid);
	public function object_to_array($obj);
}
?>