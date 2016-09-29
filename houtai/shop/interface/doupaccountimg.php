<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpAccountImg{
	public function getUpTime($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getUpTime($shopid);
	}
	public function updateAccountData($shopid, $op,$newpic, $timestamp) {
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateAccountData($shopid, $op, $newpic, $timestamp);
	}
}
$doupaccountimg=new DoUpAccountImg();
if(isset($_POST['op'])){
	$op=$_POST['op'];
	$shopid=$_SESSION['shopid'];
	$pic=$_FILES[$op];
	$client = createClient($keyId, $keySecret);
	$uptime=$doupaccountimg->getUpTime($shopid);
	if(!empty($uptime)){
		$oldkey = "certificate/".$shopid.$uptime.".jpg";
		deleteObject($client,$bucket,$oldkey);
	}
	$timestamp=time();
	$newkey= "certificate/".$shopid.$timestamp.".jpg";
	$tmpfile=$pic['tmp_name'];
	$tmpfile_size=$pic['size'];
	$tmpfile_type=$pic['type'];
	$tmpfile_error=$pic['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header("location: ../personinfo.php?status=1");exit;
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header("location: ../personinfo.php?status=2");exit;
			}
			$newpic=$oss_base_url.$newkey;
			$doupaccountimg->updateAccountData($shopid,$op, $newpic,$timestamp);
			header("location: ../personinfo.php?status=3");exit;
		}else{
			header("location: ../personinfo.php?status=4");exit;
		}
	}else{
		header("location: ../personinfo.php?status=5");exit;
	}
}
exit;
// Sample of create client
function createClient($accessKeyId, $accessKeySecret) {
	$client= OSSClient::factory(array(
			'AccessKeyId' => $accessKeyId,
			'AccessKeySecret' => $accessKeySecret,
	));
	return $client;
}

// Sample of put object from resource
function putResourceObject(OSSClient $client, $bucket, $key, $content, $size) {
	$result = $client->putObject(array(
			'Bucket' => $bucket,
			'Key' => $key,
			'Content' =>fopen($content, "r"),
			'ContentLength' => $size,
			'ContentType'=>"image/jpeg"
	));
	return $result->getETag();
}
function deleteObject(OSSClient $client,$bucket,$key){
	$client->deleteObject(array(
			'Bucket' => $bucket,
			'Key' =>$key,
	));
}
?>