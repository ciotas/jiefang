<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'IDAL/IZoneDAL.php');
require_once ('/var/www/html/DALFactory.php');
class ZoneDAL implements IZoneDAL{
	private static $coll="zone";
	private static $printer="printer";
	/* (non-PHPdoc)
	 * @see IZoneDAL::getZoneByJfid()
	 */ 
	public function getZoneByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$coll)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"zoneid"=>strval($val['_id']),
					"zonename"=>$val['zonename'],
					"sortno"=>$val['sortno'],
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;

	}
 
	/* (non-PHPdoc)
	 * @see IZoneDAL::delZOne()
	 */
	public function delZone($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($zoneid));
		DALFactory::createInstanceCollection(self::$coll)->remove($qarr);	
	}
	/* (non-PHPdoc)
	 * @see IZoneDAL::saveZone()
	 */
	public function saveZone($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array(
				"zonename"=>$inputarr['zonename'],
				"shopid"=>$inputarr['shopid'],
				"addtime"=>time(),
		);
		DALFactory::createInstanceCollection(self::$coll)->save($qarr);
	}
	/* (non-PHPdoc)
	 * @see IZoneDAL::getPrinterByPid()
	*/
	public function getPrinterByPid($printerid){
		$qarr=array("_id"=>new MongoId($printerid));
		$oparr=array("printername"=>1,"deviceno"=>1,"devicekey"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("printername"=>$result['printername']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IZoneDAL::updateZoneData()
	 */
	public function updateZoneData($zoneid, $op, $newval) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("\$set"=>array($op=>$newval));
		DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IZoneDAL::getOneZoneData()
	 */
	public function getOneZoneData($zoneid,$token) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("zonename"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$coll)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array(
					"zoneid"=>$zoneid,
					"zonename"=>$result['zonename'],
					"token"=>$token,
			);
		}
		return $arr;
	}
	
	public function changeZoneSort($zoneno){
		foreach ($zoneno as $zoneid=>$sortno){
			$qarr=array("_id"=>new MongoId($zoneid));
			$oparr=array("\$set"=>array("sortno"=>$sortno));
			DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
		}
	}
	
	public function array_sort($arr, $keys, $type = 'asc'){
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

}
?>