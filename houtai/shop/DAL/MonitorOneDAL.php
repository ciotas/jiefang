<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorOneDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
class MonitorOneDAL implements IMonitorOneDAL {
	private static $shopinfo="shopinfo";
	private static $bossaccount="bossaccount";
	private static $subaccount="subaccount";
	private static $bill="bill";
	private static $table="table";
	private static $vipcard="vipcard";
	private static $zone="zone";
	private static $myvip="myvip";
	private static $printer="printer";
	private static $food="food";
	private static $role="role";
	private static $foodtype="foodtype";
	private static $coupontype="coupontype";
	private static $billrecord="billrecord";
	private static $customer="customer";
	private static $chargerecord="chargerecord";
	private static $viprecord="viprecord";
	private static $shoptype="shoptype";
	private static $servers="servers";
	private static $shopagent="shopagent";
	private static $agent="agent";
	private static $beforebill="beforebill";
	private static $wechat_user_info="wechat_user_info";
	private static $buy_account_record="buy_account_record";
	private static $shop_use_account="shop_use_account";
	private static $donate_account_record="donate_account_record";
	private static $otherbill="otherbill";
	private static $qrpayrecord="qrpayrecord";
	private static $receiptadv="receiptadv";
	private static $paidcheck = "paidcheck";
	private static $autostock="autostock";
	private static $billshopinfo="billshopinfo";
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::DoLogin()
	 */
	public function DoLogin($mobilphone, $serverphone,$password) {
		// TODO Auto-generated method stub
		global $quarepic;
		$qarr=array("mobilphone"=>$mobilphone);
		$oparr=array("_id"=>1,"passwd"=>1,"shopname"=>1,"logo"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			$serverinfo=$this->getServerIdByServerphone($serverphone);
			if(!empty($serverinfo)){
				$serverexist=$this->isTheShopServerByUid(strval($result['_id']), $serverinfo['uid']);
				if(!$serverexist['exist']){
					return array("shopid"=>"","status"=>"none_my_server");//不是本店服务员
				}else{
					if($serverinfo['serverpasswd']==$password){
						$role=$this->isTheServerManager($serverexist['roleid']);
						if(!empty($result['logo'])){$logo=$result['logo'].$quarepic;}else{$logo="";}
						return array("shopid"=>strval($result['_id']),"shopname"=>$result['shopname'],"serverid"=>$serverexist['serverid'], "roleid"=>$serverexist['roleid'], "servername"=>$serverexist['servername'],"logo"=>$logo,"role"=>$role, "status"=>"ok");
					}else{
						return array("shopid"=>"","status"=>"error");//用户名或密码错误
					}
				}
			}else{//未注册
				return array("shopid"=>"","status"=>"none_server_reg");
			}
			
		}else{//未注册
			return array("shopid"=>"","status"=>"none");
		}
	}
	
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::doMobilphoneLogin()
	*/
	public function doMobilphoneLogin($mobilphone,$passwd){
	global $quarepic;
		$qarr=array("mobilphone"=>$mobilphone);
		$oparr=array("_id"=>1,"passwd"=>1,"shopname"=>1,"logo"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			if($result['passwd']==$passwd){
				$roleid=$this->getManagerRoleid(strval($result['_id']));
				if(!empty($result['logo'])){$logo=$result['logo'].$quarepic;}else{$logo="";}
				return array("shopid"=>strval($result['_id']),"shopname"=>$result['shopname'],"roleid"=>$roleid, "servername"=>$result['shopname'],"logo"=>$logo,"role"=>"manager", "status"=>"ok");
			}else{
				return array("shopid"=>"","status"=>"error");//用户名或密码错误
			}			
		}else{//未注册
			return array("shopid"=>"","status"=>"none");
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::checkPwd()
	 */
	public function checkPwd($shopid, $pwd) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid),"passwd"=>$pwd);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::genrPreExcel()
	 */
	public function genrPreExcel($shopid, $shopname) {
		// TODO Auto-generated method stub
		global $typeurl;
		global $zoneurl;
		$params=array("shopid"=>$shopid,"shopname"=>$shopname);
		//type
		$typepath=$this->post_curl($typeurl, $params);
		$zonepath=$this->post_curl($zoneurl, $params);
		return array("typepath"=>$typepath, "zonepath"=>$zonepath);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::post_curl()
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
	 * @see IMonitorOneDAL::getHomeData()
	 */
	public function getHomeData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,
				"billstatus"=>"done",
				"paystatus"=>"paid",
				"timestamp"=>array("\$gte"=>strtotime(date("Y-m-d",time())),"\$lte"=>time()));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr);
		$totalcusnum=0;
		$billnum=0;
		$totalmoney=0;
		$cashmoney=0;
		$unionmoney=0;
		$vipmoney=0;
		$alipay=0;
		$unionpay=0;
		$wechatpay=0;
		$mtpay=0;
		$othermoney=0;
		$tabuses=$this->getUseTabNum($shopid);
		foreach ($result as $key=>$val){
			$totalcusnum+=$val['cusnum'];
			$billnum++;
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodamount']*$fval['foodprice'];
				}
			}
			$totalmoney+=$val['othermoney'];
			$cashmoney+=$val['cashmoney'];
			$unionmoney+=$val['unionmoney'];
			$vipmoney+=$val['vipmoney'];
			$alipay+=$val['alipay'];
			$wechatpay+=$val['wechatpay'];
			$mtpay+=$val['mtpay'];
			$othermoney+=$val['othermoney'];
		}
		return array(
				"totalcusnum"=>$totalcusnum,
				"billnum"=>$billnum,
				"tabuses"=>$tabuses,
				"totalmoney"=>$totalmoney,
				"totalcashpay"=>$cashmoney,
				"totalunionpay"=>$unionmoney,
				"totalvippay"=>$vipmoney,	
				"totalalipay"=>$alipay,
				"totalwechatpay"=>$wechatpay,
				"totalmtpay"=>$mtpay,
				"othermoney"=>$othermoney,
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getUseTabNum()
	 */
	public function getUseTabNum($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabstatus"=>"online");
		$oparr=array("_id"=>1);
		return DALFactory::createInstanceCollection(self::$bill)->count($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::saveVipCard()
	 */
	public function saveVipCard($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array(
				"shopid"=>$inputarr['shopid'],
				"cardname"=>$inputarr['cardname'],
				"cardrate"=>$inputarr['cardrate'],
				"carddiscount"=>$inputarr['carddiscount'],
				"cardlimit"=>$inputarr['cardlimit'],
				"pointfactor"=>$inputarr['pointfactor'],	
		);
		DALFactory::createInstanceCollection(self::$vipcard)->save($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getVipcardList()
	 */
	public function getVipcardList($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "cardname"=>1,"cardrate"=>1,"carddiscount"=>1,"cardlimit"=>1, "pointfactor"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$vipcard)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$arr[]=array(
					"vcid"=>strval($val['_id']),
					"cardname"	=>$val['cardname'],
					"cardrate"=>$val['cardrate'],
					"carddiscount"=>$val['carddiscount'],
					"cardlimit"=>$val['cardlimit'],
					"pointfactor"=>$val['pointfactor'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::delOneVcd()
	 */
	public function delOneVcd($vcid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($vcid));
		DALFactory::createInstanceCollection(self::$vipcard)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getOneVcd()
	 */
	public function getOneVcdData($vcid) {
		// TODO Auto-generated method stub
		if(empty($vcid)){return array();}
		$qarr=array("_id"=>new MongoId($vcid));
		$oparr=array("cardname"=>1,"cardrate"=>1,"carddiscount"=>1,"cardlimit"=>1,"pointfactor"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$vipcard)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array(
					"vcid"=>$vcid,
					"cardname"	=>$result['cardname'],
					"cardrate"=>$result['cardrate'],
					"carddiscount"=>$result['carddiscount'],
					"cardlimit"=>$result['cardlimit'],
					"pointfactor"=>$result['pointfactor'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::updateOneVcd()
	 */
	public function updateOneVcd($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($inputarr['vcid']));
		$oparr=array(
				"\$set"=>array(
					"cardname"	=>$inputarr['cardname'],
					"cardrate"=>$inputarr['cardrate'],
					"carddiscount"=>$inputarr['carddiscount'],
					"cardlimit"=>$inputarr['cardlimit'],
					"pointfactor"=>$inputarr['pointfactor'],
// 					"storeflag"=>$inputarr['storeflag']
		));
		DALFactory::createInstanceCollection(self::$vipcard)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getShopTablesData()
	 */
	public function getShopTablesData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$zonearr=array();
		foreach ($result as $key=>$val){
			$hastabs=$this->hasTabsInZone(strval($val['_id']));
			if($hastabs){
				$tabarr=$this->getTabSByZoneid(strval($val['_id']));
				$zonearr[]=array(
						"zoneid"=>strval($val['_id']),
						"zonename"=>$val['zonename'],
						"table"=>$tabarr,
				);
			}
		}
		$tabstatusnum=$this->getTableStatusNum($shopid);
		return array(
				"zone"=>$zonearr,
				"book"=>$tabstatusnum['booknum'],
				"empty"=>$tabstatusnum['emptynum'],
				"online"=>$tabstatusnum['onlinenum'],
				"start"=>$tabstatusnum['startnum'],
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTabSByZoneid()
	 */
	public function getTabSByZoneid($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("zoneid"=>$zoneid,"tabswitch"=>"1");
		$oparr=array("_id"=>1,"shopid"=>1,"tabname"=>1,"zoneid"=>1, "tabstatus"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$arr=array();
		foreach ($result as $key=>$val){
			$depositmoney="0";
			$tabbillinfo=$this->getTabCounsumeInfo(strval($val['_id']));
			if(!empty($tabbillinfo['billid'])){
				$deposit=$this->getOneDesposit($tabbillinfo['billid']);
				if($deposit=="1"){
					$depositmoney=$this->getDepositmoney($val['shopid']);
				}
			}
			if(!empty($tabbillinfo)){
				$arr[]=array(
						"tabid"=>strval($val['_id']),
						"tabname"=>$val['tabname'],
						"tabstatus"=>$val['tabstatus'],
						"sortno"=>$val['sortno'],
						"zoneid"=>$val['zoneid'],
						"billid"=>$tabbillinfo['billid'],
						"cusnum"=>$tabbillinfo['cusnum'],
						"money"=>$tabbillinfo['totalmoney']+$depositmoney,
						"timestamp"=>$tabbillinfo['timestamp'],
						"buytime"=>$tabbillinfo['buytime'],
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
						"timestamp"=>"0",
						"buytime"=>"0",
				);
			}
			
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTableStatusNum()
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
	 * @see IMonitorOneDAL::getTabCounsumeInfo()
	 */
	public function getTabCounsumeInfo($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("tabid"=>$tabid,"billstatus"=>"done");
		$oparr=array("_id"=>1,"cusnum"=>"1","orginbillid"=>1, "timestamp"=>1,"food"=>1,"paystatus"=>1, "buytime"=>1);
		$arr=array();
		$totalmoney=0;
		$billid="";
		$cusnum="0";
		$timestamp="0";
		$buytime="0";
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);//找最新的两个单子
		$tabstatus=$this->getTabStatusByTabid($tabid);
		foreach ($result as $rkey=>$rval){
			foreach ($rval['food'] as $key=>$val){
				if(empty($val['present'])){
					$totalmoney+=$val['foodamount']*$val['foodprice'];
				}
			}
			if(!empty($rval['orginbillid'])){
				$timestamp=$this->getOrginbillTime($rval['orginbillid']);
			}else{
				$timestamp=$rval['timestamp'];
			}
			$billid=strval($rval['_id']);
			$cusnum=$rval['cusnum'];
			$buytime=$rval['buytime'];
		}
		return array(
				"billid"=>$billid,
				"cusnum"=>$cusnum,
				"totalmoney"=>$totalmoney,
				"timestamp"=>$timestamp,
				"buytime"=>$buytime,
				);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getChargeVipCard()
	 */
	public function getChargeVipCard($bossid) {
		// TODO Auto-generated method stub
		$arr=array();
		if(!empty($bossid)){
			$qarr=array("bossid"=>$bossid);
			$oparr=array("_id"=>1,"cardname"=>1);
			$result=DALFactory::createInstanceCollection(self::$vipcard)->find($qarr,$oparr);
			foreach ($result as $key=>$val){
				$arr[]=array(
						"cardid"=>strval($val['_id']),
						"cardname"=>$val['cardname'],
				);
			}
		}
		return $arr;
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::chargeForPeople()
	 */
	public function chargeForPeople($inputarr) {
		// TODO Auto-generated method stub		
		global $cusphonekey;
		$bossid=$this->getBossidByShopid($inputarr['shopid']);
		$phonecrypt = new CookieCrypt($cusphonekey);
		$userphone=$phonecrypt->encrypt($inputarr['userphone']);
		$uid=$this->getUidByphone($userphone);
		$qarr=array("bossid"=>$bossid,"uid"=>$uid);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr);
		if(!empty($result)){
			$cardrate=$this->getCardrate($result['cardid']);
			$inputarr['cardrate']=$cardrate;
			$inputarr['uid']=$result['uid'];
			$inputarr['cardid']=$result['cardid'];
			$inputarr['bossid']=$bossid;
			if(empty($cardrate) || $cardrate<0){
				$inputarr['accountbalance']=$result['accountbalance'];
				$oparr=array(
						"\$set"=>array(
								"accountbalance"=>$inputarr['chargemoney'],
						),
				);
			}else{
				$inputarr['accountbalance']=$result['accountbalance']+sprintf("%.0f",$inputarr['chargemoney']*(1+1/$cardrate));
				$oparr=array(
						"\$set"=>array(
								"accountbalance"=>sprintf("%.0f",$inputarr['chargemoney']*(1+1/$cardrate)),
						),
				);
			}
			DALFactory::createInstanceCollection(self::$myvip)->update($qarr,$oparr);
			//记录
			return $this->doChargeRecord($inputarr);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::hasTabsInZone()
	*/
	public function hasTabsInZone($zoneid){
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
	 * @see IMonitorOneDAL::getDaySheetData()
	 */
	public function getDaySheetData($shopid, $theday,$cashierman) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;//"paystatus"=>"paid",
		if($cashierman=="all"){
			$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		}elseif($cashierman=="notpay"){
			$qarr=array("shopid"=>$shopid,"paystatus"=>"unpay" ,"timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		}else{
			$qarr=array("shopid"=>$shopid,"serverid"=>$cashierman,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		}
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$ttotalmoney=0;
		$tdiscountfoodmoney=0;
		$cashmoney=0;
		$unionmoney=0;
		$vipmoney=0;
		$meituanpay=0;
		$dazhongpay=0;
		$nuomipay=0;
		$otherpay=0;
		$alipay=0;
		$wechatpay=0;
		$clearmoney=0;
		$othermoney=0;
		$ticketmoney=0;
		$discountmoney=0;
		$molingmoney=0;
		$signmoney=0;
		$freemoney=0;
		$billnum=0;
		$cusnum=0;
		$receivablemoney=0;
		$depositmoney=0;
		$returndepositmoney=0;
		$onedepositmoney=0;
		$discountmoney=0;
		$cash_unpay=0;
		$arr=array();
		$cashiermanarr=array();
		$ticketarr=array();
		//未买单的美团
		$mt_unpay=0;
		//未买单的大众
		$dz_unpay=0;
		//未买单糯米
		$nm_unpay=0;
		//
		$total_uncash=0;
		$the100minus5="0";
		$depositmoney=$this->getDepositmoney($shopid);
		foreach ($result as $key=>$val){
			$tabstatus=$this->getTabStatusByTabid($val['tabid']);
			$beforenum=$this->hasBillBeforeTheTab($shopid, $val['tabid'], $val['timestamp']);//去掉清台的

// 			if(!empty($beforenum) && $val['paystatus']=="unpay"){continue;}
// 			if($cashierman=="all" || ($cashierman=="notpay"&&empty($beforenum)) || ($cashierman!="all" &&$cashierman!="notpay" && empty($beforenum) ) ){
				$tabname=$this->getTablenameByTabid($val['tabid']);
				if(strstr($tabname,"测试")!=false || strstr($tabname, "test")!=false){
					continue;
				}
				if($val['paystatus']=="paid" || ($val['paystatus']=="unpay" && $tabstatus=="start" && empty($beforenum))){
				
				}else{
					continue;
				}
				if($val['deposit']=="1"){//$val['deposit']=="1" &&
// 					if( ($val['paystatus']=="unpay" && ($tabstatus=="empty" || $tabstatus=="book")) || ($val['paystatus']=="unpay"&&$tabstatus=="start" && !empty($beforenum) )){continue;}
					foreach ($val['food'] as $fkey=>$fval){
						if(strstr($fval['foodname'],"100-5")!=false){
							$the100minus5+=$fval['foodprice']*$fval['foodamount'];
							$total_uncash+=$fval['foodprice']*$fval['foodamount'];
						}
						if(strstr($fval['foodname'],"美团")!=false && strstr($fval['foodname'],"100-5")==false ){
							$mt_unpay+=$fval['foodprice']*$fval['foodamount'];
							$total_uncash+=$fval['foodprice']*$fval['foodamount'];
						}elseif(strstr($fval['foodname'],"大众")!=false || strstr($fval['foodname'],"大众点评")!=false){
							$dz_unpay+=$fval['foodprice']*$fval['foodamount'];
							$total_uncash+=$fval['foodprice']*$fval['foodamount'];
						}elseif (strstr($fval['foodname'],"糯米")!=false){
							$nm_unpay+=$fval['foodprice']*$fval['foodamount'];
							$total_uncash+=$fval['foodprice']*$fval['foodamount'];
						}
					}
				}
				
				$billnum++;
				$cusnum+=$val['cusnum'];
				$totalmoney_fooddisaccountmoney=$this->getTotalmoneyAndFooddisaccountmoney(strval($val['_id']));
				$totalmoney=$totalmoney_fooddisaccountmoney['totalmoney'];
				$ttotalmoney+=round($totalmoney);
				$discountfoodmoney=$totalmoney_fooddisaccountmoney['fooddisaccountmoney'];
				$tdiscountfoodmoney+=$discountfoodmoney;
				$cashmoney+=$val['cashmoney'];
				$unionmoney+=$val['unionmoney'];
				$vipmoney+=$val['vipmoney'];
				$meituanpay+=$val['meituanpay'];
				$dazhongpay+=$val['dazhongpay'];
				$nuomipay+=$val['nuomipay'];
				$otherpay+=$val['otherpay'];
				$alipay+=$val['alipay'];
				$wechatpay+=$val['wechatpay'];
				$clearmoney+=$val['clearmoney'];
				$othermoney+=$val['othermoney'];
				$signmoney+=$val['signmoney'];
				$freemoney+=$val['freemoney'];
				$discountmoney1=0;
				if($val['discountmode']=="part"){
					$discountmoney1=ceil((1-$val['discountval']/100)*$discountfoodmoney);
				}elseif($val['discountmode']=="all"){
					$discountmoney1=ceil((1-$val['discountval']/100)*$totalmoney);
				}
				$discountmoney+=$discountmoney1;
				if($val['deposit']=="1" && $val['paystatus']=="unpay"  && $tabstatus=="start"){//
					$totalmoney+=$depositmoney;
				}
				if($val['deposit']=="1"){
					$onedepositmoney+=$depositmoney;
				}
				if(!empty($val['returndepositmoney'])){
					$returndepositmoney+=$val['returndepositmoney'];
				}
				if(!empty($val['ticketway'])&&!empty($val['ticketval'])&&!empty($val['ticketnum'])){
					$ticketmoney+=$val['ticketval']*$val['ticketnum'];
					if(array_key_exists($val['ticketway'], $ticketarr)){
						$ticketarr[$val['ticketway']]['ticketmoney']+=$val['ticketval']*$val['ticketnum'];
					}else{
						$ticketname=$this->getTicketNameById($val['ticketway']);
						$ticketarr[$val['ticketway']]=array(
								"ticketname"=>$ticketname,
								"ticketmoney"=>$val['ticketval']*$val['ticketnum'],
						);
					}
				}
		}
		$receivablemoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;
		$ttotalmoney+=$onedepositmoney-$returndepositmoney;
		if(!empty($cusnum)){
			$avgmoney=sprintf("%.0f",$ttotalmoney/$cusnum);
		}else{
			$avgmoney=0;
		}
		$tabnum=$this->getTabNum($shopid);
		if(!empty($tabnum)){
			$changerate=sprintf("%.1f",100*($billnum/$tabnum))."%";
		}else{
			$changerate="0";
		}
		$switchtime=$this->getSwitchPayerTime($shopid);
		if($switchtime>=$starttime && $switchtime<=$endtime){
			$swtchpayertime=$switchtime;
		}else{
			$swtchpayertime=0;
		}
		return array(
				"totalmoney"=>$ttotalmoney,
				"billnum"=>$billnum,
				"cusnum"=>$cusnum,
				"receivablemoney"=>round($receivablemoney),
				"avgmoney"=>$avgmoney,
				"changerate"=>$changerate,
				"cashmoney"=>round($cashmoney),
				"unionmoney"=>round($unionmoney),
				"vipmoney"=>round($vipmoney),
				"meituanpay"=>round($meituanpay),
				"dazhongpay"=>round($dazhongpay),
				"nuomipay"=>round($nuomipay),
				"otherpay"=>round($otherpay),
				"cash_unpay"=>round($ttotalmoney-$total_uncash),
				"alipay"=>$alipay,
				"wechatpay"=>$wechatpay,
				"clearmoney"=>$clearmoney,
				"othermoney"=>round($othermoney),
				"signmoney"=>sprintf("%.0f",$signmoney),
				"freemoney"=>sprintf("%.0f",$freemoney),
				"discountmoney"=>$discountmoney,
				"ticket"=>$ticketarr,
				"depositmoney"=>$onedepositmoney,
				"returndepositmoney"=>$returndepositmoney,
// 				"cashierman"=>$cashiermanarr,
				"switchtime"=>$swtchpayertime,
				"mt_unpay"=>$mt_unpay,
				"dz_unpay"=>$dz_unpay,
				"nm_unpay"=>$nm_unpay,
				"the100minus5"=>$the100minus5,
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::judeFoodDiscountByFoodid()
	 */
	public function judeFoodDiscountByFoodid($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid) || strlen($foodid)!=24){return "1";}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("fooddisaccount"=>1);
		$fooddisaccount="1";
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr);
		if(!empty($result)){
			$fooddisaccount=$result['fooddisaccount'];
		}
		return $fooddisaccount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTabNum()
	 */
	public function getTabNum($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabswitch"=>"1");
		return DALFactory::createInstanceCollection(self::$table)->count($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTimeSheetData()
	 */
	public function getTimeSheetData($shopid, $starttime, $endtime) {
		// TODO Auto-generated method stub"paystatus"=>"paid",
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		$cashiermanarr=array();
		$ticketallarr=$this->getAllTickets($shopid);
		$depositmoney=$this->getDepositmoney($shopid);
		foreach ($result as $key=>$val){
			$tabname=$this->getTablenameByTabid($val['tabid']);
			if(strstr($tabname,"测试")!=false || strstr($tabname, "test")!=false){
				continue;
			}
			
			$totalmoney_fooddisaccountmoney=$this->getTotalmoneyAndFooddisaccountmoney(strval($val['_id']));
			$totalmoney=$totalmoney_fooddisaccountmoney['totalmoney'];
			$discountfoodmoney=$totalmoney_fooddisaccountmoney['fooddisaccountmoney'];
			$cashmoney=$val['cashmoney'];
			$unionmoney=$val['unionmoney'];
			$vipmoney=$val['vipmoney'];
			$meituanpay=$val['meituanpay'];
			$dazhongpay=$val['dazhongpay'];
			$nuomipay=$val['nuomipay'];
			$otherpay=$val['otherpay'];
			$alipay=$val['alipay'];
			$wechatpay=$val['wechatpay'];
			$clearmoney=$val['clearmoney'];
			$othermoney=$val['othermoney'];
			$ticketmoney=$val['ticketval']*$val['ticketnum'];
			$ticketarr=array();
			if(!empty($val['ticketway'])&&!empty($val['ticketval'])&&!empty($val['ticketnum'])){
				if(array_key_exists($val['ticketway'], $ticketarr)){
					$ticketarr[$val['ticketway']]['ticketmoney']+=$val['ticketval']*$val['ticketnum'];
					$ticketarr[$val['ticketway']]['ticketnum']+=$val['ticketnum'];
				}else{
					$ticketname=$this->getTicketNameById($val['ticketway']);
					$ticketarr[$val['ticketway']]=array(
							"ticketid"=>$val['ticketway'],
							"ticketname"=>$ticketname,
							"ticketnum"=>$val['ticketnum'],
							"ticketmoney"=>$val['ticketval']*$val['ticketnum'],
					);
				}
			}
				
			foreach ($ticketallarr as $tkid=>$tkval){
				if(!array_key_exists($tkid, $ticketarr)){
					$ticketarr[$tkid]=array(
							"ticketid"=>$tkid,
							"ticketname"	=>$tkval,
							"ticketnum"=>"0",
							"ticketmoney"=>"0",
					);
				}
			}
			ksort($ticketarr);
			if($val['discountmode']=="part"){
				$discountmoney=ceil((1-$val['discountval']/100)*$discountfoodmoney);
			}else{
				$discountmoney=ceil((1-$val['discountval']/100)*$totalmoney);
			}
			
			$molingmoney=$val['molingmoney'];
			$signmoney=$val['signmoney'];
			$freemoney=$val['freemoney'];
			$onedepositmoney="0";
			if($val['deposit']=="1"){
				$onedepositmoney=$depositmoney;
			}
			$returndepositmoney="0";
			if(!empty($val['returndepositmoney'])){
				$returndepositmoney=$val['returndepositmoney'];
			}
			$billnum=0;
			$billnum++;
			$cusnum=$val['cusnum'];
			$receivablemoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;
			$totalmoney+=$onedepositmoney-$returndepositmoney;
			if(array_key_exists(date("Y-m-d H",$val['timestamp']), $arr)){
				$arr[date("Y-m-d H",$val['timestamp'])]['totalmoney']+=round($totalmoney);
				$arr[date("Y-m-d H",$val['timestamp'])]['receivablemoney']+=round($receivablemoney);
				$arr[date("Y-m-d H",$val['timestamp'])]['billnum']+=$billnum;
				$arr[date("Y-m-d H",$val['timestamp'])]['cusnum']+=$cusnum;
				$arr[date("Y-m-d H",$val['timestamp'])]['cashmoney']+=round($cashmoney);
				$arr[date("Y-m-d H",$val['timestamp'])]['unionmoney']+=round($unionmoney);
				$arr[date("Y-m-d H",$val['timestamp'])]['vipmoney']+=round($vipmoney);
				$arr[date("Y-m-d H",$val['timestamp'])]['meituanpay']+=round($meituanpay);
				$arr[date("Y-m-d H",$val['timestamp'])]['dazhongpay']+=round($dazhongpay);
				$arr[date("Y-m-d H",$val['timestamp'])]['nuomipay']+=round($nuomipay);
				$arr[date("Y-m-d H",$val['timestamp'])]['otherpay']+=round($otherpay);
				$arr[date("Y-m-d H",$val['timestamp'])]['alipay']+=$alipay;
				$arr[date("Y-m-d H",$val['timestamp'])]['wechatpay']+=$wechatpay;
				$arr[date("Y-m-d H",$val['timestamp'])]['clearmoney']+=$clearmoney;
				$arr[date("Y-m-d H",$val['timestamp'])]['othermoney']+=round($othermoney);
				if(!empty($val['ticketway'])){
					$arr[date("Y-m-d H",$val['timestamp'])]['ticket'][$val['ticketway']]['ticketnum']+=$val['ticketnum'];
					$arr[date("Y-m-d H",$val['timestamp'])]['ticket'][$val['ticketway']]['ticketmoney']+=$val['ticketnum']*$val['ticketval'];
				}
				$arr[date("Y-m-d H",$val['timestamp'])]['ticketmoney']+=$ticketmoney;
				$arr[date("Y-m-d H",$val['timestamp'])]['discountmoney']+=$discountmoney;
				$arr[date("Y-m-d H",$val['timestamp'])]['signmoney']+=$signmoney;
				$arr[date("Y-m-d H",$val['timestamp'])]['freemoney']+=$freemoney;
				$arr[date("Y-m-d H",$val['timestamp'])]['depositmoney']+=$onedepositmoney;
				$arr[date("Y-m-d H",$val['timestamp'])]['returndepositmoney']+=$returndepositmoney;
			}else{
				$arr[date("Y-m-d H",$val['timestamp'])]=array(
						"totalmoney"=>round($totalmoney),
						"receivablemoney"=>round($receivablemoney),
						"billnum"=>$billnum,
						"cusnum"=>$cusnum,
						"cashmoney"=>round($cashmoney),
						"unionmoney"=>round($unionmoney),
						"vipmoney"=>round($vipmoney),
						"meituanpay"=>round($meituanpay),
						"dazhongpay"=>round($dazhongpay),
						"nuomipay"=>round($nuomipay),
						"otherpay"=>round($otherpay),
						"alipay"=>$alipay,
						"ticket"=>$ticketarr,
						"wechatpay"=>$wechatpay,
						"clearmoney"=>$clearmoney,
						"othermoney"=>round($othermoney),
						"ticketmoney"=>$ticketmoney,
						"discountmoney"=>$discountmoney,
						"signmoney"=>$signmoney,
						"freemoney"=>$freemoney,
						"depositmoney"=>$onedepositmoney,
						"returndepositmoney"=>$returndepositmoney,
						
				);
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTotalmoneyAndFooddisaccountmoney()
	 */
	public function getTotalmoneyAndFooddisaccountmoney($billid) {
		// TODO Auto-generated method stub
		$totalmoney=0;
		$fooddisaccountmoney=0;
		if(empty($billid)){return array("totalmoney"=>$totalmoney,"fooddisaccountmoney"=>$fooddisaccountmoney);}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		if(empty($result)){return array("totalmoney"=>$totalmoney,"fooddisaccountmoney"=>$fooddisaccountmoney);}
		foreach ($result['food'] as $key=>$val){
			if(empty($val['present'])){
				$totalmoney+=$val['foodamount']*$val['foodprice'];
				$isdisaccount=$this->judeFoodDiscountByFoodid($val['foodid']);
				if($isdisaccount=="1"){
					$fooddisaccountmoney+=$val['foodamount']*$val['foodprice'];
				}
			}
		}
		return array("totalmoney"=>$totalmoney,"fooddisaccountmoney"=>$fooddisaccountmoney);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodTypes()
	 */
	public function getFoodTypes($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"foodtypename"=>1,"sortno"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr)->sort(array("sortno"=>1));
		foreach ($result as $key=>$val){
			$arr[]=array(
					"ftid"=>strval($val['_id']),
					"ftname"=>$val['foodtypename'],
					"sortno"=>$val['sortno'],
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodDataByFtid()
	 */
	public function getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		$billidarr=array();
		$cashmoney=0;
		$unionmoney=0;
		$vipmoney=0;
		$meituanpay=0;
		$dazhongpay=0;
		$nuomipay=0;
		$alipay=0;
		$wechatpay=0;
		$ticketmoney=0;
		$unpaymoney=0;
		foreach ($result as $key=>$val){
		    $shopname=$this->getShopnameByBillid(strval($val['_id']));
		    $paycheckarr=$this->getUnpaidSheetData(strval($val['_id']));
		    $moneyarr=$this->getTotalmoneyAndFooddisaccountmoney(strval($val['_id']));
		    if(!empty($paycheckarr['paidmoney']) && !empty($paycheckarr['totalmoney'])){
		        $unpaymoney+=$paycheckarr['totalmoney']-$paycheckarr['paidmoney'];
		        $totalmoney+=$paycheckarr['totalmoney'];
		    }else{
		        $totalmoney+=$moneyarr['totalmoney'];
		        $unpaymoney+=$moneyarr['totalmoney'];
		    }
			foreach ($val['food'] as $fkey=>$fval){
				$foodaddtime=$this->getFoodUpTimeByFoodid($fval['foodid']);
				if(!empty($ftidarr)&&!empty($ftidarr[0])){			
					if(in_array($fval['ftid'], $ftidarr)){
						if(!in_array(strval($val['_id']), $billidarr)){
							$billidarr[]=strval($val['_id']);
						}
						if(array_key_exists($fval['foodid'], $arr)){
							$arr[$fval['foodid']]['foodnum']+=$fval['foodnum'];
							$arr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
							$arr[$fval['foodid']]['foodmoney']+=$fval['foodamount']*$fval['foodprice'];
							if(!empty($shopname)){
							    if(!in_array($shopname, $arr[$fval['foodid']]['shopname'])){
							        $arr[$fval['foodid']]['shopname'][]=$shopname;
							    }
							}
						}else{
							$arr[$fval['foodid']]=array(
									"foodname"=>$fval['foodname'],
									"orderunit"=>$fval['orderunit'],
									"foodunit"=>$fval['foodunit'],
									"foodprice"=>$fval['foodprice'],
									"foodnum"=>$fval['foodnum'],
									"foodamount"=>$fval['foodamount'],
									"foodmoney"=>$fval['foodamount']*$fval['foodprice'],
									"foodcycle"=>sprintf("%.0f",(time()-$foodaddtime)/(24*3600)),
							);
							if(!empty($shopname)){
							    $arr[$fval['foodid']]['shopname'][]=$shopname;
							}
						}
					}
				}else{
					if(!in_array(strval($val['_id']), $billidarr)){
						$billidarr[]=strval($val['_id']);
					}
					if(array_key_exists($fval['foodid'], $arr)){
						$arr[$fval['foodid']]['foodnum']+=$fval['foodnum'];
						$arr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
						$arr[$fval['foodid']]['foodmoney']+=$fval['foodamount']*$fval['foodprice'];
						if(!empty($shopname)){
						    if(!in_array($shopname, $arr[$fval['foodid']]['shopname'])){
						        $arr[$fval['foodid']]['shopname'][]=$shopname;
						    }
						}
					}else{
						$arr[$fval['foodid']]=array(
								"foodname"=>$fval['foodname'],
								"orderunit"=>$fval['orderunit'],
								"foodunit"=>$fval['foodunit'],
								"foodnum"=>$fval['foodnum'],
								"foodamount"=>$fval['foodamount'],
								"foodmoney"=>$fval['foodamount']*$fval['foodprice'],
								"foodcycle"=>sprintf("%.0f",(time()-$foodaddtime)/(24*3600)),
						);
						if(!empty($shopname)){
						    $arr[$fval['foodid']]['shopname'][]=$shopname;
						}
					}
				}
			}
		}
		$tabnum=count($billidarr);
		$arr=$this->array_sort($arr, "foodamount","desc");
		return array("tabnum"=>$tabnum, "data"=>$arr,"unpaymoney"=>$unpaymoney,"totalmoney"=>$totalmoney);
		
	}
	
	public function getShopnameByBillid($billid){
	    $qarr=array("billid"=>$billid);
	    $oparr=array("shopname"=>1);
	    $result=DALFactory::createInstanceCollection(self::$billshopinfo)->findOne($qarr,$oparr);
	    $shopname='';
	    if(!empty($result)){
	        $shopname=$result['shopname'];
	    }
	    return $shopname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodUpTimeByFoodid()
	 */
	public function getFoodUpTimeByFoodid($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return 0;}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("shopid"=>1, "addtime"=>1);
		$addtime="0";
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		
		if(!empty($result)){
			if(!empty($result['addtime'])){
				$addtime=$result['addtime'];
			}else{
				$addtime=$this->getShopRegTime($result['shopid']);
			}
		}
		return $addtime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::array_sort()
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
	 * @see IMonitorOneDAL::getShopRegTime()
	 */
	public function getShopRegTime($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("addtime"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$addtime="0";
		if(!empty($result['addtime'])){
			$addtime=$result['addtime'];
		}
		return $addtime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTypeCalcData()
	 */
	public function getTypeCalcData($shopid, $starttime, $endtime) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $rkey=>$rval){
			foreach ($rval['food'] as $key=>$val){
				if(array_key_exists($val['ftid'], $arr)){
					$arr[$val['ftid']]['soldnum']+=$val['foodnum'];
					$arr[$val['ftid']]['soldmoney']+=$val['foodamount']*$val['foodprice'];
				}else{
					$ftname=$this->getFoodtypenameByftid($val['ftid']);
					if(empty($ftname)){continue;}
					$arr[$val['ftid']]=array(
							"ftid"=>$val['ftid'],
							"ftname"=>	$ftname,
							"soldnum"=>$val['foodnum'],
							"soldmoney"=>$val['foodamount']*$val['foodprice'],
					);
				}
			}
		}
		if(!empty($arr)){$arr=$this->array_sort($arr, "soldnum","desc");}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodtypenameByftid()
	 */
	public function getFoodtypenameByftid($ftid) {
		// TODO Auto-generated method stub
		if(empty($ftid)){return "";}
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("foodtypename"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		$foodtypename="";
		if(!empty($result['foodtypename'])){
			$foodtypename=$result['foodtypename'];
		}
		return $foodtypename;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getDailySheetData()
	 */
	public function getDailySheetData($shopid, $starttime, $endtime,$type) {
		// TODO Auto-generated method stub
		if($type=="real"){
			$openhour=$this->getOpenHourByShopid($shopid);
			$starttime1=strtotime($starttime." ".$openhour.":0:0");
			$endtime1=strtotime($starttime." ".$openhour.":0:0")+86400;
			$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime1,"\$lte"=>$endtime1));
			$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		}elseif($type=="record"){
			$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
			$result=DALFactory::createInstanceCollection(self::$billrecord)->find($qarr)->sort(array("timestamp"=>-1));
		}elseif ($type=="unpay"){
			$openhour=$this->getOpenHourByShopid($shopid);
			$starttime1=strtotime($starttime." ".$openhour.":0:0");
			$endtime1=strtotime($starttime." ".$openhour.":0:0")+86400;
			$qarr=array("shopid"=>$shopid,"paystatus"=>"unpay", "timestamp"=>array("\$gte"=>$starttime1,"\$lte"=>$endtime1));
			$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>1));
		}
		$arr=array();
		foreach ($result as $rkey=>$rval){
			$tabname=$this->getTablenameByTabid($rval['tabid']);
			if(strstr($tabname,"测试")!=false || strstr($tabname, "test")!=false){
				continue;
			}
			$foodarr=array();
			$billid=strval($rval['_id']);
			$cusnum=$rval['cusnum'];
			$total_disfoodmoney=$this->getTotalmoneyAndFooddisaccountmoney($billid);
			$totalmoney=$total_disfoodmoney['totalmoney'];
			$cashmoney=$rval['cashmoney'];
			$unionmoney=$rval['unionmoney'];
			$vipmoney=$rval['vipmoney'];
			$meituanpay=$rval['meituanpay'];
			$dazhongpay=$rval['dazhongpay'];
			$nuomipay=$rval['nuomipay'];
			$otherpay=$rval['otherpay'];
			$alipay=$rval['alipay'];
			$wechatpay=$rval['wechatpay'];
			$othermoney=$rval['othermoney'];
			$ticketmoney=$rval['ticketval']*$rval['ticketnum'];
			$ticketway=$rval['ticketway'];
			if($rval['discountmode']=="part"){
				$discountmoney=ceil($total_disfoodmoney['fooddisaccountmoney']*(1-$rval['discountval']/100));
			}else{
				$discountmoney=ceil($total_disfoodmoney['totalmoney']*(1-$rval['discountval']/100));
			}
			$discountval=$rval['discountval'];
			$clearmoney=$rval['clearmoney'];
			$signmoney=$rval['signmoney'];
			$freemoney=$rval['freemoney'];
			$cuspay=$rval['cuspay'];
			if($rval['deposit']=="1"){
				$depositmoney=$this->getDepositmoney($rval['shopid']);
			}else{
				$depositmoney="0";
			}
			$returndepositmoney="0";
			$returndepositmoney=$rval['returndepositmoney'];
			
			$timestamp=$rval['timestamp'];
			$buytime=$rval['buytime'];
			if(isset($rval['buytime'])){
				$buytime=date("m-d H:i",$rval['buytime']);
			}else{
				$buytime="/";
			}
			$userarr=$this->getUserinfoByUid($rval['uid']);
			$nickname=$userarr['nickname'];
			$cashierman=$rval['cashierman'];
			$paytype=$rval['paytype'];
			$paystatus=$rval['paystatus'];
			$billstatus=$rval['billstatus'];
			$receivablemoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;
			$totalmoney+=$depositmoney-$returndepositmoney;
			//得到券种类
			$ticketname="";
			if(!empty($rval['ticketway'])&&!empty($rval['ticketval'])&&!empty($rval['ticketnum'])){
				$ticketname=$this->getTicketNameById($rval['ticketway']);
			}
			foreach ($rval['food'] as $key=>$val){
				$foodarr[]=array(
						"foodid"=>$val['foodid'],
						"foodname"=>$val['foodname'],
						"foodmoney"=>$val['foodprice']*$val['foodamount'],
						"foodnum"=>$val['foodnum'],
						"foodprice"=>$val['foodprice'],
						"cooktype"=>$val['cooktype'],
						"orderunit"=>$val['foodunit'],
						"foodunit"=>$val['foodunit'],
						"present"=>$val['present'],
				);
			}
			$arr[]=array(
					"billid"=>$billid,
					"cusnum"=>$cusnum,
					"billnum"=>$rval['billnum'],
					"tabname"=>$tabname,
					"totalmoney"=>round($totalmoney),
					"receivablemoney"=>round($receivablemoney),
					"cashmoney"=>round($cashmoney),
					"unionmoney"=>round($unionmoney),
					"vipmoney"=>round($vipmoney),
					"meituanpay"=>round($meituanpay),
					"dazhongpay"=>round($dazhongpay),
					"nuomipay"=>round($nuomipay),
					"otherpay"=>round($otherpay),
					"alipay"=>$alipay,
					"cuspay"=>$cuspay,
					"wechatpay"=>$wechatpay,
					"othermoney"=>round($othermoney),
					"ticketmoney"=>$ticketmoney,
					"ticketname"=>$ticketname,
					"ticketway"=>$ticketway,
					"discountmoney"=>$discountmoney,
					"discountval"=>$discountval,
					"clearmoney"=>$clearmoney,
					"signmoney"=>$signmoney,
					"freemoney"=>$freemoney,
					"depositmoney"=>$depositmoney,
					"returndepositmoney"=>$returndepositmoney,
					"timestamp"=>$timestamp,
					"buytime"=>$buytime,
					"nickname"=>$nickname,
					"orderrequest"=>$rval['orderrequest'],
					"cashierman"=>$cashierman,
					"paytype"=>$paytype,
					"paystatus"=>$paystatus,
					"billstatus"=>$billstatus,
					"food"=>$foodarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTicketNameById()
	 */
	public function getTicketNameById($ticketid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ticketid));
		$oparr=array("coupontype"=>1);
		$ticketname="";
		$result=DALFactory::createInstanceCollection(self::$coupontype)->findOne($qarr,$oparr);
		if(!empty($result)){
			$ticketname=$result['coupontype'];
		}
		return $ticketname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::isRegByphone()
	 */
	public function isRegByphone($phone,$shopid) {
		// TODO Auto-generated method stub
		$bossid=$this->getBossidByShopid($shopid);
		$uid=$this->getUidByphone($phone);
		if(empty($uid)){
			return false;
		}else{
			$qarr=array("uid"=>$uid,"bossid"=>$bossid);
			$oparr=array("_id"=>1);
			$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
			if(!empty($result)){
				return true;
			}else{
				return false;
			}
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getUidByphone()
	 */
	public function getUidByphone($phone) {
		// TODO Auto-generated method stub
		$qarr=array("telphone"=>$phone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		$uid="";
		if(!empty($result)){
			$uid=strval($result['_id']);
		}
		return $uid;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::doChargeRecord()
	 */
	public function doChargeRecord($inputarr) {
		// TODO Auto-generated method stub
		//充值后得到账户余额
		$arr=array(
				"bossid"=>$inputarr['bossid'],
				"shopid"=>$inputarr['shopid'],
				"uid"=>$inputarr['uid'],
				"cardid"=>$inputarr['cardid'],
				"chargemoney"=>$inputarr['chargemoney'],
				"cardrate"=>$inputarr['cardrate'],	
				"accountbalance"=>$inputarr['accountbalance'],
				"timestamp"=>time(),
		);
		DALFactory::createInstanceCollection(self::$chargerecord)->save($arr);
		return strval($arr['_id']);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getVipAccountMoney()
	 */
	public function getVipAccountMoney($uid, $shopid,$cardid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid,"cardid"=>$cardid);
		$oparr=array("accountbalance"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		$accountbalance="0";
		if(!empty($result['accountbalance'])){
			$accountbalance=$result['accountbalance'];
		}
		return $accountbalance;orderrequest;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getChargeResult()
	 */
	public function getChargeResult($recordid) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		if(empty($recordid)){return array();}
		$qarr=array("_id"=>new MongoId($recordid));
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$chargerecord)->findOne($qarr);
		if(!empty($result)){
			$userarr=$this->getUserinfoByUid($result['uid']);
			$phonecrypt = new CookieCrypt($cusphonekey);
			$telphone=$phonecrypt->decrypt($userarr['telphone']);
			$cardarr=$this->getVipcardnameByCardid($result['cardid']);
			$arr=array(
					"userphone"	=>$telphone,
					"nickname"=>$userarr['nickname'],
					"photo"=>$userarr['photo'],
					"userphone"=>$telphone,
					"cardname"=>$cardarr['cardname'],
					"chargemoney"=>$result['chargemoney'],
					"cardrate"=>$result['cardrate'],
					"accountbalance"=>$result['accountbalance'],
					"timestamp"=>$result['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getUserinfoByUid()
	 */
	public function getUserinfoByUid($uid) {
		// TODO Auto-generated method stub
		if (empty($uid) || strlen($uid)!=24){return array();}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("telphone"=>1, "photo"=>1,"nickname"=>1,"sex"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['photo'])){$photo=$result['photo'];}else{$photo="http://jfoss.meijiemall.com/userphoto/default_userphoto.jpg";}
			$arr=array(
					"telphone"=>$result['telphone'],
					"photo"=>$photo,
					"nickname"	=>$result['nickname'],
					"sex"=>$result['sex'],
			);
		}else{
			$result=$this->getWechatUserinfo($uid);
			$arr=array(
					"telphone"=>$result['telphone'],
					"photo"=>$result['headimgurl'],
					"nickname"	=>$result['nickname'],
					"sex"=>$result['sex'],
			);
		}
		return $arr;
	}
	
	public function getWechatUserinfo($uid){
		$qarr=array("uid"=>$uid);
		$oparr=array("nickname"=>1,"headimgurl"=>1,"sex"=>1);
		return DALFactory::createInstanceCollection(self::$wechat_user_info)->findOne($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getVipcardnameByCardid()
	 */
	public function getVipcardnameByCardid($cardid) {
		// TODO Auto-generated method stub
		if(empty($cardid)){return array("cardname"=>"未使用","storeflag"=>"-1");}
		$qarr=array("_id"=>new MongoId($cardid));
		$oparr=array("cardname"=>1,"storeflag"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$vipcard)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array(
					"cardname"=>$result['cardname'],
					"storeflag"=>$result['storeflag'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getChargeRecordData()
	 */
	public function getChargeRecordData($shopid,$starttime,$endtime) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$chargerecord)->find($qarr)->sort(array("timestamp"=>-1));
		foreach ($result as $key=>$val){
			$userarr=$this->getUserinfoByUid($val['uid']);
			$cardarr=$this->getVipcardnameByCardid($val['cardid']);
			$sendmoney=0;
			if(!empty($val['cardrate'])){
				$sendmoney=sprintf("%.0f",$val['chargemoney']/6);
			}
			$arr[]=array(
					"userphone"	=>$val['userphone'],
					"nickname"=>$userarr['nickname'],
					"photo"=>$userarr['photo'],
					"cardname"=>$cardarr['cardname'],
					"chargemoney"=>$val['chargemoney'],
					"sendmoney"=>$sendmoney,
					"accountbalance"=>$val['accountbalance'],
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::isSendToTheUser()
	 */
	public function isSendToTheUser($shopid, $uid, $cardid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid,"cardid"=>$cardid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		if(!empty($result)){//已赠送
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getMyVips()
	 */
	public function getMyVips($bossid,$userphone,$cardid) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		$uid="";
		if(!empty($userphone)){
			$phonecrypt = new CookieCrypt($cusphonekey);
			$enuserphone=$phonecrypt->encrypt($userphone);
			$uid=$this->getUidByphone($enuserphone);
		}
		if(empty($userphone)&&empty($cardid)){
			$qarr=array("bossid"=>$bossid);
		}elseif(empty($userphone) &&!empty($cardid)){
			$qarr=array("bossid"=>$bossid,"cardid"=>$cardid);
		}elseif(!empty($userphone) && empty($cardid)){
			$qarr=array("bossid"=>$bossid,"uid"=>$uid);
		}else{
			$qarr=array("bossid"=>$bossid,"cardid"=>$cardid,"uid"=>$uid);
		}
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$myvip)->find($qarr);
		foreach ($result as $key=>$val){
			$userarr=$this->getUserinfoByUid($val['uid']);
			$cardarr=$this->getVipcardnameByCardid($val['cardid']);
			if(empty($userarr)){continue;}
			$arr[]=array(
					"uid"=>$val['uid'],
					"nickname"=>$userarr['nickname'],
					"photo"=>$userarr['photo'],
					"userphone"=>$userarr['telphone'],
					"sex"=>$userarr['sex'],
					"cardno"=>$val['cardno'],
					"cardname"=>$cardarr['cardname'],
					"storeflag"=>$cardarr['storeflag'],
					"accountbalance"=>$val['accountbalance'],
					"addtime"=>$val['addtime'],
			);
		}
		$arr=$this->array_sort($arr, "addtime","desc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getAccountbance()
	 */
	public function getAccountbance($shopid, $uid, $cardid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid,"cardid"=>$cardid);
		$oparr=array("accountbalance"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		$accountbalance="0";
		if(!empty($result)){
			$accountbalance=$result['accountbalance'];
		}
		return $accountbalance;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::consumeByVipcard()
	 */
	public function consumeByVipcard($inputarr) {
		// TODO Auto-generated method stub
		$accountbalance=$this->getAccountbance($inputarr['shopid'], $inputarr['uid'], $inputarr['cardid']);
		$arr=array(
				"shopid"=>$inputarr['shopid'],
				"uid"=>$inputarr['uid'],
				"cardid"=>$inputarr['cardid'],
				"vippaymoney"=>$inputarr['vippaymoney'],
				"accountbalance"=>$accountbalance-$inputarr['vippaymoney'],
				"timestamp"=>time(),
		);
		DALFactory::createInstanceCollection(self::$viprecord)->save($arr);
		$this->minusAccountBlance($inputarr['shopid'], $inputarr['uid'], $inputarr['cardid'], $inputarr['vippaymoney']);
		return strval($arr['_id']);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::minusAccountBlance()
	 */
	public function minusAccountBlance($shopid, $uid, $cardid, $vippaymoney) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid,"cardid"=>$cardid);
		$oparr=array("\$inc"=>array("accountbalance"=>-$vippaymoney));
		DALFactory::createInstanceCollection(self::$myvip)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getVipConsumeRecord()
	 */
	public function getVipConsumeRecord($viprcid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($viprcid));
		$oparr=array("shopid"=>1,"uid"=>1,"cardid"=>1,"vippaymoney"=>1,"accountbalance"=>1, "timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$viprecord)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$userarr=$this->getUserinfoByUid($result['uid']);
			$cardarr=$this->getVipcardnameByCardid($result['cardid']);
			$arr=array(
					"uid"=>$result['uid'],	
					"photo"=>$userarr['photo'],
					"sex"=>$userarr['sex'],
					"userphone"=>$userarr['telphone'],
					"nickname"=>$userarr['nickname'],
					"cardname"=>$cardarr['cardname'],
					"consumemoney"=>$result['consumemoney'],
					"accountbalance"=>$result['accountbalance'],
					"vippaymoney"=>$result['vippaymoney'],
					"timestamp"=>$result['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getVipPayrecord()
	 */
	public function getVipPayrecord($shopid, $starttime, $endtime) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$viprecord)->find($qarr)->sort(array("timestamp"=>-1));
// 		var_dump($result);exit;
		foreach ($result as $key=>$val){
			$userarr=$this->getUserinfoByUid($val['uid']);
			$cardarr=$this->getVipcardnameByCardid($val['cardid']);
			$billinfo=$this->getOneBillDataByBillid($val['billid']);
			$tabname=$this->getTablenameByTabid($billinfo['tabid']);
			$arr[]=array(
					"userphone"	=>$userarr['telphone'],
					"nickname"=>$userarr['nickname'],
					"tabname"=>$tabname,
					"consumemoney"=>$val['consumemoney'],
					"photo"=>$userarr['photo'],
					"cardname"=>$cardarr['cardname'],
					"accountbalance"=>$val['accountbalance'],
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodOrderByType()
	 */
	public function getFoodOrderByType($shopid) {
		// TODO Auto-generated method stub
		$ftarr=$this->getFoodTypes($shopid);
		$arr=array();
		foreach ($ftarr as $ftkey=>$ftval){
			$foodarr=$this->getFoodsByFtid($ftval['ftid']);
			$arr[]=array(
					"ftid"=>$ftval['ftid'],
					"ftname"=>$ftval['ftname'],
					"food"=>$foodarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodsByFtid()
	 */
	public function getFoodsByFtid($ftid) {
		// TODO Auto-generated method stub
		global $resizeurl;
		$qarr=array("foodtypeid"=>$ftid);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['foodpic'])){$foodpic=$val['foodpic'];}else{$foodpic="http://jfoss.meijiemall.com/food/default_food.png";}

			$arr1=explode("http://", $foodpic);
			$http=$arr1[0];
			$exphttp=$arr1[1];
			$b=explode("/", $exphttp);
			$domain=$b[0];
			$food=$b[1];
			$detail=$b[2];
			$foodpic=$resizeurl."/".$domain."/cache!480x0/".$food."/".$detail;
			
			$zonename=$this->getZonenameByZoneid($val['zoneid']);
			$ftname=$this->getFtnameByFtid($val['foodtypeid']);
			if($val['showout']=="0"){$showout="0";}else{$showout="1";}
			if($val['showserver']=="0"){$showserver="0";}else{$showserver="1";}
			if($val['mustorder']=="1"){$mustorder="1";}else{$mustorder="0";}
			if($val['orderbynum']=="1"){$orderbynum="1";}else{$orderbynum="0";}
			if(!empty($val['foodengname'])){$foodengname=$val['foodengname'];}else{$foodengname="";}
			$foodcooktypearr=array();
			if(!empty($val['foodcooktype'])){
				$foodcooktypearr=explode("、", $val['foodcooktype']);
			}
			$arr[]=array(
					"foodid"=>strval($val['_id']),	
					"foodname"=>$val['foodname'],
					"foodengname"=>$foodengname,
					"foodprice"=>$val['foodprice'],
					"orderunit"=>$val['orderunit'],
					"foodpic"=>$foodpic,
					"foodcode"=>$val['foodcode'],
					"foodunit"=>$val['foodunit'],
					"foodcooktype"=>$foodcooktypearr,
					"zoneid"=>$val['zoneid'],
					"zonename"=>$zonename,
					"foodtypeid"=>$val['foodtypeid'],
					"ftname"=>$ftname,
					"fooddisaccount"=>$val['fooddisaccount'],
					"isweight"=>$val['isweight'],
					"ishot"=>$val['ishot'],
					"ispack"=>$val['ispack'],
					"autostock"=>$val['autostock'],
					"showout"=>$showout,
					"showserver"=>$showserver,
					"foodintro"=>$val['foodintro'],
					"foodguqing"=>$val['foodguqing'],
					"mustorder"=>$val['mustorder'],
					"orderbynum"=>$val['orderbynum'],
					"sortno"=>$val['sortno'],
			);
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodUpTime()
	 */
	public function getFoodUpTime($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("fooduptime"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$fooduptime=0;
		if(!empty($result)){
			$fooduptime=$result['fooduptime'];
		}
		return $fooduptime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::updateFoodData()
	 */
	public function updateFoodData($foodid, $newfoodpic, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("\$set"=>array("foodpic"=>$newfoodpic, "fooduptime"=>$timestamp));
		DALFactory::createInstanceCollection(self::$food)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getOneBillDataByBillid()
	 */
	public function getOneBillDataByBillid($billid) {
		// TODO Auto-generated method stub
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
			$tabstatus=$this->getTabStatusByTabid($result['tabid']);
			$arr['billid']=strval($result['_id']);
			$arr['tabstatus']=$tabstatus;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getOpenHourByShopid()
	 */
	public function getOpenHourByShopid($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return "0";}
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
	 * @see IMonitorOneDAL::getIndexPageData()
	 */
	public function getIndexPageData($shopid) {
		// TODO Auto-generated method stub
		$shopdata=$this->getShopInfo($shopid);
		$theday=$this->getTheday($shopid);
		
		$todaydata=$this->getDaySheetData($shopid,$theday,"all");
		//获取店员是出错
		$serverdata=$this->getServersByShopid($shopid);
		  
		return 
		array(
		   "shopdata"=>$shopdata,
		    "todaydata"=>$todaydata,
		    "serverdata"=>$serverdata
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getShopInfo()
	 */
	public function getShopInfo($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("logo"=>1,"shopname"=>1,"city"=>1,"district"=>1, "road"=>1,"shoptype"=>1,"addtime"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$shoptypename=$this->getShopTypenameByShoptye($result['shoptype']);
			$arr=array(
					"logo"=>$result['logo'],
					"shopname"	=>$result['shopname'],
					"city"=>$result['city'],
					"district"=>$result['district'],
					"road"=>$result['road'],
					"shoptypename"=>$shoptypename,
					"addtime"=>$result['addtime'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getShopTypenameByShoptye()
	 */
	public function getShopTypenameByShoptye($shoptype) {
		// TODO Auto-generated method stub
		if(empty($shoptype)){return "";}
		$qarr=array("_id"=>new MongoId($shoptype));
		$oparr=array("shoptypename"=>1);
		$shoptypename="";
		$result=DALFactory::createInstanceCollection(self::$shoptype)->findOne($qarr,$oparr);
		if(!empty($result)){
			$shoptypename=$result['shoptypename'];
		}
		return $shoptypename;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getServerBill()
	 */
	public function getServersByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("servername"=>1,"uid"=>1,"workstatus"=>1,"serverno"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->find($qarr,$oparr);
		$arr=array();
		$theday=$this->getTheday($shopid);
		
		foreach ($result as $key=>$val){
		    //错误在这里
			$serverbill=$this->getBillStatisticByServerid($shopid, $val['uid'], $theday);
		
			$userarr=$this->getUserinfoByUid($val['uid']);
			$arr[]=array(
					"servername"=>$val['servername'],
					"sex"=>$userarr['sex'],
					"photo"=>$userarr['photo'],
					"workstatus"=>$val['workstatus'],
					"serverno"=>$val['serverno'],
					"cusnum"=>$serverbill['cusnum'],
					"billnum"=>$serverbill['billnum'],
					"totalmoney"=>$serverbill['totalmoney'],
					"overtotalmoney"=>$serverbill['overtotalmoney'],
			);
		}
		
		$arr=$this->array_sort($arr, "billnum","desc");
		return $arr;
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getBillStatisticByServerid()
	 */
	public function getBillStatisticByServerid($shopid,$uid,$theday) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("uid"=>$uid,"timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("_id"=>1, "cusnum"=>1,"paystatus"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$cusnum=0;
		$billnum=0;
		$totalmoney=0;
		$overtotalmoney=0;
		//此处预计有bug
		foreach ($result as $key=>$val){
		    
			$cusnum+=$val['cusnum'];
			$billnum++;
			
			$total_discountfoodmoney=$this->getTotalmoneyAndFooddisaccountmoney(strval($val['_id']));
			$totalmoney+=$total_discountfoodmoney['totalmoney'];//点菜金额
			if($val['paystatus']=="paid"){
				$overtotalmoney+=$total_discountfoodmoney['totalmoney'];
			}
			$totalmoney+=$val['othermoney'];
			$overtotalmoney+=$val['othermoney'];
		}
		return array(
				"cusnum"=>$cusnum,
				"billnum"=>$billnum,
				"totalmoney"=>$totalmoney,
				"overtotalmoney"=>$overtotalmoney,
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTheday()
	 */
	public function getTheday($shopid) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$newhour=date("H",time());
		if($newhour>=$openhour){//说明是前一天
			$theday=date("Y-m-d",time());
		}else{//说明是后一天
			$theday=date("Y-m-d",strtotime(date("Y-m-d",time()))-86400);
		}
		return $theday;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getSignDetails()
	 */
	public function getSignDetails($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","signover"=>"0");
			
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::delOneBillByBillid()
	 */
	public function delOneBillByBillid($billid) {
		// TODO Auto-generated method stub
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$this->recoverStockByBilid($billid);
		DALFactory::createInstanceCollection(self::$bill)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::judgePhoneInMyShopid()
	 */
	public function judgePhoneInMyShopid($userphone, $shopid) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		global $newphonekey;
		//加密成顾客手机号
		$phonecrypt = new CookieCrypt($cusphonekey);
		$telphone=$phonecrypt->encrypt($userphone);
		//加密成商家手机号
		$phonecrypt = new CookieCrypt($newphonekey);
		$mobilphone=$phonecrypt->encrypt($userphone);
		
		$sqarr=array("mobilphone"=>$mobilphone);
		$oparr=array("_id"=>1);
		$shopresult=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($sqarr,$oparr);
		if(!empty($shopresult)){
			return true;
		}
		$cqarr=array("telphone"=>$telphone);
		$cusresult=DALFactory::createInstanceCollection(self::$customer)->findOne($cqarr,$oparr);
		if(!empty($cusresult)){
			//查看是否为本店服务人员
			$uid=strval($cusresult['_id']);
			return $this->isServerInMyShop($shopid, $uid);
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::isServerInMyShop()
	 */
	public function isServerInMyShop($shopid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		if(!$result){
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodinfoByFoodid()
	 */
	public function getFoodinfoByFoodid($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodname"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("foodname"=>$result['foodname']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getDaliAccount()
	 */
	public function getDaliAccount($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("agentid"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopagent)->findOne($qarr,$oparr);
		$agentaccount="";
		if(!empty($result)){
			$agentaccount=$this->getAgentAccountByAgentid($result['agentid']);
			if(!empty($agentaccount)){//已设置账号
				return array("status"=>"dali_ok","agentaccount"=>$agentaccount);
			}else{//已设置账号
				return array("status"=>"dali_unset","agentaccount"=>$agentaccount);
			}
		}else{//不属于代理商
			return array("status"=>"dali_none","agentaccount"=>$agentaccount);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getAgentAccountByAgentid()
	 */
	public function getAgentAccountByAgentid($agentid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($agentid));
		$oparr=array("agentaccount"=>1);
		$result=DALFactory::createInstanceCollection(self::$agent)->findOne($qarr,$oparr);
		$agentaccount="";
		if(!empty($result)){
			if(!empty($result['agentaccount'])){$agentaccount=$result['agentaccount'];}
		}
		return $agentaccount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::addToDaliAccount()
	 */
	public function addToDaliAccount($shopid, $income) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("agentid"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopagent)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oldincome=$this->getAgentIncome($result['agentid']);
			$newincome=$oldincome+$income;
			$qarr=array("_id"=>new MongoId($result['agentid']));
			$oparr=array("\$set"=>array("income"=>$newincome));
			DALFactory::createInstanceCollection(self::$agent)->update($qarr,$oparr);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getAgentIncome()
	 */
	public function getAgentIncome($agentid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($agentid));
		$oparr=array("income"=>1);
		$result=DALFactory::createInstanceCollection(self::$agent)->findOne($qarr,$oparr);
		$income=0;
		if(!empty($result['income'])){
			$income=$result['income'];
		}
		return $income;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::buyAccountRecord()
	 */
	public function buyAccountRecord($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$buy_account_record)->save($inputarr);
		return strval($inputarr['_id']);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getBuyAccountRecord()
	 */
	public function getBuyAccountRecord($buyid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($buyid));
		$oparr=array(
				"shopid"=>1,
				"tradeno"=>1,
				"buyer_email"=>1,
				"alipaytradeno"	=>1,
				"paytime"=>1,
				"buytype"=>1,
				"paymoney"=>1,
				"paystatus"=>1,
				"endtime"=>1,
		);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$buy_account_record)->findOne($qarr,$oparr);
		if(!empty($result)){
			$shoparr=$this->getShopInfo($result['shopid']);
			$arr=array(
					"shopid"=>$result['shopid'],
					"shopname"=>$shoparr['shopname'],
					"logo"=>$shoparr['logo'],
					"tradeno"=>$result['tradeno'],
					"alipaytradeno"	=>$result['alipaytradeno'],
					"paytime"=>$result['paytime'],
					"buytype"=>$result['buytype'],
					"paymoney"=>$result['paymoney'],
					"endtime"=>$result['endtime'],
			);
		}
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::addShopUseAccount()
	 */
	public function addShopUseAccount($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['shopid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shop_use_account)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$set"=>
					array(
						"buytime"=>$inputarr['buytime'],
						"endtime"=>$inputarr['endtime'],
						"accounttype"=>$inputarr['accounttype'],
					)
			);
			DALFactory::createInstanceCollection(self::$shop_use_account)->update($qarr,$oparr);
		}else{
			$arr=array(
					"shopid"=>$inputarr['shopid'],
					"buytime"=>$inputarr['buytime'],
					"endtime"=>$inputarr['endtime'],
					"accounttype"=>$inputarr['accounttype'],
			);
			DALFactory::createInstanceCollection(self::$shop_use_account)->save($arr);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getShopuseaccountEndtime()
	 */
	public function getShopuseaccountEndtime($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("endtime"=>1);
		$endtime=time();
		$result=DALFactory::createInstanceCollection(self::$shop_use_account)->findOne($qarr,$oparr);
		if(!empty($result['endtime'])){
			$endtime=$result['endtime'];
			$endtime=$endtime>time()?$endtime:time();//最及时的
		}
		return $endtime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getShopaccounttype()
	 */
	public function getShopaccounttype($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("endtime"=>1,"accounttype"=>1);
		$accounttype="";
		$leftday="0";
		$result=DALFactory::createInstanceCollection(self::$shop_use_account)->findOne($qarr,$oparr);
		if(!empty($result)){
			$accounttype=$result['accounttype'];//标准、高级
			$leftday=sprintf("%.0f",($result['endtime']-time())/86400);
		}else{
			$shoparr=$this->getShopInfo($shopid);
			$regtime=$shoparr['addtime'];
			if((time()-$regtime)/86400>15){
				$accounttype="free";
				$leftday=15-sprintf("%.0f",(time()-$regtime)/86400);
			}else{
				$accounttype="try";
				$leftday=15-sprintf("%.0f",(time()-$regtime)/86400);
			}
		}
		return array("accounttype"=>$accounttype,"leftday"=>$leftday);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getBuyAccountRecords()
	 */
	public function getBuyAccountRecords($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("tradeno"=>1,"buyer_email"=>1,"alipaytradeno"=>1,"paytime"=>1,"buytype"=>1,"paymoney"=>1,"endtime"=>1);
		$result=DALFactory::createInstanceCollection(self::$buy_account_record)->find($qarr,$oparr)->sort(array("paytime"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"tradeno"	=>$val['tradeno'],
					"buyer_email"=>$val['buyer_email'],
					"alipaytradeno"=>$val['alipaytradeno'],
					"paytime"=>$val['paytime'],
					"buytype"=>$val['buytype'],
					"paymoney"=>$val['paymoney'],
					"endtime"=>$val['endtime'],
			);
		}
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getDonateRecords()
	 */
	public function getDonateRecords($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("donatemonth"=>1,"donatereason"=>1,"donatefrom"=>1,"addtime"=>1,"endtime"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$donate_account_record)->find($qarr,$oparr)->sort(array("addtime"=>-1));
		foreach ($result as $key=>$val){
			$arr[]=array(
					"donatemonth"=>$val['donatemonth'],
					"donatereason"=>$val['donatereason'],
					"donatefrom"=>$val['donatefrom'],
					"addtime"=>$val['addtime'],
					"endtime"=>$val['endtime'],
			);	
		}
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::discountOneFood()
	 */
	public function discountOneFood($billid,$foodid, $discountval) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				if($foodid==$val['foodid']&&empty($val['present'])){
					$arr[]=array(
							"foodid"=>$val['foodid'],
							"foodname"=>$val['foodname'],
							"foodprice"=>floor($val['foodprice']*$discountval/100),
							"foodunit"=>$val['foodunit'],
							"orderunit"=>$val['orderunit'],
							"foodnum"=>$val['foodnum'],
							"foodamount"=>$val['foodamount'],
							"ftid"=>$val['ftid'],
							"zoneid"=>$val['zoneid'],
							"zonename"=>$val['zonename'],
							"fooddisaccount"=>$val['fooddisaccount'],
							"cooktype"=>$val['cooktype'],
							"foodrequest"=>$val['foodrequest'],
							"isweight"=>$val['isweight'],
							"ishot"=>$val['ishot'],
							"ispack"=>$val['ispack'],
							"present"=>$val['present'],
							"confrimweight"=>$val['confrimweight'],//默认已确认
					);
				}else{
					$arr[]=$val;
				}
			}
		}
		$oparr=array("\$set"=>array("food"=>$arr));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getTablenameByTabid()
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
	 * @see IWorkerDAL::getPayPageData()
	*/
	public function getPayPageData($billid, $shopid) {
		// TODO Auto-generated method stub
		$calcfood=$this->getTotalmoneyAndFooddisaccountmoney($billid);
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"coupontype"=>1);
		$result=DALFactory::createInstanceCollection(self::$coupontype)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$ctypearr[]=array("ctypeid"=>strval($val['_id']),"coupontype"=>$val['coupontype']);
		}
		$deposit=$this->getOneDesposit($billid);
		$depositmoney=$this->getDepositmoney($shopid);
		if($deposit=="1"){
			$getdepositmoney=$depositmoney;
		}
		$topclearmoney=$this->getTopClearmoney($shopid);
		return array(
				"totalmoney"=>strval($calcfood['totalmoney']+$getdepositmoney),
				"fooddisaccountmoney"=>strval($calcfood['fooddisaccountmoney']),
				"topclearmoney"=>$topclearmoney,
				"deposit"=>$deposit,
				"depositmoney"=>$depositmoney,
				"ctype"=>$ctypearr,
		);
	}
	
	public function getOneDesposit($billid){
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("deposit"=>1);
		$deposit="0";
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result['deposit'])){
			$deposit=$result['deposit'];
		}
		return $deposit;
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
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getAddBillOrderData()
	 */
	public function getAddBillOrderData($shopid,$starttime,$endtime) {
		// TODO Auto-generated method stub "shopid"=>$shopid,
		$qarr=array("timestamp"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$oparr=array("oldbillid"=>1,"food"=>1);
		$result=DALFactory::createInstanceCollection(self::$otherbill)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"oldbillid"=>$val['oldbillid'],
					"food"=>$val['food'],
			);
		}
		return $arr;
		
	}

	/* (non-PHPdoc)
	 * @see IQrcodeDAL::getQrcodeRecordData()
	*/
	public function getQrcodeRecordData($shopid,$starttime,$endtime) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"gmt_payment"=>array("\$gte"=>strtotime($starttime),"\$lte"=>strtotime($endtime)));
		$oparr=array(
				"gmt_payment"=>1,//交易付款时间
				"buyer_email"	=>1,//顾客支付宝账户
				"trade_no"=>1,//交易号
				"total_fee"=>1,//付款总额
				"gmt_create"=>1,//交易创建时间
				"out_trade_no"=>1,//网站商户唯一订单号
				"subject"=>1,//商品名称
				"out_trade_no"=>1,//商户订单号
				"trade_status"=>1,//交易状态
		);
		$result=DALFactory::createInstanceCollection(self::$qrpayrecord)->find($qarr,$oparr)->sort(array("gmt_payment"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"gmt_payment"=>$val['gmt_payment'],//交易付款时间
					"buyer_email"	=>$val['buyer_email'],//顾客支付宝账户
					"trade_no"=>$val['trade_no'],//交易号
					"total_fee"=>$val['total_fee'],//付款总额
					"gmt_create"=>$val['gmt_create'],//交易创建时间
					"out_trade_no"=>$val['out_trade_no'],//网站商户唯一订单号
					"subject"=>$val['subject'],//商品名称
					"out_trade_no"=>$val['out_trade_no'],
					"trade_status"=>$val['trade_status'],//交易状态
			);
		}
		return $arr;
	}
	
	public function getDaysSheetData($shopid,  $startdate, $enddate){
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($startdate." ".$openhour.":0:0");
		$endtime=strtotime($enddate." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		$ticketallarr=$this->getAllTickets($shopid);
		$depositmoney=$this->getDepositmoney($shopid);
		foreach ($result as $key=>$val){
			$tabname=$this->getTablenameByTabid($val['tabid']);
			if(strstr($tabname,"测试")!=false || strstr($tabname, "test")!=false){
				continue;
			}
			$totalmoney_fooddisaccountmoney=$this->getTotalmoneyAndFooddisaccountmoney(strval($val['_id']));
			$totalmoney=$totalmoney_fooddisaccountmoney['totalmoney'];
			$discountfoodmoney=$totalmoney_fooddisaccountmoney['fooddisaccountmoney'];
			$cashmoney=$val['cashmoney'];
			$unionmoney=$val['unionmoney'];
			$vipmoney=$val['vipmoney'];
			$meituanpay=$val['meituanpay'];
			$dazhongpay=$val['dazhongpay'];
			$nuomipay=$val['nuomipay'];
			$otherpay=$val['otherpay'];
			$alipay=$val['alipay'];
			$wechatpay=$val['wechatpay'];
			$clearmoney=$val['clearmoney'];
			$othermoney=$val['othermoney'];
			$ticketmoney=$val['ticketval']*$val['ticketnum'];
			$ticketnum=$val['ticketnum'];
			$ticketarr=array();
			if(!empty($val['ticketway'])&&!empty($val['ticketval'])&&!empty($val['ticketnum'])){
				if(array_key_exists($val['ticketway'], $ticketarr)){
					$ticketarr[$val['ticketway']]['ticketmoney']+=$ticketmoney;
					$ticketarr[$val['ticketway']]['ticketnum']+=$ticketnum;
				}else{
					$ticketname=$this->getTicketNameById($val['ticketway']);
					$ticketarr[$val['ticketway']]=array(
							"ticketid"=>$val['ticketway'],
							"ticketname"=>$ticketname,
							"ticketnum"=>$ticketnum,
							"ticketmoney"=>$ticketmoney,
					);
				}
			}
			
			foreach ($ticketallarr as $tkid=>$tkval){
				if(!array_key_exists($tkid, $ticketarr)){
					$ticketarr[$tkid]=array(
							"ticketid"=>$tkid,
							"ticketname"	=>$tkval,
							"ticketnum"=>"0",
							"ticketmoney"=>"0",
					);
				}
			}
			ksort($ticketarr);
			if($val['discountmode']=="part"){
				$discountmoney=ceil((1-$val['discountval']/100)*$discountfoodmoney);
			}else{
				$discountmoney=ceil((1-$val['discountval']/100)*$totalmoney);
			}
			
			$molingmoney=$val['molingmoney'];
			$signmoney=$val['signmoney'];
			$freemoney=$val['freemoney'];
			$onedepositmoney=0;
			if($val['deposit']=="1"){
				$onedepositmoney=$depositmoney;
			}
			$returndepositmoney="0";
			if(!empty($val['returndepositmoney'])){
				$returndepositmoney=$val['returndepositmoney'];
			}			
			$billnum=0;
			$billnum++;
			$cusnum=$val['cusnum'];
			$receivablemoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;//$signmoney
			$totalmoney+=$onedepositmoney-$returndepositmoney;
			//判断是哪一天
			$newhour=date("H",$val['timestamp']);
			if($newhour>=$openhour){//说明是前一天
				$oneday=date("Y-m-d",$val['timestamp']);
			}else{//说明是后一天
				$oneday=date("Y-m-d",$val['timestamp']-86400);
			}
			
			if(array_key_exists($oneday, $arr)){
				$arr[$oneday]['totalmoney']+=round($totalmoney);
				$arr[$oneday]['receivablemoney']+=round($receivablemoney);
				$arr[$oneday]['billnum']+=$billnum;
				$arr[$oneday]['cusnum']+=$cusnum;
				$arr[$oneday]['cashmoney']+=round($cashmoney);
				$arr[$oneday]['unionmoney']+=round($unionmoney);
				$arr[$oneday]['vipmoney']+=round($vipmoney);
				$arr[$oneday]['meituanpay']+=round($meituanpay);
				$arr[$oneday]['dazhongpay']+=round($dazhongpay);
				$arr[$oneday]['nuomipay']+=round($nuomipay);
				$arr[$oneday]['otherpay']+=round($otherpay);
				$arr[$oneday]['alipay']+=$alipay;
				$arr[$oneday]['wechatpay']+=$wechatpay;
				$arr[$oneday]['clearmoney']+=$clearmoney;
				$arr[$oneday]['othermoney']+=round($othermoney);
				$arr[$oneday]['ticketmoney']+=$ticketmoney;
				if(!empty($val['ticketway'])){
					$arr[$oneday]['ticket'][$val['ticketway']]['ticketnum']+=$ticketnum;
					$arr[$oneday]['ticket'][$val['ticketway']]['ticketmoney']+=$ticketmoney;
				}
				$arr[$oneday]['discountmoney']+=$discountmoney;
				$arr[$oneday]['signmoney']+=$signmoney;
				$arr[$oneday]['freemoney']+=$freemoney;
				$arr[$oneday]['depositmoney']+=$onedepositmoney;
				$arr[$oneday]['returndepositmoney']+=$returndepositmoney;
			}else{
				$arr[$oneday]=array(
						"totalmoney"=>round($totalmoney),
						"receivablemoney"=>round($receivablemoney),
						"billnum"=>$billnum,
						"cusnum"=>$cusnum,
						"cashmoney"=>round($cashmoney),
						"unionmoney"=>round($unionmoney),
						"vipmoney"=>round($vipmoney),
						"meituanpay"=>round($meituanpay),
						"dazhongpay"=>round($dazhongpay),
						"nuomipay"=>round($nuomipay),
						"otherpay"=>round($otherpay),
						"alipay"=>$alipay,
						"wechatpay"=>$wechatpay,
						"clearmoney"=>$clearmoney,
						"othermoney"=>round($othermoney),
						"ticketmoney"=>$ticketmoney,
						"ticket"=>$ticketarr,
						"discountmoney"=>$discountmoney,
						"signmoney"=>$signmoney,
						"freemoney"=>$freemoney,
						"depositmoney"=>$onedepositmoney,
						"returndepositmoney"=>$returndepositmoney,
				);
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::addCusSheetAdvData()
	 */
	public function addCusSheetAdvData($shopid, $content,$advurl) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"content"=>$content,"advurl"=>urlencode($advurl));
		DALFactory::createInstanceCollection(self::$receiptadv)->save($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getCusSheetAdvData()
	 */
	public function getCusSheetAdvData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "content"=>1,"advurl"=>1);
		$result=DALFactory::createInstanceCollection(self::$receiptadv)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("advid"=>strval($val['_id']),"content"=>$val['content'],"advurl"=>$val['advurl']);
		}
		return $arr;
	}
	
	public function delOneAdvByAdvid($advid){
		$qarr=array("_id"=>new MongoId($advid));
		DALFactory::createInstanceCollection(self::$receiptadv)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getZonenameByZoneid()
	 */
	public function getZonenameByZoneid($zoneid) {
		// TODO Auto-generated method stub
		if(empty($zoneid)){return "";}
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("zonename"=>1);
		$zonename="";
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		if(!empty($result)){
			$zonename=$result['zonename'];
		}
		return $zonename;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFtnameByFtid()
	 */
	public function getFtnameByFtid($ftid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("foodtypename"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		$ftname="";
		if(!empty($result)){
			$ftname=$result['foodtypename'];
		}
		return $ftname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorTwoDAL::getTabStatusByTabid()
	*/
	public function getTabStatusByTabid($tabid) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return "empty";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabstatus"=>1);
		$tabstatus="empty";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result['tabstatus'])){
			$tabstatus=$result['tabstatus'];
		}
		return $tabstatus;
	}
	
	public function getSwitchPayerTime($shopid){
		$qarr=array("shopid"=>$shopid,"role"=>"cashierman");
		$oparr=array("timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$timestamp=0;
		if(!empty($result['timestamp'])){
			$timestamp=$result['timestamp'];
		}
		return $timestamp;
	}
	public function getTopClearmoney($shopid){
		if(empty($shopid)){return "0";}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("topclearmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$topclearmoney="10000";
		if(!empty($result['topclearmoney'])){
			$topclearmoney=$result['topclearmoney'];
		}
		return $topclearmoney;
	}
	
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
	
	public function getServername($shopid, $uid){
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("servername"=>1);
		$servername="";
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		if(!empty($result['servername'])){
			$servername=$result['servername'];
		}
		return $servername;
	}
	
	public function hasBillBeforeTheTab($shopid,$tabid, $timestamp){
		$qarr=array("shopid"=>$shopid,"tabid"=>$tabid, "timestamp"=>array("\$gt"=>$timestamp));
		return DALFactory::createInstanceCollection(self::$bill)->count($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::updateBillFood()
	 */
	public function updateBillFood($foodarr, $billid, $returnnum, $foodid, $foodnum, $cooktype) {
		// TODO Auto-generated method stub
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		foreach ($foodarr as $key=>$val){
			if($val['cooktype']==null || $val['cooktype']==""){
				$cooktypeflag=true;
			}elseif($cooktype==$val['cooktype']){
				$cooktypeflag=true;
			}else{
				$cooktypeflag=false;
			}
			if($foodid==$val['foodid']&&$foodnum==$val['foodnum']&& $cooktypeflag){
			    $this->recoverStock($val['foodid'], $val['foodnum']);//恢复该食物的库存数量
				$returnamount=($val['foodamount']/$val['foodnum'])*$returnnum;
				if($val['foodnum']>$returnnum &&$val['foodamount']>$returnamount ){
					$foodarr[$key]['foodamount']=strval($val['foodamount']-$returnamount);
					$foodarr[$key]['foodnum']=strval($val['foodnum']-$returnnum);
				}else{
					unset($foodarr[$key]);//完全退，则删除此美食
				}
				break;
			}
		}
		$newfoodarr=array();
		foreach ($foodarr as $fval){
			$newfoodarr[]=$fval;
		}
		$oparr=array("\$set"=>array("food"=>$newfoodarr));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getFoodsByBillid()
	 */
	public function getFoodsByBillid($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=$result['food'];
		}
		return $arr;
	}
	
	public function getOrginbillTime($orginbillid){
		$qarr=array("_id"=>new MongoId($orginbillid));
		$oparr=array("timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$timestamp="0";
		if(!empty($result)){
			$timestamp= $result['timestamp'];
		}
		return $timestamp;
	}
	
	public function changeShowtypeStatus($ftid,$status){
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("\$set"=>array("showstatus"=>$status));
		DALFactory::createInstanceCollection(self::$foodtype)->update($qarr,$oparr);
	}
	
	public function getServerIdByServerphone($userphone){
		$qarr=array("telphone"=>$userphone);
		$oparr=array("_id"=>1,"passwd"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		$uid="0";
		$passwd="";
		if(!empty($result)){
			$uid=strval($result['_id']);
			$passwd=$result['passwd'];
		}
		return array("uid"=>$uid,"serverpasswd"=>$passwd);
	}
	
	public function isTheShopServerByUid($shopid,$uid){
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("_id"=>1,"roleid"=>1,"servername"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$exist=false;
		$roleid="0";
		$servername="";
		$serverid="";
		if(!empty($result)){
			$exist=true;
			$roleid=$result['roleid'];
			$servername=$result['servername'];
			$serverid=strval($result['serverid']);
		}
		return array("serverid"=>$serverid, "exist"=>$exist,"roleid"=>$roleid,"servername"=>$servername);
	}
	public function getManagerRoleid($shopid){
		$qarr=array("shopid"=>$shopid,"returnfood"=>"1");
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$role)->findOne($qarr,$oparr);
		$roleid="";
		if(!empty($result)){
			$roleid=strval($result['_id']);
		}
		return $roleid;
	}
	public function isTheServerManager($roleid){
		if(empty($roleid)){return "server";}
		$qarr=array("_id"=>new MongoId($roleid));
		$oparr=array("returnfood"=>1);
		$result=DALFactory::createInstanceCollection(self::$role)->findOne($qarr,$oparr);
		$role="server";
		if(!empty($result)){
			if($result['returnfood']=="1"){
				$role="manager";
			}
		}
		return $role;
	}
	
	public function getAllTickets($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"coupontype"=>1);
		$result=DALFactory::createInstanceCollection(self::$coupontype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[strval($val['_id'])]=$val['coupontype'];
		}
		ksort($arr);
		return $arr;
	}
	public function getFootypeImgUptime($ftid){
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("upftime"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		$upftime=0;
		if(!empty($result['upftime'])){
			$upftime=$result['upftime'];
		}
		return $upftime;
	}
	
	public function updateFoodtypeData($ftid, $newfypepic,$timestamp){
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("\$set"=>array("ftpic"=>$newfypepic,"upftime"=>$timestamp));
		DALFactory::createInstanceCollection(self::$foodtype)->update($qarr,$oparr);
	}
	
	public function getTablesData($shopid){
		$arr=array();
		$zonearr=$this->getZonesByShopid($shopid);
		foreach ($zonearr as $zkey=>$zval){
			$qarr=array("shopid"=>$shopid,"zoneid"=>$zval['zoneid']);
			$oparr=array("tabname"=>1,"seatnum"=>1,"tabswitch"=>1,"printerid"=>1,"sortno"=>1,"bookflag"=>1);
			$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
			$tablearr=array();
			foreach ($result as $key=>$val){
				if(empty($val['sortno'])){$sortno="0";}else{$sortno=$val['sortno'];}
				$printername=$this->getPrinternameByPid($val['printerid']);
				$tablearr[]=array(
						"tabid"=>strval($val['_id']),
						"tabname"=>$val['tabname'],
						"seatnum"=>$val['seatnum'],
						"tabswitch"=>$val['tabswitch'],
						"printerid"=>$val['printerid'],
						"bookflag"=>$val['bookflag'],
						"sortno"=>$sortno,
						"printername"=>$printername,
				);
			}
// 			if(!empty($tablearr)){
				$tablearr=$this->array_sort($tablearr, "sortno","asc");
				$arr[$zval['zoneid']]=array(
						"zoneid"=>$zval['zoneid'],
						"zonename"=>$zval['zonename'],
						"tables"=>$tablearr,
				);
// 			}
			$newarr=array();
			foreach ($arr as $nkey=>$nval){
				$newarr[]=$nval;
			}
		}
		return $newarr;
	}
	
	public function getPrinternameByPid($pid){
		if(empty($pid)){return "";}
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("printername"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		$printername="";
		if(!empty($result['printername'])){
			$printername=$result['printername'];
		}
		return $printername;
	}
	
	public function getZonesByShopid($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("zonename"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"zoneid"=>strval($val['_id']),
					"zonename"=>$val['zonename'],
					"sortno"=>$val['sortno'],
			);
		}
		return $this->array_sort($arr, "sortno","asc");
	}
	
	public function getOneTableData($tabid,$typeno){
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabname"=>1,"seatnum"=>1,"zoneid"=>1,"printerid"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$printername=$this->getPrinternameByPid($result['printerid']);
			$zonename=$this->getZonenameByZoneid($result['zoneid']);
			if(empty($result['sortno'])){$sortno="";}else{$sortno=$result['sortno'];}
			$arr=array(
					"tabid"=>strval($result['_id']),
					"tabname"=>$result['tabname'],
					"seatnum"=>$result['seatnum'],
					"zoneid"=>$result['zoneid'],
					"zonename"=>$zonename,
					"printerid"=>$result['printerid'],
					"printername"=>$printername,
					"sortno"=>$sortno,
					"typeno"=>$typeno,
			);
		}
		return $arr;
	}
	
	public function saveOneTable($inputarr){
		DALFactory::createInstanceCollection(self::$table)->save($inputarr);
	}
	
	public function updateOneTable($tabid,$inputarr){
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("\$set"=>array(
				"tabname"=>$inputarr['tabname'],
				"seatnum"=>$inputarr['seatnum'],
				"zoneid"=>$inputarr['zoneid'],
				"printerid"=>$inputarr['printerid'],
				"sortno"=>$inputarr['sortno'],
		));
		DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
	}
	
	public function delOneTableData($tabid){
		$qarr=array("_id"=>new MongoId($tabid));
		DALFactory::createInstanceCollection(self::$table)->remove($qarr);
	}
	
	public function generTabQrcodeImg($inputarr){
		global $root_url;
		global $tabqrcode_url;
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($inputarr['deviceno']);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($inputarr['devicekey']);
		$tabname=$this->getTablenameByTabid($inputarr['tabid']);
		$orderInfo='';
		$orderInfo .= '<CB>'.$tabname.'</CB><BR>';
		$orderInfo .= '<C>扫一扫，马上点餐</C><BR>';
// 		$orderInfo.='<QRcode>'.$root_url.'weshop/shopindex.php?access=tab&shopid='.$inputarr['shopid'].'&deskno='.$inputarr['tabid'].'</QRcode>';
		$orderInfo.='<QRcode>'.$tabqrcode_url.'?m=Admin&c=Index&a=doorbutton&type=inhouse&deskno='.$inputarr['tabid'].'</QRcode>';
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	public function getCardrate($cardid){	
		if(empty($cardid)){return "0";}
		$qarr=array("_id"=>new MongoId($cardid));
		$oparr=array("cardrate"=>1);
		$cardrate="0";
		$result=DALFactory::createInstanceCollection(self::$vipcard)->findOne($qarr,$oparr);
		if(!empty($result['cardrate'])){
			$cardrate=$result['cardrate'];
		}
		return $cardrate;
	}
	
	public function getTabdataByShopid($shopid){
		if(empty($shopid)){return "0";}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("tabdata"=>1);
		$tabdata="0";
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result['tabdata'])){
			$tabdata=$result['tabdata'];
		}
		return $tabdata;
	}
	
	public function delOneBerforeBill($beforebillid){
		if(empty($beforebillid)){return ;}
		$qarr=array("_id"=>new MongoId($beforebillid));
		DALFactory::createInstanceCollection(self::$beforebill)->remove($qarr);
	}
	
	public function getFoodsByBeforeBillid($billid){
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$beforebill)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=$result['food'];
		}
		return $arr;
	}
	
	public function getMyvipinfo($shopid,$cardno){
		global $cusphonekey;
		$qarr=array("shopid"=>$shopid,"cardno"=>$cardno);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr);
		if(!empty($result)){
			$userarr=$this->getUserinfoByUid($result['uid']);
			$phonecrypt = new CookieCrypt($cusphonekey);
			$telphone=$phonecrypt->decrypt($userarr['telphone']);
			$cardarr=$this->getVipcardnameByCardid($result['cardid']);
			$arr=array(
					"uid"=>$result['uid'],
					"telphone"=>$telphone,
					"cardid"=>$result['cardid'],
					"accountbalance"=>$result['accountbalance'],
					"cardno"=>$result['cardno'],
			);
		}
		return $arr;
	}
	
	public function consumeVipMoney($shopid,$cardno,$money){
		$qarr=array("shopid"=>$shopid,"cardno"=>$cardno);
		$oparr=array("_id"=>1,"accountbalance"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		if(!empty($result)){
			if($result['accountbalance']>=$money){
				$newaccountbalance=$result['accountbalance']-$money;
			}
		}
		$oparr=array("\$set"=>array("accountbalance"=>$newaccountbalance));
		DALFactory::createInstanceCollection(self::$myvip)->update($qarr,$oparr);
	}
	public function intoVipConsumeRecord($inputarr){
		DALFactory::createInstanceCollection(self::$viprecord)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getBossidByShopid()
	 */
	public function getBossidByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("bossid"=>1);
		$result=DALFactory::createInstanceCollection(self::$subaccount)->findOne($qarr,$oparr);
		$bossid="";
		if(!empty($result)){
			$bossid=strval($result['bossid']);
		}
		return $bossid;
	}
	
	public function getUnPayCheckData($shopid){		
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>time()-3*86400));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr);
		$arr=array();
		foreach($result as $rkey=>$rval){
			$billid=strval($rval['_id']);
			$paidbillarr=$this->getUnpaidSheetData($billid);
			if($paidbillarr['totalmoney']==$paidbillarr['paidmoney'] && !empty($paidbillarr['paidmoney'])){continue;}
			$foodarr=array();
			$cusnum=$rval['cusnum'];
			$total_disfoodmoney=$this->getTotalmoneyAndFooddisaccountmoney($billid);
			$totalmoney=$total_disfoodmoney['totalmoney'];
			$cashmoney=$rval['cashmoney'];
			$unionmoney=$rval['unionmoney'];
			$vipmoney=$rval['vipmoney'];
			$meituanpay=$rval['meituanpay'];
			$dazhongpay=$rval['dazhongpay'];
			$nuomipay=$rval['nuomipay'];
			$otherpay=$rval['otherpay'];
			$alipay=$rval['alipay'];
			$wechatpay=$rval['wechatpay'];
			$othermoney=$rval['othermoney'];
			$ticketmoney=$rval['ticketval']*$rval['ticketnum'];
			$ticketway=$rval['ticketway'];
			if($rval['discountmode']=="part"){
				$discountmoney=ceil($total_disfoodmoney['fooddisaccountmoney']*(1-$rval['discountval']/100));
			}else{
				$discountmoney=ceil($total_disfoodmoney['totalmoney']*(1-$rval['discountval']/100));
			}
			$discountval=$rval['discountval'];
			$clearmoney=$rval['clearmoney'];
			$signmoney=$rval['signmoney'];
			$freemoney=$rval['freemoney'];
			$cuspay=$rval['cuspay'];
			if($rval['deposit']=="1"){
				$depositmoney=$this->getDepositmoney($rval['shopid']);
			}else{
				$depositmoney="0";
			}
			$returndepositmoney="0";
			$returndepositmoney=$rval['returndepositmoney'];
			
			$timestamp=$rval['timestamp'];
			$buytime=$rval['buytime'];
			if(isset($rval['buytime'])){
				$buytime=date("m-d H:i",$rval['buytime']);
			}else{
				$buytime="/";
			}
			$userarr=$this->getUserinfoByUid($rval['uid']);
			$nickname=$userarr['nickname'];
			$cashierman=$rval['cashierman'];
			$paytype=$rval['paytype'];
			$paystatus=$rval['paystatus'];
			$billstatus=$rval['billstatus'];
			$receivablemoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;
			$totalmoney+=$depositmoney-$returndepositmoney;
			//得到券种类
			$ticketname="";
			if(!empty($rval['ticketway'])&&!empty($rval['ticketval'])&&!empty($rval['ticketnum'])){
				$ticketname=$this->getTicketNameById($rval['ticketway']);
			}
			foreach ($rval['food'] as $key=>$val){
				$foodarr[]=array(
						"foodid"=>$val['foodid'],
						"foodname"=>$val['foodname'],
						"foodmoney"=>$val['foodprice']*$val['foodamount'],
						"foodnum"=>$val['foodnum'],
						"foodprice"=>$val['foodprice'],
						"cooktype"=>$val['cooktype'],
						"orderunit"=>$val['foodunit'],
						"foodunit"=>$val['foodunit'],
						"present"=>$val['present'],
				);
			}
			$arr[]=array(
					"billid"=>$billid,
					"cusnum"=>$cusnum,
					"billnum"=>$rval['billnum'],
					"tabname"=>$tabname,
					"totalmoney"=>round($totalmoney),
					"receivablemoney"=>round($receivablemoney),
					"cashmoney"=>round($cashmoney),
					"unionmoney"=>round($unionmoney),
					"vipmoney"=>round($vipmoney),
					"meituanpay"=>round($meituanpay),
					"dazhongpay"=>round($dazhongpay),
					"nuomipay"=>round($nuomipay),
					"otherpay"=>round($otherpay),
					"alipay"=>$alipay,
					"cuspay"=>$cuspay,
					"wechatpay"=>$wechatpay,
					"othermoney"=>round($othermoney),
					"ticketmoney"=>$ticketmoney,
					"ticketname"=>$ticketname,
					"ticketway"=>$ticketway,
					"discountmoney"=>$discountmoney,
					"discountval"=>$discountval,
					"clearmoney"=>$clearmoney,
					"signmoney"=>$signmoney,
					"freemoney"=>$freemoney,
					"depositmoney"=>$depositmoney,
					"returndepositmoney"=>$returndepositmoney,
					"timestamp"=>$timestamp,
					"buytime"=>$buytime,
					"nickname"=>$nickname,
					"orderrequest"=>$rval['orderrequest'],
					"cashierman"=>$cashierman,
					"paytype"=>$paytype,
					"paystatus"=>$paystatus,
					"billstatus"=>$billstatus,
					"food"=>$foodarr,
			);
		}
		return $arr;
	}
	
	public function getUnpaidSheetData($billid){
		$qarr=array("billid"=>$billid);
		$result=DALFactory::createInstanceCollection(self::$paidcheck)->findOne($qarr);
		$totalmoney=0;
		$paidmoney=0;
		if(!empty($result)){
			$totalmoney=$result['totalmoney'];
			$paidmoney=$result['paidmoney'];
		}
		return array("totalmoney"=>$totalmoney,"paidmoney"=>$paidmoney);
	}
	//删单恢复库存
	public function recoverStockByBilid($Billid)
	{
	    if(empty($Billid)){return "";}
	    $qarr=array("_id"=>new MongoId($Billid));
	    $billinfo = DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
	    $food = $billinfo['food'];
	    foreach ($food as $v)
	    {
	        $this->recoverStock($v['foodid'],$v['foodnum']);
	    }
	}
	public function recoverStock($foodid,$num){
	    if(!$foodid || !$num){return "";}
	    $qarr = array('foodid' =>$foodid);
	    $result=DALFactory::createInstanceCollection(self::$autostock)->findOne($qarr);	   
	    if(!empty($result)){
	        $oldnum=(int)$result['num'];
	        $num+=$oldnum;
	        $oparr = array('$set' => array('num' => $num));
	        DALFactory::createInstanceCollection(self::$autostock)->update($qarr,$oparr);
	    }
	}
}
?>
