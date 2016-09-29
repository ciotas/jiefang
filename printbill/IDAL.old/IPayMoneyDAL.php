<?php 
interface IPayMoneyDAL{
    public function updateCommonPayData($inputarr);
    public function updateSignPayData($inputarr);
    public function updateFreePayData($inputarr);
    public function judgeOneAntiBillExistByBillid($billid);
    public function getOneBillInfoByBillid($billid);
    public function getPackHistoryData($billid,$pkid);
    public function updateSelfStock($billid);
    public function judgeAutoStock($foodid);
   public function updateSelfStocknum($foodid,$foodamount);
}
?>