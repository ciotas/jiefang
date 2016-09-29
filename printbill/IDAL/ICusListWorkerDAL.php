<?php 
interface ICusListWorkerDAL{
	public function printCuslistData($type);
	public function createContentHtml($arr,$deviceno,$devicekey);
	public function outPutHtml($apicode,$html);
	public function getStableLenStr($str, $len);
	public function geTakeoutAddress($uid);
	public function getCusSheetAdv($shopid);
	public function getAdvUrlByAdvid($advid);
	public function getMenuMoney($shopid);
	public function createSmallContentHtml($arr,$deviceno,$devicekey);
}
?>