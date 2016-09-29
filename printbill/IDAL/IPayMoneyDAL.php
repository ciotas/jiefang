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
   public function addBalance($shopid,$paymoney);
   //变更月度库存
   public function changeMonthStock($foodid,$num);
   public function getShopidByFoodid($foodid);
}
?>