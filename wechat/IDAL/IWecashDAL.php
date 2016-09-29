<?php
// require_once (_ROOT.'wechat/Model/User.php');
interface IWecashDAL{
    public function payToMerchant($openid='', $casharr, $user_name='');
    public function getTixianRecord($shopid='');
    public function getBalance($openid = '');
    public function getOpenidFromCode($code = '');
    public function isBindShopOpenid($openid='');
}
?>
