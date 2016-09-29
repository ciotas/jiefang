<?php
interface IWxpayDAL{
    public function jsApiCall($openid = '', $orderno = '', $orderfee = '', $attach = '');
    public function notifyUrl();
    public function write_logs($content = '');
}
?>
