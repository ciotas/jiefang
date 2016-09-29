<?php
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'IDAL/ITabDAL.php');
require_once ('/var/www/html/DALFactory.php');
date_default_timezone_set("PRC");
class TabDAL implements  ITabDAL{
	private static $table="table";
	private static $zone="zone";
	private static $bill="bill";
	private static $servers="servers";
	private static $printer="printer";
	private static $role="role";
	private static $shopinfo="shopinfo";
	/* (non-PHPdoc)
	 * @see ITabDAL::findTab()
	 */
	public function findTab($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tag"=>array("\$ne"=>"tmp"));
		$oparr=array("_id"=>1, "tabname"=>1,"seatnum"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['seatnum'])){$seatnum=$val['seatnum'];}else{$seatnum="0";}
			$arr[]=array(
					"tabid"=>strval($val['_id']),
					"tabname"=>$val['tabname'],
					"seatnum"=>$val['seatnum'],
					"sortno"=>$val['sortno'],
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see ITabDAL::delTab()
	 */
	public function delTab($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		DALFactory::createInstanceCollection(self::$table)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::saveTable()
	 */
	public function addData($inputarr) {
		// TODO Auto-generated method stub
		$arr=array(
				"shopid"=>$inputarr['shopid'],
				"tabname"=>$inputarr['tabname'],
				"seatnum"=>$inputarr['seatnum'],
				"tabstatus"=>$inputarr['tabstatus'],
				"tabswitch"=>$inputarr['tabswitch'],
				"tablowest"=>$inputarr['tablowest'],
				"zoneid"=>$inputarr['zoneid'],
				"printerid"=>$inputarr['printerid'],
				"addtime"=>$inputarr['addtime'],
		);
		DALFactory::createInstanceCollection(self::$table)->insert($arr);
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getOneTable()
	 */
	public function getOneTable($tabid,$session) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabname"=>1,"seatnum"=>1,"tabstatus"=>1, "tabswitch"=>1,"tablowest"=>1,"zoneid"=>1,"printerid"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$arr=array();
		$zoneid="0";
		$printerid="0";
		$printername="";
		$zonename="";
		if(is_array($result)&&!empty($result)){
			if(!empty($result['seatnum'])){$seatnum=$result['seatnum'];}else{$seatnum="0";}
			if(!empty($result['tabswitch'])){$tabswitch=$result['tabswitch'];}else{$tabswitch="1";}
			if(!empty($result['tabstatus'])){$tabstatus=$result['tabstatus'];}else{$tabstatus="empty";}
			if(!empty($result['tablowest'])){$tablowest=$result['tablowest'];}else{$tablowest="0";}
			if(!empty($result['zoneid'])){
				$zoneid=$result['zoneid'];
				$zonename=$this->getZonenameByZoneid($result['zoneid']);
			}
			if(!empty($result['printerid'])){
				$printerid=$result['printerid'];
				$printername=$this->getPrinterNameByPid($printerid);
			}
			$arr=array(
					"tabid"=>strval($tabid),
					"tabname"=>$result['tabname'],
					"tabswitch"=>$tabswitch,
					"tabstatus"	=>$tabstatus,
					"seatnum"=>$seatnum,
					"tablowest"=>$tablowest,
					"zoneid"=>$zoneid,
					"zonename"=>$zonename,
					"printerid"=>$printerid,
					"printername"=>$printername,
					"token"=>$session
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::updateData()
	 */
	public function updateData($tabid, $op, $newval) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("\$set"=>array($op=>$newval));
		DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getZonenameByZoneid()
	 */
	public function getZonenameByZoneid($zoneid) {
		// TODO Auto-generated method stub
		if(empty($zoneid)){return "";}
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		$zonename="";
		if(!empty($result)){
			$zonename=$result['zonename'];
		}
		return $zonename;
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getShopTablesData()
	 */
	public function getShopTablesData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$zonearr=array();
		$tabarr=$this->getOnLineTable($shopid);
		foreach ($result as $key=>$val){			
			$hastab=$this->hasTabsInZone(strval($val['_id']));
			if($hastab){
				$zonearr[]=array(
						"zoneid"=>strval($val['_id']),
						"zonename"=>$val['zonename'],
				);
			}
			
		}
		$tabstatusnum=$this->getTableStatusNum($shopid);
		return array(
				"zone"=>$zonearr,
				"table"=>$tabarr,
				"book"=>$tabstatusnum['booknum'],
				"empty"=>$tabstatusnum['emptynum'],
				"online"=>$tabstatusnum['onlinenum'],
				"start"=>$tabstatusnum['startnum'],
		);
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getOnLineTable()
	 */
	public function getOnLineTable($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabswitch"=>"1");
		$oparr=array("_id"=>1,"tabname"=>1,"zoneid"=>1, "tabstatus"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$arr=array();
		foreach ($result as $key=>$val){
			$depositmoney="0";
			$tabbillinfo=$this->getTabCounsumeInfo(strval($val['_id']));
			if(!empty($tabbillinfo['billid'])){
				$deposit=$this->getOneDesposit($tabbillinfo['billid']);
			}else{
				$deposit=0;
			}
			if($deposit=="1"){
				$depositmoney=$this->getDepositmoney($shopid);
			}
			if(!empty($tabbillinfo)){
				$arr[]=array(
						"tabid"=>strval($val['_id']),
						"tabname"=>$val['tabname'],
						"sortno"=>$val['sortno'],
						"tabstatus"=>$val['tabstatus'],
						"zoneid"=>$val['zoneid'],
						"billid"=>$tabbillinfo['billid'],
						"cusnum"=>$tabbillinfo['cusnum'],
						"money"=>sprintf("%.1f",$tabbillinfo['totalmoney']+$depositmoney),
						"disaccountmoney"=>sprintf("%.2f",$tabbillinfo['disaccountmoney']),
				);
			}else{
				$arr[]=array(
						"tabid"=>strval($val['_id']),
						"tabname"=>$val['tabname'],
						"sortno"=>$val['sortno'],
						"tabstatus"=>$val['tabstatus'],
						"zoneid"=>$val['zoneid'],
						"billid"=>"",
						"cusnum"=>"0",
						"money"=>"0"+$depositmoney,
						"disaccountmoney"=>"0",
				);
			}
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getOtherTabname()
	 */
	public function getTabCounsumeInfo($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("tabid"=>$tabid,"billstatus"=>"done");//"paystatus"=>"unpay",
		$oparr=array("_id"=>1,"cusnum"=>"1","food"=>1,"timestamp"=>1,"buytime"=>1);
		$arr=array();
		$totalmoney=0;
		$disaccountmoney=0;
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		foreach ($result as $rkey=>$rval){
			if(!empty($rval['buytime'])){$buytime=$rval['buytime'];}else{$buytime="0";}
			foreach ($rval['food'] as $key=>$val){
				if(empty($val['present'])){
					$totalmoney+=$val['foodamount']*$val['foodprice'];
					if($val['fooddisaccount']=="1"){
						$disaccountmoney+=$val['foodamount']*$val['foodprice'];
					}
				}
			}
			$arr=array(
					"billid"=>strval($rval['_id']),
					"cusnum"=>$rval['cusnum'],
					"totalmoney"=>$totalmoney,
					"disaccountmoney"=>$disaccountmoney,
					"timestamp"=>$rval['timestamp'],
					"buytime"=>$buytime,
			);
			break;
		}
		return $arr;
	}
	
	public function getTabStatusByTabid($tabid){
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabstatus"=>1);
		
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getTableStatusNum()
	 */
	public function getTableStatusNum($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabswitch"=>"1");
		$oparr=array("_id"=>1,"tabstatus"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		$booknum=0;
		$onlinenum=0;
		$emptynum=0;
		$startnum=0;
		foreach ($result as $key=>$val){
			switch ($val['tabstatus']){
				case "book": $booknum++;break;
				case "online":$onlinenum++; break;
				case "empty":$emptynum++; break;
				case "start":$startnum++; break;
			}
		}
		return array("booknum"=>$booknum,"onlinenum"=>$onlinenum,"emptynum"=>$emptynum,"startnum"=>$startnum);
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::getUseTables()
	 */
	public function getUseTables($shopid,$uid) {
		// TODO Auto-generated method stub
// 		$qarr=array("shopid"=>$shopid,"tabswitch"=>"1","tag"=>array("\$ne"=>"tmp"));
		$arr=array();
		$resarr=$this->getServerTabids($shopid, $uid);
		$tabarr=$this->getServerTabInfo($resarr);
		foreach ($tabarr as $key=>$val){
			$arr[$val['tabstatus']][]=$val;
		}
		ksort($arr);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::hasTabsInZone()
	 * true or false
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
	
	public function getServerTabsData($shopid,$uid){
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("tables"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$arr=array();
		$tabidarr=array();
		if(!empty($result['tables'])){
			$tabidarr=$result['tables'];
		}
		if(!empty($tabidarr)){
			$arr=$this->getTabInfoByTabidarr($tabidarr);
		}
		return $arr;
	}
	public function getTabInfoByTabidarr($tabidarr){
		$newtabidarr=array();
		foreach ($tabidarr as $tabid){
			$newtabidarr[]=new MongoId($tabid);
		}
		$qarr=array("_id"=>array("\$in"=>$newtabidarr),"tabswitch"=>"1");
		$oparr=array("_id"=>1,"tabname"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"tabid"=>strval($val['_id'])	,
					"tabname"=>$val['tabname'],
					"sortno"=>$val['sortno'],
			);
		}
		return $arr;
	}

	public function getServerTabs($shopid,$uid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$zonearr=array();
		$arr=array();
		$resarr=$this->getServerTabids($shopid, $uid);
		$tabarr=$this->getServerTabInfo($resarr);
		foreach ($result as $zkey=>$zval){
			$ishowtab=$this->hasTabInZoneOrBelongTheServer(strval($zval['_id']), $uid, $resarr);
			if($ishowtab){
				$zonearr[]=array("zoneid"=>strval($zval['_id']),"zonename"=>$zval['zonename']);
			}
		}
		$tabstatusnum=$this->getServerTabNum($resarr);
		$roles=$this->getoneRole($uid,$shopid);
		return array(
				"zone"=>$zonearr,
				"table"=>$tabarr,
				"book"=>$tabstatusnum['booknum'],
				"empty"=>$tabstatusnum['emptynum'],
				"online"=>$tabstatusnum['onlinenum'],
				"start"=>$tabstatusnum['startnum'],
				"roles"=>$roles,
		);
	}

	public function hasTabInZoneOrBelongTheServer($zoneid,$uid,$resarr){
		$flag=false;
		foreach ($resarr as $key=>$val){
		    if($zoneid==$val['zoneid']){
		        $flag=true;
		    }
		}
		return $flag;
	}
	
	public function getServerTabids($shopid,$uid){
		$qarr=array("shopid"=>$shopid);
// 		$oparr=array("_id"=>1);
		return DALFactory::createInstanceCollection(self::$table)->find($qarr)->sort(array("sortno"=>1));
// 		$arr=array();
// 		foreach ($result as $key=>$val){
// 			$arr[]=strval($val['_id']);
// 		}
// 		$serverarr=$this->getViceTabid($arr);
// 		if(!empty($serverarr)){
// 			$arr=array_merge($arr,$serverarr);
// 		}
// 		return $arr;
	}
	
	public function getServerTabInfo($resarr){
// 		$tabidarr=array();
// 		foreach ($tabids as $key=>$tabid){
// 			if(empty($tabid)){continue;}
// 			$tabidarr[]=new MongoId($tabid);
// 		}
// 		$qarr=array("_id"=>array("\$in"=>$tabidarr),"tabswitch"=>"1");
// 		$qarr=array("tabswitch"=>"1");
// 		$oparr=array("_id"=>1,"shopid"=>1, "tabname"=>1,"zoneid"=>1, "tabstatus"=>1,"sortno"=>1);
// 		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$arr=array();
		foreach ($resarr as $key=>$val){
			$depositmoney="0";
			$tabbillinfo=$this->getTabCounsumeInfo(strval($val['_id']));
			if(!empty($tabbillinfo['billid'])){
				$deposit=$this->getOneDesposit($tabbillinfo['billid']);
			}else{
				$deposit="0";
			}
			if($deposit=="1"){
				$depositmoney=$this->getDepositmoney($val['shopid']);
			}
			if($val['tabstatus']=="start" || $val['tabstatus']=="online"){$flowtime= sprintf("%.0f",(time()-$tabbillinfo['timestamp'])/60);}elseif(!empty($tabbillinfo['buytime'])){$flowtime= sprintf("%.0f",($tabbillinfo['buytime']-$tabbillinfo['timestamp'])/60);}else{$flowtime= "0";}
			if(!empty($val['zoneid'])){$zonename=$this->getZonenameByZoneid($val['zoneid']);}else{$zonename="";}
			if(!empty($tabbillinfo)){
				$shopsetarr=$this->getShopSetData($val['shopid']);
				if(empty($shopsetarr)){$tabdata="1";}else{$tabdata=$shopsetarr['tabdata'];}
				if( $val['tabstatus']=="start" || $val['tabstatus']=="online" || $tabdata=="1" ){
					$arr[]=array(
							"tabid"=>strval($val['_id']),
							"tabname"=>$val['tabname'],
							"sortno"=>$val['sortno'],
							"tabstatus"=>$val['tabstatus'],
							"zoneid"=>$val['zoneid'],
							"zonename"=>$zonename,
							"billid"=>$tabbillinfo['billid'],
							"cusnum"=>$tabbillinfo['cusnum'],
							"money"=>$tabbillinfo['totalmoney']+$depositmoney,
							"disaccountmoney"=>$tabbillinfo['disaccountmoney'],
							"timestamp"=>date("m-d H:s",$tabbillinfo['timestamp']),
							"flowtime"=>$flowtime,
					);
				}else{
					$arr[]=array(
							"tabid"=>strval($val['_id']),
							"tabname"=>$val['tabname'],
							"sortno"=>$val['sortno'],
							"tabstatus"=>$val['tabstatus'],
							"zoneid"=>$val['zoneid'],
							"zonename"=>$zonename,
							"billid"=>"",
							"cusnum"=>"0",
							"money"=>"0"+$depositmoney,
							"disaccountmoney"=>"0",
					);
				}			
			}else{
					$arr[]=array(
							"tabid"=>strval($val['_id']),
							"tabname"=>$val['tabname'],
							"sortno"=>$val['sortno'],
							"tabstatus"=>$val['tabstatus'],
							"zoneid"=>$val['zoneid'],
							"zonename"=>$zonename,
							"billid"=>"",
							"cusnum"=>"0",
							"money"=>"0"+$depositmoney,
							"disaccountmoney"=>"0",
					);
				}
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	
	public function getServerTabNum($resarr){
// 		$tabidarr=array();
// 		foreach ($tabids as $key=>$tabid){
// 			$tabidarr[]=new MongoId($tabid);
// 		}
// 		$qarr=array("tabswitch"=>"1");
// 		$qarr=array("_id"=>array("\$in"=>$tabidarr),"tabswitch"=>"1");
// 		$oparr=array("_id"=>1,"tabstatus"=>1);
// 		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$booknum=0;
		$onlinenum=0;
		$emptynum=0;
		$startnum=0;
		foreach ($resarr as $key=>$val){
			switch ($val['tabstatus']){
				case "book": $booknum++;break;
				case "online":$onlinenum++; break;
				case "empty":$emptynum++; break;
				case "start":$startnum++; break;
			}
		}
		return array("booknum"=>$booknum,"onlinenum"=>$onlinenum,"emptynum"=>$emptynum,"startnum"=>$startnum);
	}
	/* (non-PHPdoc)
	 * @see ITabDAL::array_sort()
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
	
	public function changeTabSortno($tabno){
		foreach ($tabno as $tabid=>$sortno){
			$qarr=array("_id"=>new MongoId($tabid));
			$oparr=array("\$set"=>array("sortno"=>$sortno));
			DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
		}
	}
	public function getTabsData($shopid){
		$qarr=array("shopid"=>$shopid,"tabswitch"=>"1","tag"=>array("\$ne"=>"tmp"));
		$oparr=array("_id"=>1, "tabname"=>1,"tabstatus"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$arr[$val['tabstatus']][]=array(
					"tabid"=>strval($val['_id'])	,
					"tabname"=>$val['tabname'],
					"tabstatus"=>$val['tabstatus'],
			);
		}
		krsort($arr);
		return $arr;
	}
	
	public function getPrinterNameByPid($pid){
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("printername"=>1);
		$printername="";
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(!empty($result)){
			$printername=$result['printername'];
		}
		return $printername;
	}
	
	public function getViceTabid($servertabids){
		$qarr=array("motherid"=>array("\$in"=>$servertabids),"tag"=>"tmp");
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
				$arr[]=strval($val['_id']);
		}
		return $arr;
	}
	
	public function getTabTypeData($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"zoneid"=>strval($val['_id']),
					"zonename"	=>$val['zonename'],
			);
			/*
			$hastab=$this->hasTabsInZone(strval($val['_id']));
			if($hastab){
				$arr[]=array(
						"zoneid"=>strval($val['_id']),
						"zonename"	=>$val['zonename'],
				);
			}
			*/
		}
		return $arr;
	}
	
	public function getTabDataByzoneid($zoneid){
		$qarr=array("zoneid"=>$zoneid);
		$oparr=array("_id"=>1,"tabname"=>1,"sortno"=>1,"tabstatus"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"tabid"=>strval($val['_id'])	,
					"tabname"=>$val['tabname'],
					"sortno"=>$val['sortno'],
					"tabstatus"=>$val['tabstatus'],
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	
	public function getoneRole($uid,$shopid){
		$qarr=array("uid"=>$uid, "shopid"=>$shopid);
		$oparr=array("roleid"=>1);
		$rolearr=array();
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		if(!empty($result['roleid'])){
			$roleid=$result['roleid'];
			$rolearr=$this->getOneRoleData($roleid);
		}
		return $rolearr;
	}
	
	public function getOneRoleData($roleid) {
		// TODO Auto-generated method stub
		if(empty($roleid)){
			$detail="1";
			$donate="1";
			$weight="1";
			$returnfood="1";
			$outsheet="1";
			$empty="1";
			$book="1";
			$start="1";
			$online="1";
			$changetab="1";
			$pay="1";
			$signpay="1";
			$freepay="1";
			$deposit="1";
			$changeprice="1";
		}else{
			if(empty($roleid)){
				
			}
			$qarr=array("_id"=>new MongoId($roleid));
			$oparr=array(
					"rolename"=>1,
					"detail"=>1,
					"donate"=>1,
					"weight"=>1,
					"returnfood"=>1,
					"outsheet"=>1,
					"empty"=>1,
					"book"=>1,
					"start"=>1,
					"online"=>1,
					"changetab"=>1,
					"pay"=>1,
					"signpay"=>1,
					"freepay"=>1,
					"deposit"=>1,
					"changeprice"=>1,
			);
			
			$result=DALFactory::createInstanceCollection(self::$role)->findOne($qarr,$oparr);
			if(!empty($result)){
				if(isset($result['detail'])){$detail=$result['detail'];}else{$detail="0";}
				if(isset($result['donate'])){$donate=$result['donate'];}else{$donate="0";}
				if(isset($result['weight'])){$weight=$result['weight'];}else{$weight="0";}
				if(isset($result['returnfood'])){$returnfood=$result['returnfood'];}else{$returnfood="0";}
				if(isset($result['outsheet'])){$outsheet=$result['outsheet'];}else{$outsheet="0";}
				if(isset($result['empty'])){$empty=$result['empty'];}else{$empty="0";}
				if(isset($result['book'])){$book=$result['book'];}else{$book="0";}
				
				if(isset($result['start'])){$start=$result['start'];}else{$start="0";}
				if(isset($result['online'])){$online=$result['online'];}else{$online="0";}
				if(isset($result['changetab'])){$changetab=$result['changetab'];}else{$changetab="0";}
				if(isset($result['pay'])){$pay=$result['pay'];}else{$pay="0";}
				
				if(isset($result['signpay'])){$signpay=$result['signpay'];}else{$signpay="0";}
				if(isset($result['freepay'])){$freepay=$result['freepay'];}else{$freepay="0";}
				if(isset($result['deposit'])){$deposit=$result['deposit'];}else{$deposit="0";}
				if(isset($result['changeprice'])){$changeprice=$result['changeprice'];}else{$changeprice="0";}
// 				$detail=$result['detail'];
// 				$donate=$result['donate'];
// 				$weight=$result['weight'];
// 				$returnfood=$result['returnfood'];
// 				$outsheet=$result['outsheet'];
// 				$empty=$result['empty'];
// 				$book=$result['book'];
// 				$start=$result['start'];
// 				$online=$result['online'];
// 				$changetab=$result['changetab'];
// 				$pay=$result['pay'];
// 				$signpay=$result['signpay'];
// 				$freepay=$result['freepay'];
// 				$deposit=$result['deposit'];
// 				$changeprice=$result['changeprice'];
			}
		}
		$arr=array(
				"detail"=>$detail,
				"donate"=>$donate,
				"weight"=>$weight,
				"returnfood"=>$returnfood,
				"outsheet"=>$outsheet,
				"empty"=>$empty,
				"book"=>$book,
				"start"=>$start,
				"online"=>$online,
				"changetab"=>$changetab,
				"pay"=>$pay,
				"signpay"=>$signpay,
				"freepay"=>$freepay,
				"deposit"=>$deposit,
				"changeprice"=>$changeprice,
		);
		return $arr;
	}
	
	public function getDiancaiTables($shopid,$session){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$arr=array();
		$depositmoney=$this->getDepositmoney($shopid);
		foreach ($result as $key=>$val){
			$hastab=$this->hasTabsInZone(strval($val['_id']));
			if($hastab){
				$tabarr=$this->getTabDataByzoneid(strval($val['_id']));
				$arr[]=array(
						"zoneid"=>strval($val['_id']),
						"zonename"=>$val['zonename'],
						"tables"=>$tabarr,
				);
			}
		}
		return array("token"=>$session,"data"=>$arr,"depositmoney"=>$depositmoney);
	}
	public function getZonesByShopid($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"zoneid"=>strval($val['_id']),
					"zonename"	=>$val['zonename'],
			);
		}
		return $arr;
	}
	public function getDepositmoney($shopid){
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$depositmoney="0";
		if(!empty($result['depositmoney'])){
			$depositmoney=$result['depositmoney'];
		}
		return $depositmoney;
	}
	public function getOneDesposit($billid){
		if(empty($billid)){return '0';}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("deposit"=>1);
		$deposit="0";
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result['deposit'])){
			$deposit=$result['deposit'];
		}
		return $deposit;
	}
	
	public function getShopSetData($shopid){
		if(empty($shopid)){return array();}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("tabdata"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(isset($result['tabdata'])){
				$tabdata=$result['tabdata'];
			}else{
				$tabdata="1";
			}
			$arr=array("tabdata"=>$tabdata);
		}
		return $arr;
	}
}
?>