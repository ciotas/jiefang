<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorCusDAL.php');
require_once (_ROOT.'DALFactory.php');
require_once (_ROOT.'HttpClient.class.php');
require_once (_ROOT.'des.php');

require_once ('MonitorOneDAL.php');

class MonitorCusDAL implements IMonitorCusDAL{
	private static $wechat_user_info="wechat_user_info";
	private static $bill="bill";
	private static $food="food";
	
	/* (non-PHPdoc)
	 * @see IMonitorCusDAL::getCusConsumeInfo()
	 */
	public function getCusConsumeInfo($shopid,$theday) {
		// TODO Auto-generated method stub
		$monitoronedal=new MonitorOneDAL();
		$openhour=$monitoronedal->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"payrole"=>"customer","paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("uid"=>1,"paymoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			if(array_key_exists($val['uid'], $arr)){
				$arr[$val['uid']]['num']+=1;
				$arr[$val['uid']]['money']+=$val['paymoney'];
			}else{
				$userinfo=$this->getWechatUserinfo($val['uid']);
				if(empty($userinfo)){continue;}
				if($userinfo['sex']=="1"){$sexname="男";}else{$sexname="女";}
				$arr[$val['uid']]=array(
						"nickname"=>$userinfo['nickname'],
						"headimgurl"=>$userinfo['headimgurl'],
						"sexname"=>$sexname,
						"sex"=>$userinfo['sex'],
						"province"=>$userinfo['province'],
						"city"=>$userinfo['city'],
						"platform"=>$userinfo['platform'],
						"num"=>1,
						"money"=>$val['paymoney'],
				);
			}
		}
		$arr=$monitoronedal->array_sort($arr, "num","desc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorCusDAL::getWechatUserinfo()
	 */
	public function getWechatUserinfo($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid);
		$result=DALFactory::createInstanceCollection(self::$wechat_user_info)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"nickname"=>$result['nickname'],
					"headimgurl"=>$result['headimgurl'],
					"sex"=>$result['sex'],
					"province"=>$result['province'],
					"city"=>$result['city'],
					"platform"=>$result['platform'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorCusDAL::getSexPercentRate()
	 */
	public function getSexPercentRate($inputarr) {
		// TODO Auto-generated method stub
		$piearr=array();
		$arr=array();
		$totalsex=0;
		foreach ($inputarr as $key=>$val){
			$totalsex+=1;
			if(array_key_exists($val['sexname'], $arr)){
				$arr[$val['sexname']]+=1;
			}else{
				$arr[$val['sexname']]=1;
			}
		}
		foreach ($arr as $sexname=>$num){
			$color="rgba(".mt_rand(50, 255).",".mt_rand(50, 255).",".mt_rand(50, 255).",1)";
			$piearr[]=array(
					"value"=>$num,
					"color"=>	$color,
					"highlight"=>$color,
					"label"=>$sexname."(".sprintf("%.0f",100*$num/$totalsex)."%)",
			);
		}
		return $piearr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorCusDAL::getPlatformPercent()
	 */
	public function getPlatformPercent($inputarr) {
		// TODO Auto-generated method stub
		$piearr=array();
		$arr=array();
		$totalplatform=0;
		foreach ($inputarr as $key=>$val){
			$totalplatform+=1;
			if(array_key_exists($val['platform'], $arr)){
				$arr[$val['platform']]+=1;
			}else{
				$arr[$val['platform']]=1;
			}
		}
		foreach ($arr as $platform=>$num){
			$color="rgba(".mt_rand(50, 255).",".mt_rand(50, 255).",".mt_rand(50, 255).",1)";
			$piearr[]=array(
					"value"=>$num,
					"color"=>	$color,
					"highlight"=>$color,
					"label"=>$platform."(".sprintf("%.0f",100*$num/$totalplatform)."%)",
			);
		}
		return $piearr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorCusDAL::getCityPercet()
	 */
	public function getCityPercet($inputarr) {
		// TODO Auto-generated method stub
		$piearr=array();
		$arr=array();
		$totalcity=0;
		foreach ($inputarr as $key=>$val){
			$totalcity+=1;
			if(array_key_exists($val['city'], $arr)){
				$arr[$val['city']]+=1;
			}else{
				$arr[$val['city']]=1;
			}
		}
		foreach ($arr as $city=>$num){
			$color="rgba(".mt_rand(50, 255).",".mt_rand(50, 255).",".mt_rand(50, 255).",1)";
			$piearr[]=array(
					"value"=>$num,
					"color"=>	$color,
					"highlight"=>$color,
					"label"=>$city."(".sprintf("%.0f",100*$num/$totalcity)."%)",
			);
		}
		return $piearr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorCusDAL::getFoodpicsData()
	 */
	public function getFoodpicsData($searchfoodname,$p,$pagenum) {
		// TODO Auto-generated method stub
		if(!empty($searchfoodname)){
			$qarr=array("foodname"=>array("\$regex"=>$searchfoodname), "foodpic"=>array("\$ne"=>null,"\$ne"=>"","\$exists"=>true));
		}else{
			$qarr=array("foodpic"=>array("\$ne"=>null,"\$ne"=>"","\$exists"=>true));
		}
		$oparr=array("_id"=>1,"foodname"=>1,"foodpic"=>1);
		$sarr=array("addtime"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr)->sort($sarr)->limit($pagenum)->skip($pagenum*$p-$pagenum);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"foodid"=>strval($val['_id']),
					"foodname"=>$val['foodname'],
					"foodpic"=>$val['foodpic'],
			);
		}
		return $arr;
	}
	
}
?>