<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpImg{
	public function getFoodUpTime($foodid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodUpTime($foodid);
	}
	public function updateFoodData($foodid, $newfoodpic,$timestamp){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->updateFoodData($foodid, $newfoodpic, $timestamp);
	}
}
$doupimg=new DoUpImg();
if(isset($_POST['foodid'])){
	$foodid=$_POST['foodid'];
	$sortno=$_POST['sortno'];	
	$foodpic=$_FILES['foodpic'];
	$client = createClient($keyId, $keySecret);
	$fooduptime=$doupimg->getFoodUpTime($foodid);
	if(!empty($fooduptime)){
		$oldkey = "food/".$foodid.$fooduptime.".png";
		deleteObject($client,$bucket,$oldkey);
	}
	$timestamp=time();
	$newkey= "food/".$foodid.$timestamp.".png";
	$tmpfile=$foodpic['tmp_name'];
	$tmpfile_size=$foodpic['size'];
	$tmpfile_type=$foodpic['type'];
	$tmpfile_error=$foodpic['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header("location: ../upfoodpic.php?status=fail&sortno=".$sortno);
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header("location: ../upfoodpic.php?status=fail&sortno=".$sortno);
			}
			$newfoodpic=$oss_base_url.$newkey;
			$doupimg->updateFoodData($foodid, $newfoodpic,$timestamp);
			header("location: ../upfoodpic.php?status=ok&sortno=".$sortno);
		}else{
			header("location: ../upfoodpic.php?status=formaterror&sortno=".$sortno);
		}
	}else{
		header("location: ../upfoodpic.php?status=imgerror&sortno=".$sortno);
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