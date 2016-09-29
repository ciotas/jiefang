<?php 
interface IPayDAL{
	public function addPayRecord($inputarr);
	public function getPayRecordData($trade_no,$billid);
	public function getShopInfo($shopid);
	public function getTablenameByTabid($tabid);
	public function getYearfeeMoney($shopid);
	public function addBuyGoodsRecord($inputarr);
	public function getOneBuyGoodsRecordData($id);
}
?>