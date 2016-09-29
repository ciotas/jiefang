<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IWorkerTwoDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');

require_once ('WorkerDAL.php');

class WorkerTwoDAL implements IWorkerTwoDAL{
	private static $table="table";
	private static $bill="bill";
	private static $servers="servers";
	private static $customer="customer";
	private static $shopinfo="shopinfo";
	private static $food="food";
	private static $foodtype="foodtype";
	private static $printer="printer";
	private static $role="role";
	private static $zone="zone";
	private static $returnbill="returnbill";
	private static $selfdonate="selfdonate";
	private static $sellpackage="sellpackage";
	private static $package="package";
	private static $coupontype="coupontype";
	private static $billrecord="billrecord";
	private static $otherbill="otherbill";
	private static $stock="stock";
	private static $autostock="autostock";
	private static $beforbill="beforebill";
	private static $payrecord="payrecord";
	private static $shop_info="shop_info";
	private static $billshopinfo="billshopinfo";
	private static $prebillshopinfo="prebillshopinfo";
	private static $sheetno="sheetno";
	private static $wechat_user_info="wechat_user_info";
	private static $wechat_shop="wechat_shop";
	private static $returnfoodrecord="returnfoodrecord";
	private static $switchfoodrecord="switchfoodrecord";
	private static $switchtabrecord="switchtabrecord";
	private static $replacefoodrecord="replacefoodrecord";
	private static $change_tabstatus_record="change_tabstatus_record";
	private static $takeoutdiscount = "takeoutdiscount";
	private static $takeoutfare = "takeoutfare";
	private static $myaddress = "myaddress";
	private static $geokey="U5CBZ-6RBWX-CJB4F-7SBFJ-JKHHE-F5BUS";
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getHistoryLists()
	 * $data指定日期，暂时保留
	 */
	public function getHistoryLists($shopid,$uid,$date=NULL) {
		// TODO Auto-generated method stub
		if(empty($date))
		{
		    $date = date('Y-m-d');
		}
		//$ltedate = strtotime($date)+1*86400;
		$gtedate = strtotime(date('Y-m-d')) - 3*86400;
		$qarr=array("uid"=>$uid,"shopid"=>$shopid,"gmt_payment" =>array('$gte'=>$gtedate,));
		$result=DALFactory::createInstanceCollection(self::$payrecord)->find($qarr);
		$arr=array();
		$workerdal=new WorkerDAL();
		foreach ($result as $key=>$val){
			$shopinfo=$workerdal->getShopInfo($val['shopid']);
			$arr[]=array(
					"billid"=>$val['billid'],
					"time"=>$val['gmt_payment'],
					"orderno"=>$val['orderno'],
					"paymoney"=>$val['total_fee'],	
					"shopname"=>$shopinfo['shopname'],
			);
		}
		
		$arr=$this->array_sort($arr, "time",desc);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::array_sort()
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
	 * @see IWorkerTwoDAL::intoShop_infoData()
	 */
	public function intoShop_infoData($inputarr) {
		// TODO Auto-generated method stub
		if(!empty($inputarr['shopid'])){
			$qarr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid']);
			$oparr=array("_id"=>1);
			$result=DALFactory::createInstanceCollection(self::$shop_info)->findOne($qarr,$oparr);
			if(!empty($result)){
				$oparr=array(
					"\$set"=>array(
							"prov"=>$inputarr['prov'],
							"city"=>$inputarr['city'],
							"dist"=>$inputarr['dist'],
							"road"=>$inputarr['road'],
							"shopname"=>$inputarr['shopname'],
							"contact"=>$inputarr['contact'],
							"phone"=>$inputarr['phone'],
							"picktime"=>$inputarr['picktime'],
							"orderrequest"=>$inputarr['orderrequest'],
					)
				);
				DALFactory::createInstanceCollection(self::$shop_info)->update($qarr,$oparr);
			}else{
				DALFactory::createInstanceCollection(self::$shop_info)->save($inputarr);
			}
		}
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getOneShop_infoData()
	 */
	public function getOneShop_infoData($shopid,$uid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){
		    $qarr=array("uid"=>$uid);
		}else{
		    $qarr=array("shopid"=>$shopid,"uid"=>$uid);
		}
		$result=DALFactory::createInstanceCollection(self::$shop_info)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::intoBillShopinfo()
	 */
	public function intoBillShopinfo($inputarr,$tab) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$inputarr['billid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection($tab)->findOne($qarr,$oparr);
		if(!empty($result)){//修改
			$oparr=array(
				"\$set"=>array(
						"prov"=>$inputarr['prov'],
						"city"=>$inputarr['city'],
						"dist"=>$inputarr['dist'],
						"road"=>$inputarr['road'],
						"shopname"=>$inputarr['shopname'],
						"contact"=>$inputarr['contact'],
						"phone"=>$inputarr['phone'],
						"picktime"=>$inputarr['picktime'],	
				)	
			);
			DALFactory::createInstanceCollection($tab)->update($qarr,$oparr);
		}else{
			DALFactory::createInstanceCollection($tab)->save($inputarr);
		}
		
	}
	
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getOneBillShopinfo()
	 */
	public function getOneBillShopinfo($tab, $billid) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$billid);
		return DALFactory::createInstanceCollection($tab)->findOne($qarr);
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getVipdiscount()
	 */
	public function getVipdiscount($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("vipdiscount"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$vipdiscount=100;
		if(!empty($result['vipdiscount'])){
			$vipdiscount=$result['vipdiscount'];
		}
		return $vipdiscount;
	}
	
	public function addBillNum($shopid,$billid,$theday){
		$qarr=array("shopid"=>$shopid,"theday"=>$theday);
		$oparr=array("no"=>1);
		$result=DALFactory::createInstanceCollection(self::$sheetno)->find($qarr,$oparr)->sort(array("no"=>-1))->limit(1);
		if(!empty($result)){
			foreach ($result as $key=>$val){
				$no=strval($val['no']);break;
			}
			$no+=1;
		}else{
			$no=1;
		}
		$arr=array("shopid"=>$shopid,"billid"=>$billid, "theday"=>$theday,"no"=>$no);
		DALFactory::createInstanceCollection(self::$sheetno)->save($arr);
	}
	
	public function getBillNum($billid){
		$qarr=array("billid"=>$billid);
		$oparr=array("no"=>1);
		$result=DALFactory::createInstanceCollection(self::$sheetno)->findOne($qarr,$oparr);
		$no="";
		if(!empty($result)){
			$no=$result['no'];
			$no++;
		}
		return $no;
	}
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::isBindShopByOpenid()
     */
    public function isBindShopByOpenid($openid)
    {
        // TODO Auto-generated method stub
        $qarr=array("openid"=>$openid);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$wechat_shop)->findOne($qarr,$oparr);
        if(!empty($result)){
            return "1";
        }else{
            return "0";
        }
    }
    public function intoInnerShop_infoData($inputarr){
        if(!empty($inputarr['shopid'])){
            $qarr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid']);
            $oparr=array("_id"=>1);
            $result=DALFactory::createInstanceCollection(self::$shop_info)->findOne($qarr,$oparr);
            if(!empty($result)){
                $oparr=array(
                    "\$set"=>array(
                        "distribution"=>$inputarr['distribution'],
                        "porttype"=>$inputarr['porttype'],
                        "carno"=>$inputarr['carno'],
                        "author"=>$inputarr['author'],
                        "prov"=>$inputarr['prov'],
                        "city"=>$inputarr['city'],
                        "dist"=>$inputarr['dist'],
                        "road"=>$inputarr['road'],
                        "shopname"=>$inputarr['shopname'],
                        "contact"=>$inputarr['contact'],
                        "phone"=>$inputarr['phone'],
                        "picktime"=>$inputarr['picktime'],
                        "orderrequest"=>$inputarr['orderrequest'],
                    )
                );
                DALFactory::createInstanceCollection(self::$shop_info)->update($qarr,$oparr);
            }else{
                DALFactory::createInstanceCollection(self::$shop_info)->save($inputarr);
            }
        }
    }
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::intoBillInnerShopinfo()
     */
    public function intoBillInnerShopinfo($inputarr, $tab)
    {
        // TODO Auto-generated method stub
        $qarr=array("billid"=>$inputarr['billid']);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection($tab)->findOne($qarr,$oparr);
        if(!empty($result)){//修改
            $oparr=array(
                  "distribution"=>$inputarr['distribution'],
                        "porttype"=>$inputarr['porttype'],
                        "carno"=>$inputarr['carno'],
                        "author"=>$inputarr['author'],
                        "prov"=>$inputarr['prov'],
                        "city"=>$inputarr['city'],
                        "dist"=>$inputarr['dist'],
                        "road"=>$inputarr['road'],
                        "shopname"=>$inputarr['shopname'],
                        "contact"=>$inputarr['contact'],
                        "phone"=>$inputarr['phone'],
                        "picktime"=>$inputarr['picktime'],
                        "orderrequest"=>$inputarr['orderrequest'],
                
            );
            DALFactory::createInstanceCollection($tab)->update($qarr,$oparr);
        }else{
            DALFactory::createInstanceCollection($tab)->save($inputarr);
        }
    }
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::getShopidByOpenid()
     */
    public function getShopidByOpenid($openid)
    {
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
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::getDIstance()
     */
    public function getDIstance($inputarr)
    {
        // TODO Auto-generated method stub
        $detailaddr=$inputarr['prov'].$inputarr['city'].$inputarr['dist'].$inputarr['road'];
        $detailaddr=urlencode($detailaddr);
        $url="http://apis.map.qq.com/ws/geocoder/v1/?address=$detailaddr&key=".self::$geokey;
        $res=$this->getGetRequest($url);
        $dis=0;
        if($res['status']=="0"){
            $workerdal=new WorkerDAL();
            $shoparr=$workerdal->getShopInfo($inputarr['shopid']);
            if(!empty($shoparr['loc'])){
                $dis=$this->getDistanceByLatLon($shoparr['loc']['coordinates'][1], $shoparr['loc']['coordinates'][0], $res['result']['location']['lat'], $res['result']['location']['lng']);
            }
        }
        return $dis;
        
    }
    
    private function getGetRequest($url = '') {
		// TODO Auto-generated method stub
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算>法是否存在
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$data = curl_exec($curl); // 执行操作
		if (curl_errno($curl)) {
			echo 'Errno: '.curl_error($curl);//捕抓异常
		}
		curl_close($curl); // 关闭CURL会话
		$jsoninfo = json_decode($data,true); // 返回数据
		return $jsoninfo;
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
    
	/**
	 *  @desc 根据两点间的经纬度计算距离
	 *  @param float $lat 纬度值
	 *  @param float $lng 经度值
	 */
	function getDistanceByLatLon($lat1, $lng1, $lat2, $lng2)
	{
	    $earthRadius = 6367000; //approximate radius of earth in meters
	
	    /*
	     Convert these degrees to radians
	     to work with the formula
	     */
	
	    $lat1 = ($lat1 * pi() ) / 180;
	    $lng1 = ($lng1 * pi() ) / 180;
	
	    $lat2 = ($lat2 * pi() ) / 180;
	    $lng2 = ($lng2 * pi() ) / 180;
	
	    /*
	     Using the
	     Haversine formula
	
	     http://en.wikipedia.org/wiki/Haversine_formula
	
	     calculate the distance
	     */
	
	    $calcLongitude = $lng2 - $lng1;
	    $calcLatitude = $lat2 - $lat1;
	    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	    $calculatedDistance = $earthRadius * $stepTwo;
	
	    return round($calculatedDistance);
	}
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::getDistanceLimit()
     */
    public function getDistanceLimit($shopid)
    {
        // TODO Auto-generated method stub
        if(empty($shopid)){return 0;}
        $qarr=array("_id"=>new MongoId($shopid));
        $oparr=array("distance"=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
        $distance=0;
        if(!empty($result['distance'])){
            $distance=$result['distance'];
        }
        return $distance;
    }
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::getTakeoutSwitch()
     */
    public function getTakeoutSwitch($shopid)
    {
        // TODO Auto-generated method stub
        if(empty($shopid)){return "1";}
        $qarr=array("_id"=>new MongoId($shopid));
        $oparr=array("takeoutswitch"=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
        $takeoutswitch="1";
        if(isset($result['takeoutswitch'])){
            $takeoutswitch=$result['takeoutswitch'];
        }
        return $takeoutswitch;
    }
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::sendDownbillMsg()
     */
    public function sendDownbillMsg($shopid,$billid,$paymoney)
    {
        // TODO Auto-generated method stub
        global $token;
        $workerdal=new WorkerDAL();
        $shoparr=$workerdal->getShopInfo($shopid);
        if(!empty($shoparr)){
            if(empty($billid)){return ;}
		    $qarr=array("_id"=>new MongoId($billid));
		    $result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		    
		    $oneshopinfo=$this->getOneBillShopinfo("billshopinfo", $billid);
		    $orderInfo="订单总额：".$paymoney."元。";
		    if(!empty($oneshopinfo)){
			    $orderInfo.='收货地址：'.$oneshopinfo['prov'].$oneshopinfo['city'].$oneshopinfo['dist'].$oneshopinfo['road'].';';
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
		    $orderInfofood="";
		    foreach($result['food'] as $key=>$val){
			    $orderInfofood.=$val['foodname'].":".$val['foodnum'].$val['foodunit'].";";
		    }
		    
            $msgarr=array($orderInfo,$orderInfofood);
            $msg= json_encode($msgarr);
            $time=time();
            $userphone="";
            if(!empty($oneshopinfo['phone'])){
                $userphone=$oneshopinfo['phone'];
            }
            if($shopid=="57848d0d1a156f97728b45c5"){
                $mobilphone="18072709337,".$userphone;
            }else{
                $mobilphone=$shoparr['mobilphone'].",".$userphone;
            }
            $signature=strtoupper(md5($mobilphone.$msg.$time.$token));
            $data=array("phone"=>$mobilphone,"msg"=>$msg,"timestamp"=>$time,"signature"=>$signature);
            $url=_ROOTURL."yuntongxun/interface/downbillnotice.php";
            $this->getPostRequest($url,$data);
        }
    }
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::getDiscountByOnline()
     */
    public function getDiscountByOnline($shopid, $paymoney)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $result=DALFactory::createInstanceCollection(self::$takeoutdiscount)->find($qarr);
        $arr=array();
        $thekey=0;
        foreach ($result as $key=>$val){
            $arr[$val['money']]=$val['discount'];
            if($thekey<=$val['money'] && $paymoney>=$val['money']){
                $thekey=$val['money'];
            }
        }
        
        if(!empty($thekey)){
            return $arr[$thekey];
        }else{
            return 0;
        }
        
        
    }

    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::getDistributeFee()
     */
    public function getDistributeFee($inputarr){
           // TODO Auto-generated method stub
        $qarr=array("shopid"=>$inputarr['shopid']);
        $result=DALFactory::createInstanceCollection(self::$takeoutfare)->find($qarr);
        $dis=$this->getDIstance($inputarr);
        $arr=array();
        $thekey=0;
        $maxfee=0;
        foreach ($result as $key=>$val){
                $arr[$val['area']*1000]=$val['fare'];
                $maxfee=$maxfee>$val['fare']?$maxfee:$val['fare'];
            }
            $arr[$dis]=0;
            ksort($arr);
            $i=0;
            $newarr=array();
            foreach ($arr as $akey=>$aval){
                $newarr[]=$aval;
            }
            foreach ($newarr as $nkey=>$nval){
                if($nval==0){
                    $thekey=$nkey+1;
                }
            }
            if(!empty($newarr[$thekey])){
                return $newarr[$thekey];
            }else{
                return $maxfee;
            }        
    }
    
    public function LogOutMyWechat($shopid,$openid){
        $qarr=array("shopid"=>$shopid);
        $result=DALFactory::createInstanceCollection(self::$wechat_shop)->findOne($qarr);
        if(!empty($result)){
            $oparr=array("\$set"=>array("openid"=>$openid));
            DALFactory::createInstanceCollection(self::$wechat_shop)->update($qarr,$oparr);
        }
    }
    
    public function sendTixianErrorMsg($msg){
        global $token;
        $phone="13071870889";
        $msgarr=array($msg);
        $msg= json_encode($msgarr);
        $time=time();
        $signature=strtoupper(md5($phone.$msg.$time.$token));
        $data=array("phone"=>$phone,"msg"=>$msg,"timestamp"=>$time,"signature"=>$signature);
        $url=_ROOTURL."yuntongxun/interface/downbillnotice.php";
        $this->getPostRequest($url,$data);
    }
    
    public function isReDownbill($shopid,$uid,$foodjson){
        $qarr=array("shopid"=>$shopid,"uid"=>$uid, "timestamp"=>array("\$gte"=>time()-30));
        $oparr=array("uid"=>1,"food"=>1);
        $result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(3);
        $billexist=false;
        foreach($result as $key=>$val){
            if(MD5($foodjson)==MD5(json_encode($val['food']))){
                $billexist=true;break;
            }
        }
       return $billexist; 
    }
    
    public function saveMyaddress($inputarr){
        $qarr=array("uid"=>$inputarr['uid']);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$myaddress)->findOne($qarr,$oparr);
        if(!empty($result)){
            $oparr=array(
                "\$set"=>array(
                    "prov"=>$inputarr['prov'],
                    "city"=>$inputarr['city'],
                    "dist"=>$inputarr['dist'],
                    "road"=>$inputarr['road'],
                    "contact"=>$inputarr['contact'],
                    "phone"=>$inputarr['phone'],
                )
            );
             DALFactory::createInstanceCollection(self::$myaddress)->update($qarr,$oparr);
        }else{
            $arr=array(
                    "uid"=>$inputarr['uid'],
                    "prov"=>$inputarr['prov'],
                    "city"=>$inputarr['city'],
                    "dist"=>$inputarr['dist'],
                    "road"=>$inputarr['road'],
                    "contact"=>$inputarr['contact'],
                    "phone"=>$inputarr['phone'],
            );
            DALFactory::createInstanceCollection(self::$myaddress)->save($inputarr);
        }
    }
    
    public function getMyAddress($uid){
        $qarr=array("uid"=>$uid);
        $result=DALFactory::createInstanceCollection(self::$myaddress)->findOne($qarr);
        $arr=array();
        if(!empty($result)){
            $arr=array(
                    "uid"=>$result['uid'],
                    "prov"=>$result['prov'],
                    "city"=>$result['city'],
                    "dist"=>$result['dist'],
                    "road"=>$result['road'],
                    "contact"=>$result['contact'],
                    "phone"=>$result['phone'],
            );
        }
        return $arr;
    }
    public function getStartmoney($shopid){
        if(empty($shopid)){return 0;}
        $qarr=array("_id"=>new MongoId($shopid));
        $oparr=array("startmoney"=>1);
        $result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
        $startmoney=0;
        if(!empty($result)){
            $startmoney=$result['startmoney'];
        }
        return $startmoney;
    }


}

?>