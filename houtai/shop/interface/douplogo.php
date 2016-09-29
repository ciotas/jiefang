<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpLogo{
	public function getLogoUpTime($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getLogoUpTime($shopid);
	}
	public function updateLogoData($shopid, $logourl, $timestamp) {
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateLogoData($shopid, $logourl, $timestamp);
	}
}
$douplogo=new DoUpLogo();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$client = createClient($keyId, $keySecret);
	$timestamp=time();
	$newkey = "logo/logo-".$shopid.$timestamp.".png";
	
	$logo=$_FILES['logo'];
	$tmpfile=$logo['tmp_name'];
	$tmpfile_size=$logo['size'];
	$tmpfile_type=$logo['type'];
	$tmpfile_error=$logo['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header('location: ../shopinfo.php?status=fail');
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header('location: ../shopinfo.php?status=fail');
			}
			$logouptime=$douplogo->getLogoUpTime($shopid);
			if(!empty($logouptime)){
				$oldkey="logo/logo-".$shopid.$logouptime.".png";
				deleteObject($client,$bucket,$oldkey);
			}
			//先删除旧的再更新				
			$douplogo->updateLogoData($shopid, $oss_base_url.$newkey,$timestamp);
			header('location: ../shopinfo.php?status=ok');
			exit ;
		}
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