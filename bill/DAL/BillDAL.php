<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'IDAL/IBillDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
require_once ('/var/www/html/HttpClient.class.php');
class BillDAL implements IBillDAL{
	private static $bill="bill";
	private static $food="food";
	private static $foodtype="foodtype";
	private static $table="table";
	private static $shopinfo="shopinfo";
	private static $printer="printer";
	private static $customer="customer";
	private static $sheetno="sheetno";
	
	/* (non-PHPdoc)
	 * @see IBillDAL::getOneBillInfoByBillid()
	 */
	public function getOneBillInfoByBillid($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		return $result;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getDaySheetData()
	 */
	public function getDaySheetData($shopid,$theday,$token) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$totalmoney=0;
		$cashmoney=0;
		$unionmoney=0;
		$vipmoney=0;
		$meituanpay=0;
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
		$arr=array();
		$depositmoney=$this->getDepositmoney($shopid);
		foreach ($result as $key=>$val){
			$tabname=$this->getTabnameByTabid($val['tabid']);
			$tabstatus=$this->getTabStatusByTabid($val['tabid']);
			if(strstr($tabname, "测试")!=false || strstr($tabname, "test")!=false){
				continue;
			}
			$discountfoodmoney=0;
			$billnum++;
			$cusnum+=$val['cusnum'];
			$totalmoney_fooddisaccountmoney=$this->getTotalmoneyAndFoodDiscountmoney(strval($val['_id']));
			$totalmoney+=$totalmoney_fooddisaccountmoney['totalmoney'];
			
			if($val['deposit']=="1" && $val['paystatus']=="unpay" && $tabstatus=="start"){
// 				$totalmoney+=$depositmoney;
			}
			
			$discountfoodmoney+=$totalmoney_fooddisaccountmoney['fooddisaccountmoney'];
			$cashmoney+=$val['cashmoney'];
			$unionmoney+=$val['unionmoney'];
			$vipmoney+=$val['vipmoney'];
			$meituanpay+=$val['meituanpay'];
			$alipay+=$val['alipay'];
			$wechatpay+=$val['wechatpay'];
			$clearmoney+=$val['clearmoney'];
			$othermoney+=$val['othermoney'];
			$signmoney+=$val['signmoney'];
			$freemoney+=$val['freemoney'];
			$discountmoney+=(1-$val['discountval']/100)*$discountfoodmoney;
			$ticketmoney+=$val['ticketval']*$val['ticketnum'];
		}
		$receivablemoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$alipay+$wechatpay+$ticketmoney+$signmoney;
		if(!empty($cusnum)){
			$avgmoney=sprintf("%.0f",$totalmoney/$cusnum);
		}else{
			$avgmoney=0;
		}
		$tabnum=$this->getTabNum($shopid);
		if(!empty($tabnum)){
			$changerate=sprintf("%.1f",100*($billnum/$tabnum))."%";
		}else{
			$changerate="0";
		}
		return array(
				"totalmoney"=>strval(sprintf("%.0f",$totalmoney)),
				"billnum"=>strval($billnum),
				"cusnum"=>strval($cusnum),
				"receivablemoney"=>strval($receivablemoney),
				"avgmoney"=>strval($avgmoney),
				"changerate"=>strval($changerate),
				"cashmoney"=>strval($cashmoney),
				"unionmoney"=>strval($unionmoney),
				"vipmoney"=>strval($vipmoney),
				"meituanpay"=>strval($meituanpay),
				"alipay"=>strval($alipay),
				"wechatpay"=>strval($wechatpay),
				"clearmoney"=>strval($clearmoney),
				"othermoney"=>strval($othermoney),
				"signmoney"=>strval($signmoney),
				"freemoney"=>strval($freemoney),
				"discountmoney"=>strval($discountmoney),
				"ticketmoney"=>strval($ticketmoney),
				"token"=>$token,
		);
		
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getTotalmoneyAndFoodDiscountmoney()
	 */
	public function getTotalmoneyAndFoodDiscountmoney($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		$totalmoney=0;
		$fooddisaccountmoney=0;
		foreach ($result['food'] as $key=>$val){
			if(empty($val['present'])){
				$totalmoney+=$val['foodamount']*$val['foodprice'];
				$fooddisaccount=$this->judgeTheFoodDisaccount($val['foodid']);
				if($fooddisaccount=="1"){
					$fooddisaccountmoney+=$val['foodamount']*$val['foodprice'];
				}
			}
		}
		return array("totalmoney"=>$totalmoney,"fooddisaccountmoney"=>$fooddisaccountmoney);
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::judgeTheFoodDisaccount()
	 */
	public function judgeTheFoodDisaccount($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("fooddisaccount"=>1);
		$fooddisaccount="1";
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		if(!empty($result)){
			$fooddisaccount=$result['fooddisaccount'];
		}
		return $fooddisaccount;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getFoodSheetData()
	 */
	public function getFoodSheetData($shopid,$theday, $starttime,$endtime) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		if(!empty($theday)){
			$starttime=strtotime($theday." ".$openhour.":0:0");
			$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		}
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid",  "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("_id"=>1,"tabid"=>1, "food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$tabname=$this->getTabnameByTabid($val['tabid']);
			if(strstr($tabname, "测试")!=false || strstr($tabname, "test")!=false){
				continue;
			}
			foreach ($val['food'] as $fkey=>$fval){
				if(array_key_exists($fval['foodid'], $arr)){
					if($fval['present']=="1"){
						$foodprice="0";
					}else{
						$foodprice=$fval['foodprice'];
					}
					$arr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
					$arr[$fval['foodid']]['foodmoney']+=$fval['foodamount']*$foodprice;
				}else{
					if($fval['present']=="1"){
						$foodprice="0";
					}else{
						$foodprice=$fval['foodprice'];
					}
					$foodarr=$this->getFoodinfoByFoodid($fval['foodid']);
					if(empty($foodarr)){continue;}
					$arr[$fval['foodid']]=array(
							"foodname"=>$foodarr['foodname'],
							"foodamount"=>$fval['foodamount'],
							"foodmoney"=>$fval['foodamount']*$foodprice,
							"orderunit"=>$fval['orderunit'],
					);
				}
			}
		}
		$arr=$this->array_sort($arr, "foodamount","desc");
		$totalnum=0;
		$totalmoney=0;
		$newarr=array();
		foreach ($arr as $rkey=>$rval){
			$totalnum+=$rval['foodamount'];
			$totalmoney+=$rval['foodmoney'];
		}
		foreach ($arr as $tkey=>$tval){
			$newarr[]=array(
					"foodname"=>$tval['foodname'],
					"foodnum"=>$tval['foodamount'],
					"foodmoney"=>sprintf("%.2f",$tval['foodmoney']),
					"orderunit"=>$tval['orderunit'],
					"numrate"=>sprintf("%.1f",$tval['foodamount']/$totalnum),
					"moneyrate"=>sprintf("%.1f",$tval['foodmoney']/$totalmoney),
			);
		}
		return $newarr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::array_sort()
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
	 * @see IBillDAL::getFoodtypeSheetData()
	 */
	public function getFoodtypeSheetData($shopid,$theday, $starttime,$endtime) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		if(!empty($theday)){
			$starttime=strtotime($theday." ".$openhour.":0:0");
			$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		}
		$qarr=array("shopid"=>$shopid,"paystatus"=>"paid",  "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$oparr=array("tabid"=>1, "food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$tabname=$this->getTabnameByTabid($val['tabid']);
			if(strstr($tabname, "测试")!=false || strstr($tabname, "test")!=false){
				continue;
			}
			foreach ($val['food'] as $fkey=>$fval){
				$ftarr=$this->getFoodTypInfoByFoodid($fval['foodid']);
// 				print_r($ftarr);exit;
				if(empty($ftarr)){continue;}
				if(array_key_exists($ftarr['ftid'], $arr)){
					if($fval['present']=="1"){
						$foodprice="0";
					}else{
						$foodprice=$fval['foodprice'];
					}
					$arr[$ftarr['ftid']]['typenum']+=$fval['foodnum'];
					$arr[$ftarr['ftid']]['typemoney']+=$fval['foodamount']*$foodprice;
				}else{
					if($fval['present']=="1"){
						$foodprice="0";
					}else{
						$foodprice=$fval['foodprice'];
					}
					$arr[$ftarr['ftid']]=array(
							"foodtypename"=>$ftarr['foodtypename'],
							"typenum"=>$fval['foodnum'],
							"typemoney"=>$fval['foodamount']*$foodprice,
					);
				}
			}
		}
		$arr=$this->array_sort($arr, "typenum","desc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getFoodTypeNameByFtid()
	 */
	public function getFoodTypInfoByFoodid($foodid){
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodtypeid"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result['foodtypeid'])){
			$foodtypename=$this->getFoodTypInfoByFtid($result['foodtypeid']);
			$arr=array("ftid"=>$result['foodtypeid'],"foodtypename"=>$foodtypename);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getFoodTypInfoByFtid()
	 */
	public function getFoodTypInfoByFtid($ftid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("foodtypename"=>1);
		$foodtypename="";
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		if(!empty($result)){
			$foodtypename=$result['foodtypename'];
		}
		return $foodtypename;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getTodayOnlineData()
	 */
	public function getTodayOnlineData($shopid,$theday,$token) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$newhour=date("H",time());
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid, "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$totalmoney=0;
		$cashmoney=0;
		$unionmoney=0;
		$vipmoney=0;
		$depositmoney=0;
		$returndepositmoney=0;
		$onedepositmoney=0;
		$arr=array();
		$depositmoney=$this->getDepositmoney($shopid);
		foreach ($result as $key=>$val){
			$tabstatus=$this->getTabStatusByTabid($val['tabid']);
			$beforenum=$this->hasBillBeforeTheTab($shopid, $val['tabid'], $val['timestamp']);
			if($val['paystatus']=="paid" || ($val['deposit']=="1" && $val['paystatus']=="unpay" && $tabstatus=="start" && empty($beforenum)) ){
				$tabname=$this->getTablenameByTabid($val['tabid']);
				if(strstr($tabname,"测试")!=false || strstr($tabname, "test")!=false){
					continue;
				}
				
				$totalmoney_fooddisaccountmoney=$this->getTotalmoneyAndFoodDiscountmoney(strval($val['_id']));
				$totalmoney+=$totalmoney_fooddisaccountmoney['totalmoney'];
				if(!empty($val['cashmoney'])){
					$cashmoney+=$val['cashmoney'];
				}
				if(!empty($val['unionmoney'])){
					$unionmoney+=$val['unionmoney'];
				}
				if(!empty($val['vipmoney'])){
					$vipmoney+=$val['vipmoney'];
				}
				
				if($val['deposit']=="1" && $val['paystatus']=="unpay" && $tabstatus=="start"){
					$totalmoney+=$depositmoney;
				}
				if($val['deposit']=="1"){
					$onedepositmoney+=$depositmoney;
				}
				if(!empty($val['returndepositmoney'])){
					$returndepositmoney+=$val['returndepositmoney'];
				}
			}
		}
	
		$typefood=$this->getFoodtypeSheetData($shopid,"",$starttime,$endtime);
		$typefood=$this->getPreArray($typefood, 5);
		$food=$this->getFoodSheetData($shopid,"", $starttime,$endtime);
		$food=$this->getPreArray($food, 5);
		return array(
				"totalmoney"=>sprintf("%.2f",$totalmoney),
				"cashmoney"=>$cashmoney,
				"unionmoney"=>$unionmoney,
				"vipmoney"=>$vipmoney,
				"token"=>$token,
				"typefood"=>$typefood,
				"food"=>$food,
		);
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getPreArray()
	 */
	public function getPreArray($inpuarr, $n) {
		// TODO Auto-generated method stub
		$arr=array();
		$i=0;
		foreach ($inpuarr as $key=>$val){
			if($i<$n){
				$arr[]=$val;
			}
			$i++;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getTabNum()
	 */
	public function getTabNum($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabswitch"=>"1");
		return DALFactory::createInstanceCollection(self::$table)->count($qarr);
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getOpenHourByShopid()
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
	 * @see IBillDAL::getFoodinfoByFoodid()
	 */
	public function getFoodinfoByFoodid($foodid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodname"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("foodname"=>$result['foodname']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getOnePrinterInfoByPid()
	 */
	public function getOnePrinterInfoByPid($pid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("deviceno"=>1,"devicekey"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"deviceno"=>$result['deviceno'],
					"devicekey"=>$result['devicekey'],	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::generPrintContent()
	 */
	public function generPrintContent($deviceno, $devicekey, $datarr,$theday) {
		// TODO Auto-generated method stub
		global $phonekey;
		$phonecrypt = new CookieCrypt($phonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($phonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>'.$theday.'日报表</CB>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.=$this->getStableLenStr("消费人数：", 16).$datarr['cusnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("下单数：", 16).$datarr['billnum'].'<BR>';
		$orderInfo.=$this->getStableLenStr("翻台率：", 16).$datarr['changerate'].'<BR>';
		$orderInfo.=$this->getStableLenStr("消费总额：", 16).$datarr['totalmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("人均消费：", 16).$datarr['avgmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("应收款：", 16).$datarr['receivablemoney'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$this->getStableLenStr("现金：", 16).$datarr['cashmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("银联卡：", 16).$datarr['unionmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("会员卡：", 16).$datarr['vipmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("券：", 16).$datarr['ticketmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("美团账户：", 16).$datarr['meituanpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("支付宝：", 16).$datarr['alipay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("微信支付：", 16).$datarr['wechatpay'].'<BR>';
		$orderInfo.=$this->getStableLenStr("其他收入：", 16).$datarr['othermoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("签单：", 16).$datarr['signmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("免单：", 16).$datarr['freemoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("折扣额：", 16).$datarr['discountmoney'].'<BR>';
		$orderInfo.=$this->getStableLenStr("抹零：", 16).$datarr['clearmoney'].'<BR>';
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
	/* (non-PHPdoc)
	 * @see IBillDAL::sendSelfFormatMessage()
	 */
	public function sendSelfFormatMessage($msgInfo) {
		// TODO Auto-generated method stub
		$client = new HttpClient(IP,PORT);
		if(!$client->post(HOSTNAME.'/printOrderAction',$msgInfo)){
			echo 'error';
		}
		else{
			$result= $client->getContent();
			$rearr=json_decode($result,true);
			return $rearr['responseCode'];
		}
	}
	
	public function getStableLenStr($str, $len){
		$strlength=(strlen($str) + mb_strlen($str,'UTF8'))/2;
		if($strlength<$len){
			return $str.str_repeat(" ",($len-$strlength+1));
		}else{
			return $str;
		}
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getTabnameByTabid()
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

	public function getDepositmoney($shopid){
		if(empty($shopid)){return "0";}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$depositmoney="0";
		if(!empty($result['depositmoney'])){
			$depositmoney=$result['depositmoney'];
		}
		return $depositmoney;
	}
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
	public function hasBillBeforeTheTab($shopid,$tabid, $timestamp){
		$qarr=array("shopid"=>$shopid,"tabid"=>$tabid, "timestamp"=>array("\$gt"=>$timestamp));
		return DALFactory::createInstanceCollection(self::$bill)->count($qarr);
	}
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
	 * @see IFoodDAL::getTakeoutData()
	*/
	public function getTakeoutData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"takeout"=>"1","timestamp"=>array("\$gte"=>time()-7*86400));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			if(empty($val['takeouttype'])){$takeouttype="unconfrim";}else{$takeouttype=$val['takeouttype'];}
			$userarr=$this->getUserinfo($val['uid']);
			if(empty($userarr)){continue;}
			$arr[$takeouttype][strval($val['_id'])]=array(
					"billid"=>	strval($val['_id']),
					"cusnum"=>$val['cusnum'],
					"uid"=>$val['uid'],
					"shopid"=>$val['shopid'],
					"nickname"=>$userarr['nickname'],
					"takeoutphone"=>$val['takeoutphone'],
					"takeoutaddress"=>$val['takeoutaddress'],
			);
		}
		return $arr;
	}
	
	public function getUserinfo($uid){
		if(empty($uid)){return array();}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("nickname"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("nickname"=>$result['nickname']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDAL::getBillsData()
	 */
	public function getBillsData($shopid, $searchday) {
		// TODO Auto-generated method stub
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($searchday." ".$openhour.":0:0");
		$endtime=$starttime+86400;
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			$totalmoney=0;
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodprice']*$fval['foodamount'];
				}
			}
			$billnum=$this->getBillNo(strval($val['_id']));
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"billnum"=>"#".$billnum,
					"totalmoney"=>$totalmoney,	
			);
		}
		return $arr;
	}

	public function getBillNo($billid){
		$qarr=array("billid"=>$billid);
		$oparr=array("no"=>1);
		$result=DALFactory::createInstanceCollection(self::$sheetno)->findOne($qarr,$oparr);
		$no="";
		if(!empty($result)){
			$no=$result['no'];
		}
		return $no;
	}
}
?>