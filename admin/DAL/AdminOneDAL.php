<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'admin/global.php');
require_once (_ROOT.'admin/IDAL/IAdminOneDAL.php');
require_once (_ROOT.'DALFactory.php');
require_once (_ROOT.'HttpClient.class.php');
require_once (_ROOT.'des.php');
class AdminOneDAL implements IAdminOneDAL{
	private static $bp_user="bp_user";
	private static $shopinfo="shopinfo";
	private static $donate_account_record="donate_account_record";
	private static $shop_use_account="shop_use_account";
	private static $bill="bill";
	private static $food="food";
	private static $table="table";
	private static $printer="printer";
	private static $busizone="busizone";
	private static $goodstype="goodstype";
	private static $goods="goods";
	private static $shopcheckcode="shopcheckcode";
	private static $question="question";
	private static $transfer = "transfer_log";
	private static $userinfo = "wechat_user_info";
	private static $wechat_balance = "wechat_balance";
	private static $foodtype = "foodtype";
	private static $payrecord = "payrecord";
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::DoLogin()
	 */
	public function DoLogin($useremail, $password) {
		// TODO Auto-generated method stub
		$qarr=array("useremail"=>$useremail,"password"=>md5($password));
		$oparr=array("_id"=>1,"username"=>1);
	
		$result=DALFactory::createInstanceCollection(self::$bp_user)->findOne($qarr,$oparr);
		if(!empty($result['_id'])){
			return array("user_id"=>strval($result['_id']),"username"=>$result['username']);
		}else{
			return array("user_id"=>"","username"=>"");
		}
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::isShopphoneReg()
	 */
	public function isShopphoneReg($shopphone) {
		// TODO Auto-generated method stub
		global $phonekey;
		$phonecrypt = new CookieCrypt($phonekey);
		$shopphone=$phonecrypt->encrypt($shopphone);
		$qarr=array("mobilphone"=>$shopphone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return array("shopid"=>strval($result['_id']),"status"=>"1");
		}else{
			return array("shopid"=>"","status"=>"0");
		}
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::addToDonateAccount()
	 */
	public function addToDonateAccount($inputarr) {
		// TODO Auto-generated method stub
		$oldendtime=$this->getShopuseaccountEndtime($inputarr['shopid']);
		if(!empty($oldendtime)){
			$endtime=$oldendtime+$inputarr['donatemonth']*30*24*3600;
			$qarr=array("shopid"=>$inputarr['shopid']);
			$oparr=array("\$set"=>array("endtime"=>$endtime,"buytime"=>time()));
			DALFactory::createInstanceCollection(self::$shop_use_account)->update($qarr,$oparr);
		}else{
			$endtime=time()+$inputarr['donatemonth']*30*24*3600;
			$arr=array("shopid"=>$inputarr['shopid'],"buytime"=>time(),"endtime"=>$endtime,"accounttype"=>"standard");
			DALFactory::createInstanceCollection(self::$shop_use_account)->save($arr);
		}
		$arr=array(
				"shopid"	=>$inputarr['shopid'],
				"donatemonth"=>$inputarr['donatemonth'],
				"donatereason"=>$inputarr['donatereason'],
				"donatefrom"=>$inputarr['donatefrom'],
				"addtime"=>$inputarr['addtime'],
				"endtime"=>$endtime,
		);
		DALFactory::createInstanceCollection(self::$donate_account_record)->save($arr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getShopuseaccountEndtime()
	 */
	public function getShopuseaccountEndtime($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("endtime"=>1);
		$endtime="0";
		$result=DALFactory::createInstanceCollection(self::$shop_use_account)->findOne($qarr,$oparr);
		if(!empty($result)){
			$endtime=$result['endtime'];
			$endtime=$endtime>time()?$endtime:time();
		}
		return $endtime;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getAllOnLineShop()
	 */
	public function getAllOnLineShop($mobile = NULL)
	{
		// TODO Auto-generated method stub
	    
		global $phonekey;
		global $pwdkey;
		
		$qarr=array("shopstatus"=>"3");
		
		if($mobile)
		{
		    $phonecrypt = new CookieCrypt($phonekey);
		    $where['mobilphone']=$phonecrypt->encrypt($mobile);
		   
		    $result = DALFactory::createInstanceCollection(self::$shopinfo)->find($where);
		}
		else 
		{
		      $result=DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr)->sort(array("addtime"=>-1));
		}
		$arr=array();
		foreach ($result as $key=>$val){
			$phonecrypt = new CookieCrypt($phonekey);
			$mobilphone=$phonecrypt->decrypt($val['mobilphone']);
			$pwdcrypt = new CookieCrypt($pwdkey);
			$passwd=$pwdcrypt->decrypt($val['passwd']);
			if(!empty($val['shopaccount'])){
				$accountcrypt = new CookieCrypt($phonekey);
				$shopaccount=$accountcrypt->decrypt($val['shopaccount']);
			}else{
				$shopaccount="";
			}
			if(!empty($val['pay'])){$pay="1";}else{$pay="0";}
			if(!empty($val['prepay'])){$prepay="1";}else{$prepay="0";}
			if(!empty($val['repay'])){$repay="1";}else{$repay="0";}
			if(!empty($val['signpay'])){$signpay="1";}else{$signpay="0";}
			if(!empty($val['freepay'])){$freepay="1";}else{$freepay="0";}
			if(!empty($val['strictpay'])){$strictpay="1";}else{$strictpay="0";}
			if(!empty($val['hot'])){$hot="1";}else{$hot="0";}
			if(!empty($val['tabdata'])){$tabdata="1";}else{$tabdata="0";}
			if(!empty($val['topclearmoney'])){$topclearmoney=$val['topclearmoney'];}else{$topclearmoney="0";}
			if(!empty($val['depositmoney'])){$depositmoney=$val['depositmoney'];}else{$depositmoney="0";}
			if(!empty($val['allowinbalance'])){$allowinbalance="1";}else{$allowinbalance="0";}
			if(!empty($val['busi_zoneid'])){$busi_zoneid=$val['busi_zoneid'];}else{$busi_zoneid="0";}
			if(!empty($val['decreasenum'])){$decreasenum=$val['decreasenum'];}else{$decreasenum="1";}
			if(!empty($val['yearfee'])){$yearfee=$val['yearfee'];}else{$yearfee="0";}
			if(!empty($val['doublesheet'])){$doublesheet=$val['doublesheet'];}else{$doublesheet="0";}
			if(!empty($val['openid'])){$openid=$val['openid'];}else{$openid="0";}
			if(!empty($val['logo'])){$logo=$val['logo'];}else{$logo="http://jfoss.meijiemall.com/food/default_food.png";}
			if(isset($val['menumoney'])){
				if($val['menumoney']=="1"){
					$menumoney="1";
				}else{
					$menumoney="0";
				}
			}else{
				$menumoney="1";
			}
			$where['phone'] = $val['mobilphone'];
			$code = DALFactory::createInstanceCollection(self::$shopcheckcode)->findOne($where);
			
			$arr[]=array(
					"shopid"=>strval($val['_id']),
					"shopname"=>$val['shopname']." ".$val['branchname'],
					"mobilphone"=>$mobilphone,
			        "checkcode" =>$code['checkcode'],
					"passwd"=>$passwd,
					"address"=>$val['city']." ".$val['district']." ".$val['road'],
					"logo"=>$logo,
					"pay"=>$pay,
					"repay"=>$repay,
					"prepay"=>$prepay,
					"signpay"=>$signpay,
					"freepay"=>$freepay,
					"strictpay"=>$strictpay,
					"hot"=>$hot,
					"tabdata"=>$tabdata,
			        "openid"=>$openid,
					"busi_zoneid"=>$busi_zoneid,
					"topclearmoney"=>$topclearmoney,
					"depositmoney"=>$depositmoney,
					"decreasenum"=>$decreasenum,
					"yearfee"=>$yearfee,
					"allowinbalance"=>$allowinbalance,
					"menumoney"=>$menumoney,
					"addtime"=>$val['addtime'],
					"accountname"=>$val['accountname'],
					"shopaccount"=>$shopaccount,
					"doublesheet"=>$doublesheet,
			         "wechatstatus"=>$val['wechatstatus'],
			);  
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::updateShopSwitch()
	 */
	public function updateShopSwitch($shopid, $type, $status) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return ;}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("\$set"=>array($type=>$status));
		DALFactory::createInstanceCollection(self::$shopinfo)->update($qarr,$oparr);
	}
	
	public function getStaticsData($starttime){
		$qarr=array("paystatus"=>"paid","billstatus"=>"done","timestamp"=>array("\$gt"=>$starttime));
		$oparr=array("cusnum"=>1,"food"=>1,"timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$cusnum=0;
		$totalmoney=0;
		$billnum=DALFactory::createInstanceCollection(self::$bill)->count();
		$foodnum=DALFactory::createInstanceCollection(self::$food)->count();
		$newtime=$starttime;
		foreach ($result as $key=>$val){
			$newtime=$newtime>$val['timestamp']?$newtime:$val['timestamp'];
			$cusnum+=$val['cusnum'];
			foreach ($val['food'] as $fkey=>$fval){
				$totalmoney+=$fval['foodprice']*$fval['foodamount'];
			}
		}
		return array(
				"cusnum"=>$cusnum,
				"totalmoney"=>sprintf("%.0f",$totalmoney),
				"billnum"=>$billnum,
				"foodnum"=>$foodnum,
				"newtime"=>$newtime,
		);
		
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getOnlineData()
	 */
	public function getOnlineData() {
		// TODO Auto-generated method stub
		$arr=array();
		$qarr=array("shopstatus"=>"3");
		$oparr=array("_id"=>1,"shopname"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr,$oparr)->sort(array("addtime"=>-1));
		foreach ($result as $key=>$val){
			$startnum=DALFactory::createInstanceCollection(self::$table)->count(array("shopid"=>strval($val['_id']),"tabstatus"=>"start"));
			$onlinenum=DALFactory::createInstanceCollection(self::$table)->count(array("shopid"=>strval($val['_id']),"tabstatus"=>"online"));
			$today=$this->getTheday(strval($val['_id']));
			$yestoday=date("Y-m-d",strtotime($today)-86400);
			$openhour=$this->getOpenHourByShopid(strval($val['_id']));
			$todayarr=$this->getBillCountByDay(strval($val['_id']), $today, $openhour);
			$yestodayarr=$this->getBillCountByDay(strval($val['_id']), $yestoday, $openhour);
			$arr[]=array(
					"shopid"=>strval($val['_id']),
					"shopname"=>$val['shopname'],
					"startnum"=>$startnum,
					"onlinenum"=>$onlinenum,
					"today"=>$today,
					"yestoday"=>$yestoday,
					"todaydata"=>$todayarr,
					"yestodaydata"=>$yestodayarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getBillCountByDay()
	 */
	public function getBillCountByDay($shopid, $theday,$openhour) {
		// TODO Auto-generated method stub
		$billnum=0;
		$cusnum=0;
		$totalmoney=0;
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("food"=>1,"cusnum"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$billnum++;
			$cusnum+=$val['cusnum'];
			foreach ($val['food'] as $fkey=>$fval){
				$totalmoney+=$fval['foodprice']*$fval['foodamount'];
			}
		}
		return array("billnum"=>$billnum,"cusnum"=>$cusnum,"totalmoney"=>$totalmoney);
	}
	
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getOpenHourByShopid()
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
	 * @see IAdminOneDAL::getTheday()
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
	 * @see IAdminOneDAL::getPrintersStatus()
	 */
	public function getPrintersStatus() {
		// TODO Auto-generated method stub
		global $phonekey;
		$arr=array();
		$shoparr=$this->getAllOnLineShop();
		foreach ($shoparr as $skey=>$sval){
			$qarr=array("shopid"=>$sval['shopid']);
			$oparr=array("deviceno"=>1,"devicekey"=>1);
			$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
			foreach ($result as $key=>$val){
				if(!array_key_exists($val['deviceno'], $arr)){
					$workstatus=$this->queryPrinterStatus($val['deviceno'],$val['devicekey']);
					$phonecrypt = new CookieCrypt($phonekey);
					$device_no=$phonecrypt->decrypt($val['deviceno']);
					$arr[$val['deviceno']]=array(
							"shopname"=>$sval['shopname'],
							"deviceno"=>$device_no,
							"workstatus"=>$workstatus,
					);
				}
			}
		}
		
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::queryPrinterStatus()
	 */
	public function queryPrinterStatus($device_no, $device_key) {
		// TODO Auto-generated method stub
		global $phonekey;
		$phonecrypt = new CookieCrypt($phonekey);
		$device_no=$phonecrypt->decrypt($device_no);
		$phonecrypt = new CookieCrypt($phonekey);
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
	 * @see IAdminOneDAL::getShopinfo()
	 */
	public function getShopinfo($shopid) {
		// TODO Auto-generated method stub
		$arr=array();
		if(empty($shopid)){return $arr;}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("shopname"=>$result['shopname']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getCusFlowData()
	 */
	public function getCusFlowData($datearr) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		$shoparr=$this->getAllOnLineShop();
		foreach ($datearr as $day){
			$cusnum=0;
			foreach ($shoparr as $skey=>$sval){
				$openhour=$this->getOpenHourByShopid($sval['shopid']);
				$starttime=strtotime($day." ".$openhour.":0:0");
				$endtime=strtotime($day." ".$openhour.":0:0")+86400;
				
				$qarr=array("shopid"=>$sval['shopid'],"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
				$oparr=array("cusnum"=>1,"timestamp"=>1);
				$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
				foreach ($result as $rkey=>$rval){
					$cusnum+=$rval['cusnum'];
				}
			}
			$arr[$day]=$cusnum;
		}
		if(!empty($arr)){
			$datasets[]=array(
					"label"=>"",
					"fillColor"=>"rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",0.2)",
					"strokeColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointStrokeColor"=> "#fff",
					"pointHighlightFill" => "#fff",
					"pointHighlightStroke" => "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"data" =>$arr,
			);
			$lineChartarr=array(
					"labels"=>$datearr,
					"datasets"=>$datasets,
			);
		}
		// 		print_r($lineChartarr);exit;
		return $lineChartarr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getMoneyFlowData()
	 */
	public function getMoneyFlowData($datearr) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		$shoparr=$this->getAllOnLineShop();
		foreach ($datearr as $day){
			$money=0;
			foreach ($shoparr as $skey=>$sval){
				$openhour=$this->getOpenHourByShopid($sval['shopid']);
				$starttime=strtotime($day." ".$openhour.":0:0");
				$endtime=strtotime($day." ".$openhour.":0:0")+86400;
		
				$qarr=array("shopid"=>$sval['shopid'],"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
				$oparr=array("food"=>1);
				$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
				foreach ($result as $rkey=>$rval){
					foreach ($rval['food'] as $fkey=>$fval){
						$money+=$fval['foodprice']*$fval['foodamount'];
					}
				}
			}
			$arr[$day]=$money;
		}
		if(!empty($arr)){
			$datasets[]=array(
					"label"=>"",
					"fillColor"=>"rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",0.2)",
					"strokeColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointStrokeColor"=> "#fff",
					"pointHighlightFill" => "#fff",
					"pointHighlightStroke" => "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"data" =>$arr,
			);
			$lineChartarr=array(
					"labels"=>$datearr,
					"datasets"=>$datasets,
			);
		}
		// 		print_r($lineChartarr);exit;
		return $lineChartarr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getBillnumFlowData()
	 */
	public function getBillnumFlowData($datearr) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		$shoparr=$this->getAllOnLineShop();
		foreach ($datearr as $day){
			$billnum=0;
			foreach ($shoparr as $skey=>$sval){
				$openhour=$this->getOpenHourByShopid($sval['shopid']);
				$starttime=strtotime($day." ".$openhour.":0:0");
				$endtime=strtotime($day." ".$openhour.":0:0")+86400;
				
				$qarr=array("shopid"=>$sval['shopid'],"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
				$oneshopbillnum=DALFactory::createInstanceCollection(self::$bill)->count($qarr);
				$billnum+=$oneshopbillnum;
			}
			$arr[$day]=$billnum;
		}
		if(!empty($arr)){
			$datasets[]=array(
					"label"=>"",
					"fillColor"=>"rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",0.2)",
					"strokeColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointStrokeColor"=> "#fff",
					"pointHighlightFill" => "#fff",
					"pointHighlightStroke" => "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"data" =>$arr,
			);
			$lineChartarr=array(
					"labels"=>$datearr,
					"datasets"=>$datasets,
			);
		}
		// 		print_r($lineChartarr);exit;
		return $lineChartarr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getBusinessZoneData()
	 */
	public function getBusinessZoneData() {
		// TODO Auto-generated method stub
		$result=DALFactory::createInstanceCollection(self::$busizone)->find();
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"busi_zoneid"=>strval($val['_id']),
					"city"=>$val['city'],
					"busi_zonename"	=>$val['busi_zonename'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::addBusizoneData()
	 */
	public function addBusizoneData($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$busizone)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::updateBusiZoneData()
	 */
	public function updateBusiZoneData($inputarr,$busi_zoneid) {
		// TODO Auto-generated method stub
		if(empty($busi_zoneid)){return ;}
		$qarr=array("_id"=>new MongoId($busi_zoneid));
		$oparr=array("\$set"=>array("city"=>$inputarr['city'],"busi_zonename"=>$inputarr['busi_zonename']));
		DALFactory::createInstanceCollection(self::$busizone)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getOneBusizoneData()
	 */
	public function getOneBusizoneData($busi_zoneid) {
		// TODO Auto-generated method stub
		if(empty($busi_zoneid)){return array();}
		$qarr=array("_id"=>new MongoId($busi_zoneid));
		$oparr=array("city"=>1,"busi_zonename"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$busizone)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("busi_zoneid"=>strval($result['_id']), "city"=>$result['city'],"busi_zonename"=>$result['busi_zonename']);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::delOneBusizoneData()
	 */
	public function delOneBusizoneData($busi_zoneid) {
		// TODO Auto-generated method stub
		if(empty($busi_zoneid)){return ;}
		$qarr=array("_id"=>new MongoId($busi_zoneid));
		DALFactory::createInstanceCollection(self::$busizone)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::addShopToBusiZoneid()
	 */
	public function addShopToBusiZoneid($shopid,$busi_zoneid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return ;}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("\$set"=>array("busi_zoneid"=>$busi_zoneid));
		DALFactory::createInstanceCollection(self::$shopinfo)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::addOneGoodsTypeData()
	 */
	public function addOneGoodsTypeData($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$goodstype)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::updateOneGoodsTypeData()
	 */
	public function updateOneGoodsTypeData($goodstypeid,$inputarr) {
		// TODO Auto-generated method stub
		if(empty($goodstypeid)){return ;}
		$qarr=array("_id"=>new MongoId($goodstypeid));
		$oparr=array("\$set"=>array("goodstypename"=>$inputarr['goodstypename'],"sortno"=>$inputarr['sortno']));
		DALFactory::createInstanceCollection(self::$goodstype)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getOneGoodsTypeData()
	 */
	public function getOneGoodsTypeData($goodstypeid) {
		// TODO Auto-generated method stub
		if(empty($goodstypeid)){return array();}
		$qarr=array("_id"=>new MongoId($goodstypeid));
		$result=DALFactory::createInstanceCollection(self::$goodstype)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=array("goodstypeid"=>$goodstypeid,"goodstypename"=>$result['goodstypename'],"sortno"=>$result['sortno']);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getGoodsTypeData()
	 */
	public function getGoodsTypeData() {
		// TODO Auto-generated method stub
		$qarr=array();
		$result=DALFactory::createInstanceCollection(self::$goodstype)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"goodstypeid"	=>strval($val['_id']),
					"goodstypename"=>$val['goodstypename'],
					"sortno"=>$val['sortno'],
			);
		}
		$arr=$this->array_sort($arr, "sortno");
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::delOneGoodsTypeData()
	 */
	public function delOneGoodsTypeData($goodstypeid) {
		// TODO Auto-generated method stub
		if(empty($goodstypeid)){return ;}
		$qarr=array("_id"=>new MongoId($goodstypeid));
		DALFactory::createInstanceCollection(self::$goodstype)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getOneGoodsData()
	 */
	public function getOneGoodsData($goodsid) {
		// TODO Auto-generated method stub
		$arr=array();
		if(empty($goodsid)){return array();}
		$qarr=array("_id"=>new MongoId($goodsid));
		$result=DALFactory::createInstanceCollection(self::$goods)->findOne($qarr);
		if(!empty($result)){
			$arr=$result;
			$arr['goodsid']=strval($result['_id']);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getGoodsData()
	 */
	public function getGoodsData() {
		// TODO Auto-generated method stub
		$arr=array();
		$goodstypearr=$this->getGoodsTypeData();
		foreach ($goodstypearr as $gtkey=>$gtval){
			$qarr=array("goodstypeid"=>$gtval['goodstypeid']);
			$result=DALFactory::createInstanceCollection(self::$goods)->find($qarr);
			$onegoods=array();
			foreach ($result as $key=>$val){
				$goodsid=strval($val['_id']);
				$onegoods[]=array(
						"goodsid"=>$goodsid,
						"goodsname"=>$val['goodsname'],
						"goodspic"=>$val['goodspic'],
						"otherprice"=>$val['otherprice'],
						"ourprice"=>$val['ourprice'],
						"goodsunit"=>$val['goodsunit'],
						"goodssoldunit"=>$val['goodssoldunit'],
						"goodstypeid"=>$val['goodstypeid'],
						"goodsdesc"=>$val['goodsdesc'],
						"online"=>$val['online'],
						"goodsformat"=>$val['goodsformat'],
				);
			}
			$arr[$gtval['goodstypeid']]=array(
					"goodstypeid"=>$gtval['goodstypeid'],
					"goodstypename"=>$gtval['goodstypename'],
					"goods"=>$onegoods,
			);
		}
		$newarr=array();
		foreach ($arr as $akey=>$aval){
			$newarr[]=array(
					"goodstypeid"=>$aval['goodstypeid'],
					"goodstypename"=>$aval['goodstypename'],
					"goods"=>$aval['goods'],
			);
		}
		return $newarr;
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::addGoodsData()
	 */
	public function addGoodsData($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$goods)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::updateGoodsData()
	 */
	public function updateGoodsData($goodsid, $inputarr) {
		// TODO Auto-generated method stub
		if(empty($goodsid)){return ;}
		$qarr=array("_id"=>new MongoId($goodsid));
		$oparr=array("\$set"=>array(
				"goodsname"=>$inputarr['goodsname'],
				"otherprice"=>$inputarr['otherprice'],
				"ourprice"=>$inputarr['ourprice'],
				"goodsunit"=>$inputarr['goodsunit'],
				"goodssoldunit"=>$inputarr['goodssoldunit'],
				"goodstypeid"=>$inputarr['goodstypeid'],
				"goodsdesc"=>$inputarr['goodsdesc'],
				"online"=>$inputarr['online'],
				"goodsformat"=>$inputarr['goodsformat'],
		));
		DALFactory::createInstanceCollection(self::$goods)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IAdminOneDAL::delOneGoodsData()
	 */
	public function delOneGoodsData($goodsid) {
		// TODO Auto-generated method stub
		if(empty($goodsid)){return ;}
		$qarr=array("_id"=>new MongoId($goodsid));
		DALFactory::createInstanceCollection(self::$goods)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::getGoodsUpTime()
	 */
	public function getGoodsPicUpTime($goodsid) {
		// TODO Auto-generated method stub
		if(empty($goodsid)){return time();}
		$qarr=array("_id"=>new MongoId($goodsid));
		$oparr=array("goodsuptime"=>1);
		$goodsuptime=time();
		$result=DALFactory::createInstanceCollection(self::$goods)->findOne($qarr,$oparr);
		if(!empty($result['goodsuptime'])){
			$goodsuptime=$result['goodsuptime'];
		}
		return $goodsuptime;
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::updateGoodsImgData()
	 */
	public function updateGoodsImgData($goodsid, $newgoodspic, $timestamp) {
		// TODO Auto-generated method stub
		if(empty($goodsid)){return ;}
		$qarr=array("_id"=>new MongoId($goodsid));
		$oparr=array("\$set"=>array("goodspic"=>$newgoodspic,"goodsuptime"=>$timestamp));
		DALFactory::createInstanceCollection(self::$goods)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::array_sort()
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
	/*
	 * 获取题目
	 * @return:array
	 */
	public function  getQuestionData(){
	    $qarr=array();
	    $result=DALFactory::createInstanceCollection(self::$question)->find($qarr);
	
	    return $result;
	    //return $arr;
	}
	/*
	 * 更新题目
	 * @return:bool
	 */
    public function  updateQuestion($id,$arr){
        if(empty($id)){return ;}
        $qarr=array("_id"=>new MongoId($id));
        $oparr=array("\$set"=>array(
            "questionInfo" => $arr['questionInfo'],
            "answerA"      => $arr['answerA'],
            "answerB"      => $arr['answerB'],
            "answerC"      => $arr['answerC'],
            "answerD"      => $arr['answerD'],
            "right"            => $arr['right']
        ));
        DALFactory::createInstanceCollection(self::$question)->update($qarr,$oparr);
    }
    public function getOneQuestion($id = null){
        //获取全部数组
        
        //获取数组长度$num
        //$id = rand(0,$num);
        
        $qarr = array("_id"=>new MongoId($id));
        $data = DALFactory::createInstanceCollection(self::$question)->findOne($qarr);
        return $data;
    }
    /**
     * {@inheritDoc}
     * @see IAdminOneDAL::delOneQuestion()
     */
    public function delOneQuestion($id)
    {
        // TODO Auto-generated method stub
        $qarr =  array("_id"=>new MongoId($id));
        $data = DALFactory::createInstanceCollection(self::$question)->remove($qarr);
        
    }
    /**
     * {@inheritDoc}
     * @see IAdminOneDAL::addOneQuestion()
     */
    public function addOneQuestion($arr)
    {
        // TODO Auto-generated method stub
        $arr['time'] = time();
        
        DALFactory::createInstanceCollection(self::$question)->insert($arr);
    }
    public function  getPrizeData()
    {
        $data = DALFactory::createInstanceCollection(self::$prize)->find();
    }
    public function getTransferLog($where = NULL)
    {
        if(!$where){
            $where['day'] =date('Y-m-d');   
        }
        //如果是手机号查询应该通过手机号查询商户表获取商户ID再继续查询
        $data = DALFactory::createInstanceCollection(self::$transfer)->find($where);
        return $data;
    }
    public function addOneTransferLog($arr)
    {
        $arr['time'] = time();
        $data = DALFactory::createInstanceCollection(self::$transfer)->insert($arr);
    }
    public function delOneTransferLog($id)
    {
        $qarr =  array("_id"=>new MongoId($id));
        DALFactory::createInstanceCollection(self::$transfer)->remove($qarr);
    }
   public function getCodeLog($mobile = null)
   {
       global $phonekey;
       global $pwdkey;
       $phonecrypt = new CookieCrypt($phonekey);
       if($mobile)
       {
           
           $where['phone']=$phonecrypt->encrypt($mobile);
            
           $result = DALFactory::createInstanceCollection(self::$shopcheckcode)->find($where)->sort(array("timestamp"=>-1));
       }
       else
       {
           $result=DALFactory::createInstanceCollection(self::$shopcheckcode)->find()->sort(array("timestamp"=>-1))->limit(20);
       }   
        $data = array();
        $i = 1;
        foreach ($result as $v)
        {
            $data[$i]['mobilphone'] = $phonecrypt->decrypt($v['phone']);
            $data[$i]['code'] = $v['checkcode'];
            $data[$i]['timestamp'] = $v['timestamp'];
            $i++;
        }  
       return $data;
   }
   public function getUserList()
   {
       $result = DALFactory::createInstanceCollection(self::$userinfo)->find()->sort(array("timestamp"=>-1));
       return $result;
   }
    
   public function getDayReport($day = NULL)
   {
       $arr=array();
       $qarr=array("wechatstatus"=>"1");
       $oparr=array("_id"=>1,"shopname"=>1,"mobilphone"=>1);
       $result=DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr,$oparr)->sort(array("addtime"=>-1));
       foreach ($result as $key=>$val){
         
           $openhour=$this->getOpenHourByShopid(strval($val['_id']));
           $todayarr=$this->getDayReportByDay(strval($val['_id']), $day, $openhour);
           $arr[]=array(
               "shopid"=>strval($val['_id']),
               "shopname"=>$val['shopname'],
               "todaydata"=>$todayarr,
               "mobilphone" => $val['mobilphone'],
           );
       }
       return $arr;
   }
   public function getDayReportByDay($shopid, $theday,$openhour) {
       // TODO Auto-generated method stub
       $alipay=0;
       $wechatpay=0;
       $starttime=strtotime($theday." ".$openhour.":0:0");
       $endtime=strtotime($theday." ".$openhour.":0:0")+86400;
       $qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
       $oparr=array("paymoney"=>1,"paytype"=>1);
       $result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
       foreach ($result as $key=>$val){
           switch ($val['paytype'])
           {
               case 'wechatpay':
                   $wechatpay+=$val['paymoney'];
                   break;
               case 'alipay':
                   $alipay+=$val['paymoney'];
                   break;
           }
       }
       return array("wechatpay"=>$wechatpay,"alipay"=>$alipay);
   }
   /*
    * 获取商户的店名和手机号码等信息
    */
   public function getShopInfoById($shopid)
   {
       global $phonekey;
       $phonecrypt = new CookieCrypt($phonekey);
       $arr = array();
       if(empty($shopid)){return $arr;}
       $qarr=array("_id"=>new MongoId($shopid));
       $oparr=array("shopname"=>1,'mobilphone'=>1);
       $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
       if(!empty($result)){
           $phone = $phonecrypt->decrypt($result['mobilphone']);
           $arr=array(
               "shopname"=>$result['shopname'],
               "mobilphone" => $phone,
           );
       }
       return $arr;
   }
   public function getTransferState($shopid, $day)
   {
       $qarr = array('shopid'=>$shopid,'day'=>$day);
       $result = DALFactory::createInstanceCollection(self::$transfer)->findOne($qarr);
       if(empty($result))
       {
           return true;
       }else{
           return false;
       }
   }
/**
     * {@inheritDoc}
     * @see IAdminOneDAL::getOnWechatShop()
     */
    public function getOnWechatShop()
    {
        // TODO Auto-generated method stub
       $qarr=array("wechatstatus"=>"1","shopstatus"=>"3");
       $oparr=array("_id"=>1,"shopname"=>1,"mobilphone"=>1,"openid"=>1);
       $result=DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr,$oparr)->sort(array("addtime"=>-1));
       foreach ($result as $key=>$val){
           $shoparr=$this->getShopInfoById(strval($val['_id']));      
           $money=$this->getShopaccountByShopid(strval($val['_id']));
           $arr[]=array(
               "shopid"=>strval($val['_id']),
               "shopname"=>$val['shopname'],
               "mobilphone" => $shoparr['mobilphone'],
               "money"=>$money,
               "openid"=>$val['openid'],
           );
       }
       return $arr;
    }
    /**
     * {@inheritDoc}
     * @see IAdminOneDAL::getShopaccountByShopid()
     */
    public function getShopaccountByShopid($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("money"=>1);
        $result=DALFactory::createInstanceCollection(self::$wechat_balance)->findOne($qarr,$oparr);
        $money=0;
        if(!empty($result)){
            $money=$result['money'];
        }
        return $money;
    }
    /**
     * {@inheritDoc}
     * @see IAdminOneDAL::initShopAccontMoney()
     */
    public function initShopAccontMoney($inputarr)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$inputarr['shopid']);
        $result=DALFactory::createInstanceCollection(self::$wechat_balance)->findOne($qarr);
        if(!empty($result)){
            $oparr=array("\$set"=>array("money"=>$inputarr['money']));
            DALFactory::createInstanceCollection(self::$wechat_balance)->update($qarr,$oparr);
        }else{
            $arr=array("shopid"=>$inputarr['shopid'],"money"=>$inputarr['money']);
            DALFactory::createInstanceCollection(self::$wechat_balance)->save($arr);
        }
    }

    /*
     * 打印机监控
     */
    public function controlPrinter($shopinfo)
    {
        global $phonekey;
        $phonecrypt = new CookieCrypt($phonekey);
        $result=DALFactory::createInstanceCollection(self::$printer)->find();
        $arr = array();
        foreach ($result as $v)
        {
            $deviceno=$phonecrypt->decrypt($v['deviceno']);
              if(!array_key_exists($v['deviceno'], $arr) && strlen($deviceno)==9){  
                  $arr[$v['deviceno']] = array(
                      'shopid' => $v['shopid'],
                      'deviceno' => $v['deviceno'],
                      'devicekey' => $v['devicekey'],
                  );
              }             
        }
        $errorRes = array();
        
//         $shopinfo = $this->getAllShopinfo();
        foreach ($arr as $v)
        {
           $res = null;
           
           $res =  $this->queryPrinterStatus($v['deviceno'], $v['devicekey']);
            if($res != "在线,纸张正常")
            {
                $shopres = $shopinfo[$v['shopid']];
                if(empty($shopres)){continue;}
                $v['res'] = $res;
                $v['shopname'] = $shopres['shopname'];
                $v['logo'] = $shopres['logo'];
                $v['mobilphone'] = $shopres['mobilphone'];
                $v['deviceno'] =$deviceno;
                $errorRes[] = $v;
            }
        }
         return $errorRes;
    }
    public function controlFood($shopinfo){
        //或方法
//         $shopinfo = $this->getAllShopinfo();
      
        $qarr = array();
        $oqarr = array(
            'shopid' =>1,
            'foodname' =>1,
            'foodprice' =>1,
            'foodcooktype' =>1,
            "foodunit" => 1,
            "orderunit"=>1,
        );
        $result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oqarr);
        $arr = array();
        foreach ($result as $v)
        {
            $res=array();
          if(empty($v['foodname']))
          {
//               $res = "没有菜单名";
              $res[]="没有菜单名";
              
          }
          if (empty($v['foodprice'] )&& is_numeric($v['foodprice']))
          {
//               $res = "没有菜品价格或价格非数字";
              $res[]="没有菜品价格或价格非数字";
              
          }
          if (empty($v['foodunit']))
          {
//               $res = "没有输入菜品单位";
              $res[]="没有输入菜品单位";
              
          }
          if(strpos($v['foodcooktype'],'，') || strpos($v['foodcooktype'],','))
          {
              $res[] = "菜品口味不合法";             
          }
          if(!empty($res))
          {
              $shopres = $shopinfo[$v['shopid']];
              if(empty($shopres)){continue;} 
              $arr[] = array(
                  'shopname' => $shopres['shopname'],
                  'mobilphone' => $shopres['mobilphone'],
                  'logo' => $shopres['logo'],
                  'foodname' => $v['foodname'],
                  'res' => $res,
              );
          }
        }
        return $arr;
    }
    public function getAllShopinfo()
    {
        global $phonekey;
        $phonecrypt = new CookieCrypt($phonekey);
        
        $qarr = array("shopstatus"=>"3");
        $oqarr = array('shopname' => 1,'mobilphone'=> 1,'logo'=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr,$oqarr);
        $arr = array();
        foreach ($result as $k => $v)
        {
            $arr[strval($k)] = array(
                'shopname' => $v['shopname'],
                'mobilphone' =>$phonecrypt->decrypt($v['mobilphone']),
                'logo' => $v['logo'],
            );
        }
        return  $arr;
    }
    /*
     * 监控所有菜品分类
     */
    public function controlFoodType($shopinfo)
    {
//         $shopinfo = $this->getAllShopinfo();
        $qarr = array();
        $oqarr = array('shopid' => 1,'foodtypename'=> 1,'printerid'=>1,);
        $result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oqarr);
        $arr = array();
        foreach ($result as $v)
        {
            $res=array();
            if(empty($v['foodtypename']))
            {
                $res[] = "类别名不能为空";
            }
            if (empty($v['printerid'])){
                $res[] = "未绑定打印机！";
            }
            if(!empty($res))
            {
                $shopres = $shopinfo[$v['shopid']];
                if(empty($shopres)){continue;}
                $arr[] = array(
                    'shopname' => $shopres['shopname'],
                    'mobilphone' => $shopres['mobilphone'],
                    'foodtypename' => $v['foodtypename'],
                    'logo' => $v['logo'],
                    'res' => $res,
                );
            }
        }
        return $arr;
    }
    public function getReport(){  
        //月报开始
        $over = strtotime(date('Y-m-d'))+86400;        
        $start = $over - 32*86400;
        $qarr = array("gmt_payment"=>array('$gte'=>$start,'$lte'=>$over));
        
        $data = DALFactory::createInstanceCollection(self::$payrecord)->find($qarr);
        $money = array();
        $result = array();
        //获取指定日期的数据
        foreach ($data as $v)
        {
            $day = date('Y-m-d',$v['gmt_payment']);
            $money[$day] += $v['total_fee'];
            $result['totalnum'] +=1;
            $result['totalmoney']+=$v['total_fee'];
        }
        //月报结束
        //日报开始
        $qarr['gmt_payment'] = array('$gte' => strtotime(date("Y-m-d")));
        $res = DALFactory::createInstanceCollection(self::$payrecord)->find($qarr);
        $todayarr = array();
        $today = array();
        for($i=0;$i<24;$i++)
        {
            $number=$i;
            $todayarr[$number]=0;
        }
    
        foreach ($res as $val)
        {
            $num = 0;
            $val['gmt_payment'] = date("H:i:s",$val['gmt_payment']);
            $val['buyer'];
            $val['shopid'];
            $val['shopinfo'] = $this->getShopInfoById($val['shopid']);
            $result['todaynum'] += 1;
            $result['todaymoney'] += $val['total_fee'];
            $today[] = $val;
            $num = date('H',$val['downtime']);
            $num = (int)$num;
            $todayarr[$num]+=1; 
        }
        $result['money'] = $money;
        $result['todayarr'] = $todayarr;
        $result['today'] = $today;
       return $result;
    }
    public function getShopInfoByOrderno($orderno){
        if(empty($orderno)){return "";}
        $qarr = array('orderno'=>$orderno);
        $bill = DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
        if(empty($bill)){
            //返回 暂无订单信息
            $data ="未查到订单信息";
        }else{
            $shopid = $bill['shopid'];
            $data = $this->getShopInfoById($shopid);
            $data['money'] = $bill['paymoney'];
        }
        return $data;
    }
}
?>