<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpShopImg{
	public function updateShopimgData($shopid, $newshopimgpic, $timestamp, $op){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateShopimgData($shopid, $newshopimgpic, $timestamp, $op);
	}
	public function getShopImgUpTime($shopid,$op){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getShopImgUpTime($shopid, $op);
	}
}
$doupshopimg=new DoUpShopImg();
if(isset($_POST['op'])){
	$shopid=$_SESSION['shopid'];
	$shopimg=$_FILES['shopimg'];
	$op=$_POST['op'];
	$client = createClient($keyId, $keySecret);
	$foodimgtime=$doupshopimg->getShopImgUpTime($shopid,$op);
	if(!empty($foodimgtime)){
		$oldkey = "logo/".$shopid.$foodimgtime.".png";
		deleteObject($client,$bucket,$oldkey);
	}
	$timestamp=time();
	$newkey= "logo/".$shopid.$timestamp.".png";
	$tmpfile=$shopimg['tmp_name'];
	$tmpfile_size=$shopimg['size'];
	$tmpfile_type=$shopimg['type'];
	$tmpfile_error=$shopimg['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header("location: ../shopinfo.php?status=fail");
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header("location: ../shopinfo.php?status=fail");
			}
			$newshopimgpic=$oss_base_url.$newkey;
			$doupshopimg->updateShopimgData($shopid, $newshopimgpic,$timestamp,$op);
			header("location: ../shopinfo.php?status=ok");
		}else{
			header("location: ../shopinfo.php?status=formaterror");
		}
	}else{
		header("location: ../shopinfo.php?status=imgerror");
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