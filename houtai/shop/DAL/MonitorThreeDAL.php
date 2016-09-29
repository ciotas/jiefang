<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorThreeDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once ('/var/www/html/des.php');
class MonitorThreeDAL implements IMonitorThreeDAL{
	private static $food="food";
	private static $foodtype="foodtype";
	private static $donateticket_rule="donateticket_rule";
	private static $donateticket="donateticket";
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::updateFoodtypeDonateticket()
	 */
	public function updateFoodtypeDonateticket($ftid, $donateticket) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("\$set"=>array("donateticket"=>$donateticket));
		DALFactory::createInstanceCollection(self::$foodtype)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::getFoodtypesByShopid()
	 */
	public function getFoodtypesByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "foodtypename"=>1,"foodtypecode"=>1,"sortno"=>1,"donateticket"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if($val['donateticket']=="1"){$donateticket="1";}else{$donateticket="0";}
			$arr[]=array(
					"ftid"=>strval($val['_id']),
					"ftname"=>$val['foodtypename'],
					"ftcode"=>$val['foodtypecode'],
					"sortno"=>$val['sortno'],
					"donateticket"=>$donateticket,
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::array_sort()
	 */
	public function array_sort($arr, $keys, $type = 'asc') {
		// TODO Auto-generated method stub
		$keysvalue = $new_array = array();
		foreach ($arr as $k => $v) {
			$keysvalue[$k] = $v[$keys];
		}
		if ($type == 'asc') {
			asort($keysvalue);
		} else {
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k => $v) {
			$new_array[] = $arr[$k];
		}
		return $new_array;
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::saveRuleData()
	 */
	public function saveRuleData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array(
				"shopid"=>$inputarr['shopid'],
				"fullmoney"=>$inputarr['fullmoney'],
				"sendmoney"=>$inputarr['sendmoney'],
		);
		DALFactory::createInstanceCollection(self::$donateticket_rule)->save($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::getAllDonateticketData()
	 */
	public function getAllDonateticketData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "fullmoney"=>1,"sendmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket_rule)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"ruleid"=>strval($val['_id']),
					"fullmoney"=>$val['fullmoney']	,
					"sendmoney"=>$val['sendmoney'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::getOneDonateTicketRule()
	 */
	public function getOneDonateTicketRule($ruleid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ruleid));
		$oparr=array("fullmoney"=>1,"sendmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket_rule)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("ruleid"=>$ruleid, "fullmoney"=>$result['fullmoney'],"sendmoney"=>$result['sendmoney']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::updateDonateticketData()
	 */
	public function updateDonateticketData($ruleid, $inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ruleid));
		$oparr=array(
				"\$set"=>array(
						"fullmoney"=>$inputarr['fullmoney'],
						"sendmoney"=>$inputarr['sendmoney'],
				)	
		);
		DALFactory::createInstanceCollection(self::$donateticket_rule)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::delOneDonateticketData()
	 */
	public function delOneDonateticketData($ruleid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ruleid));
		DALFactory::createInstanceCollection(self::$donateticket_rule)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::isInDonateticketTable()
	 */
	public function isInDonateticketTable($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::saveDonateticketContent()
	 */
	public function saveDonateticketContent($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['shopid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$push"=>array("content"=>array("tipcontent"=>$inputarr['tipcontent'],"tipswitch"=>$inputarr['tipswitch'],"sortno"=>$inputarr['sortno'])));
			DALFactory::createInstanceCollection(self::$donateticket)->update($qarr,$oparr);
		}else{
			$arr=array(
					"shopid"=>$inputarr['shopid'],
					"content"	=>array(0=>array("tipcontent"=>$inputarr['tipcontent'],"tipswitch"=>$inputarr['tipswitch'],"sortno"=>$inputarr['sortno'])),
			);
			DALFactory::createInstanceCollection(self::$donateticket)->save($arr);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::getDonateticketTips()
	 */
	public function getDonateticketTips($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("content"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result['content'])){
			foreach ($result['content'] as $key=>$val){
				$arr[]=array("tipcontent"=>$val['tipcontent'],"tipswitch"=>$val['tipswitch'],"sortno"=>$val['sortno']);
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorThreeDAL::delDonateticketTips()
	 */
	public function delDonateticketTips($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['shopid']);
		$oparr=array("\$pull"=>array("content"=>array("tipcontent"=>$inputarr['tipcontent'],"tipswitch"=>$inputarr['tipswitch'],"sortno"=>$inputarr['sortno'])));
// 		print_r($oparr);exit;
		DALFactory::createInstanceCollection(self::$donateticket)->update($qarr,$oparr);
	}


}
?>