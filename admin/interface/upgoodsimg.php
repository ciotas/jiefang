<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/oss/aliyun.php');
use Aliyun\OSS\OSSClient;
class DoUpImg{
	public function getGoodsPicUpTime($goodsid){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getGoodsPicUpTime($goodsid);
	}
	public function updateGoodsData($goodsid, $newgoodspic,$timestamp){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->updateGoodsImgData($goodsid, $newgoodspic, $timestamp);
	}
}
$doupimg=new DoUpImg();
if(isset($_POST['goodsid'])){
	$goodsid=$_POST['goodsid'];
	$sortno=$_POST['sortno'];	
	$goodspic=$_FILES['goodspic'];
	$client = createClient($keyId, $keySecret);
	$goodsuptime=$doupimg->getGoodsPicUpTime($goodsid);
	if(!empty($goodsuptime)){
		$oldkey = "goods/".$goodsid.$goodsuptime.".jpg";
		deleteObject($client,$bucket,$oldkey);
	}
	$timestamp=time();
	$newkey= "goods/".$goodsid.$timestamp.".jpg";
	$tmpfile=$goodspic['tmp_name'];
	$tmpfile_size=$goodspic['size'];
	$tmpfile_type=$goodspic['type'];
	$tmpfile_error=$goodspic['error'];
	if(!$tmpfile_error){
		if ((($tmpfile_type == 'image/gif') || ($tmpfile_type == 'image/webp') || ($tmpfile_type == 'image/jpeg') || ($tmpfile_type == 'image/pjpeg') ||
				($tmpfile_type == 'image/png')) && ($tmpfile_size > 0)){
			try {
				putResourceObject($client, $bucket, $newkey, $tmpfile, $tmpfile_size);
			}
			catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
				header("location: ../goods.php?status=fail&typeno=".$sortno);
			} catch (\Aliyun\Common\Exceptions\ClientException $ex) {
				header("location: ../goods.php?status=fail&typeno=".$sortno);
			}
			$newgoodspic=$oss_base_url.$newkey;
			$doupimg->updateGoodsData($goodsid, $newgoodspic,$timestamp);
			header("location: ../goods.php?status=ok&typeno=".$sortno);
		}else{
			header("location: ../goods.php?status=formaterror&typeno=".$sortno);
		}
	}else{
		header("location: ../goods.php?status=imgerror&typeno=".$sortno);
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