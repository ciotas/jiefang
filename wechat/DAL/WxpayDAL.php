<?php
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/IDAL/IWxpayDAL.php');
require_once ('/var/www/html/DALFactory.php');


include_once("SDKRuntimeException.php");
include_once("WxPay.pub.config.php");
include_once("WxPayPubHelper.php");

class WxpayDAL implements IWxpayDAL {
//     private $apiurl = "http://test.meijiemall.com/wechat/interface/paydemo.php";
    public function jsApiCall($openid = '', $orderno = '', $orderfee = '', $attach = ''){
        $order_sn = $orderno;
        $this->write_logs('123aaaaaaaaaaaaaa');
        if (empty($order_sn) || empty($openid)) {
                //header('location:'.__ROOT__.'/');
        }
        $this-> write_logs("jsapicall=".$attach);
        $jsApi = new JsApi_pub();
        //$orderfee_fen = (int)($orderfee*100);
        $res = array('order_sn' => $order_sn, 'order_amount' => $orderfee);
        $unifiedOrder = new UnifiedOrder_pub();
        $total_fee = (int)($orderfee*100);
        //$total_fee = 0.01;
        $body = "订单支付{$res['order_sn']}";
        //$body = $attach;
        $unifiedOrder->setParameter("openid", "$openid");//用户标识
        $unifiedOrder->setParameter("body", $body);//商品描述
        $out_trade_no = $res['order_sn'];
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee", $total_fee);//总金额
        $this-> write_logs("jsapicall fee=".$total_fee);
        $unifiedOrder->setParameter("notify_url", WxPayConf_pub::NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("attach", $attach);//通知地址
        $this->write_logs('openid2='.$openid);
        $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型

        //$unifiedOrder->setParameter("sub_mch_id","1280193901");//子商户号
        $prepay_id = $unifiedOrder->getPrepayId();
        $this->write_logs('prepayid='.$prepay_id);
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        $wxconf = json_decode($jsApiParameters, true);
        $this->write_logs('wxconf='.$jsApiParameters);
        if ($wxconf['package'] == 'prepay_id=') {
                //error('当前订单存在异常，不能使用支付');
        }

        $this->write_logs('openid3='.$openid);
        $result = array();
        $result['orderno'] = $order_sn;
        $result['jsApiParameters'] = $jsApiParameters;
        $result['res'] = $res;
        return $result;
    }

    public function notifyUrl(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        $this-> write_logs("notifyUrl=".$xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code", "FAIL");//返回状态码
            $notify->setReturnParameter("return_msg", "签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
            $data = json_decode($notify->data["attach"], true);
            $notify->setReturnParameter("openid", $data["openid"]);
            $notify->setReturnParameter("orderno", $data["orderno"]);
            $notify->setReturnParameter("tabid", $data["tabid"]);
            $notify->setReturnParameter("billid", $data["billid"]);
            $notify->setReturnParameter("uid", $data["uid"]);
            $notify->setReturnParameter("shopid", $data["shopid"]);//设置返回码
            $notify->setReturnParameter("orderfee", $data["orderfee"]);//设置返回码
            $notify->setReturnParameter("orderrequest", $data["orderrequest"]);//设置返回码

        }
        $returnXml = $notify->returnXml();
        $this-> write_logs("notifyUrl2=".$returnXml);
        $this-> write_logs("notifyUrl3=".$notify->data["attach"]);
        //echo $returnXml;
        $parameter = $notify->xmlToArray($xml);
        $this->write_logs("[接收到的notify通知]: ".json_encode($parameter));
       $paystatus="error";
        if($notify->checkSign() == TRUE){
            if ($notify->data["return_code"] == "FAIL") {
               $this-> write_logs("[通信出错]");
                $this->write_logs($xml);
               $paystatus="error";
                echo 'error';
            }
            else if($notify->data["result_code"] == "FAIL"){
                $this->write_logs("[业务出错]");
               $this-> write_logs($xml);
               $paystatus="error";
                echo 'error';
            }
            else{
            	$paystatus="success";
            	echo 'success';
//                 if ($this->process($parameter)) {
//                     $this->write_logs("[支付成功]");
//                     $this->write_logs($xml);
//                     echo 'success';
//                 }else {
//                     echo 'error';
//                 }
            }
        }
        if($paystatus=="success"){
        	return $data;
        }else{
        	return array();
        }
        
    }


    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }

} 

