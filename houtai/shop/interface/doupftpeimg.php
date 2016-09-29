<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpFtypeImg{
	public function getFootypeImgUptime($ftid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFootypeImgUptime($ftid);
	}
	public function updateFoodtypeData($ftid, $newfypepic,$timestamp){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->updateFoodtypeData($ftid, $newfypepic,$timestamp);
	}
}
$doupftimg=new DoUpFtypeImg();
if(isset($_POST['ftid'])){
	$shopid=$_SESSION['shopid'];
	$ftid=$_POST['ftid'];
	$foodtypepic=$_FILES['foodtypepic'];
	$client = createClient($keyId, $keySecret);
	$ftypeuptime=$doupftimg->getFootypeImgUptime($ftid);
	if(!empty($ftypeuptime)){
		$oldkey = "foodtype/".ftid.$ftypeuptime.".png";
		deleteObject($client,$bucket,$oldkey);
	}
	$timestamp=time();
	$newkey= "foodtype/".$ftid.$timestamp.".png";
	$tmpfile=$foodtypepic['tmp_name'];
	$tmpfile_size=$foodtypepic['size'];
	$tmpfile_type=$foodtypepic['type'];
	$tmpfile_error=$foodtypepic['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header("location: ../foodtype.php?status=fail");
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header("location: ../foodtype.php?status=fail");
			}
			$newfypepic=$oss_base_url.$newkey;
			$doupftimg->updateFoodtypeData($ftid, $newfypepic,$timestamp);
			header("location: ../foodtype.php?status=ok");
		}else{
			header("location: ../foodtype.php?status=formaterror");
		}
	}else{
		header("location: ../foodtype.php?status=imgerror");
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