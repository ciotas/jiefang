<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorFourDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once ('/var/www/html/des.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorSixDAL.php');
class MonitorFourDAL implements IMonitorFourDAL{
	private static $food="food";
	private static $stock="stock";
	private static $stockrecord="stockrecord";
	private static $shopinfo="shopinfo";
	private static $bill="bill";
	private static $billshopinfo = "billshopinfo";
	private static $rawtype="rawtype";
	private static $shopraw="shopraw";
	private static $shoprawleft="shoprawleft";
	private static $autostock="autostock";
	private static $shopraw_storage="shopraw_storage";
	private static $add_shopraw_record="add_shopraw_record";
	private static $monthstock = "monthstock";
	private static $wechat_user_info = "wechat_user_info";
	private static $servers = "servers";
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getAutoStockFoods()
	 */
	public function getAutoStockFoods($shopid,$theday) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"autostock"=>"1");
		$oparr=array("_id"=>1, "foodpic"=>1,"foodname"=>1,"foodprice"=>1,"foodunit"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['foodpic'])){$foodpic=$val['foodpic'];}else{$foodpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$stockarr=$this->getOneStockData($shopid, strval($val['_id']));
			$stocknum="0";
			$soldfoodnum=$this->getAutostockFoodsoldnumByfoodid($shopid, $theday, strval($val['_id']));
			if(!empty($stockarr['num'])){
				$stocknum=$stockarr['num'];
			}
			$arr[]=array(
					"foodid"=>strval($val['_id']),
					"foodpic"=>$foodpic,
					"foodname"	=>$val['foodname'],
					"foodprice"=>$val['foodprice'],
					"foodunit"=>$val['foodunit'],
					"num"=>$stocknum,
					"soldnum"=>$soldfoodnum,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::saveAutoStockFood()
	 */
	public function saveAutoStockFood($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$inputarr['shopid'],"foodid"=>$inputarr['foodid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$stock)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array(
					"\$set"=>array(
						"format"=>$inputarr['format'],
						"packunit"=>$inputarr['packunit'],
						"packrate"=>$inputarr['packrate'],
				),
			);
			DALFactory::createInstanceCollection(self::$stock)->update($qarr,$oparr);
		}else{
			DALFactory::createInstanceCollection(self::$stock)->save($inputarr);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::changeStockRecord()
	 */
	public function changeStockRecord($inputarr) {
		// TODO Auto-generated method stub
		$arr=array(
				"foodid"=>$inputarr['foodid'],
				"shopid"=>$inputarr['shopid'],
				"num"=>$inputarr['num'],
				"timestamp"=>time(),
		);
		DALFactory::createInstanceCollection(self::$stockrecord)->save($arr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getChangeStockRecord()
	 */
	public function getChangeStockRecord($shopid,$theday) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>strtotime($theday),"\$lte"=>strtotime($theday)+86400));
		$oparr=array("foodid"=>1,"num"=>1, "timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$stockrecord)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$foodarr=$this->getFoodInfoByFoodid($val['foodid']);
			if(empty($foodarr)){continue;}
// 			$stockinfo=$this->getOneStockInfo($val['foodid']);
// 			if(empty($stockinfo)){continue;}
			$arr[]=array(
					"rid"=>strval($val['_id']),
					"foodid"	=>$val['foodid'],
					"foodpic"=>$foodarr['foodpic'],
					"foodname"=>$foodarr['foodname'],
					"foodunit"=>$foodarr['foodunit'],
					"num"=>$val['num'],
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getFoodInfoByFoodid()
	 */
	public function getFoodInfoByFoodid($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return array();}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodname"=>1,"foodpic"=>1,"foodunit"=>1,"foodprice"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['foodpic'])){$foodpic=$result['foodpic'];}else{$foodpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$arr=array("foodname"=>$result['foodname'],"foodunit"=>$result['foodunit'],"foodprice"=>$result['foodprice'], "foodpic"=>$foodpic);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getOneStockData()
	 */
	public function getOneStockData($shopid, $foodid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"foodid"=>$foodid);
		$oparr=array("num"=>1);
		$result=DALFactory::createInstanceCollection(self::$autostock)->findOne($qarr,$oparr);
		$arr=array();
		$foodinfo=$this->getFoodInfoByFoodid($foodid);
// 		$stockinfo=$this->getOneStockInfo($foodid);
		$arr=array(
				"foodid"=>$foodid,
				"foodname"=>$foodinfo['foodname'],
				"foodunit"=>$foodinfo['foodunit'],
				"num"=>$result['num'],
		);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::saveStockNum()
	 */
	public function saveStockNumData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("foodid"=>$inputarr['foodid'],"shopid"=>$inputarr['shopid']);
		$oparr=array("_id"=>1,"num"=>1);
		$result=DALFactory::createInstanceCollection(self::$autostock)->findOne($qarr,$oparr);
		if(!empty($result)){
			$num=$result['num']+$inputarr['num'];
			$oparr=array("\$set"=>array("num"=>$num));
			DALFactory::createInstanceCollection(self::$autostock)->update($qarr,$oparr);
		}else{
		    $num = $inputarr['num'];
			DALFactory::createInstanceCollection(self::$autostock)->save($inputarr);
		}
		$inputarr['nownum'] = $num;
		$this->changeStockRecord($inputarr);
		$this->addStockin($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getConsumeListData()
	 */
	public function getConsumeListData($shopid, $theday) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array();
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			foreach ($val['food'] as $fkey=>$fval){
				$autostock=$this->judgeAutoStock($fval['foodid']);
				if($autostock=="1"){
					if(array_key_exists($fval['foodid'], $arr)){
						$arr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
					}else{
						$arr[$fval['foodid']]=array(
								"foodname"=>$fval['foodname'],
								"foodamount"=>$fval['foodamount'],
								"foodunit"=>$fval['foodunit'],
								"foodprice"=>$fval['foodprice'],
						);
					}
				}
			}
		}
		return $arr;
	}
	
	public function getAutostockFoodsoldnumByfoodid($shopid,$theday,$foodid){
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array();
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		$foodamount=0;
		foreach ($result as $key=>$val){
			foreach ($val['food'] as $fkey=>$fval){
				$autostock=$this->judgeAutoStock($fval['foodid']);
				if($autostock=="1"){
					if($foodid==$fval['foodid']){
						$foodamount+=$fval['foodamount'];
					}
				}
			}
		}
		return $foodamount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getOpenHourByShopid()
	 */
	public function getOpenHourByShopid($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return "5";}
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
	 * @see IMonitorFourDAL::judgeAutoStock()
	 */
	public function judgeAutoStock($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return "0";}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("autostock"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$autostock="0";
		if(!empty($result['autostock'])){
			$autostock=$result['autostock'];
		}
		return $autostock;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getDayStockData()
	 */
	public function getDayStockData($shopid, $theday,$thehour) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"autostock"=>"1");
		$oparr=array("_id"=>1, "foodpic"=>1,"foodname"=>1,"foodprice"=>1,"foodunit"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['foodpic'])){$foodpic=$val['foodpic'];}else{$foodpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$starttime=strtotime($theday." ".$thehour.":0:0");
			$endtime=strtotime($theday." ".$thehour.":0:0")+86400;
			$stockinfo=$this->getOneStockInfo(strval($val['_id']));
			$tstockamount=$this->getStockamountBeforeThetime($shopid, strval($val['_id']), $stockinfo['packrate'], $starttime);
			$firsttime=$this->getFisrtStockTime($shopid);
			$tsoldamount=$this->getConsumeFoodAmountByFoodid($shopid, strval($val['_id']), $firsttime,$starttime);
			$nowstockamount="0";
			$totalstockamount="0";
			$totalpacknum="0";
		
			$totalstockamount=$tstockamount-$tsoldamount;
			$totalpacknum=floor($totalstockamount / $stockinfo['packrate']);
			$totalretailnum=$totalstockamount % $stockinfo['packrate'];
			//今日添加
			$starttime1=strtotime($theday." "."0:0:0");
			$endtime1=strtotime($theday." "."0:0:0")+86400;
			$todaystockarr=$this->getOneStockData($shopid, strval($val['_id']),$starttime1,$endtime1);
			$todayamount="0";
			if(!empty($todaystockarr)){
				$todayamount=$todaystockarr['packnum']*$todaystockarr['packrate']+$todaystockarr['retailnum'];
			}
			//今日消耗
			
			$nowsoldamount=$this->getConsumeFoodAmountByFoodid($shopid, strval($val['_id']), $starttime,$endtime);
			$nowstockamount+=$totalstockamount-$nowsoldamount+$todayamount;
			$nowpacknum=floor($nowstockamount / $stockinfo['packrate']);
			$nowretailnum=$nowstockamount % $stockinfo['packrate'];
			
			
			
			$arr[]=array(
					"foodid"=>strval($val['_id']),
					"foodpic"=>$foodpic,
					"foodname"	=>$val['foodname'],
					"foodprice"=>$val['foodprice'],
					"foodunit"=>$val['foodunit'],
					"format"=>$stockinfo['format'],
					"packunit"=>$stockinfo['packunit'],
					"nowpacknum"=>$nowpacknum,
					"nowretailnum"=>$nowretailnum,
					"nowstockamount"=>$nowstockamount,
					"packrate"=>$stockinfo['packrate'],
					"totalpacknum"=>$totalpacknum,
					"totalretailnum"=>$totalretailnum,
					"totalstockamount"=>$totalstockamount,
					"soldamount"=>$nowsoldamount,
					"todayamount"=>$todayamount,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getStockAmountByDay()
	 */
	public function getTotalStockAmountByDay($foodid,$thetime) {
		// TODO Auto-generated method stub
		$qarr=array("foodid"=>$foodid,"timestamp"=>array("\$lte"=>strtotime($starttime)));
		$oparr=array("packnum"=>1,"retailnum"=>1);
		$result=DALFactory::createInstanceCollection(self::$stockrecord)->find($qarr,$oparr);
		$totalpacknum=0;
		$totalretailnum=0;
		foreach ($result as $key=>$val){
			$totalpacknum+=$val['packnum'];
			$totalretailnum+=$val['retailnum'];
		}
		return array("totalpacknum"=>$totalpacknum,"totalretailnum"=>$totalretailnum);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getConsumeFoodByFoodid()
	 */
	public function getConsumeFoodAmountByFoodid($shopid,$foodid, $starttime,$endtime) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime, "\$lte"=>$endtime));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$soldamount=0;
		foreach ($result as $key=>$val){
			foreach ($val['food'] as $fkey=>$fval){
				if($foodid==$fval['foodid']){
					$autostock=$this->judgeAutoStock($fval['foodid']);
					if($autostock=="1"){
						$soldamount+=$fval['foodamount'];
					}
				}
			}
		}
		return $soldamount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::generPrintAutostockContent()
	 */
	public function generPrintAutostockContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo .= '<CB> 酒水库存盘点</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 10).date("Y-m-d",time()).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("美食名", 16).$this->getStableLenStr("入库", 8).$this->getStableLenStr("售出", 6).'金额<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$foodlength=(strlen($fval['foodname']) + mb_strlen($fval['foodname'],'UTF8'))/2;
			if($foodlength>16){
				$foodname=$fval['foodname']."<BR>";
			}else{
				$foodname=$this->getStableLenStr($fval['foodname'], 16);
			}
			$orderInfo.=$this->getStableLenStr($foodname, 16).$this->getStableLenStr($fval['num'].$fval['foodunit'], 8).$this->getStableLenStr($fval['soldnum'].$fval['foodunit'], 6).'￥'.$fval['num']*$fval['foodprice'].'<BR>';
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
	 * @see IMonitorFourDAL::getStableLenStr()
	 */
	public function getStableLenStr($str, $len) {
		// TODO Auto-generated method stub
		$strlength=(strlen($str) + mb_strlen($str,'UTF8'))/2;
		if($strlength<$len){
			return $str.str_repeat(" ",($len-$strlength+1));
		}else{
			return $str;
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::addOneRawtype()
	 */
	public function addOneRawtype($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$rawtype)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::updateOneRawtype()
	 */
	public function updateOneRawtype($rtnid, $inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rtnid));
		$oparr=array("\$set"=>array("rawtypename"=>$inputarr['rawtypename']));
		DALFactory::createInstanceCollection(self::$rawtype)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawtypeData()
	 */
	public function getRawtypeData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"rawtypename"=>1);
		$result=DALFactory::createInstanceCollection(self::$rawtype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("rtnid"=>strval($val['_id']),"rawtypename"=>$val['rawtypename']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getOneRawtypenameByid()
	 */
	public function getOneRawtypenameByid($rtnid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rtnid));
		$oparr=array("_id"=>1,"rawtypename"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$rawtype)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("rtnid"=>strval($result['_id']),"rawtypename"=>$result['rawtypename']);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::delOneRawtypenameById()
	 */
	public function delOneRawtypenameById($rtnid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rtnid));
		DALFactory::createInstanceCollection(self::$rawtype)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getBeforeStarttimeSoldamount()
	 */
	public function getBeforeStarttimeSoldamount($shopid, $foodid, $starttime) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$lte"=>strtotime($starttime)));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$soldamount=0;
		foreach ($result as $key=>$val){
			foreach ($val['food'] as $fkey=>$fval){
				if($foodid==$fval['foodid']){
					$autostock=$this->judgeAutoStock($fval['foodid']);
					if($autostock=="1"){
						$soldamount+=$fval['foodamount'];
					}
				}
			}
		}
		return $soldamount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::addOneRawinfo()
	 */
	public function addOneRawinfo($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$shopraw)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::updateOneRawinfo()
	 */
	public function updateOneRawinfo($rawid, $inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rawid));
		$oparr=array(
			"\$set"=>array(
					"rawname"=>$inputarr['rawname'],
					"rawcode"=>$inputarr['rawcode'],
					"rawformat"=>$inputarr['rawformat'],
					"rawunit"=>$inputarr['rawunit'],		
					"rawtypeid"=>$inputarr['rawtypeid'],
					"rawtinyunit"=>$inputarr['rawtinyunit'],
					"rawpackrate"=>$inputarr['rawpackrate'],
			)
		);
		DALFactory::createInstanceCollection(self::$shopraw)->update($qarr,$oparr);
	}

	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getOneRawinfo()
	 */
	public function getOneRawinfo($rawid) {
		// TODO Auto-generated method stub
		if(empty($rawid)){return array();}
		$qarr=array("_id"=>new MongoId($rawid));
		$oparr=array("rawname"=>1,"rawcode"=>1,"rawformat"=>1,"rawunit"=>1,"rawtypeid"=>1,"rawpic"=>1,"rawtinyunit"=>1,"rawpackrate"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['rawpic'])){$rawpic=$result['rawpic'];}else{$rawpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$arr=array(
					"rawid"=>$rawid,
					"rawname"=>$result['rawname'],
					"rawcode"=>$result['rawcode'],
					"rawformat"=>$result['rawformat'],
// 					"rawpackunit"=>$result['rawpackunit'],
					"rawunit"=>$result['rawunit'],
					"rawtypeid"=>$result['rawtypeid'],
					"rawpic"=>$rawpic,
					"rawtinyunit"=>$result['rawtinyunit'],
					"rawpackrate"=>$result['rawpackrate'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawsOrderByRawtype()
	 */
	public function getRawsOrderByRawtype($shopid) {
		// TODO Auto-generated method stub
		$rawtypearr=$this->getRawtypeData($shopid);
		$arr=array();
		foreach ($rawtypearr as $key=>$val){
			$rawarr=$this->getRawDataByRawtypeid($val['rtnid']);
			$arr[]=array(
					"rawtypeid"=>$val['rtnid'],
					"rawtypename"=>$val['rawtypename'],
					"raw"=>$rawarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawDataByRawtypeid()
	 */
	public function getRawDataByRawtypeid($rawtypeid) {
		// TODO Auto-generated method stub
		$qarr=array("rawtypeid"=>$rawtypeid);
		$oparr=array("_id"=>1, "rawpic"=>1, "rawname"=>1,"rawcode"=>1,"rawformat"=>1,
				"rawunit"=>1,"rawtypeid"=>1,"rawamount"=>1,"rawmoney"=>1,"rawpaymoney"=>1,
				"rawtinyunit"=>1,"rawpackrate"=>1,
		);
		$result=DALFactory::createInstanceCollection(self::$shopraw)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['rawpic'])){$rawpic=$val['rawpic'];}else{$rawpic="http://jfoss.meijiemall.com/food/default_food.png";}
			if(!empty($val['rawamount'])){$rawamount=$val['rawamount'];}else{$rawamount=0;}
			$arr[]=array(
					"rawid"=>strval($val['_id']),
					"rawname"=>$val['rawname'],
					"rawcode"	=>$val['rawcode'],
					"rawformat"=>$val['rawformat'],
// 					"rawpackunit"=>$val['rawpackunit'],
					"rawunit"=>$val['rawunit'],
					"rawtypeid"=>$val['rawtypeid'],
					"rawpic"=>$rawpic,
					"rawamount"=>$rawamount,
					"rawmoney"=>$val['rawmoney'],
					"rawpaymoney"=>$val['rawpaymoney'],
					"rawtinyunit"=>$val['rawtinyunit'],
					"rawpackrate"=>$val['rawpackrate'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::delOnerawByRawid()
	 */
	public function delOnerawByRawid($rawid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rawid));
		DALFactory::createInstanceCollection(self::$shopraw)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawUpTime()
	 */
	public function getRawUpTime($rawid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rawid));
		$oparr=array("uprawpictime"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw)->findOne($qarr,$oparr);
		$uprawpictime=0;
		if(!empty($result['uprawpictime'])){
			$uprawpictime=$result['uprawpictime'];
		}
		return $uprawpictime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::updateRawData()
	 */
	public function updateRawData($rawid, $newrawpic, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($rawid));
		$oparr=array("\$set"=>array("rawpic"=>$newrawpic,"uprawpictime"=>$timestamp));
		DALFactory::createInstanceCollection(self::$shopraw)->update($qarr,$oparr);
	}


	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::addRawamountRecord()
	 */
	public function addRawamountRecord($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$add_shopraw_record)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawRecordData()
	 */
	public function getRawRecordData($shopid,$theday) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"theday"=>$theday);
		$oparr=array(
				"rawid"=>1,
				"rawamount"=>1,
				"rawtinyamount"=>1,
				"rawprice"=>1,
				"rawpaymoney"=>1,
				"manager"=>1,
				"addtime"=>1,
		);
		$result=DALFactory::createInstanceCollection(self::$add_shopraw_record)->find($qarr,$oparr)->sort(array("addtime"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$rawarr=$this->getOneRawinfo($val['rawid']);
			$arr[]=array(
					"rawname"=>$rawarr['rawname'],
					"rawunit"=>$rawarr['rawunit'],
					"rawtinyunit"=>$rawarr['rawtinyunit'],
					"rawpackrate"=>$rawarr['rawpackrate'],
					"rawamount"=>$val['rawamount'],
					"rawtinyamount"=>$val['rawtinyamount'],
					"rawprice"=>$val['rawprice'],
					"rawpaymoney"=>$val['rawpaymoney'],
					"manager"=>$val['manager'],
					"rawpic"=>$rawarr['rawpic'],
					"addtime"=>$val['addtime'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawsOrderByTime()
	 */
	public function getRawsOrderByTime($shopid, $theyear,$themonth) {
		// TODO Auto-generated method stub
		$rawtypearr=$this->getRawtypeData($shopid);
		$arr=array();
		foreach ($rawtypearr as $key=>$val){
			$rawarr=$this->getRawDataByRawtypeidAndTime($val['rtnid'], $theyear,$themonth);
			$arr[]=array(
					"rawtypeid"=>$val['rtnid'],
					"rawtypename"=>$val['rawtypename'],
					"raw"=>$rawarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawDataByRawtypeidAndTime()
	 */
	public function getRawDataByRawtypeidAndTime($rawtypeid,$theyear,$themonth) {
		// TODO Auto-generated method stub
		$qarr=array("rawtypeid"=>$rawtypeid);
		$oparr=array("_id"=>1,"shopid"=>1, "rawpic"=>1, "rawname"=>1,"rawcode"=>1,"rawformat"=>1,"rawunit"=>1,"rawpackrate"=>1,"rawtinyunit"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['rawpic'])){$rawpic=$val['rawpic'];}else{$rawpic="http://jfoss.meijiemall.com/food/default_food.png";}
// 			$rawamount=$this->getRawamountBytime(strval($val['_id']), $theyear,$themonth);
			$rawleftarr=$this->getRawleftData(strval($val['_id']),$val['rawpackrate'], $theyear, $themonth);
// 			$totalmoney=$this->getTotalmoneyBymonth($val['shopid'], $theyear, $themonth);
			$rawstorearr=$this->getRawDataBymonth(strval($val['_id']),$val['rawpackrate'], $theyear, $themonth);
			
			if(!empty($rawleftarr)){
				$pandianstatus="1";
				$rawusetotalamount=($rawstorearr['rawamount']*$val['rawpackrate']+$rawstorearr['rawtinyamount'])-($rawleftarr['rawleftamount']*$val['rawpackrate']+$rawleftarr['rawlefttinyamount']);
				$rawuseamount=floor($rawusetotalamount / $val['rawpackrate']);
				$rawusetinyamount=$rawusetotalamount % $val['rawpackrate'];
				$rawusemoney=$rawstorearr['rawmoney']-$rawleftarr['rawleftmoney'];
			}else{
				$rawuseamount="0";
				$rawusetinyamount="0";
				$rawusemoney="0";
				$pandianstatus="0";
			}
			$arr[]=array(
					"rawid"=>strval($val['_id']),
					"rawname"=>$val['rawname'],
					"rawcode"	=>$val['rawcode'],
					"rawformat"=>$val['rawformat'],
					"rawpackrate"=>$val['rawpackrate'],
					"rawunit"=>$val['rawunit'],
					"rawtypeid"=>$val['rawtypeid'],
					"rawpic"=>$rawpic,
					"rawtinyunit"=>$val['rawtinyunit'],
					"rawamount"=>$rawstorearr['rawamount'],
					"rawtinyamount"=>$rawstorearr['rawtinyamount'],
					"rawmoney"=>$rawstorearr['rawmoney'],
					"rawpaymoney"=>$rawstorearr['rawpaymoney'],
					"rawleftamount"=>$rawleftarr['rawleftamount'],
					"rawlefttinyamount"=>$rawleftarr['rawlefttinyamount'],
					"rawleftmoney"=>$rawleftarr['rawleftmoney'],
					"rawuseamount"=>$rawuseamount,
					"rawusetinyamount"=>$rawusetinyamount,
					"rawusemoney"=>$rawusemoney,
					"pandianstatus"=>$pandianstatus,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawamountBytime()
	 */
	public function getRawamountBytime($rawid,$theyear,$themonth) {
		// TODO Auto-generated method stub
		$starttime=strtotime($theyear."-".$themonth."-01");
		$endtime=strtotime($theyear."-".($themonth+1)."-01");
		$qarr=array("rawid"=>$rawid,"addtime"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("rawamount"=>1);
		$rawamount=0;
		$result=DALFactory::createInstanceCollection(self::$add_shopraw_record)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$rawamount+=$val['rawamount'];
		}
		return $rawamount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::addRawleftamountData()
	 */
	public function addRawleftamountData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("rawid"=>$inputarr['rawid'],"theyear"=>$inputarr['theyear'],"themonth"=>$inputarr['themonth']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shoprawleft)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array(
				"\$set"=>array("rawleftamount"=>$inputarr['rawleftamount'],"rawlefttinyamount"=>$inputarr['rawlefttinyamount'], "rawleftmoney"=>($inputarr['rawleftamount']*$inputarr['rawpackrate']+$inputarr['rawlefttinyamount'])*$inputarr['newrawprice'])	
			);
			DALFactory::createInstanceCollection(self::$shoprawleft)->update($qarr,$oparr);
		}else{
			DALFactory::createInstanceCollection(self::$shoprawleft)->save($inputarr);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawleftamountData($starttime, $endtime)
	 */
	public function getRawleftData($rawid,$rawpackrate,$theyear,$themonth) {
		// TODO Auto-generated method stub
		$qarr=array("rawid"=>$rawid,"theyear"=>$theyear,"themonth"=>$themonth);
		$oparr=array("rawleftamount"=>1,"rawlefttinyamount"=>1, "newrawprice"=>1);
		$result=DALFactory::createInstanceCollection(self::$shoprawleft)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"rawleftamount"=>$result['rawleftamount'],
					"rawlefttinyamount"=>$result['rawlefttinyamount'],
					"rawleftmoney"=>($result['rawleftamount']*$rawpackrate+$result['rawlefttinyamount'])*$result['newrawprice'],
			);
		}
// 		print_r($arr);exit;
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getTotalmoneyBymonth()
	 */
	public function getTotalmoneyBymonth($shopid, $theyear, $themonth) {
		// TODO Auto-generated method stub
		$starttime=strtotime($theyear."-".$themonth."-01");
		$endtime=strtotime($theyear."-".($themonth+1)."-01");
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$totalmoney=0;
		foreach ($result as $key=>$val){
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodamount']*$fval['foodprice'];
				}
			}
		}
		return $totalmoney;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawLastInputprice()
	 */
	public function getRawLastInputprice($shopid, $rawid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"rawid"=>$rawid);
		$oparr=array("rawprice"=>1);
		$result=DALFactory::createInstanceCollection(self::$add_shopraw_record)->find($qarr,$oparr)->sort(array("addtime"=>-1))->limit(1);
		$newrawprice=0;
		foreach ($result as $key=>$val){
			$newrawprice=$val['rawprice'];
		}
		return $newrawprice;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getDayRawDetail()
	 */
	public function getDayRawDetail($shopid, $theday) {
		// TODO Auto-generated method stub
		$rawtypearr=$this->getRawtypeData($shopid);
		$arr=array();
		foreach ($rawtypearr as $key=>$val){
			$rawarr=$this->getRawsByRawtypeidAndTheday($val['rtnid'],$theday);
			$arr[]=array(
					"rawtypeid"=>$val['rtnid'],
					"rawtypename"=>$val['rawtypename'],
					"raw"=>$rawarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawsByRawtypeidAndTheday()
	 */
	public function getRawsByRawtypeidAndTheday($rawtypeid, $theday) {
		// TODO Auto-generated method stub
		$qarr=array("rawtypeid"=>$rawtypeid);
		$oparr=array("_id"=>1, "rawpic"=>1, "rawname"=>1,"rawcode"=>1,"rawformat"=>1,"rawunit"=>1,"rawtinyunit"=>1,"rawpackrate"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['rawpic'])){$rawpic=$val['rawpic'];}else{$rawpic="http://jfoss.meijiemall.com/food/default_food.png";}
			$rawputarr=$this->getRawsByRawidAndTheday(strval($val['_id']), $theday);
			$rawmoney=($rawputarr['rawamount']+$rawputarr['rawtinyamount']*(1/$val['rawpackrate']))*$rawputarr['rawprice'];
			$rawmoney=sprintf("%.1f",$rawmoney);
			$arr[]=array(
					"rawid"=>strval($val['_id']),
					"rawname"=>$val['rawname'],
					"rawcode"	=>$val['rawcode'],
					"rawformat"=>$val['rawformat'],
					"rawunit"=>$val['rawunit'],
					"rawtinyunit"=>$val['rawtinyunit'],
					"rawpackrate"=>$val['rawpackrate'],
					"rawtypeid"=>$val['rawtypeid'],
					"rawpic"=>$rawpic,
					"rawamount"=>$rawputarr['rawamount'],
					"rawtinyamount"=>$rawputarr['rawtinyamount'],
					"rawprice"=>$rawputarr['rawprice'],
					"rawmoney"=>$rawmoney,
					"rawpaymoney"=>$rawputarr['rawpaymoney'],
			);
		}
		return $arr;
		
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawsByRawidAndTheday()
	 */
	public function getRawsByRawidAndTheday($rawid, $theday) {
		// TODO Auto-generated method stub
		$qarr=array("rawid"=>$rawid,"theday"=>$theday);
		$oparr=array("rawprice"=>1,"rawamount"=>1,"rawpaymoney"=>1,"rawtinyamount"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$shopraw_storage)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"rawprice"=>$result['rawprice'],
					"rawamount"=>$result['rawamount'],
					"rawpaymoney"=>$result['rawpaymoney'],
					"rawtinyamount"=>$result['rawtinyamount'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::saveRawStorage()
	 */
	public function saveRawStorage($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("rawid"=>$inputarr['rawid'],"theday"=>$inputarr['theday']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw_storage)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array(
					"\$set"=>array(
							"rawprice"=>$inputarr['rawprice'],
							"rawamount"=>$inputarr['rawamount'],
							"rawtinyamount"=>$inputarr['rawtinyamount'],
							"rawpaymoney"=>$inputarr['rawpaymoney'],
					)
			);
			DALFactory::createInstanceCollection(self::$shopraw_storage)->update($qarr,$oparr);
		}else{
			DALFactory::createInstanceCollection(self::$shopraw_storage)->save($inputarr);
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getRawDataBymonth()
	 */
	public function getRawDataBymonth($rawid,$rawpackrate, $theyear, $themonth) {
		// TODO Auto-generated method stub
		$qarr=array("rawid"=>$rawid,"theday"=>array("\$gte"=>$theyear."-".$themonth."-01","\$lte"=>$theyear."-".str_pad($themonth+1,2,"0",STR_PAD_LEFT)."-01"));
// 		print_r($qarr);exit;
		$oparr=array("rawprice"=>1,"rawamount"=>1,"rawtinyamount"=>1, "rawpaymoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw_storage)->find($qarr,$oparr);
		$rawamount=0;
		$rawmoney=0;
		$rawpaymoney=0;
		$rawtinyamount=0;
		foreach ($result as $key=>$val){
			$rawamount+=$val['rawamount'];
			$rawtinyamount+=$val['rawtinyamount'];
			$rawmoney+=($val['rawamount']+$val['rawtinyamount']/$rawpackrate)*$val['rawprice'];
			$rawpaymoney+=$val['rawpaymoney'];
		}
		return array("rawamount"=>$rawamount,"rawtinyamount"=>$rawtinyamount, "rawmoney"=>$rawmoney,"rawpaymoney"=>$rawpaymoney);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::generDayRawinPrintContent()
	 */
	public function generDayRawinPrintContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$totalraw=0;
		$totalrealraw=0;
		foreach ($inputarr['data'] as $dkey=>$dval){
			foreach ($dval['raw'] as $key=>$val){
				$totalraw+=($val['rawamount']*$val['rawpackrate']+$val['rawtinyamount'])*$val['rawprice'];
				$totalrealraw+=$val['rawpaymoney'];
			}
		}
		$orderInfo='';
		$orderInfo .= '<CB>日进货表</CB><BR>';
		$orderInfo.=$this->getStableLenStr("日期：", 10).$inputarr['theday'].'<BR>';
		$orderInfo.=$this->getStableLenStr("计算总额：", 10).'￥'.$totalraw.'<BR>';
		$orderInfo.=$this->getStableLenStr("实付总额：", 10).'￥'.$totalrealraw.'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("名称", 16).$this->getStableLenStr("数量", 8).$this->getStableLenStr("价格", 6).$this->getStableLenStr("计算额", 8).'实付额<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			foreach ($fval['raw'] as $rkey=>$rval){
				if(!empty($rval['rawprice'])){
					$orderInfo.=$this->getStableLenStr($rval['rawname'], 16).$this->getStableLenStr($rval['rawamount'].$rval['rawunit'].$rval['rawtinyamount'].$rval['rawtinyunit'], 8).$this->getStableLenStr($rval['rawprice'], 6).$this->getStableLenStr("￥".($rval['rawamount']*$rval['rawpackrate']+$rval['rawtinyamount'])*$rval['rawprice'], 8)."￥".$rval['rawpaymoney'].'<BR>';
				}
			}
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='操作员：'.$inputarr['manager_name'].'<BR>';
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
	 * @see IMonitorFourDAL::generRawStockPrintContent()
	 */
	public function generRawStockPrintContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$totalraw=0;
		$totalrealraw=0;
		foreach ($inputarr['data'] as $dkey=>$dval){
			foreach ($dval['raw'] as $key=>$val){
				$totalraw+=$val['rawamount']*$val['rawprice'];
				$totalrealraw+=$val['rawpaymoney'];
			}
		}
		$orderInfo='';
		$orderInfo .= '<CB>'.$inputarr['theyear'].'-'.$inputarr['themonth'].'月库存盘点</CB><BR>';
		$orderInfo.=$this->getStableLenStr("计算总额：", 10).'￥'.$inputarr['T_rawmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("实付总额：", 10).'￥'.$inputarr['T_rawpaymoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("消耗总额：", 10).'￥'.$inputarr['T_rawusemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("剩余总额：", 10).'￥'.$inputarr['T_rawleftmoney'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("名称", 14).$this->getStableLenStr("总数",8).$this->getStableLenStr("实付", 7).$this->getStableLenStr("消耗", 7).'剩余<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			foreach ($fval['raw'] as $rkey=>$rval){
				if(!empty($rval['rawleftmoney'])){$rawleftmoney=$rval['rawleftmoney']; }else{$rawleftmoney="0";}
				if(!empty($rval['rawusemoney'])){$rawusemoney=$rval['rawusemoney']; }else{$rawusemoney="0";}
				if(!empty($rval['pandianstatus'])){
					
				}
				$orderInfo.=$this->getStableLenStr($rval['rawname'], 14).$this->getStableLenStr($rval['rawamount'].$rval['rawunit'].$rval['rawtinyamount'].$rval['rawtinyunit'], 8).$this->getStableLenStr("￥".$rval['rawpaymoney'], 7).$this->getStableLenStr("￥".$rawusemoney, 7)."￥".$rawleftmoney.'<BR>';
			}
				
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
	 * @see IMonitorFourDAL::getOneRawinfoByday()
	 */
	public function getOneRawinfoByday($rawid, $theday) {
		// TODO Auto-generated method stub
		$rawinfoarr=$this->getOneRawinfo($rawid);
		$rawputarr=$this->getRawsByRawidAndTheday($rawid, $theday);
		return array(
				"rawid"=>$rawinfoarr['rawid'],
				"rawname"=>$rawinfoarr['rawname'],
				"rawcode"=>$rawinfoarr['rawcode'],
				"rawformat"=>$rawinfoarr['rawformat'],
				"rawunit"=>$rawinfoarr['rawunit'],
				"rawtypeid"=>$rawinfoarr['rawtypeid'],
				"rawpic"=>$rawinfoarr['rawpic'],
				"rawtinyunit"=>$rawinfoarr['rawtinyunit'],
				"rawpackrate"=>$rawinfoarr['rawpackrate'],
				
				"rawamount"=>$rawputarr['rawamount'],
				"rawtinyamount"=>$rawputarr['rawtinyamount'],
				"rawprice"=>$rawputarr['rawprice'],
				"rawmoney"=>($rawputarr['rawamount']+$rawputarr['rawtinyamount']/$rawinfoarr['rawpackrate'])*$rawputarr['rawprice'],
				"rawpaymoney"=>$rawputarr['rawpaymoney'],
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getOneRawinfoByMonth()
	 */
	public function getOneRawinfoByMonth($rawid, $theyear, $themonth) {
		// TODO Auto-generated method stub
		$rawinfoarr=$this->getOneRawinfo($rawid);
		$rawleftarr=$this->getRawleftData($rawid,$rawinfoarr['rawpackrate'], $theyear, $themonth);
		return array(
				"rawid"=>$rawinfoarr['rawid'],
				"rawpackrate"=>$rawinfoarr['rawpackrate'],
				"rawname"=>$rawinfoarr['rawname'],
				"rawunit"=>$rawinfoarr['rawunit'],
				"rawtinyunit"=>$rawinfoarr['rawtinyunit'],
				"rawlefttinyamount"=>$rawleftarr['rawlefttinyamount'],
				"rawleftamount"=>$rawleftarr['rawleftamount'],
		);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getOneStockInfo()
	 */
	public function getOneStockInfo($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("foodid"=>$foodid);
		$oparr=array("format"=>1,"packunit"=>1,"packrate"=>1);
		$result=DALFactory::createInstanceCollection(self::$stock)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"format"=>$result['format'],
					"packunit"	=>$result['packunit'],
					"packrate"=>$result['packrate'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getFisrtStockTime()
	 */
	public function getFisrtStockTime($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$stockrecord)->find($qarr,$oparr)->sort(array("timestamp"=>1));
		$starttime=time();
		foreach ($result as $key=>$val){
			if(!empty($val['timestamp'])){
				$starttime=$val['timestamp'];break;
			}
		}
		return $starttime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getStockamountBeforeThetime()
	 */
	public function getStockamountBeforeThetime($shopid, $foodid, $packrate,$thetime) {
		// TODO Auto-generated method stub
		$starttime=$this->getFisrtStockTime($shopid);
		$qarr=array("foodid"=>$foodid,"shopid"=>$shopid,"timestamp"=>array("\$gte"=>$starttime, "\$lte"=>$thetime));
		$oparr=array("packnum"=>1,"retailnum"=>1);
		$result=DALFactory::createInstanceCollection(self::$stockrecord)->find($qarr,$oparr);
		$tstockamount=0;
		$packnum=0;
		$retailnum=0;
		foreach ($result as $key=>$val){
			$packnum+=$val['packnum'];
			$retailnum+=$val['retailnum'];
		}
		$tstockamount=$packnum*$packrate+$retailnum;
		return $tstockamount;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::getSumrawData()
	 */
	public function getSumrawData($shopid, $startdate, $endate) {
		// TODO Auto-generated method stub
// 		$starttime=strtotime($startdate);
// 		$endtime=strtotime($endate)+86400;
		$qarr=array("shopid"=>$shopid,"theday"=>array("\$gte"=>$startdate,"\$lte"=>$endate));
		$oparr=array("_id"=>1, "rawid"=>1,"rawprice"=>1,"rawamount"=>1,"rawtinyamount"=>1, "rawpaymoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopraw_storage)->find($qarr,$oparr);
		$rawarr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['rawtinyamount'])){$rawtinyamount=$val['rawtinyamount'];}else{$rawtinyamount="0";}
			if(!empty($val['rawamount'])){$rawamount=$val['rawamount'];}else{$rawamount="0";}
			if(array_key_exists($val['rawid'], $rawarr)){
				$rawarr[$val['rawid']]['rawamount']+=$rawamount;
				$rawarr[$val['rawid']]['rawtinyamount']+=$rawtinyamount;
				$rawarr[$val['rawid']]['rawpaymoney']+=$val['rawpaymoney'];
			}else{//第一次
				$rawinfo=$this->getOneRawinfo($val['rawid']);
				if(empty($rawinfo)){continue;}
				if(!empty($rawinfo['rawpic'])){$rawpic=$rawinfo['rawpic'];}else{$rawpic="http://jfoss.meijiemall.com/food/default_food.png";}
				$rawarr[$val['rawid']]=array(
						"rawname"=>$rawinfo['rawname'],
						"rawpic"=>$rawpic,
						"rawformat"=>$rawinfo['rawformat'],
						"rawunit"=>$rawinfo['rawunit'],
						"rawtinyunit"=>$rawinfo['rawtinyunit'],
						"rawpackrate"=>$rawinfo['rawpackrate'],
						"rawprice"=>$val['rawprice'],
						"rawamount"=>$rawamount,
						"rawpaymoney"=>$val['rawpaymoney'],
						"rawtinyamount"=>$rawtinyamount,
				);
			}
		}
		
		return $rawarr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::generPrintCalcRawContent()
	 */
	public function generPrintCalcRawContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$trawcalcmoney=0;
		$trawpaymoney=0;
		foreach ($inputarr['data'] as $key=>$val){
			$trawcalcmoney+=sprintf("%.0f",($val['rawamount']+$val['rawtinyamount']/$val['rawpackrate'])*$val['rawprice']);
			$trawpaymoney+=$val['rawpaymoney'];
		}
		$orderInfo='';
		$orderInfo .= '<CB>原料进货汇总</CB><BR>';
		$orderInfo.=$this->getStableLenStr("日期：", 10).$inputarr['startdate'].' ~ '.$inputarr['enddate'].'<BR>';
		$orderInfo.=$this->getStableLenStr("计算总额：", 10).'￥'.$trawcalcmoney.'<BR>';
		$orderInfo.=$this->getStableLenStr("实付总额：", 10).'￥'.$trawpaymoney.'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("名称", 16).$this->getStableLenStr("数量", 10).'实付额<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			if($val['rawpackrate']=="1"){ $rawamount= ($val['rawamount']+$val['rawtinyamount']/$val['rawpackrate']).$val['rawunit'];}else{$rawamount= $val['rawamount'].$val['rawunit'].$val['rawtinyamount'].$val['rawtinyunit'];}
			$orderInfo.=$this->getStableLenStr($fval['rawname'], 16).$this->getStableLenStr($rawamount, 10)."￥".$fval['rawpaymoney'].'<BR>';
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
	 * @see IMonitorFourDAL::getStockCalcData()
	 */
	public function getStockCalcData($shopid, $startdate, $endate) {
		// TODO Auto-generated method stub
		$starttime=strtotime($startdate);
		$endtime=strtotime($endate)+86400;
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("foodid"=>1,"num"=>1);
		$result=DALFactory::createInstanceCollection(self::$stockrecord)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$foodinfo=$this->getFoodInfoByFoodid($val['foodid']);
			if(empty($foodinfo)){continue;}
// 			$stockinfo=$this->getOneStockInfo($val['foodid']);
// 			if(empty($stockinfo)){continue;}
			if(array_key_exists($val['foodid'], $arr)){
				$arr[$val['foodid']]['num']+=$val['num'];
			}else{
				$arr[$val['foodid']]=array(
						"foodpic"=>$foodinfo['foodpic'],
						"foodname"=>$foodinfo['foodname'],
						"foodprice"=>$foodinfo['foodprice'],
						"foodunit"=>$foodinfo['foodunit'],
						"num"=>$val['num'],
				);
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFourDAL::generPrintStockCalcContent()
	 */
	public function generPrintStockCalcContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		$tpaymoney=0;
		foreach ($inputarr['data'] as $key=>$val){
			$tpaymoney+=$val['paymoney'];
		}
		$orderInfo='';
		$orderInfo .= '<CB>烟酒水进货汇总</CB><BR>';
		$orderInfo.=$this->getStableLenStr("日期：", 10).$inputarr['startdate'].' ~ '.$inputarr['enddate'].'<BR>';
		$orderInfo.=$this->getStableLenStr("实付总额：", 10).'￥'.$tpaymoney.'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("名称", 16).$this->getStableLenStr("数量", 10).'实付额<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$orderInfo.=$this->getStableLenStr($fval['foodname'], 16).$this->getStableLenStr($fval['packnum'].$fval['packunit'].$fval['retailnum'].$fval['foodunit'], 10)."￥".$fval['paymoney'].'<BR>';
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
	 * @see IMonitorFourDAL::generPrintAutostockSamllContent()
	 */
	public function generPrintAutostockSamllContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo .= '<CB> 酒水库存盘点</CB><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("查询日期：", 10).date("Y-m-d",time()).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("美食名", 14).$this->getStableLenStr("入库", 6).$this->getStableLenStr("售出", 6).'金额<BR>';
		foreach ($inputarr['data'] as $fkey=>$fval){
			$foodlength=(strlen($fval['foodname']) + mb_strlen($fval['foodname'],'UTF8'))/2;
			if($foodlength>14){
				$foodname=$fval['foodname']."<BR>";
			}else{
				$foodname=$this->getStableLenStr($fval['foodname'], 14);
			}
			$orderInfo.=$this->getStableLenStr($foodname, 14).$this->getStableLenStr($fval['num'].$fval['foodunit'], 6).$this->getStableLenStr($fval['soldnum'].$fval['foodunit'], 6).'￥'.$fval['num']*$fval['foodprice'].'<BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s').'<BR>';
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	public function getOutgoing($shopid,$ym)
	{
	   if(!$shopid){return ;};
	   $qarr=array("_id"=>new MongoId($shopid));
	   $oparr=array("shopname"=>1);
	   $shopname=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
	   $yms = date('Y-m-01',strtotime($ym));
	   $lastmonth = date('Y-m', strtotime($yms. ' -1 month'));
	   $yme = date('Y-m-d', strtotime($yms. ' +1 month -1 day'));
	   $yms = strtotime($yms);
	   $yme = strtotime($yme)+86400;   
	   $day = ($yme - $yms)/86400;
	   $qarr = array('shopid' => $shopid,'autostock'=>'1' );
	   $stock = DALFactory::createInstanceCollection(self::$food)->find($qarr);
	   $fooddata = array();
	   foreach ($stock as $v)
	   {
	      $qarr = array('foodid'=>strval($v['_id']));
	       //获取产品当前库存量
	      $qarr = array('foodid'=>strval($v['_id']),'month' => $lastmonth);
	      $res = DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr);
	       if(!$res['num']){$res['num'] = 0;}
	      $fooddata[strval($v['_id'])] = array('name' => $v['foodname'],'nownum' => $res['num']);
	      for($i=1;$i<=$day;$i++)
	      {
	          $fooddata[strval($v['_id'])]['sale'][$i] = 0;
	          $fooddata[strval($v['_id'])]['salenum'] = 0;
	      }
	      
	   }
	   $qarr = array('shopid' => $shopid,'timestamp' => array('$gte' => $yms,'$lte' => $yme));
	   $shopbill = DALFactory::createInstanceCollection(self::$bill)->find($qarr);
	   
	   foreach ($shopbill as $v){
	      foreach ($v['food'] as $val){
	          if(array_key_exists($val['foodid'], $fooddata))
	          {
	              $fooddata[$val['foodid']]['sale'][(int)date('d',$v['timestamp'])]+=$val['foodnum'];
	              $fooddata[$val['foodid']]['salenum']+=$val['foodnum'];
	          }
	      }
	   }
	  
	  $data['shopname'] = $shopname['shopname'];
	  $data['food'] = $fooddata;
	  $data['day'] = $day;
	  
	   return $data;
	}
	//增加/更新月度库存量
	public function addStockin($inputarr)
	{
	    $qarr=array("foodid"=>$inputarr['foodid'],"month"=>date('Y-m'));
	    $oparr=array("_id"=>1,"num"=>1);
	    $result=DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr,$oparr);
	    if(!empty($result)){
	        $num=$inputarr['nownum'];
	        $oparr=array("\$set"=>array("num"=>$num));
	        DALFactory::createInstanceCollection(self::$monthstock)->update($qarr,$oparr);
	    }else{
	        $arr['foodid'] =$inputarr["foodid"];
	        $arr['shopid'] = $inputarr["shopid"];
	        $arr['num'] = $inputarr['nownum'];
	        $arr['month'] = date('Y-m');
	        $arr['timestamp'] = time();
	        DALFactory::createInstanceCollection(self::$monthstock)->save($arr);
	    }
	}
    public function getStockin($shopid,$theday)
    {
        if(!$shopid){return ;};
        $qarr=array("_id"=>new MongoId($shopid));
        $oparr=array("shopname"=>1);
        $shopname=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
        $yms = date('Y-m-01',strtotime($theday));
        $yme = date('Y-m-d', strtotime($yms. ' +1 month -1 day'));
        //月第一天和最后一天 是一个月
        $lastmonth = date('Y-m', strtotime($yms. ' -1 month'));
        $yms = strtotime($yms)-86400;
        $yme = strtotime($yme);   
        $month = date('Y-m',$yms);
        $day = ($yme - $yms)/86400;        
        //获取全部酒水
        $qarr = array('shopid' => $shopid,'autostock'=>'1' );
        $stock = DALFactory::createInstanceCollection(self::$food)->find($qarr);
        $fooddata = array();
        foreach ($stock as $v)
        {
            //获取产品上月库存量
            $qarr = array('foodid'=>strval($v['_id']),'month' => $lastmonth);
            $res = DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr);
            if(!$res['num']){$res['num'] = 0;}
            
            $fooddata[strval($v['_id'])] = array('name' => $v['foodname'],'lastnum' => $res['num'],'stocknum'=>0,'stock'=>array(),'final'=>$res['num']);
            for($i=1;$i<=$day;$i++)
            {
                $fooddata[strval($v['_id'])]['stock'][$i] = 0;
            }
        }
         //将食物数据遍历进入数据数组
        $qarr = array('shopid' => $shopid,'timestamp' => array('$gte' => $yms,'$lte' => $yme));
        $stockinrecord = DALFactory::createInstanceCollection(self::$stockrecord)->find($qarr);
        foreach ($stockinrecord as $v){
                if(array_key_exists($v['foodid'], $fooddata))
                {
                    $fooddata[$v['foodid']]['stock'][(int)date('d',$v['timestamp'])]+=(int)$v['num'];
                    $fooddata[$v['foodid']]['stocknum']+=(int)$v['num'];
                    $fooddata[$v['foodid']]['final']+=(int)$v['num'];
                }
        }
        $data['shopname'] = $shopname['shopname'];
        $data['food'] = $fooddata;
        $data['day'] = $day;
         
        return $data;
    }
    public function getServerBill($shopid, $datestart, $dateover,$uid=NULL)
    {
        if(!$shopid){return ;};
        if($datestart == $dateover){$dateover+=86400;}
        $qarr = array('shopid' => $shopid,'timestamp' =>array('$gte' => strtotime($datestart),'$lte' => strtotime($dateover)),);
        if($uid){$qarr['uid'] = $uid;}   
        //$foodshop = "5747a74b5bc10906068b45c3";
        $foodshop = $shopid;
        $food = DALFactory::createInstanceCollection(self::$food)->find(array('shopid'=>$foodshop));       
        $shopbill = DALFactory::createInstanceCollection(self::$bill)->find($qarr);
        $data = $this->dealBillToServer($shopbill,$food,$uid);
        return $data;
    }
    public function getServers($shopid){
        if(!$shopid){return ;};
        $qarr = array('shopid' => $shopid);
        $serverinfo = DALFactory::createInstanceCollection(self::$servers)->find($qarr);
        $servers = array();
        foreach ($serverinfo as $v)
        {
            $res = DALFactory::createInstanceCollection(self::$wechat_user_info)->findOne(array('openid' => $v['openid']));
            $servers[$res['uid']] = $v['servername'];  
        }
        return $servers;
    }
    public function dealBillToServer($bill,$food,$uid=NULL){
        $Six = new MonitorSixDAL();
        $data = array();
        foreach ($bill as $v)
        {
            $res = NULL;
            $servers = NULL;
            $money = $Six->getPaidBillData(strval($v['_id']));
            $res = DALFactory::createInstanceCollection(self::$billshopinfo)->findOne(array('billid'=>strval($v['_id'])));
            $foodarr = array();
            //定义菜单模板
            foreach ($food as $f){ $foodarr[strval($f['_id'])] = array('foodname' => $f['foodname'],  'foodnum' => 0, ); }
            //将菜单数量写入模板
            foreach ($v['food'] as $val)
            {
                if(array_key_exists($val['foodid'], $foodarr)){
                    $foodarr[$val['foodid']]['foodnum'] += (int)$val['foodnum'];
                }
            }
            //数据存在就增加菜品数量，如果不存在就新增数据
            if(array_key_exists($res['shopname'],$data)){
                $openid = $Six->getOpenidByuid($v['uid']);
                $serverinfo = DALFactory::createInstanceCollection(self::$servers)->findOne(array('openid'=>$openid));
                if(!in_array($serverinfo['servername'], $data[$res['shopname']]['server']) && $serverinfo['servername']){
                    array_push($data[$res['shopname']]['server'], $serverinfo['servername']);
                }
                foreach ($foodarr as $key=>$val){
                    $data[$res['shopname']]['food'][$key]['foodnum'] += (int)$val['foodnum'];
                }
                $data[$res['shopname']]['money'] += (int)$money['paidmoney'];
            }else{
                $openid = $Six->getOpenidByuid($v['uid']);
                $serverinfo = DALFactory::createInstanceCollection(self::$servers)->findOne(array('openid'=>$openid));
                empty($serverinfo['servername']) ? $servers=array( ) : $servers[]=$serverinfo['servername'];
                $data[$res['shopname']]=array(
                    "food"=>$foodarr,
                    "dist"=>$res['dist'],
                    "road"=>$res['road'],
                    "serverid" => $uid,
                    "server"=>$servers,
                    "money" => $money['paidmoney'],
                );
            }
        }
        return $data;
    }
    public function addMonthStock($shopid,$foodid,$month,$num){
        if(empty($shopid) || empty($foodid) || empty($month)){return "";}
        //$month =  date('Y-m',strtotime($month));
        $qarr = array('shopid' => $shopid,'foodid' => $foodid,'month' => $month);
       $res = DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr);
     
       if(empty($res)){
           $qarr['num'] = $num;
           $qarr['timestamp'] = time();
           DALFactory::createInstanceCollection(self::$monthstock)->save($qarr);
          
       }else{
          
           $oparr=array(
               "\$set"=>array(
                   "num"=>$num,
                   "timestamp"=>time(),
               ),
           );
           DALFactory::createInstanceCollection(self::$monthstock)->update($qarr,$oparr);
       }
    }
    public function getFoodByShopid($shopid){
        if(empty($shopid)){return "";};
        $qarr = array('shopid'=> $shopid,'autostock' =>'1');   
        $res = DALFactory::createInstanceCollection(self::$food)->find($qarr);
        $data = array();
        $month = date('Y-m',strtotime(date('Y-m-d')."-1 month"));
        foreach ($res as $v)
        {
            $stock = 0;
            $where = array('foodid'=>strval($v['_id']),'month'=>$month);
            $stockres = DALFactory::createInstanceCollection(self::$monthstock)->findOne($where);
            $stock = $stockres['num'];
            $data[$v['foodname']]=array('id'=>strval($v['_id']),'foodpic'=>$v['foodpic'],'num' =>$stock );
        }
        return $data;
    }
    public function getMonthStock($foodid){
        if(empty($foodid)){return "";}     
        $qarr = array('foodid' => $foodid);
        $res = DALFactory::createInstanceCollection(self::$monthstock)->find($qarr);
        $data = array();
        foreach ($res as $v)
        {
            $data[] = array('month' => $v['month'],'num' => $v['num']);
        }
        
        return $data;
    }
    public function getOneMonth($foodid, $month)
    {
        if(empty($foodid) || empty($month)){ return "";}
        //$month =  date('Y-m',strtotime($month));
        $qarr = array('foodid' =>$foodid,'month'=>$month);
        $res = DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr);
        return $res;
    }
    public function updateBillShopInfoByBillid($billid,$info){
        if(empty($billid) || empty($info)){return "";}
        $qarr = array('billid' => $billid);
        
        $res = DALFactory::createInstanceCollection(self::$billshopinfo)->findOne($qarr);
        if($res){
            $oparr = array('$set'=>$info);
            //echo json_encode($oparr);die;
            DALFactory::createInstanceCollection(self::$billshopinfo)->update($qarr,$oparr);
            
        }
    }
}
?>