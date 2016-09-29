<?php 
interface IRunnerWorkerDAL{
	public function printChuanCaiData($type);
	public function createContentHtml($arr,$deviceno,$devicekey);
	public function outPutHtml($apicode,$html);
	public function getTabnameByTabid($tabid);
	public function createSmallContentHtml($arr,$deviceno,$devicekey);
}
?>