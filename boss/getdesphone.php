<?php 
require_once ('/var/www/html/boss/global.php');
require_once ('/var/www/html/des.php');
if(isset($_GET['phone'])&&isset($_GET['sid'])){
    $phone=$_GET['phone'];
    $phonecrypt= new CookieCrypt($phonekey);
    $mobilphone=$phonecrypt->encrypt($phone);
    $timestamp=time();
    $signature=strtoupper(md5($mobilphone.$timestamp.$token));
    $params=array("phone"=>$mobilphone,"timestamp"=>$timestamp,"signature"=>$signature);
    $url=$sendcodeurl;
	$ch = curl_init();//初始化curl
	curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
	curl_setopt($ch, CURLOPT_PORT,80);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_TIMEOUT,300);
	$data = curl_exec($ch);//运行curl
	curl_close($ch);
	return $data;
}
?>