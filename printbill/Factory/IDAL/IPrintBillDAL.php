<?php 
interface IPrintBillDAL{
	public function tobeRunner($inputdarr);
	public function tobeCusList($inputdarr);
	public function findThePrinter($shopid,$outputtype);
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney);
	public function orderByprinterid($inputdarr);
	public function toBeWaiting($arr);
	public function getTheBillSortNum($shopid);
	public function findTheChuanCaiPrinter($tabid,$outputtype);
	public function getPrinterIdByZoneid($zoneid,$printertype);
	public function getPrinterInfoByPid($pid);
	public function getTabnameByTabid($tabid);
	public function getPrinteridByFtid($ftid);
	public function getPrinteridByTabid($tabid);
	public function getPrinterInfoByType($shopid,$outputtype);
	public function isTherVirtualTab($tabid);
	public function getPrebillByBillid($billid);
	public function getOneDesposit($billid);
	public function getDepositmoney($shopid);
	public function judgeAutoStock($foodid);
}
?>