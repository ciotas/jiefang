<?php 
interface ISendMsgDAL{
	public function writeShopCheckCodeToDB($phone,$checkcode,$timestamp);
	public function checkShopCode($phone,$checkcode,$timestamp);
	public function isRegisteredPhone($mobilphone);
	public function writeCusCheckCodeToDB($phone,$checkcode,$timestamp);
	public function checkCusCode($phone,$checkcode,$timestamp);
	public function isCusRegisteredPhone($telphone);
}
?>