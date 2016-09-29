<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorTwoDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once ('/var/www/html/des.php');

class MonitorTwoDAL implements IMonitorTwoDAL{
	private static $shopinfo="shopinfo";
	private static $bill="bill";
	private static $table="table";
	private static $zone="zone";
	private static $printer="printer";
	private static $foodtype="foodtype";
	private static $food="food";
	private static $returnbill="returnbill";
	private static $switchfoodrecord="switchfoodrecord";
	private static $servers="servers";
	private static $role="role";
	private static $customer="customer";
	private static $antibill="antibill";
	private static $myvip="myvip";
	private static $cuscheckcode="cuscheckcode";
	private static $coupontype="coupontype";
	private static $switchtabrecord="switchtabrecord";
	private static $otherbill="otherbill";
	private static $change_tabstatus_record="change_tabstatus_record";
	
	
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getZoneTabByShopid()
	 */
	public function getZoneTabByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$hastab=$this->hasTabsInZone(strval($val['_id']));
			if(!$hastab){continue;}
			$tabarr=$this->getTabsByZoneid(strval($val['_id']));
			$arr[]=array(
					"zoneid"=>strval($val['_id']),
					"zonename"=>$val['zonename'],
					"table"=>$tabarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getTabsByZoneid()
	 */
	public function getTabsByZoneid($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("zoneid"=>$zoneid,"tabswitch"=>"1");
		$oparr=array("_id"=>1,"tabname"=>1,"sortno"=>1,"tabstatus"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("tabid"=>strval($val['_id']),"tabname"=>$val['tabname'],"tabstatus"=>$val['tabstatus'], "sortno"=>1);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::hasTabsInZone()
	 */
	public function hasTabsInZone($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("zoneid"=>$zoneid,"tabswitch"=>"1");
		$oparr=array("_id"=>1);
		$flag=false;
		$num=DALFactory::createInstanceCollection(self::$table)->count($qarr);
		if(!empty($num)){
			$flag=true;
		}
		return $flag;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::combineTwoTab()
	 */
	public function combineTwoTab($tabid1, $tabid2) {
		// TODO Auto-generated method stub
		$billid1=$this->getStartBillidByTabid($tabid1);
		$billid2=$this->getStartBillidByTabid($tabid2);
		if(!empty($billid1)&&!empty($billid2)){
			$qarr1=array("_id"=>new MongoId($billid1));
			$oparr=array("food"=>1);
			$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr1,$oparr);
			if(!empty($result)){
				foreach ($result['food'] as $fval){
					$qarr2=array("_id"=>new MongoId($billid2));
					$opar=array("\$push"=>array("food"=>$fval));
					DALFactory::createInstanceCollection(self::$bill)->update($qarr2,$opar);
				}
				DALFactory::createInstanceCollection(self::$bill)->remove($qarr1);
				$this->updateTabStatus($tabid1, "empty");
			}
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getStartBillidByTabid()
	 */
	public function getStartBillidByTabid($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("tabid"=>$tabid,"paystatus"=>"unpay");
		$tabstatus=$this->getTabStatusByTabid($tabid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$billid="";
		foreach ($result as $key=>$val){
			if($tabstatus=="start"){
				$billid=strval($val['_id']);
			}
			break;
		}
		return $billid;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::updateTabStatus()
	 */
	public function updateTabStatus($tabid, $tabstatus) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("\$set"=>array("tabstatus"=>$tabstatus));
		DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getTabStatusByTabid()
	 */
	public function getTabStatusByTabid($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabstatus"=>1);
		$tabstatus="empty";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result['tabstatus'])){
			$tabstatus=$result['tabstatus'];
		}
		return $tabstatus;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::array_sort()
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
	 * @see IMonitorTwoDAL::getPrintersByShopid()
	 */
	public function getPrintersByShopid($shopid) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"printername"=>1,"deviceno"=>1,"devicekey"=>1,"printername"=>1,"printertype"=>1, "workphone"=>1,"outputtype"=>1,"zoneid"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$workstatus=$this->queryPrinterStatus($val['deviceno'],$val['devicekey']);
			$zonename=$this->getZoneNameByZoneId($val['zoneid']);
			$phonecrypt = new CookieCrypt($newphonekey);
			$device_no=$phonecrypt->decrypt($val['deviceno']);
			
			$phonecrypt = new CookieCrypt($newphonekey);
			$device_key=$phonecrypt->decrypt($val['devicekey']);
			if(!empty($val['printertype'])){$printertype=$val['printertype'];}else{$printertype="80";}
			$arr[]=array(
					"printerid"=>strval($val['_id']),
					"printername"=>$val['printername'],
					"deviceno"=>$device_no,
					"devicekey"=>$device_key,
					"workphone"=>$val['workphone'],
					"outputtype"=>$val['outputtype'],
					"zoneid"=>$val['zoneid'],
					"printertype"=>$printertype,
					"zonename"=>$zonename,
					"workstatus"=>$workstatus,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getFoodtypesByShopid()
	 */
	public function getFoodtypesByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "foodtypename"=>1,"foodtypecode"=>1,"printerid"=>1,"sortno"=>1,"showstatus"=>1,"ftpic"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$oneprintarr=$this->getOnePrinterByPid($val['printerid']);
			if(!empty($oneprintarr['printername'])){$printername=$oneprintarr['printername'];}else{$printername="";}
			if($val['showstatus']=="0"){$showstatus="0";}else{$showstatus="1";}
			if(!empty($val['ftpic'])){$ftpic=$val['ftpic'];}else{$ftpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$arr[]=array(
					"ftid"=>strval($val['_id']),
					"ftname"=>$val['foodtypename'],
					"ftcode"=>$val['foodtypecode'],
					"printerid"	=>$val['printerid'],
					"printername"=>$printername,
					"sortno"=>$val['sortno'],
					"showstatus"=>$showstatus,
					"ftpic"=>$ftpic,
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getOnePrinterByPid()
	 */
	public function getOnePrinterByPid($pid) {
		// TODO Auto-generated method stub
		global $newphonekey;
		if(empty($pid)){return  array();}
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("_id"=>1,"deviceno"=>1,"devicekey"=>1,"workphone"=>1, "printername"=>1,"printertype"=>1, "outputtype"=>1,"zoneid"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)&&is_array($result)){
			if(empty($result['outputtype'])){return array();}
			$workstatus=$this->queryPrinterStatus($result['deviceno'],$result['devicekey']);
			$zonename=$this->getZoneNameByPos($result['zoneid']);
			
			$phonecrypt = new CookieCrypt($newphonekey);
			$device_no=$phonecrypt->decrypt($result['deviceno']);
			$phonecrypt = new CookieCrypt($newphonekey);
			$device_key=$phonecrypt->decrypt($result['devicekey']);
			if(!empty($result['printertype'])){$printertype=$result['printertype'];}else{$printertype="80";}
			$arr=array(
					"pid"=>strval($result['_id']),
					"deviceno"=>$result['deviceno'],
					"device_no"=>$device_no,
					"devicekey"=>$result['devicekey'],
					"device_key"=>$device_key,
					"workstatus"=>$workstatus,
					"workphone"=>strval($result['workphone']),
					"printername"=>$result['printername'],
					"outputtype"=>$result['outputtype'],
					"zoneid"=>$result['zoneid'],
					"zonename"=>$zonename,
					"printertype"=>$printertype,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::queryPrinterStatus()
	 */
	public function queryPrinterStatus($device_no,$device_key) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$device_no=$phonecrypt->decrypt($device_no);
		$phonecrypt = new CookieCrypt($newphonekey);
		$device_key=$phonecrypt->decrypt($device_key);
		
		$msgInfo = array(
				'sn'=>$device_no,
				'key'=>$device_key,
		);
		$client = new HttpClient(IP,PORT);
		if(!$client->post(HOSTNAME.'/queryPrinterStatusAction',$msgInfo)){
			echo 'error';
		}
		else{
			$result = $client->getContent();
// 			echo $result;exit;
			$arr=json_decode($result,true);
			return $arr['msg'];
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getZoneNameByPos()
	 */
	public function getZoneNameByPos($posid) {
		// TODO Auto-generated method stub
		if(empty($posid)){return "";}
		$qarr=array("_id"=>new MongoId($posid));
		$oparr=array("zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		$zonename="";
		if(!empty($result['zonename'])){
			$zonename= $result['zonename'];
		}
		return $zonename;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getOneFoodTypeByFtid()
	 */
	public function getOneFoodTypeByFtid($ftid) {
		// TODO Auto-generated method stub
		if(empty($ftid)){return array();}
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("foodtypename"=>1,"foodtypecode"=>1,"printerid"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$printername=$this->getPrinterNameByPid($result['printerid']);
			$arr=array(
					"ftid"=>$ftid,
					"ftname"=>$result['foodtypename'],
					"ftcode"=>$result['foodtypecode'],
					"printerid"=>$result['printerid'],
					"sortno"=>$result['sortno'],
					"printername"=>$printername,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getPrinterNameByPid()
	 */
	public function getPrinterNameByPid($pid) {
		// TODO Auto-generated method stub
		if(empty($pid)){return "";}
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("printername"=>1);
		$printername="";
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(!empty($result)){
			$printername=$result['printername'];
		}
		return $printername;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::addOneFoodTypeData()
	 */
	public function addOneFoodTypeData($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$foodtype)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::delOneFoodTypeData()
	 */
	public function delOneFoodTypeData($ftid) {
		// TODO Auto-generated method stub
		$qarr=array("foodtypeid"=>$ftid);
		$oparr=array("_id"=>1);
		$num=DALFactory::createInstanceCollection(self::$food)->count($qarr);
		if($num==0){
			$qarr=array("_id"=>new MongoId($ftid));
			DALFactory::createInstanceCollection(self::$foodtype)->remove($qarr);
			return "1";
		}else{
			return "0";
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::upFoodToDB()
	 */
	public function upFoodToDB($foodarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$food)->insert($foodarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::updateFoodByFid()
	 */
	public function updateFoodByFid($foodid, $inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array(
				"\$set"=>array(
					"foodname"=>$inputarr['foodname'],
					"foodengname"=>$inputarr['foodengname'],
					"foodcode"=>$inputarr['foodcode'],
					"sortno"=>$inputarr['sortno'],
					"foodtypeid"=>$inputarr['foodtypeid'],
					"foodprice"=>$inputarr['foodprice'],
					"foodunit"=>$inputarr['foodunit'],
					"orderunit"=>$inputarr['orderunit'],
					"foodcooktype"=>$inputarr['foodcooktype'],
					"foodtypeid"=>$inputarr['foodtypeid'],
					"zoneid"=>$inputarr['zoneid'],
					"fooddisaccount"=>$inputarr['fooddisaccount'],
					"isweight"=>$inputarr['isweight'],
					"ishot"=>$inputarr['ishot'],
					"ispack"=>$inputarr['ispack'],
					"foodguqing"=>$inputarr['foodguqing'],
					"autostock"=>$inputarr['autostock'],
					"showout"=>$inputarr['showout'],
					"showserver"=>$inputarr['showserver'],
					"mustorder"=>$inputarr['mustorder'],
					"orderbynum"=>$inputarr['orderbynum'],
					"foodintro"=>$inputarr['foodintro']
		));
		DALFactory::createInstanceCollection(self::$food)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getZonesByShopid()
	 */
	public function getZonesByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("zoneid"=>strval($val['_id']),"zonename"=>$val['zonename'],"sortno"=>$val['sortno']);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getOneFoodData()
	 */
	public function getOneFoodData($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr);
		if(!empty($result)){
			if(!empty($result['foodpic'])){$foodpic=$result['foodpic'];}else{$foodpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$zonename=$this->getZoneNameByZoneId($result['zoneid']);
			if($result['showout']=="0"){$showout="0";}else{$showout="1";}
			if($result['showserver']=="0"){$showserver="0";}else{$showserver="1";}
			if($result['mustorder']=="1"){$mustorder="1";}else{$mustorder="0";}
			if($result['orderbynum']=="1"){$orderbynum="1";}else{$orderbynum="0";}
			$arr=array(
					"foodid"=>strval($result['_id']),	
					"foodname"=>$result['foodname'],
					"foodengname"=>$result['foodengname'],
					"foodprice"=>$result['foodprice'],
					"orderunit"=>$result['orderunit'],
					"foodpic"=>$foodpic,
					"foodcode"=>$result['foodcode'],
					"foodunit"=>$result['foodunit'],
					"foodcooktype"=>$result['foodcooktype'],
					"zoneid"=>$result['zoneid'],
					"ftid"=>$result['foodtypeid'],
					"fooddisaccount"=>$result['fooddisaccount'],
					"isweight"=>$result['isweight'],
					"ishot"=>$result['ishot'],
					"ispack"=>$result['ispack'],
					"autostock"=>$result['autostock'],
					"showout"=>$showout,
					"showserver"=>$showserver,
					"foodintro"=>$result['foodintro'],
					"foodguqing"=>$result['foodguqing'],
					"mustorder"=>$mustorder,
					"orderbynum"=>$orderbynum,
					"sortno"=>$result['sortno'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getZoneNameByZoneId()
	 */
	public function getZoneNameByZoneId($zoneid) {
		// TODO Auto-generated method stub
		if(empty($zoneid)){return "";}
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		$zonename="";
		if(!empty($result['zonename'])){
			$zonename=$result['zonename'];
		}
		return $zonename;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::delOneFoodData()
	 */
	public function delOneFoodData($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		DALFactory::createInstanceCollection(self::$food)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::updateOneFoodtypeData()
	 */
	public function updateOneFoodtypeData($ftid, $inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array(
				"\$set"=>array(
						"foodtypename"=>$inputarr['foodtypename'],
						"foodtypecode"=>$inputarr['foodtypecode'],
						"sortno"=>$inputarr['sortno'],
						"printerid"=>$inputarr['printerid'],
				),	
		);
		DALFactory::createInstanceCollection(self::$foodtype)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getGuqingFoodData()
	 */
	public function getGuqingFoodData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"foodguqing"=>"1");
		$oparr=array("_id"=>1, "foodpic"=>1,"foodname"=>1,"foodtypeid"=>1, "foodprice"=>1,"foodunit"=>1,"foodguqing"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['foodpic'])){$foodpic=$val['foodpic'];}else{$foodpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$ftname=$this->getFtnameByFtid($val['foodtypeid']);
			$arr[]=array(
					"foodid"=>strval($val['_id']),
					"foodname"=>$val['foodname'],
					"ftname"=>$ftname,
					"foodpic"=>$foodpic,
					"foodprice"=>$val['foodprice'],
					"foodunit"=>$val['foodunit'],	
					"foodguqing"=>$val['foodguqing'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getFtnameByFtid()
	 */
	public function getFtnameByFtid($ftid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("foodtypename"=>1);
		$ftname="";
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		if(!empty($result['foodtypename'])){
			$ftname=$result['foodtypename'];
		}
		return $ftname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::updateGuqingStatus()
	 */
	public function updateGuqingStatus($foodid, $guqingstatus) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("\$set"=>array("foodguqing"=>$guqingstatus));
		DALFactory::createInstanceCollection(self::$food)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getRecashier()
	 */
	public function getFuncRole($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("pay"=>1,"repay"=>1, "prepay"=>1,"signpay"=>1,"freepay"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
// 			$cashierman=$this->getCashierMan($shopid);
			if($result['pay']!=null){$pay=$result['pay'];}else{$pay="1";}
			if($result['repay']!=null){$repay=$result['repay'];}else{$repay="1";}
			if($result['prepay']!=null){$prepay=$result['prepay'];}else{$prepay="1";}
			if($result['signpay']!=null){$signpay=$result['signpay'];}else{$signpay="1";}
			if($result['freepay']!=null){$freepay=$result['freepay'];}else{$freepay="1";}
			$arr=array(
					"pay"=>$pay,
					"repay"=>$repay,
					"prepay"=>$prepay,
					"signpay"=>$signpay,
					"freepay"=>$freepay,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::addOnePrinter()
	 */
	public function addOnePrinter($inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$device_no=$phonecrypt->encrypt($inputarr['deviceno']);
		
		$phonecrypt = new CookieCrypt($newphonekey);
		$device_key=$phonecrypt->encrypt($inputarr['devicekey']);
		
		$qarr=array(
				"shopid"	=>$inputarr['shopid'],
				"deviceno"=>$device_no,
				"devicekey"=>$device_key,
				"workphone"=>$inputarr['workphone'],
				"printername"=>$inputarr['printername'],
				"outputtype"=>$inputarr['outputtype'],
				"printertype"=>$inputarr['printertype'],
				"zoneid"=>$inputarr['zoneid'],
				"addtime"=>time(),
		);
		DALFactory::createInstanceCollection(self::$printer)->insert($qarr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::updateOnePrinter()
	 */
	public function updateOnePrinter($inputarr, $printerid) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$qarr=array("_id"=>new MongoId($printerid));
		$phonecrypt = new CookieCrypt($newphonekey);
		$device_no=$phonecrypt->encrypt($inputarr['deviceno']);
		
		$phonecrypt = new CookieCrypt($newphonekey);
		$device_key=$phonecrypt->encrypt($inputarr['devicekey']);
		$oparr=array(
				"\$set"=>array(
					"deviceno"=>$device_no,
					"devicekey"=>$device_key,
					"workphone"=>$inputarr['workphone'],
					"printername"=>$inputarr['printername'],
					"outputtype"=>$inputarr['outputtype'],
					"printertype"=>$inputarr['printertype'],
					"zoneid"=>$inputarr['zoneid'],
		));
		DALFactory::createInstanceCollection(self::$printer)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::delOnePrinter()
	 */
	public function delOnePrinterData($pid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($pid));
		DALFactory::createInstanceCollection(self::$printer)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getReturnFoodData()
	 */
	public function getReturnFoodData($shopid, $theday) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"returntime"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("uid"=>1,"billid"=>1,"nickname"=>1,"tabname"=>1, "returnnum"=>1,"orderunit"=>1,"foodid"=>1,"returntime"=>1,"timestamp"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$returnbill)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$foodname=$this->getFoodnameByFoodid($val['foodid']);
			$nickname=$this->getServername($shopid, $val['uid']);
			$arr[]=array(
					"uid"=>$val['uid'],
					"billid"=>$val['billid'],
					"foodname"=>$foodname,
					"tabname"=>$val['tabname'],
					"nickname"=>$nickname,
					"returnnum"=>$val['returnnum'],
					"orderunit"=>$val['orderunit'],
					"returntime"=>$val['returntime'],
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getOpenHourByShopid()
	 */
	public function getOpenHourByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("openhour"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$openhour="0";
		if(!empty($result['openhour'])){
			$openhour=$result['openhour'];
		}
		return $openhour;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getFoodnameByFoodid()
	 */
	public function getFoodnameByFoodid($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodname"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$foodname="";
		if(!empty($result['foodname'])){
			$foodname=$result['foodname'];
		}
		return $foodname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getFoodDonateData()
	 */
	public function getFoodDonateData($shopid, $theday) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("tabid"=>1,"timestamp"=>1, "food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$tabname=$this->getTablenameByTabid($val['tabid']);
			foreach ($val['food'] as $fkey=>$fval){
				if($fval['present']=="1"){
					$arr[]=array(
							"foodname"=>$fval['foodname'],
							"foodamount"=>$fval['foodamount'],
							"foodunit"=>$fval['foodunit'],
							"foodprice"=>$fval['foodprice'],
							"tabname"=>$tabname,
							"timestamp"=>$val['timestamp'],
					);
				}
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getTablenameByTabid()
	 */
	public function getTablenameByTabid($tabid) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabname"=>1);
		$tabname="";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		If(!empty($result)){
			$tabname=$result['tabname'];
		}
		return $tabname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getFoodSwitchRecord()
	 */
	public function getFoodSwitchRecord($shopid, $theday) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"addtime"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("foodname"=>1, "oldtabname"=>1,"newtabname"=>1,"foodamount"=>1, "timestamp"=>1,"addtime"=>1);
		$result=DALFactory::createInstanceCollection(self::$switchfoodrecord)->find($qarr,$oparr)->sort(array("addtime"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"foodname"=>$val['foodname'],
					"oldtabname"=>$val['oldtabname'],
					"newtabname"=>$val['newtabname'],
					"foodamount"=>$val['foodamount'],
					"timestamp"=>$val['timestamp'],
					"addtime"=>$val['addtime'],	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::isShopphoneUse()
	 */
	public function isShopphoneUse($phone) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$mobilphone=$phonecrypt->encrypt($phone);
		$qarr=array("mobilphone"=>$mobilphone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return false;
		}else{
			return true;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::post_curl()
	 */
	public function post_curl($url, $params) {
		// TODO Auto-generated method stub
		$ch = curl_init();//初始化curl
		curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_PORT,80);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_TIMEOUT,300);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		return $data;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::regShopData()
	 */
	public function regShopData($inputarr) {
		// TODO Auto-generated method stub
		global $token;
        global $checkcodeurl;
        global $newphonekey;
        global $newpwdkey;
        $phonecrypt= new CookieCrypt($newphonekey);
        $mobilphone=$phonecrypt->encrypt($inputarr['mobilphone']);
        $pwdcrypt= new CookieCrypt($newpwdkey);
        $passwd=$pwdcrypt->encrypt($inputarr['passwd']);
        $signature=strtoupper(md5($mobilphone.$inputarr['addtime'].$token));
        $params=array("phone"=>$mobilphone,"checkcode"=>$inputarr['checkcode'], "timestamp"=>$inputarr['addtime'],"signature"=>$signature);
        $result=$this->post_curl($checkcodeurl, $params);
        $statusarr=json_decode($result,true);
//         print_r($statusarr);exit;
        if($statusarr['status']=="1"){//验证通过，注册
            $qarr=array("mobilphone"=>$mobilphone);
            $oparr=array("_id"=>1);
            $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
            if(!empty($result)){
               //已注册
            	return array("status"=>"registered");
            }else{
            	$arr=array(
            			"mobilphone"	=>$mobilphone,
            			"checkcode"=>$inputarr['checkcode'],
            			"passwd"=>$passwd,
            			"shopname"=>$inputarr['shopname'],
            			"shopstatus"=>"3",
            			"allowinbalance"=>"1",
            			"addtime"=>$inputarr['addtime'],
            	);
                DALFactory::createInstanceCollection(self::$shopinfo)->insert($arr);
                return array("status"=>"ok");
            }
        }else{
            return array("status"=>"codeerror");
        }
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getSeversByShopid()
	 */
	public function getSeversByShopid($shopid,$role="server") {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("uid"=>1,"servername"=>1,"roleid"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$servers)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			if($role=="manager"){
				$manager=$this->getRoletypeByRoleid($val['roleid']);
				if($manager){
					$arr[]=array("payer"=>$val['servername'],"uid"=>$val['uid']);
				}
			}elseif($role=="server"){
				$arr[]=array("payer"=>$val['servername'],"uid"=>$val['uid']);
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::checkPayerPwd()
	 */
	public function checkPayerPwd($uid, $shopid,$passwd) {
		// TODO Auto-generated method stub
		global $cuspwdkey;
		$pwdcrypt= new CookieCrypt($cuspwdkey);
		$passwd=$pwdcrypt->encrypt($passwd);
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("passwd"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if($result['passwd']){
			if($passwd==$result['passwd']){
				$servername=$this->getServername($shopid, $uid);
				return array("status"=>"ok","payer"=>$servername);
			}else{
				return array("status"=>"error_pwd","payer"=>"");
			}
		}else{
			return array("status"=>"none","payer"=>"");
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getServername()
	 */
	public function getServername($shopid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("servername"=>1);
		$servername="";
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		if(!empty($result['servername'])){
			$servername=$result['servername'];
		}
		return $servername;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::updateCashierMan()
	 */
	public function updateCashierMan($shopid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("\$set"=>array("role"=>"","timestamp"=>0));
		DALFactory::createInstanceCollection(self::$servers)->update($qarr,$oparr);
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("\$set"=>array("role"=>"cashierman", "timestamp"=>time()));
		DALFactory::createInstanceCollection(self::$servers)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getCashierMan()
	 */
	public function getCashierMan($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"role"=>"cashierman");
		$oparr=array("uid"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$servername="";
		if(!empty($result['uid'])){
			$servername=$this->getServername($shopid, $result['uid']);
		}
		return $servername;
	}
	
	public function getAntiBillAndBill($shopid,$theday){
// 		$openhour=$this->getOpenHourByShopid($shopid);
		$openhour="0";
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid, "antitime"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$result=DALFactory::createInstanceCollection(self::$antibill)->find($qarr)->sort(array("antitime"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$newtotalmoney=0;
			$oldtotalmoney=0;
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$newbillarr=$this->getOneBillDataByBillid(strval($val['_id']));
			$oldticketname=$this->getOneCounponType($val['ticketway']);
			$newticketname=$this->getOneCounponType($newbillarr['ticketway']);

			foreach ($newbillarr['food'] as $newkey=>$newval){
				if(empty($newval['present'])){
					$newtotalmoney+=$newval['foodamount']*$newval['foodprice'];
				}
			}
			foreach ($val['food'] as $oldkey=>$oldval){
				if(empty($oldval['present'])){
					$oldtotalmoney+=$oldval['foodamount']*$oldval['foodprice'];
				}
			}
			
			$olddepositmoney=0;
			$newdepositmoney=0;
			if($val['deposit']=="1"){
				$olddepositmoney=$this->getDepositmoney($val['shopid']);
			}
			
			if($newbillarr['deposit']=="1"){
				$newdepositmoney=$this->getDepositmoney($newbillarr['shopid']);
			}
			
			$oldreturndepositmoney="0";
			$newreturndepositmoney="0";
			if(!empty($val['returndepositmoney']) || $val['returndepositmoney']=="0"){
				$oldreturndepositmoney=$val['returndepositmoney'];
			}
			if(!empty($newbillarr['returndepositmoney']) || $newbillarr['returndepositmoney']=="0"){
				$newreturndepositmoney=$newbillarr['returndepositmoney'];
			}
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"tabname"=>$tabname,
					"antitime"=>$val['antitime'],
					"old"=>array(
							"totalmoney"=>$oldtotalmoney,
							"cashmoney"=>$val['cashmoney'],
							"unionmoney"=>$val['unionmoney'],
							"vipmoney"=>$val['vipmoney'],
							"meituanpay"=>$val['meituanpay'],
							"dazhongpay"=>$val['dazhongpay'],
							"nuomipay"=>$val['nuomipay'],
							"alipay"=>$val['alipay'],
							"wechatpay"=>$val['wechatpay'],
							"ticket"=>$val['ticketval']*$val['ticketnum'],
							"ticketname"=>$oldticketname,
							"discountval"=>$val['discountval'],
							"clearmoney"=>$val['clearmoney'],
							"depositmoney"=>$olddepositmoney,
							"returndepositmoney"=>$oldreturndepositmoney,
							"cashierman"=>$val['cashierman'],
							"buytime"=>$val['buytime'],
					),
					"new"=>array(
							"totalmoney"=>$newtotalmoney,
							"cashmoney"=>$newbillarr['cashmoney'],
							"unionmoney"=>$newbillarr['unionmoney'],
							"vipmoney"=>$newbillarr['vipmoney'],
							"meituanpay"=>$newbillarr['meituanpay'],
							"dazhongpay"=>$newbillarr['dazhongpay'],
							"nuomipay"=>$newbillarr['nuomipay'],
							"alipay"=>$newbillarr['alipay'],
							"wechatpay"=>$newbillarr['wechatpay'],
							"ticket"=>$newbillarr['ticketval']*$newbillarr['ticketnum'],
							"ticketname"=>$newticketname,
							"discountval"=>$newbillarr['discountval'],
							"clearmoney"=>$newbillarr['clearmoney'],
							"depositmoney"=>$newdepositmoney,
							"returndepositmoney"=>$newreturndepositmoney,
							"cashierman"=>$newbillarr['cashierman'],
							"buytime"=>$newbillarr['buytime'],
					),
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getOneBillDataByBillid()
	 */
	public function getOneBillDataByBillid($billid) {
		// TODO Auto-generated method stub
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
			$arr['billid']=strval($result['_id']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getOneCounponType()
	 */
	public function getOneCounponType($ctid) {
		// TODO Auto-generated method stub
		if(empty($ctid)){return "";}
		$qarr=array("_id"=>new MongoId($ctid));
		$oparr=array("coupontype"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$coupontype)->findOne($qarr,$oparr);
		$coupontype="";
		if(!empty($result['coupontype'])){
			$coupontype=$result['coupontype'];
		}
		return $coupontype;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getDepositmoney()
	 */
	public function getDepositmoney($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$depositmoney="0";
		if(!empty($result['depositmoney'])){
			$depositmoney=$result['depositmoney'];
		}
		return $depositmoney;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::doCashiermanLogout()
	 */
	public function doCashiermanLogout($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"role"=>"cashierman");
		$oparr=array("\$set"=>array("role"=>"","timestamp"=>0));
		DALFactory::createInstanceCollection(self::$servers)->update($qarr,$oparr);
	}
	
	public function getTabChangedData($shopid,$theday){
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"addtime"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("billid"=>1,"newtabname"=>1,"oldtabname"=>1,"uid"=>1,"addtime"=>1);
		$result=DALFactory::createInstanceCollection(self::$switchtabrecord)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$servername=$this->getServername($shopid, $val['uid']);
			$arr[]=array(
					"billid"	=>$val['billid'],
					"newtabname"=>$val['newtabname'],
					"oldtabname"=>$val['oldtabname'],
					"uid"=>$val['uid'],
					"servername"=>$servername,
					"addtime"=>$val['addtime'],
			);
		}
		return $arr;
	}
	
	public function getCheckBillidByShopid($shopid){
		$qarr=array("shopid"=>$shopid,"outputtype"=>"checkout");
		$oparr=array("_id"=>1,"deviceno"=>1,"devicekey"=>1,"printertype"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['printertype'])){$printertype=$result['printertype'];}else{$printertype="80";}
			$arr=array("pid"=>strval($result['_id']),"deviceno"=>$result['deviceno'],"devicekey"=>$result['devicekey'],"printertype"=>$printertype);
		}
		return $arr;
	}
	
	public function generPrintContent($deviceno, $devicekey, $datarr,$theday){
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>'.$theday.'日报表</CB><BR>';
// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("消费人数：", 16).$datarr['cusnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("下单数：", 16).$datarr['billnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("翻台率：", 16).$datarr['changerate'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("消费总额：", 16).$datarr['totalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("人均消费：", 16).$datarr['avgmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("应收款：", 16).$datarr['receivablemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("现金：", 16).$datarr['cashmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("银联卡：", 16).$datarr['unionmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("会员卡：", 16).$datarr['vipmoney'].'<BR>';
		foreach ($datarr['ticket'] as $ticketway=>$ticketval){
			$orderInfo.=$this->getStableLenStr($ticketval['ticketname']."：", 16).$ticketval['ticketmoney'].'<BR>';
		}
		$orderInfo.=$this->getStableLenStr("美团账户：", 16).$datarr['meituanpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("大众账户：", 16).$datarr['dazhongpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("糯米账户：", 16).$datarr['nuomipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("支付宝：", 16).$datarr['alipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("微信支付：", 16).$datarr['wechatpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("其他收入：", 16).$datarr['otherpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("签单：", 16).$datarr['signmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("免单：", 16).$datarr['freemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("折扣额：", 16).$datarr['discountmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("抹零：", 16).$datarr['clearmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("收押金：", 16).$datarr['depositmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("退押金：", 16).$datarr['returndepositmoney'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='收银员：'.$datarr['cashierman'].'<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
// 				'apitype'=>'php',
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function getStableLenStr($str, $len){
		$strlength=(strlen($str) + mb_strlen($str,'UTF8'))/2;
		if($strlength<$len){
			return $str.str_repeat(" ",($len-$strlength+1));
		}else{
			return $str;
		}
	}
	
	public function sendSelfFormatMessage($msgInfo) {
		// TODO Auto-generated method stub
		$client = new HttpClient(IP,PORT);
		if(!$client->post(HOSTNAME.'/printOrderAction',$msgInfo)){
			echo 'error';
		}
		else{
			$result= $client->getContent();//{"responseCode":0,"msg":"服务器接收订单成功","orderindex":"xxxxxxxxxxxxxxxxxx"}
			$rearr=json_decode($result,true);
			return $rearr['responseCode'];
		}
	}
	
	public function getAddFoodRecordData($shopid,$theday){
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid, "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("oldbillid"=>1,"timestamp"=>1,"food"=>1);
		$result=DALFactory::createInstanceCollection(self::$otherbill)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$oldbillarr=$this->getOneBillDataByBillid($val['oldbillid']);
			$tabname="";
			if(!empty($oldbillarr)){
				$tabname=$this->getTablenameByTabid($oldbillarr['tabid']);
				$nickname=$this->getServername($shopid, $oldbillarr['uid']);
			}
			$arr[]=array(
					"oldbillid"=>$val['oldbillid'],
					"tabname"=>$tabname,
					"nickname"=>$nickname,
					"timestamp"=>$val['timestamp'],
					"food"=>$val['food'],
			);
		}
		return $arr;
	}
	
	public function getFoodtypenameByFtidarr($ftidarr){
		$objftidarr=array();
		foreach ($ftidarr as $ftid){
		    if(!empty($ftid)){
		        $objftidarr[]=new MongoId($ftid);
		    }
		}
		$qarr=array("_id"=>array("\$in"=>$objftidarr));
		$oparr=array("foodtypename"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$arr[]=$val['foodtypename'];
		}
		return $arr;
		
	}
	
	public function generFoodCalcPrintContent($deviceno, $devicekey, $inputarr){
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		if(!empty($inputarr['ftnamearr'])){$ftname=  implode("、", $inputarr['ftnamearr']);}else{$ftname='所有';}
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB> 美食统计</CB><BR>';
// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 10).$inputarr['startdate'].' '.$inputarr['starthour'].'时 ~ '.$inputarr['enddate'].' '.$inputarr['endhour'].'时<BR>';
		$orderInfo.=$this->getStableLenStr("消费台数：", 10).$inputarr['tabnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("下单数：", 10).$inputarr['foodtotalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("查询分类：", 10).$ftname.'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("美食名", 22).$this->getStableLenStr("数量", 10)."金额".'<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$orderInfo.=$this->getStableLenStr($fval['foodname'], 22).$this->getStableLenStr($fval['foodamount'].$fval['foodunit'], 10).$fval['foodmoney'].'<BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
// 				'apitype'=>'php',
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function getUpdatestatusRecord($shopid,$theday){
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("tabid"=>1, "tabstatus"=>1,"timestamp"=>1,"uid"=>1);
		$result=DALFactory::createInstanceCollection(self::$change_tabstatus_record)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$servername=$this->getServername($shopid, $val['uid']);
			$arr[]=array(
					"tabid"=>$val['tabid'],
					"tabname"=>$tabname,
					"tabstatus"=>$val['tabstatus'],
					"servername"=>$servername,
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	
	public function getRoletypeByRoleid($roleid){
		$qarr=array("_id"=>new MongoId($roleid));
		$oparr=array("pay"=>1);
		$result=DALFactory::createInstanceCollection(self::$role)->findOne($qarr,$oparr);
		if(!empty($result['pay'])){
			return true;
		}else{
			return false;
		}
	}
	
	public function getDecreasenum($shopid){
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("decreasenum"=>1);
		$decreasenum="1";
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result['decreasenum'])){
			$decreasenum=$result['decreasenum'];
		}
		return sprintf("%.2f",1/$decreasenum);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::generTotalcalcPrintContent()
	 */
	public function generTotalcalcPrintContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>营业汇总</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 16).$inputarr['startdate'].' ~ '.$inputarr['enddate'].'<BR>';
		$orderInfo.=$this->getStableLenStr("消费人数：", 16).$inputarr['data']['cusnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("下单数：", 16).$inputarr['data']['billnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("消费总额：", 16).$inputarr['data']['totalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("人均消费：", 16).$inputarr['data']['avgmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("应收款：", 16).$inputarr['data']['receivablemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("现金：", 16).$inputarr['data']['cashmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("银联卡：", 16).$inputarr['data']['unionmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("会员卡：", 16).$inputarr['data']['vipmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("美团账户：", 16).$inputarr['data']['meituanpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("大众账户：", 16).$inputarr['data']['dazhongpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("糯米账户：", 16).$inputarr['data']['nuomipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("支付宝：", 16).$inputarr['data']['alipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("微信支付：", 16).$inputarr['data']['wechatpay'].'<BR>';
		foreach ($inputarr['data']['ticket'] as $tkey=>$tval){
			$orderInfo.=$this->getStableLenStr("".$tval['ticketname']."：", 16).$tval['ticketmoney'].'<BR>';
		}
		$orderInfo.=$this->getStableLenStr("其他收入：", 16).$inputarr['data']['otherpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("优惠券：", 16).$inputarr['data']['ticketmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("签单：", 16).$inputarr['data']['signmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("免单：", 16).$inputarr['data']['freemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("折扣额：", 16).$inputarr['data']['discountmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("抹零：", 16).$inputarr['data']['clearmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("收押金：", 16).$inputarr['data']['depositmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("退押金：", 16).$inputarr['data']['returndepositmoney'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::generTotalFoodcalcPrintContent()
	 */
	public function generTotalFoodcalcPrintContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo .= '<CB>类别统计</CB><BR>';
		$orderInfo.=$this->getStableLenStr("日期：", 10).$inputarr['startdate'].' ~ '.$inputarr['enddate'].'<BR>';
		$orderInfo.=$this->getStableLenStr("销售总额：", 10).'￥'.$inputarr['soldtotalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("销售总量：", 10).$inputarr['soldtotalnum'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("名称", 16).$this->getStableLenStr("数量", 10).'金额<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$orderInfo.=$this->getStableLenStr($fval['ftname'], 16).$this->getStableLenStr($fval['soldnum'], 10)."￥".$fval['soldmoney'].'<BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::bindMyPhone()
	*/
	public function bindMyPhone($inputarr) {
		// TODO Auto-generated method stub
		global $token;
		global $checkcuscodeurl;
		global $cusphonekey;
		$phonecrypt= new CookieCrypt($cusphonekey);
		$telphone=$phonecrypt->encrypt($inputarr['telphone']);
		$signature=strtoupper(md5($telphone.$inputarr['addtime'].$token));
		$params=array("phone"=>$telphone,"checkcode"=>$inputarr['checkcode'], "timestamp"=>$inputarr['addtime'],"signature"=>$signature);
		$result=$this->post_curl($checkcuscodeurl, $params);
// 		var_dump($result);exit;
		$statusarr=json_decode($result,true);
		if($statusarr['status']=="1"){//验证通过，注册
			$qarr=array("_id"=>new MongoId($inputarr['uid']));
			$oparr=array("\$set"=>array("telphone"=>$telphone));
			DALFactory::createInstanceCollection(self::$customer)->update($qarr,$oparr);
			return  array("status"=>"ok");
		}else{
			return array("status"=>"codeerror");
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::changeTwoTable()
	 */
	public function changeTwoTable($tabid1, $tabid2,$shopid) {
		// TODO Auto-generated method stub
		$billarr=$this->getBillinfoByTabid($tabid1, $shopid);
		if(empty($billarr)){return ;}
		$oldtabname=$this->getTablenameByTabid($tabid1);
		$oldtabstatus=$this->getTabStatusByTabid($tabid1);
		$newtabname=$this->getTablenameByTabid($tabid2);
		$qarr=array("_id"=>new MongoId($billarr['billid']));
		$oparr=array("\$set"=>array("tabname"=>$newtabname,"tabid"=>$tabid2,"timestamp"=>time()));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		$this->updateTabStatus($tabid1, "empty");
		$this->updateTabStatus($tabid2, $oldtabstatus);
		//添加换台记录
		$record=array(
				"billid"=>$billarr['billid'],
				"shopid"=>$shopid,
				"newtabname"=>$newtabname,
				"oldtabname"=>$oldtabname,
				"uid"=>$billarr['uid'],
				"addtime"=>time(),
		);
		$this->intoChangeTabRecord($record);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getBillibByTabid()
	 */
	public function getBillinfoByTabid($tabid, $shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabid"=>$tabid);
		$oparr=array("_id"=>1,"uid"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		foreach ($result as $key=>$val){
			$arr=array("billid"=>strval($val['_id']),"uid"=>$val['uid']);
			break;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::intoChangeTabRecord()
	 */
	public function intoChangeTabRecord($record) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$switchtabrecord)->save($record);
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::sendBookMsg()
	 */
	public function sendBookMsg($inputarr) {
		// TODO Auto-generated method stub
		global $token;
		global $sendmsgurl;
		$phone=$inputarr['cusphone'];
		$shopinfo=$this->getShopinfoData($inputarr['shopid']);
		$tabname=$this->getTablenameByTabid($inputarr['tabid']);
// 		$msg=array(
// 				"cusname"=>$inputarr['cusname'],
// 				"shopname"=>$shopinfo['shopname'],
// 				"tabname"=>$tabname,
// 				"booktime"=>$inputarr['bookdate']." ".$inputarr['booktime'],
// 				"servicephone"=>$shopinfo['servicephone'],
// 		);
		$msg=array($inputarr['cusname'],$shopinfo['shopname'],$tabname,$inputarr['bookdate']." ".$inputarr['booktime'],$shopinfo['servicephone']);
		$time=time();		
		$signature=strtoupper(md5($phone.json_encode($msg).$time.$token));
		$params=array("phone"=>$phone,"msg"=>json_encode($msg), "timestamp"=>$time,"signature"=>$signature);
		$status=$this->post_curl($sendmsgurl, $params);
// 		echo $status;exit;
		return $status;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getShopinfoData()
	 */
	public function getShopinfoData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1,"servicephone"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("shopname"=>$result['shopname'],"servicephone"=>$result['servicephone']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::generConsumeFoodPrintContent()
	 */
	public function generConsumeFoodPrintContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB> 酒水消耗</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 10).$inputarr['theday'].'<BR>';
		$orderInfo.=$this->getStableLenStr("售出金额：", 10).'￥'.$inputarr['consumemoney'].'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("美食名", 16).$this->getStableLenStr("消耗量", 8)."金额".'<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$foodlength=(strlen($fval['foodname']) + mb_strlen($fval['foodname'],'UTF8'))/2;
			if($foodlength>16){
				$foodname=$fval['foodname']."<BR>";
			}else{
				$foodname=$this->getStableLenStr($fval['foodname'], 16);
			}
			
			$orderInfo.=$this->getStableLenStr($foodname, 16).$this->getStableLenStr($fval['foodamount'].$fval['foodunit'], 8)."￥".$fval['foodamount']*$fval['foodprice'].'<BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}

	public function generConsumeFoodPrintSmallContent($deviceno, $devicekey, $inputarr){
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB> 酒水消耗</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 10).$inputarr['theday'].'<BR>';
		$orderInfo.=$this->getStableLenStr("售出金额：", 10).'￥'.$inputarr['consumemoney'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("美食名", 22).$this->getStableLenStr("消耗量", 10)."金额".'<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$orderInfo.=$this->getStableLenStr($fval['foodname'], 22).$this->getStableLenStr($fval['foodamount'].$fval['foodunit'], 10)."￥".$fval['foodamount']*$fval['foodprice'].'<BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::generPrintSmallContent()
	 */
	public function generPrintSmallContent($deviceno, $devicekey, $datarr, $theday) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>'.$theday.'日报表</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("消费人数：", 16).$datarr['cusnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("下单数：", 16).$datarr['billnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("翻台率：", 16).$datarr['changerate'].'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("消费总额：", 16).$datarr['totalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("人均消费：", 16).$datarr['avgmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("应收款：", 16).$datarr['receivablemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("现金：", 16).$datarr['cashmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("银联卡：", 16).$datarr['unionmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("会员卡：", 16).$datarr['vipmoney'].'<BR>';
		foreach ($datarr['ticket'] as $ticketway=>$ticketval){
			$orderInfo.=$this->getStableLenStr($ticketval['ticketname']."：", 16).$ticketval['ticketmoney'].'<BR>';
		}
		$orderInfo.=$this->getStableLenStr("美团账户：", 16).$datarr['meituanpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("大众账户：", 16).$datarr['dazhongpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("糯米账户：", 16).$datarr['nuomipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("支付宝：", 16).$datarr['alipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("微信支付：", 16).$datarr['wechatpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("其他收入：", 16).$datarr['otherpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("签单：", 16).$datarr['signmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("免单：", 16).$datarr['freemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("折扣额：", 16).$datarr['discountmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("抹零：", 16).$datarr['clearmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("收押金：", 16).$datarr['depositmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("退押金：", 16).$datarr['returndepositmoney'].'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='收银员：'.$datarr['cashierman'].'<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				// 				'apitype'=>'php',
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::generTotalcalcPrintSmallContent()
	 */
	public function generTotalcalcPrintSmallContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>营业汇总</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 16).$inputarr['startdate'].' ~ '.$inputarr['enddate'].'<BR>';
		$orderInfo.=$this->getStableLenStr("消费人数：", 16).$inputarr['data']['cusnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("下单数：", 16).$inputarr['data']['billnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("消费总额：", 16).$inputarr['data']['totalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("人均消费：", 16).$inputarr['data']['avgmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("应收款：", 16).$inputarr['data']['receivablemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("现金：", 16).$inputarr['data']['cashmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("银联卡：", 16).$inputarr['data']['unionmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("会员卡：", 16).$inputarr['data']['vipmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("美团账户：", 16).$inputarr['data']['meituanpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("大众账户：", 16).$inputarr['data']['dazhongpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("糯米账户：", 16).$inputarr['data']['nuomipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("支付宝：", 16).$inputarr['data']['alipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("微信支付：", 16).$inputarr['data']['wechatpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("其他收入：", 16).$inputarr['data']['otherpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("优惠券：", 16).$inputarr['data']['ticketmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("签单：", 16).$inputarr['data']['signmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("免单：", 16).$inputarr['data']['freemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("折扣额：", 16).$inputarr['data']['discountmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("抹零：", 16).$inputarr['data']['clearmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("收押金：", 16).$inputarr['data']['depositmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("退押金：", 16).$inputarr['data']['returndepositmoney'].'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}

	public function regCusinfo($inputarr){
		global $token;
		global $checkcuscodeurl;
		global $cusphonekey;
		$phonecrypt= new CookieCrypt($cusphonekey);
		$userphone=$phonecrypt->encrypt($inputarr['userphone']);
		$signature=strtoupper(md5($userphone.$inputarr['addtime'].$token));
		$params=array("phone"=>$userphone,"checkcode"=>$inputarr['checkcode'], "timestamp"=>$inputarr['addtime'],"signature"=>$signature);
		$result=$this->post_curl($checkcuscodeurl, $params);
		$statusarr=json_decode($result,true);
// 		print_r($statusarr);exit;
		if($statusarr['status']=="1"){//验证通过，注册
			$uid=$this->regCusinfoData($inputarr);
			$inputarr['uid']=$uid;
			$this->regMyVipinfo($inputarr);
			return array("status"=>"ok");
		}else{
			return array("status"=>"sorry");
		}
	}

	public function regCusinfoData($inputarr){
		global $cusphonekey;
		$phonecrypt= new CookieCrypt($cusphonekey);
		$userphone=$phonecrypt->encrypt($inputarr['userphone']);
		$qarr=array("telphone"=>$userphone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			return strval($result['_id']);
		}else{
			$arr=array("telphone"=>$userphone,"nickname"=>$inputarr['realname'],"addtime"=>$inputarr['addtime']);
			DALFactory::createInstanceCollection(self::$customer)->save($arr);
			return strval($arr['_id']);
		}
	}
	
	public function regMyVipinfo($inputarr){
		$monitordal=new MonitorOneDAL();
		$bossid=$monitordal->getBossidByShopid($inputarr['shopid']);
		if(empty($bossid)){return ;}
		$qarr=array("bossid"=>$bossid,"uid"=>$inputarr['uid'],"cardid"=>$inputarr['cardid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		if(empty($result)){
			$arr=array("bossid"=>$bossid, "uid"=>$inputarr['uid'],"tagid"=>$inputarr['tagid'],"cardid"=>$inputarr['cardid'], "accountbalance"=>"0","addtime"=>$inputarr['addtime']);
			DALFactory::createInstanceCollection(self::$myvip)->save($arr);
		}else{
			$oparr=array("\$set"=>array("bossid"=>$bossid, "tagid"=>$inputarr['tagid'],"cardid"=>$inputarr['cardid']));
			DALFactory::createInstanceCollection(self::$myvip)->update($qarr,$oparr);
		}
	}
	
	public function getCheckCodeByphone($phone){
		$qarr=array("phone"=>$phone);
		$oparr=array("checkcode"=>1,"timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$cuscheckcode)->findOne($qarr,$oparr);
		$checkcode="";
		if(!empty($result)){
			if(time()-$result['timestamp']<=60*60){
				$checkcode=$result['checkcode'];
			}
		}
		return $checkcode;
	}
	
	public function judgeCardnoStatus($shopid,$phone){
		$monitoronedal=new MonitorOneDAL();
		$uid=$monitoronedal->getUidByphone($phone);
		$bossid=$monitoronedal->getBossidByShopid($shopid);
		$qarr=array("bossid"=>$bossid,"uid"=>$uid);
		$oparr=array("_id"=>1,"accountbalance"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		if(!empty($result)){
			return array("status"=>"ok", "accountbalance"=>$result['accountbalance']);
		}else{
			return array("status"=>"no");
		}
	}
	
	public function syncData($shopid){
	    global $clearcache_url;
	    global $clearfoodcache_url;
	    global $syncwechat_url;
	    global $syncphwechat_url;
	    global $synctakeoutwechat_url;
	    file_get_contents($clearcache_url."delCache.php?shopid=$shopid&");
	    file_get_contents($clearfoodcache_url."delCache.php?shopid=$shopid&");
	    file_get_contents($syncwechat_url."syncfood.php?shopid=$shopid&");
	    file_get_contents($syncphwechat_url."syncfood.php?shopid=$shopid&");
	    file_get_contents($synctakeoutwechat_url."syncfood.php?shopid=$shopid&");
	    
	}
    /**
     * {@inheritDoc}
     * @see IMonitorTwoDAL::syncAllShopData()
     */
    public function syncAllShopData()
    {
        // TODO Auto-generated method stub
//         global $clearcache_url;
//         global $clearfoodcache_url;
//         global $syncwechat_url;
//         global $syncphwechat_url;
//         global $synctakeoutwechat_url;
        
        $clearcache_url="http://shop.meijiemall.com/shophome/interface/";
        $clearfoodcache_url="http://shop.meijiemall.com/food/interface/";
        $syncwechat_url="http://shop.meijiemall.com/wechat/interface/";
        $syncphwechat_url="http://shop.meijiemall.com/phwechat/interface/";
        $synctakeoutwechat_url="http://shop.meijiemall.com/takeoutwechat/interface/";
        
        $qarr=array("shopstatus"=>"3");
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr,$oparr);
        foreach ($result as $key=>$val){
            $shopid=strval($val['_id']);
            file_get_contents($clearcache_url."delCache.php?shopid=$shopid&");
            file_get_contents($clearfoodcache_url."delCache.php?shopid=$shopid&");
            file_get_contents($syncwechat_url."syncfood.php?shopid=$shopid&");
            file_get_contents($syncphwechat_url."syncfood.php?shopid=$shopid&");
            file_get_contents($synctakeoutwechat_url."syncfood.php?shopid=$shopid&");
        }
        
    }

}
?>