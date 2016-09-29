<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpRawImg{
	public function getRawUpTime($rawid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawUpTime($rawid);
	}
	public function updateRawData($rawid, $newrawpic,$timestamp){
		QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->updateRawData($rawid, $newrawpic, $timestamp);
	}
}
$douprawimg=new DoUpRawImg();
if(isset($_POST['rawid'])){
	$shopid=$_SESSION['shopid'];
	$rawid=$_POST['rawid'];
	$rawpic=$_FILES['rawpic'];
	$typeno=$_POST['typeno'];
	$client = createClient($keyId, $keySecret);
	$rawuptime=$douprawimg->getRawUpTime($rawid);
	if(!empty($rawuptime)){
		$oldkey = "rawpic/".$rawid.$rawuptime.".png";
		deleteObject($client,$bucket,$oldkey);
	}
	$timestamp=time();
	$newkey= "rawpic/".$rawid.$timestamp.".png";
	$tmpfile=$rawpic['tmp_name'];
	
	$tmpfile_size=$rawpic['size'];
	$tmpfile_type=$rawpic['type'];
	$tmpfile_error=$rawpic['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header("location: ../stock/rawinfo.php?status=fail&typeno=$typeno");
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header("location: ../stock/rawinfo.php?status=fail&typeno=$typeno");
			}
			$newrawpic=$oss_base_url.$newkey;
			$douprawimg->updateRawData($rawid, $newrawpic,$timestamp);
			header("location: ../stock/rawinfo.php?status=ok&typeno=$typeno");
		}else{
			header("location: ../stock/rawinfo.php?status=fail&typeno=$typeno");
		}
	}else{
		header("location: ../stock/rawinfo.php?status=fail&typeno=$typeno");
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
			'ContentType'=>"image/png"
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