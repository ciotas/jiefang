<?php 
interface IWaitingDAL{
	public function PrintWaitingData($type,$json);
	public function createContentHtml($arr);
	public function getStableLenStr($str, $len);
}
?>