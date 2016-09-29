<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorSixDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once ('/var/www/html/des.php');

require_once (_ROOT.'userinfo/DAL/CustomerDAL.php');
require_once (_ROOT.'boss/DAL/BossOneDAL.php');
require_once 'MonitorOneDAL.php';
class MonitorSixDAL implements IMonitorSixDAL{
	private static $foodtype="foodtype";
	private static $food="food";
	private static $bill="bill";
	private static $shopinfo="shopinfo";
	private static $table="table";
	private static $customer="customer";
	private static $coupontype="coupontype";
	private static $role="role";
	private static $servers="servers";
	private static $prebill="prebill";
	private static $myvip="myvip";
	private static $viprecord="viprecord";
	private static $vipcard="vipcard";
	private static $zone="zone";
	private static $viptag="viptag";
	private static $subaccount="subaccount";
	private static $shopaccount="shopaccount";
	private static $wechat_shop="wechat_shop";
	private static $wechat_balance = "wechat_balance";
	private static $takeout_money = "takeout_money";
	private static $paidcheck = "paidcheck";
	private static $wechat_user_info = "wechat_user_info";
	
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getFoodidsData()
	 */
	public function getFoodidsData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "foodtypename"=>1,"sortno"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr)->sort(array("sortno"=>1));
		$arr=array();
		foreach ($result as $key=>$val){
			$foodarr=$this->getFoodidsByFtid(strval($val['_id']));
			if(empty($foodarr)){continue;}
			$arr[]=array(
					"ftid"=>strval($val['_id']),
					"ftname"=>$val['foodtypename'],	
					"food"=>$foodarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getFoodidsByFtid()
	 */
	public function getFoodidsByFtid($ftid) {
		// TODO Auto-generated method stub
		$arr=array();
		if(empty($ftid)){return $arr;}
		$qarr=array("foodtypeid"=>$ftid);
		$oparr=array("_id"=>1,"foodname"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr)->sort(array("sortno"=>1));
		foreach ($result as $key=>$val){
			$arr[]=array("foodid"=>strval($val['_id']),"foodname"=>$val['foodname']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getFoodsTrendsData()
	 */
	public function getFoodsTrendsData($shopid,$datearr,$foodidarr, $thehour) {
		// TODO Auto-generated method stub
		$arr=array();
		foreach ($datearr as $day){
			$starttime=strtotime($day." ".$thehour.":0:0");
			$endtime=strtotime($day." ".$thehour.":0:0")+86400;
			$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
			$oparr=array("food"=>1);
			$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
			foreach ($result as $key=>$val){
				foreach ($val['food'] as $fkey=>$fval){
					if(in_array($fval['foodid'], $foodidarr)){
						if(!empty($fval['foodamount'])){$foodamount=$fval['foodamount'];}else{$foodamount="0";}					
							$arr[$day][$fval['foodid']]+=$foodamount;
					}
				}
			}
			
		}
		$newarr=array();
		foreach ($arr as $theday=>$foods){
			foreach ($foods as $foodid=>$foodamount){
				if(empty($foodamount)){$foodamount="0";}
				$newarr[$foodid][$theday]+=$foodamount;
			}
		}
		$newnewarr=array();
		foreach ($newarr as $foodid=>$tval){
			foreach ($tval as $theday=>$foodamount){
				foreach ($datearr as $tday){
					if($tday==$theday){
						$newnewarr[$foodid][$tday]+=$foodamount;
					}else{
						$newnewarr[$foodid][$tday]+="0";
					}
				}
			}
		}
		
		foreach ($newnewarr as $foodid=>$val){
			$foodamoutarr=array();
			foreach ($val as $theday=>$foodamout){
				$foodamoutarr[]=$foodamout;
			}
			$datasets[]=array(
							"label"=>"q",
							"fillColor"=>"rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",0.2)",
							"strokeColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
							"pointColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
							"pointStrokeColor"=> "#fff",
							"pointHighlightFill" => "#fff",
							"pointHighlightStroke" => "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
							"data" =>$foodamoutarr,
					);
		}
		
		$lineChartarr=array(
				"labels"=>$datearr,
				"datasets"=>$datasets,
		);
// 		print_r($lineChartarr);exit;
		return $lineChartarr;
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getTurnfoodTrendData()
	 */
	public function getTurnfoodTrendData($shopid, $startdate, $enddate,$datearr, $thehour) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		foreach ($datearr as $day){
			$cashmoney=0;
			$unionmoney=0;
			$vipmoney=0;
			$meituanpay=0;
			$dazhongpay=0;
			$nuomipay=0;
			$alipay=0;
			$wechatpay=0;
			$ticketmoney=0;
			$starttime=strtotime($day." ".$thehour.":0:0");
			$endtime=strtotime($day." ".$thehour.":0:0")+86400;
			$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
			$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr);
			foreach ($result as $key=>$val){
				$cashmoney+=$val['cashmoney'];
				$unionmoney+=$val['unionmoney'];
				$vipmoney+=$val['vipmoney'];
				$meituanpay+=$val['meituanpay'];
				$dazhongpay+=$val['dazhongpay'];
				$nuomipay+=$val['nuomipay'];
				$alipay+=$val['alipay'];
				$wechatpay+=$val['wechatpay'];
				if(!empty($val['ticketway'])&&!empty($val['ticketval'])&&!empty($val['ticketnum'])){
					$ticketmoney+=$val['ticketval']*$val['ticketnum'];
				}
			}
			$arr[$day]=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$alipay+$wechatpay+$ticketmoney;//$signmoney
		}
		if(!empty($arr)){
			$datasets[]=array(
					"label"=>"q",
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
	 * @see IMonitorSixDAL::getCusnumTrendData()
	 */
	public function getCusnumTrendData($shopid, $startdate, $enddate, $datearr, $thehour) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		foreach ($datearr as $day){
			$cusnum=0;
			$starttime=strtotime($day." ".$thehour.":0:0");
			$endtime=strtotime($day." ".$thehour.":0:0")+86400;
			$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
			$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr);
			foreach ($result as $key=>$val){
				$cusnum+=$val['cusnum'];
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
	 * @see IMonitorSixDAL::getCusnumRealTrendData()
	 */
	public function getCusnumRealTrendData($inputarr) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		$starttime=strtotime($inputarr['theday']." ".$inputarr['openhour'].":0:0");
		$endtime=strtotime($inputarr['theday']." ".$inputarr['openhour'].":0:0")+86400;
		
		$qarr=array("shopid"=>$inputarr['shopid'],"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("cusnum"=>1,"timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$thehour=date("H",$val['timestamp']);
			if(array_key_exists($thehour, $arr)){
				$arr[date("H",$val['timestamp'])]++;
			}else{
				$arr[date("H",$val['timestamp'])]=1;
			}
			
		}
		for ($hour=$inputarr['openhour'];$hour<$inputarr['openhour']+24;$hour++){
			if($hour>=24){
				$datearr[]=str_pad(($hour-24),2,"0",STR_PAD_LEFT);
			}else{
				$datearr[]=str_pad($hour,2,"0",STR_PAD_LEFT);
			}
		}
		$newarr=array();
		foreach ($datearr as $dkey=>$dval){
			if(array_key_exists($dval, $arr)){
				$newarr[$dval]=$arr[$dval];
			}else{
				$newarr[$dval]=0;
			}
		}
		$newdate=array();
		foreach ($datearr as $dateval){
			$newdate[]=$dateval."点";
		}
		$newnewarr=array();
		foreach ($newarr as $nval){
			$newnewarr[]=sprintf("%.0f",$nval);
		}
// 		print_r($newarr);exit;
		if(!empty($newnewarr)){
			$datasets[]=array(
					"label"=>"",
					"fillColor"=>"rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",0.2)",
					"strokeColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointStrokeColor"=> "#fff",
					"pointHighlightFill" => "#fff",
					"pointHighlightStroke" => "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"data" =>$newnewarr,
			);
			$lineChartarr=array(
					"labels"=>$newdate,
					"datasets"=>$datasets,
			);
		}
// 		print_r($lineChartarr);exit;
		return $lineChartarr;
		
	}
	
	public function getFoodSoldByTypeData($inputarr){
		$starttime=strtotime($inputarr['startdate']." ".$inputarr['thehour'].":0:0");
		$endtime=strtotime($inputarr['enddate']." ".$inputarr['thehour'].":0:0")+86400;
		$qarr=array("shopid"=>$inputarr['shopid'],"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		$billidarr=array();
		foreach ($result as $key=>$val){
			foreach ($val['food'] as $fkey=>$fval){
				if(!empty($inputarr['ftid'])&&!empty($inputarr['ftid'][0])){
					if(in_array($fval['ftid'], $inputarr['ftid'])){
						if(!in_array(strval($val['_id']), $billidarr)){
							$billidarr[]=strval($val['_id']);
						}
		
						if(array_key_exists($fval['foodid'], $arr)){
							$arr[$fval['foodid']]['foodnum']+=$fval['foodnum'];
							$arr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
						}else{
							$arr[$fval['foodid']]=array(
									"foodname"=>$fval['foodname'],
									"foodnum"=>$fval['foodnum'],
									"foodamount"=>$fval['foodamount'],
							);
						}
					}
				}else{
					if(!in_array(strval($val['_id']), $billidarr)){
						$billidarr[]=strval($val['_id']);
					}
					if(array_key_exists($fval['foodid'], $arr)){
						$arr[$fval['foodid']]['foodnum']+=$fval['foodnum'];
						$arr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
					}else{
						$arr[$fval['foodid']]=array(
								"foodname"=>$fval['foodname'],
								"foodnum"=>$fval['foodnum'],
								"foodamount"=>$fval['foodamount'],
						);
					}
				}
			}
		}
		$piearr=array();
		foreach ($arr as $dkey=>$dval){
			$color="rgba(".mt_rand(50, 255).",".mt_rand(50, 255).",".mt_rand(50, 255).",1)";
			$piearr[]=array(
					"value"=>$dval['foodamount'],
					"color"=>	$color,
					"highlight"=>$color,
					"label"=>$dval['foodname'],
			);
		}
// 		print_r($piearr);exit;
		return $piearr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getOneUnpayBill()
	 */
	public function getOneUnpayBill($uid) {
		// TODO Auto-generated method stub
		$qarr=array("customerid"=>$uid);
		$oparr=array(
				"_id"=>1,
				"shopid"=>1,
				"tabid"	=>1,
				"cusnum"=>1,
				"timestamp"=>1,
				"food"=>1,
				"deposit"=>1,
				"paystatus"=>1,
		);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$arr=array();
		foreach ($result as $key=>$val){
			if($val['deposit']=="1"){$deposit="1";}else{$deposit="0";}
			$arr=$val;
			$arr['billid']=strval($val['_id']);
			$arr['tabname']=$this->getTabnameByTabid($val['tabid']);
			$shopinfo=$this->getShopInfo($val['shopid']);
			$arr['shopname']=$shopinfo['shopname'];
			$arr['logo']=$shopinfo['logo'];
			$arr['deposit']=$deposit;
			$arr['paystatus']=$val['paystatus'];
			break;
		}
		unset($arr['_id']);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getShopInfo()
	 */
	public function getShopInfo($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1,"mobilphone"=>1, "branchname"=>1,"logo"=>1,"depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		
		$arr=array();
		if(!empty($result)){
			if(!empty($result['logo'])){$logo=$result['logo'];}else{$logo="http://jfoss.meijiemall.com/food/default_food.png";}
			$arr=array(
					"shopid"=>strval($result['_id']),
					"mobilphone"=>$result['mobilphone'],
					"shopname"=>$result['shopname']." ".$result['branchname'],
					"logo"=>$logo,
					"depositmoney"=>$result['depositmoney'],
			);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getTabnameByTabid()
	 */
	public function getTabnameByTabid($tabid) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabname"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$tabname="";
		if(!empty($result['tabname'])){
			$tabname=$result['tabname'];
		}
		return $tabname;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getUserphoneByuid()
	 */
	public function getUserphoneByuid($uid) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		if(empty($uid)){return array("status"=>"no","telphone"=>"");}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("telphone"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result['telphone'])){
			$phonecrypt = new CookieCrypt($cusphonekey);
			$telphone=$phonecrypt->decrypt($result['telphone']);
			return array("status"=>"exist","telphone"=>$telphone);
		}else{
			return array("status"=>"no","telphone"=>"");
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getCoupontypesByShopid()
	 */
	public function getCoupontypesByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"coupontype"=>1);
		$result=DALFactory::createInstanceCollection(self::$coupontype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("cpid"=>strval($val['_id']),"couponname"=>$val['coupontype']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getOneCouponByCpid()
	 */
	public function getOneCouponByCpid($cpid) {
		// TODO Auto-generated method stub
		if(empty($cpid)){return array();}
		$qarr=array("_id"=>new MongoId($cpid));
		$oparr=array("coupontype"=>1);
		$result=DALFactory::createInstanceCollection(self::$coupontype)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("cpid"=>$cpid, "couponname"=>$result['coupontype']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::addOneCoupon()
	 */
	public function addOneCoupon($inputarr) {
		// TODO Auto-generated method stub
		$arr=array("shopid"=>$inputarr['shopid'],"coupontype"=>$inputarr['couponname']);
		DALFactory::createInstanceCollection(self::$coupontype)->save($arr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateOneCoupon()
	 */
	public function updateOneCoupon($cpid, $inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($cpid));
		$oparr=array("\$set"=>array("coupontype"=>$inputarr['couponname']));
		DALFactory::createInstanceCollection(self::$coupontype)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::delOneCoupon()
	 */
	public function delOneCoupon($cpid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($cpid));
		DALFactory::createInstanceCollection(self::$coupontype)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getRolesData()
	 */
	public function getRolesData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);		
		$result=DALFactory::createInstanceCollection(self::$role)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
// 			$roleright=$this->getOneRoleData(strval($val['_id']));
// 			if(empty($roleright)){continue;}
			$arr[]=array(
					"roleid"=>strval($val['_id']),
					"rolename"	=>$val['rolename'],
					"rolename"=>$val['rolename'],
					"detail"=>$val['detail'],
					"donate"=>$val['donate'],
					"weight"=>$val['weight'],
					"returnfood"=>$val['returnfood'],
					"outsheet"=>$val['outsheet'],
					"empty"=>$val['empty'],
					"book"=>$val['book'],
					"start"=>$val['start'],
					"online"=>$val['online'],
					"changetab"=>$val['changetab'],
					"pay"=>$val['pay'],
					"repay"=>$val['repay'],
					"signpay"=>$val['signpay'],
					"freepay"=>$val['freepay'],
					"deposit"=>$val['deposit'],
					"changeprice"=>$val['changeprice'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getOneRoleData()
	 */
	public function getOneRoleData($roleid) {
		// TODO Auto-generated method stub
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
				"repay"=>1,
				"signpay"=>1,
				"freepay"=>1,
				"deposit"=>1,
		);
		$result=DALFactory::createInstanceCollection(self::$role)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"rolename"=>$result['rolename'],
					"detail"=>$result['detail'],
					"donate"=>$result['donate'],
					"weight"=>$result['weight'],
					"returnfood"=>$result['returnfood'],
					"outsheet"=>$result['outsheet'],
					"empty"=>$result['empty'],
					"book"=>$result['book'],
					"start"=>$result['start'],
					"online"=>$result['online'],
					"changetab"=>$result['changetab'],
					"pay"=>$result['pay'],
					"repay"=>$result['repay'],
					"signpay"=>$result['signpay'],
					"freepay"=>$result['freepay'],
					"deposit"=>$result['deposit'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::changeOneRole()
	 */
	public function changeOneRole($roleid, $type, $status) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($roleid));
		$oparr=array("\$set"=>array($type=>$status));
		DALFactory::createInstanceCollection(self::$role)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::addOneRoleData()
	 */
	public function addOneRoleData($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$role)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::delOneRoleByRoleid()
	 */
	public function delOneRoleByRoleid($roleid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($roleid));
		DALFactory::createInstanceCollection(self::$role)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getShopServers()
	 */
	public function getShopServers($shopid) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		global $cuspwdkey;
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"uid"=>1, "servername"=>1,"serverphone"=>1,"serverno"=>1,"roleid"=>1,"openid"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$onerole=$this->getOneRoleData($val['roleid']);
			$phonecrypt = new CookieCrypt($cusphonekey);
			$serverphone=$phonecrypt->decrypt($val['serverphone']);
			$serverpwd=$this->getPwdByUid($val['uid']);
			$pwdecrypt = new CookieCrypt($cuspwdkey);
			$serverpwd=$pwdecrypt->decrypt($serverpwd);
			$arr[]=array(
					"serverid"=>strval($val['_id']),
					"servername"=>$val['servername'],
					"uid"=>$val['uid'],
					"serverphone"=>$serverphone,
					"serverpwd"=>$serverpwd,
					"serverno"=>$val['serverno'],
					"rolename"=>$onerole['rolename'],
					"roleid"=>$val['roleid'],
					"openid"=>$val['openid'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getOneServerByServerid()
	 */
	public function getOneServerByServerid($serverid) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		global $cuspwdkey;
		if(empty($serverid)){return array();}
		$qarr=array("_id"=>new MongoId($serverid));
		$oparr=array("_id"=>1, "servername"=>1,"uid"=>1, "serverphone"=>1,"serverno"=>1,"roleid"=>1,"openid"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$onerole=$this->getOneRoleData($serverid);
			$phonecrypt = new CookieCrypt($cusphonekey);
			$serverphone=$phonecrypt->decrypt($result['serverphone']);
			
			$customerdal=new CustomerDAL();
			$oneuserarr=$customerdal->getOneCustomerbyUid($result['uid']);
			$serverpwd="";
			if(!empty($oneuserarr)){
				$serverpwd=$oneuserarr['passwd'];
				$pwdcrypt = new CookieCrypt($cuspwdkey);
				$serverpwd=$pwdcrypt->decrypt($serverpwd);
			}
			$arr=array(
					"serverid"=>$serverid,
					"servername"=>$result['servername'],
					"serverphone"=>$serverphone,
					"serverpwd"=>$serverpwd,
					"serverno"=>$result['serverno'],
					"uid"=>$result['uid'],
					"rolename"=>$onerole['rolename'],
					"roleid"=>$result['roleid'],
					"openid"=>$result['openid'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::addOneServer()
	 */
	public function addOneServer($inputarr) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		global $cuspwdkey;
		$uid="";
		$phonecrypt = new CookieCrypt($cusphonekey);
		$serverphone=$phonecrypt->encrypt($inputarr['serverphone']);
		$pwdcrypt = new CookieCrypt($cuspwdkey);
		$serverpwd=$pwdcrypt->encrypt($inputarr['serverpwd']);
		$uid=$this->getUidByPhone($serverphone);
		$customerdal=new CustomerDAL();
		$isserver=$customerdal->isSomeShopServer($serverphone, $inputarr['shopid']);
		if($isserver){return ;}
		if(empty($uid)){
			$userarr=$customerdal->addCustomer($serverphone, $serverpwd, $inputarr['servername']);
			if($userarr['status']=="ok"){
				$uid=$userarr['uid'];
			}
		}
		if(!empty($uid)){
			$arr=array(
					"shopid"=>$inputarr['shopid'],
					"servername"=>$inputarr['servername'],
					"serverphone"=>$serverphone,
					"uid"=>$uid,
					"serverno"=>$inputarr['serverno'],
					"roleid"=>$inputarr['roleid'],
					"tables"=>array(),
					"workstatus"=>"work",
					"openid"=>$inputarr['openid'],
					"addtime"=>time(),
			);
			DALFactory::createInstanceCollection(self::$servers)->save($arr);
		}
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateOneServer()
	 */
	public function updateOneServer($serverid, $inputarr) {
		// TODO Auto-generated method stub
		global $cusphonekey;
		global $cuspwdkey;
		$phonecrypt = new CookieCrypt($cusphonekey);
		$serverphone=$phonecrypt->encrypt($inputarr['serverphone']);
		$pwdcrypt = new CookieCrypt($cuspwdkey);
		$serverpwd=$pwdcrypt->encrypt($inputarr['serverpwd']);
		if(empty($serverid)){return ;}
		$qarr=array("_id"=>new MongoId($serverid));
		$oparr=array("\$set"=>array(
				"servername"=>$inputarr['servername'],
				"serverphone"=>$serverphone,
				"serverno"=>$inputarr['serverno'],
				"roleid"=>$inputarr['roleid'],
				"openid"=>$inputarr['openid'],
		));
		DALFactory::createInstanceCollection(self::$servers)->update($qarr,$oparr);
		//修改密码
		$customerdal=new CustomerDAL();
		$serverarr=$this->getOneServerByServerid($serverid);
		if(!empty($serverarr)){
			$customerdal->updatePwdByUid($serverarr['uid'], $serverpwd);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::delOneServerByServerid()
	 */
	public function delOneServerByServerid($serverid) {
		// TODO Auto-generated method stub
		if(empty($serverid)){return ;}
		$qarr=array("_id"=>new MongoId($serverid));
		DALFactory::createInstanceCollection(self::$servers)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::addPreConsumeBill()
	 */
	public function addPreConsumeBill($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$inputarr['billid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$prebill)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$set"=>
					array(
							"ticketway"=>$inputarr['ticketway'],
							"ticketval"=>$inputarr['ticketval'],
							"ticketnum"=>$inputarr['ticketnum'],
							"discountval"=>$inputarr['discountval'],
							"allcount"=>$inputarr['allcount'],
					        "serverfee"=>$inputarr['serverfee'],
					        "servermoney"=>$inputarr['servermoney'],
							"returndepositmoney"=>$inputarr['returndepositmoney'],
							"clearmoney"=>$inputarr['clearmoney'],
							"shouldpay"=>$inputarr['shouldpay'],
					)
			);
			DALFactory::createInstanceCollection(self::$prebill)->update($qarr,$oparr);
		}else{
			$ticketname="";
				if(!empty($inputarr['ticketway'])){
				$ticketarr=$this->getOneCouponByCpid($inputarr['ticketway']);
				$ticketname=$ticketarr['couponname'];
			}
			$inputarr['ticketname']=$ticketname;
			DALFactory::createInstanceCollection(self::$prebill)->save($inputarr);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getDiscountmoney()
	 */
	public function getPreBillByBillid($billid) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$billid);
		$result=DALFactory::createInstanceCollection(self::$prebill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::isMyBindPhone()
	 */
	public function isMyBindPhone($uid,$shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("telphone"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result['telphone'])){
			$accountbalance=$this->getMyVipMoney($shopid, $uid);
			$cardid=$this->getCardid($shopid, $uid);
			if(!empty($cardid)){
				$cardinfo=$this->getOneVcdData($cardid);
				if(!empty($cardinfo)){
					return array("status"=>"ok","accountbalance"=>$accountbalance,"carddiscount"=>$cardinfo['carddiscount']);
				}else{
					return array("status"=>"unuse","accountbalance"=>$accountbalance);
				}
			}else{
				return array("status"=>"unrecharge","accountbalance"=>0);
			}
		}else{
			return array("status"=>"unbind","accountbalance"=>0);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getMyVipMoney()
	 */
	public function getMyVipMoney($shopid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid);
		$oparr=array("accountbalance"=>1,"cardid"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		$accountbalance=0;
		if(!empty($result)){
			$accountbalance=$result['accountbalance'];
		}
		return $accountbalance;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateMyvipAccount($inputarr)
	 */
	public function updateMyvipAccount($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid']);
		$oparr=array("\$set"=>array("accountbalance"=>$inputarr['accountbalance']-$inputarr['shouldpay']));
		DALFactory::createInstanceCollection(self::$myvip)->update($qarr,$oparr);
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
	 * @see IMonitorSixDAL::vipPayrecord()
	 */
	public function vipPayrecord($inputarr) {
		// TODO Auto-generated method stub
		$accountbalance=$this->getMyVipMoney($inputarr['shopid'], $inputarr['uid']);
		$cardid=$this->getCardid($inputarr['shopid'], $inputarr['uid']);
		$arr=array(
				"billid"=>$inputarr['billid'],
				"shopid"=>$inputarr['shopid'],
				"uid"=>$inputarr['uid'],
				"cardid"=>$cardid,
				"vippaymoney"=>$inputarr['shouldpay']-$inputarr['discounmoney'],
				"accountbalance"=>$accountbalance,
				"discounmoney"=>$inputarr['discounmoney'],
				"timestamp"=>time(),
		);
		DALFactory::createInstanceCollection(self::$viprecord)->save($arr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getCardid()
	 */
	public function getCardid($shopid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("cardid"=>1);
		$cardid="";
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		if(!empty($result['cardid'])){
			$cardid=$result['cardid'];
		}
		return $cardid;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::delPrebillByBillid()
	 */
	public function delPrebillByBillid($billid) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$billid);
		DALFactory::createInstanceCollection(self::$prebill)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getUidByPhone()
	 */
	public function getUidByPhone($phone) {
		// TODO Auto-generated method stub
		$qarr=array("telphone"=>$phone);
		$oparr=array("_id"=>1);
		$uid="";
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			$uid=strval($result['_id']);
		}
		return $uid;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getZonesByShopid()
	 */
	public function getZonesByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"zoneid"=>strval($val['_id']),
					"zonename"=>$val['zonename']	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getOnezoneByZoneid()
	 */
	public function getOnezoneByZoneid($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("_id"=>1,"zonename"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("zoneid"=>strval($result['_id']),"zonename"=>$result['zonename']);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::delOneZoneByZoneid()
	 */
	public function delOneZoneByZoneid($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($zoneid));
		DALFactory::createInstanceCollection(self::$zone)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateOneZone()
	 */
	public function updateOneZone($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($inputarr['zoneid']));
		$oparr=array("\$set"=>array("zonename"=>$inputarr['zonename']));
		DALFactory::createInstanceCollection(self::$zone)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::addOneZone()
	 */
	public function addOneZone($inputarr) {
		// TODO Auto-generated method stub
		$arr=array("zonename"=>$inputarr['zonename'],"shopid"=>$inputarr['shopid']);
		DALFactory::createInstanceCollection(self::$zone)->save($arr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::isShowVipPay()
	 */
	public function isShowVipPay($uid, $shopid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$myvip)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}

	/* (non-PHPdoc)
	 * @see ITestDAL::getFoodinfo()
	*/
	public function getFoodinfo($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"foodname"=>1,"foodprice"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("foodid"=>strval($val['_id']),"foodname"=>$val['foodname'],"foodprice"=>$val['foodprice']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see ITestDAL::sortfoodData()
	*/
	public function sortfoodData($arr) {
		// TODO Auto-generated method stub
		foreach ($arr as $foodid=>$val){
		    if(empty($foodid)){continue;}
			$qarr=array("_id"=>new MongoId($foodid));
			$oparr=array("\$set"=>array("sortno"=>$val['sortno']));
			DALFactory::createInstanceCollection(self::$food)->update($qarr,$oparr);
		}
	}

	public function getPwdByUid($uid){
		if(empty($uid)){return "";}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("passwd"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		$passwd="";
		if(!empty($result)){
			$passwd=$result['passwd'];
		}
		return $passwd;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::saveOneTag()
	 */
	public function saveOneTag($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$viptag)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateOneTag()
	 */
	public function updateOneTag($viptagid, $inputarr) {
		// TODO Auto-generated method stub
		if(empty($viptagid)){return ;}
		$qarr=array("_id"=>new MongoId($viptagid));
		$oparr=array("\$set"=>array("tagname"=>$inputarr['tagname']));
		DALFactory::createInstanceCollection(self::$viptag)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getOneTagByTagid()
	 */
	public function getOneTagByTagid($viptagid) {
		// TODO Auto-generated method stub
		if(empty($viptagid)){return array();}
		$qarr=array("_id"=>new MongoId($viptagid));
		$oparr=array("_id"=>1,"tagname"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$viptag)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array(
					"viptagid"=>$viptagid,
					"tagname"=>$result['tagname'],	
			);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::delOneTagByTagid()
	 */
	public function delOneTagByTagid($viptagid) {
		// TODO Auto-generated method stub
		if(empty($viptagid)){return ;}
		$qarr=array("_id"=>new MongoId($viptagid));
		DALFactory::createInstanceCollection(self::$viptag)->remove($qarr);
	}

	public function getViptagsData($bossid){
		$qarr=array("bossid"=>$bossid);
		$oparr=array("_id"=>1,"tagname"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$viptag)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$arr[]=array(
					"tagid"=>strval($val['_id']),
					"tagname"=>$val['tagname'],	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getBossidByShopid()
	 */
	public function getBorthershopByShopid($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$qarr=array("shopid"=>$shopid);
		$oparr=array("bossid"=>1);
		$result=DALFactory::createInstanceCollection(self::$subaccount)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$bossid=$result['bossid'];
			$bossonedal=new BossOneDAL();
			$arr=$bossonedal->getMyShoplistData($bossid);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getFoodtypeByShopid()
	 */
	public function getFoodtypeByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"foodtypename"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"ftid"=>strval($val['_id']),
					"ftname"=>$val['foodtypename'],	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::DoCopyMenuData()
	 */
	public function DoCopyMenuData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['fromshopid'],"foodtypeid"=>$inputarr['fromftid']);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr);
		$arr=array();
		//先删除
		$delqarr=array("shopid"=>$inputarr['toshopid'],"foodtypeid"=>$inputarr['toftid']);
		DALFactory::createInstanceCollection(self::$food)->remove($delqarr);
		foreach ($result as $key=>$val){
			$val['zoneid']=$inputarr['tozoneid'];
			$val['shopid']=$inputarr['toshopid'];
			$val['foodtypeid']=$inputarr['toftid'];
			unset($val['_id']);
			DALFactory::createInstanceCollection(self::$food)->insert($val);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getZoneIdByShopid()
	 */
	public function getZoneIdByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("zoneid"=>strval($val['_id']),"zonename"=>$val['zonename']);
		}
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
	 * @see IMonitorSixDAL::setShopaccountData()
	 */
	public function setShopaccountData($shopid, $op, $val) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopaccount)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$set"=>array($op=>$val));
			DALFactory::createInstanceCollection(self::$shopaccount)->update($qarr,$oparr);
		}else{
			$arr=array("shopid"=>$shopid,$op=>$val);
			DALFactory::createInstanceCollection(self::$shopaccount)->save($arr);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getShopaccountData()
	 */
	public function getShopaccountData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$result=DALFactory::createInstanceCollection(self::$shopaccount)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"shopkeeper"=>$result['shopkeeper'],
					"bankno"=>$result['bankno'],
					"bankbranch"	=>$result['bankbranch'],
			);
		}
		return $arr;
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getUpTime()
	 */
	public function getUpTime($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("uptime"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopaccount)->findOne($qarr,$oparr);
		$uptime=time();
		if(!empty($result)){
			$uptime=$result['uptime'];
		}
		return $uptime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateAccountData()
	 */
	public function updateAccountData($shopid, $op,$newpic, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$result=DALFactory::createInstanceCollection(self::$shopaccount)->findOne($qarr);
		if(!empty($result)){
			$oparr=array("\$set"=>array($op=>$newpic,"uptime"=>$timestamp));
			DALFactory::createInstanceCollection(self::$shopaccount)->update($qarr,$oparr);
		}else{
			$arr=array("shopid"=>$shopid,$op=>$newpic,"uptime"=>$timestamp);
			DALFactory::createInstanceCollection(self::$shopaccount)->save($arr);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::saveShopAccount()
	 */
	public function saveShopAccount($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$shopaccount)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::updateShopAccount()
	 */
	public function updateShopAccount($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['shopid']);
		$oparr=array(
			"\$set"=>array(
					"shopkeeper"=>$inputarr['shopkeeper'],
					"bankno"=>$inputarr['bankno'],
					"brankbranch"=>$inputarr['brankbranch'],		
			)
		);
		DALFactory::createInstanceCollection(self::$shopaccount)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getShopAcountData()
	 */
	public function getShopAcountData($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$qarr=array("shopid"=>$shopid);
		$result= DALFactory::createInstanceCollection(self::$shopaccount)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$IDCardface=$this->ImageSetSize($result['IDCardface'], 480, 0);
			$IDCardback=$this->ImageSetSize($result['IDCardback'], 480, 0);
			$banckcardface=$this->ImageSetSize($result['banckcardface'], 480, 0);
			$arr=array(
				"shopid"=>$result['shopid'],
					"shopkeeper"=>$result['shopkeeper'],
					"bankno"=>$result['bankno'],
					"bankbranch"=>$result['bankbranch'],
					"IDCardface"=>$IDCardface,
					"IDCardback"=>$IDCardback,
					"banckcardface"=>$banckcardface,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::bindShopidAndOpenid()
	 */
	public function bindShopidAndOpenid($shopid, $openid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$wechat_shop)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$set"=>array("openid"=>$openid));
			DALFactory::createInstanceCollection(self::$wechat_shop)->update($qarr,$oparr);
		}else{
			$arr=array("shopid"=>$shopid,"openid"=>$openid);
			DALFactory::createInstanceCollection(self::$wechat_shop)->save($arr);
		}
	}

	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::getShopidByOpenid()
	 */
	public function getShopidByOpenid($openid) {
		// TODO Auto-generated method stub
		$qarr=array("openid"=>$openid);
		$oparr=array("shopid"=>1);
		$shopid="";
		$result=DALFactory::createInstanceCollection(self::$wechat_shop)->findOne($qarr,$oparr);
		if(!empty($result)){
			$shopid=$result['shopid'];
		}
		return $shopid;
	}
	/* (non-PHPdoc)
	 * @see IMonitorSixDAL::ImageSetSize()
	 */
	public function ImageSetSize($imgurl, $width, $height) {
		// TODO Auto-generated method stub
		global $resizeurl;
		$arr1=explode("http://", $imgurl);
		$http=$arr1[0];
		$exphttp=$arr1[1];
		$b=explode("/", $exphttp);
		$domain=$b[0];
		$food=$b[1];
		$detail=$b[2];
		$newfoodpic=$resizeurl."/".$domain."/cache!".$width."x".$height."/".$food."/".$detail;
		return $newfoodpic;
	}
    /**
     * {@inheritDoc}
     * @see IMonitorSixDAL::getRealOpenidByShopid()
     */
    public function getRealOpenidByShopid($shopid)
    {
        // TODO Auto-generated method stub
        if(empty($shopid)){return "0";}
        $qarr=array("_id"=>new MongoId($shopid));
        $oparr=array("openid"=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
        $realopenid="0";
        if(!empty($result['openid'])){
            $realopenid=$result['openid'];
        }
        return $realopenid;
    }
    /**
     * {@inheritDoc}
     * @see IMonitorSixDAL::getShopmoneyByShopid()
     */
    public function getShopmoneyByShopid($shopid)
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
     * @see IMonitorSixDAL::getTodayMoney()
     */
    public function getTodayMoney($shopid)
    {
        // TODO Auto-generated method stub
        $monitoronedal=new MonitorOneDAL();
        $openhour=$monitoronedal->getOpenHourByShopid($shopid);
        $theday=$monitoronedal->getTheday($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime =strtotime($theday." ".$openhour.":0:0")+86400;//"paystatus"=>"paid",
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$wechatpay=0;
		$alipay=0;
		foreach ($result as $key=>$val){
		    $wechatpay+=$val['wechatpay'];
		    $alipay+=$val['alipay'];
		}
		return $wechatpay+$alipay;
    }
    /**
     * {@inheritDoc}
     * @see IMonitorSixDAL::addTransferLog()
     */
    public function addTransferLog($inputarr)
    {
        // TODO Auto-generated method stub
        DALFactory::createInstanceCollection(self::$takeout_money)->save($inputarr);
        if($inputarr['is_ok']==1){
        	$this->updateShoperAccount($inputarr['shopid'], $inputarr['origincash']);
        }
    }
    /**
     * {@inheritDoc}
     * @see IMonitorSixDAL::updateShoperAccount()
     */
    public function updateShoperAccount($shopid, $account)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $money=$this->getShopmoneyByShopid($shopid);
        if($account<=$money){
            $oparr=array("\$set"=>array("money"=>$money-$account));
            DALFactory::createInstanceCollection(self::$wechat_balance)->update($qarr,$oparr);
        }
    }

    /**
     * {@inheritDoc}
     * @see IMonitorSixDAL::getShopidByPhoneAndPwd()
     */
    public function getShopidByPhoneAndPwd($phone, $passwd)
    {
        // TODO Auto-generated method stub
        global $phonekey;
        global $pwdkey;
        $phonecrypt = new CookieCrypt($phonekey);
        $phone=$phonecrypt->encrypt($phone);
        $pwdcrypt = new CookieCrypt($pwdkey);
        $passwd=$pwdcrypt->encrypt($passwd);
        $qarr=array("mobilphone"=>$phone,"passwd"=>$passwd);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
        $shopid="";
        if(!empty($result)){
            $shopid=strval($result['_id']);
        }
        return $shopid;
    }





	
	public function setPaidBillData($inputarr){
		$qarr=array("billid"=>$inputarr['billid']);
		$result=DALFactory::createInstanceCollection(self::$paidcheck)->findOne($qarr);
		if(!empty($result)){
			$oparr=array("\$set"=>array("paidmoney"=>$inputarr['paidmoney'],"totalmoney"=>$inputarr['totalmoney']));
			DALFactory::createInstanceCollection(self::$paidcheck)->update($qarr,$oparr);
		}else{
			$arr=array("billid"=>$inputarr['billid'],"shopid"=>$inputarr['shopid'], "paidmoney"=>$inputarr['paidmoney'],"totalmoney"=>$inputarr['totalmoney']);
			DALFactory::createInstanceCollection(self::$paidcheck)->save($arr);
		}
	}
	
	public function getPaidBillData($billid){
		$qarr=array("billid"=>$billid);
		$result=DALFactory::createInstanceCollection(self::$paidcheck)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['paidmoney'])){$paidmoney=$result['paidmoney'];}else{$paidmoney=0;}
			if(!empty($result['totalmoney'])){$totalmoney=$result['totalmoney'];}else{$totalmoney=0;}
		}
		$arr=array(
				"paidmoney"=>$paidmoney,
				"totalmoney"=>$totalmoney,
			);
		return $arr;
	}
	
	public function sendSmsEmerg($billid){
		global $token;
        $msg= json_encode(array());
        $time=time();
        $phone=$this->getPhoneByBillid($billid);
        $signature=strtoupper(md5($phone.$msg.$time.$token));
        $data=array("phone"=>$phone,"msg"=>$msg,"timestamp"=>$time,"signature"=>$signature);
        $url=_ROOTURL."yuntongxun/interface/sendcuikuanmsg.php";
        $this->getPostRequest($url,$data);
        
	}
	
	private function getPostRequest($url='', $data){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $tmpInfo = curl_exec($curl);
	    if (curl_errno($curl)) {
	        echo 'Errno: '.curl_error($curl);
	    }
	    curl_close($curl);
	    $datas = json_decode($tmpInfo,true);
	    return $datas;
	}
	
	public function getPhoneByBillid($billid){
		global $cusphonekey;
		if(empty($billid)){return "";}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("uid"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result['uid'])){
			 $openid=$this->getOpenidByuid($result['uid']);
			 if(!empty($openid)){
			 	$serverphone=$this->getPhoneByOpenid($openid);
			 }
		}
		if(!empty($serverphone)){
			$phonecrypt = new CookieCrypt($cusphonekey);
			$serverphone=$phonecrypt->decrypt($serverphone);
		}
		return $serverphone;
	}
	
	public function getOpenidByuid($uid){
		$qarr=array("uid"=>$uid);
		$oparr=array("openid"=>1);
		$result=DALFactory::createInstanceCollection(self::$wechat_user_info)->findOne($qarr,$oparr);
		$openid="";
		if(!empty($result)){
			$openid=$result['openid'];
		}
		$openid=trim($openid);
		return $openid;
	}
	
	public function getPhoneByOpenid($openid){
	    $openid = trim($openid);
		$qarr=array("openid"=>$openid,"shopid"=>"5747a74b5bc10906068b45c3");
		$oparr=array("serverphone"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$serverphone="";
		if(!empty($result)){
			$serverphone=$result['serverphone'];
		}
		return $serverphone;
	}
	
	public function sendSMSBillInfo($billid,$op){
		global $token;
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		
		$oneshopinfo=$this->getOneBillShopinfo("billshopinfo", $billid);
		if(!empty($oneshopinfo)){
			$orderInfo='收货地址：'.$oneshopinfo['prov'].$oneshopinfo['city'].$oneshopinfo['dist'].$oneshopinfo['road'].';';
			if(!empty($oneshopinfo['shopname'])){
			    $orderInfo.='店名：'.$oneshopinfo['shopname'].';';
			}
			if(!empty($oneshopinfo['author'])){
			    $orderInfo.='下单人：'.$oneshopinfo['author'].';';
			}
			if(!empty($oneshopinfo['contact'])){
			     $orderInfo.='联系人：'.$oneshopinfo['contact'].';';
			}
			if(!empty($oneshopinfo['phone'])){
			    $orderInfo.='电话：'.$oneshopinfo['phone'].';';
			}
			if(!empty($oneshopinfo['carno'])){
			    $orderInfo.='车牌号：'.$oneshopinfo['carno'].';';
			}
			if(!empty($oneshopinfo['picktime'])){
			    $orderInfo.='提送时间：'.$oneshopinfo['picktime'].';';
			}
			if(!empty($oneshopinfo['orderrequest'])){
			    $orderInfo.="备注：".$oneshopinfo['orderrequest'].';';
			}
		}
		$orderInfo.="以下是下单明细：";
		foreach($result['food'] as $key=>$val){
			$orderInfo.=$val['foodname'].":".$val['foodnum'].$val['foodunit'].";";
		}
        
        $time=time();
        if($op=="inner" || empty($op)){
        	$phone=$this->getPhoneByBillid($billid);
        }else{
        	$phone="18867131386";
        }
        $area = $this->getAreaByBillid($billid);
        if($area){
            $managerphone = $this->getManagerPhone($area);
        }
        if(!empty($managerphone) && $managerphone!=$phone){
            $phone=$phone.",".$managerphone;
        }
        $msg= json_encode(array($orderInfo));
        $signature=strtoupper(md5($phone.$msg.$time.$token));
        $data=array("phone"=>$phone,"msg"=>$msg,"timestamp"=>$time,"signature"=>$signature);
        $url=_ROOTURL."yuntongxun/interface/comebill.php";
        $res=$this->getPostRequest($url,$data);
	}
	
	public function getOneBillShopinfo($tab, $billid) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$billid);
		return DALFactory::createInstanceCollection($tab)->findOne($qarr);
		
	}
	public function getAreaByBillid($billid){
	    if(empty($billid)){return '';}
	    $qarr=array("_id"=>new MongoId($billid));
	    $result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
	    if(!empty($result['uid'])){
	        $openid=$this->getOpenidByuid($result['uid']);
	        $qarr = array('openid' => $openid,"shopid"=>"5747a74b5bc10906068b45c3");
	        $oparr=array("serverno"=>1);
	        $man = DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
	        $num = strpos($man['serverno'], '区');
	    }
	    $area = NULL;
	    if($num){
	        //获取区 中文使用 mb_substr
	        $area =  mb_substr($man['serverno'], 0 , $num);
	    }
	     
	    return $area;
	}
	//根据uid获取到区域经理手机号
	public function getManagerPhone($area)
	{
	    global $cusphonekey;
	    //$qarr= array('');
	    if(empty($area)){return "";}
	    $qarr = array('shopid' => '5747a74b5bc10906068b45c3');
	    $server = DALFactory::createInstanceCollection(self::$servers)->find($qarr);
	
	    //获取全部经理
	    $managers = array();
	    foreach ($server as $v){
	        if(strpos($v['serverno'], '经理')){ $managers[]=$v;}
	    }
	    $phone=NULL;
	    foreach ($managers as $v){
	        if(strpos($v['serverno'], $area)!==false){
	            //获取他的手机
	            $phone =  $v['serverphone'];
	            break;
	        }
	    }
	    if(!empty($phone)){
	        $phonecrypt = new CookieCrypt($cusphonekey);
	        $phone=$phonecrypt->decrypt($phone);
	    }
	    return $phone;
	}
}
?>