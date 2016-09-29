<?php 
require_once ('/var/www/html/yuntongxun/global.php');
require_once (DOCUMENT_ROOT.'IDAL/ISendMsgDAL.php');
require_once ('/var/www/html/DALFactory.php');
class SendMsgDAL implements ISendMsgDAL{
	private static $shopcheckcode="shopcheckcode";
	private static $cuscheckcode="cuscheckcode";
	private static $shopinfo="shopinfo";
	private static $customer="customer";
	/* (non-PHPdoc)
	 * @see ISendMsgDAL::writeCheckCodeToDB()
	 */
	public function writeShopCheckCodeToDB($phone, $checkcode,$timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("phone"=>$phone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopcheckcode)->findOne($qarr,$oparr);
		if(is_array($result)&&!empty($result)){//存在就更新
			$qarr=array("_id"=>$result['_id']);
			$oparr=array("\$set"=>array("checkcode"=>$checkcode,"timestamp"=>$timestamp));
			DALFactory::createInstanceCollection(self::$shopcheckcode)->update($qarr,$oparr);
		}else{//插入
			$arr=array("phone"=>$phone,"checkcode"=>$checkcode,"timestamp"=>$timestamp);
			DALFactory::createInstanceCollection(self::$shopcheckcode)->insert($arr);
		}
	}
	/* (non-PHPdoc)
	 * @see ISendMsgDAL::checkShopCode()
	 */
	public function checkShopCode($phone, $checkcode, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("phone"=>$phone,"checkcode"=>$checkcode, "timestamp"=>array("\$gte"=>$timestamp));
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopcheckcode)->findOne($qarr,$oparr);
		if(!empty($result)){
			return "1";
		}else{
			return "0";
		}
	}
	/* (non-PHPdoc)
	 * @see ISendMsgDAL::writeCusCheckCodeToDB()
	 */
	public function writeCusCheckCodeToDB($phone, $checkcode, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("phone"=>$phone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$cuscheckcode)->findOne($qarr,$oparr);
		if(is_array($result)&&!empty($result)){//存在就更新
			$qarr=array("_id"=>$result['_id']);
			$oparr=array("\$set"=>array("checkcode"=>$checkcode,"timestamp"=>$timestamp));
			DALFactory::createInstanceCollection(self::$cuscheckcode)->update($qarr,$oparr);
		}else{//插入
			$arr=array("phone"=>$phone,"checkcode"=>$checkcode,"timestamp"=>$timestamp);
			DALFactory::createInstanceCollection(self::$cuscheckcode)->insert($arr);
		}
	}

	/* (non-PHPdoc)
	 * @see ISendMsgDAL::checkCusCode()
	 */
	public function checkCusCode($phone, $checkcode, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("phone"=>$phone,"checkcode"=>$checkcode, "timestamp"=>array("\$gte"=>$timestamp));
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$cuscheckcode)->findOne($qarr,$oparr);
		if(!empty($result)){
			return "1";
		}else{
			return "0";
		}
	}
	/* (non-PHPdoc)
	 * @see ISendMsgDAL::isCusRegisteredPhone()
	 */
	public function isCusRegisteredPhone($telphone) {
		// TODO Auto-generated method stub
		$qarr=array("telphone"=>$telphone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			return "1";
		}else{
			return "0";
		}
	}
	/* (non-PHPdoc)
	 * @see ISendMsgDAL::isRegisteredPhone()
	 */
	public function isRegisteredPhone($mobilphone) {
		// TODO Auto-generated method stub
		$qarr=array("mobilphone"=>$mobilphone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return "1";
		}else{
			return "0";
		}
	}


}
?>
