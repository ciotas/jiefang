<?php 
interface IPieceListDAL{ 
	public function tobePieceList($foodarr);
	public function getOutPutType($printerid);
	public function fendanList($op="single",$printerid,$printertype,$deviceno,$devicekey,$orderfoodarr, $valarr);
	public function fenzongList($deviceno, $devicekey,$printertype,$orderfoodarr, $valarr);
	public function doubleList($op="double",$printerid,$deviceno,$devicekey,$printertype,$orderfoodarr, $valarr);
	public function TotalList($op="total",$printerid,$deviceno,$devicekey,$printertype,$orderfoodarr, $valarr);
}
?>