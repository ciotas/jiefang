<?php
interface IKitchenWorkerDAL{
	public function PrintKitchenData($json);
	public function create_F_ContentHtml($deviceno,$devicekey,$arr);
	public function create_F_SmallContentHtml($deviceno,$devicekey,$arr);
	public function create_FZ_ContentHtml($deviceno,$devicekey,$arr);
	public function create_FZ_SmallContentHtml($deviceno,$devicekey,$arr);
	public function getTabnameByTabid($tabid);
	public function create_Total_ContentHtml($arr,$deviceno,$devicekey);
	public function create_Total_SmallContentHtml($arr,$deviceno,$devicekey);
	public function getBillnumToday($shopid);
	public function getTheday($shopid);
	public function getOpenHourByShopid($shopid);
}
?>