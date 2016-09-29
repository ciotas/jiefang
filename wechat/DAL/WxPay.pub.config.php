<?php
/**
* 	配置账号信息
*/

class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	//const APPID = 'wxe1aa8d53b1d87662';
	const APPID = 'wxdda2d472561e3e3c';
	//受理商ID，身份标识
	//const MCHID = '1260051101';
	const MCHID = '10024436';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	//const KEY = 'E5FD581636734ACEBADD5B77B7F526A1';
	const KEY = '953885DC58534F9D9D3625C48E038107';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	//const APPSECRET = '5d8d5eb683c472bb6fb2a888735d074e';
	const APPSECRET = 'a03d0092c7c5bce975f9a93a89d25287';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = '';
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = '/var/www/html/wechat/DAL/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = '/var/www/html/wechat/DAL/cacert/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://shop.meijiemall.com/wechat/interface/notify_url.php';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>
