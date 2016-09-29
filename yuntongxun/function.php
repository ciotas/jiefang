<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
include_once(DOCUMENT_ROOT."SDK/CCPRestSDK.php");

/**
 * 发送模板短信
 * @param to 手机号码集合,用英文逗号分开
 * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
 * @param $tempId 模板Id
 */
function sendTemplateSMS($to,$datas,$tempId)
{
	// 初始化REST SDK
	global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
	$rest = new REST($serverIP,$serverPort,$softVersion);
	$rest->setAccount($accountSid,$accountToken);
	$rest->setAppId($appId);

	// 发送模板短信
// 	echo "Sending TemplateSMS to $to <br/>";
	$result = $rest->sendTemplateSMS($to,$datas,$tempId);
	$status="";
	if($result == NULL ) {
// 		echo "result error!";
		return "error";
// 		break;
     }
     if($result->statusCode!=0) {
     	return  "error";
//     	 return $result->statusMsg;
//      echo "error code :" . $result->statusCode . "<br>";
//      echo "error msg :" . $result->statusMsg . "<br>";
     		//TODO 添加错误处理逻辑
     }else{
//      echo "Sendind TemplateSMS success!<br/>";
     	// 获取返回信息
    	 return "ok";
//          $smsmessage = $result->TemplateSMS;
//      	echo "dateCreated:".$smsmessage->dateCreated."<br/>";
//          echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
     }
}
?>