<?php 
interface IToBePointDAL{
    public function getThePointPrinter($inputarr,$shopid,$foodid);
    public function getPrinterInfoByFoodid($foodid);
    public function getApiCodeByPrinterid($printerid);
    public function getPrintContent($inputarr,$printerinfoarr);
    public function findThePrinter($shopid, $outputtype);
    public function getTobePointOther($inputarr);
} 
?>