<?php
/**
 * Created by PhpStorm.
 * User: wangjj
 * Date: 6/10/16
 * Time: 16:57
 */

interface IWecashBLL{
    public function payToMerchant($openid='', $casharr, $user_name='');
    public function getTixianRecord($shopid='');
    public function getBalance($openid = '');
    public function getOpenidFromCode($code = '');
    public function isBindShopOpenid($openid='');
}
