<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IMonitorFiveDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once ('/var/www/html/des.php');

class MonitorFiveDAL implements IMonitorFiveDAL{
	private static $shopinfo="shopinfo";
	private static $table="table";
	private static $payrecord="payrecord";
	private static $article="article";
	private static $food="food";
	private static $bill="bill";
	private static $customer="customer";
	private static $pageview="pageview";
	private static $booklist="booklist";
	private static $beforebill="beforebill";
	private static $change_tabstatus_record="change_tabstatus_record";
	private static $timeslot = "timeslot";
	private static $prebillshopinfo = "prebillshopinfo";
	private static $bill_receive_status="bill_receive_status";
	private static $takeoutdiscount = "takeoutdiscount";
	private static $takeoutfare = "takeoutfare";
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getAllowinbalanceValue()
	 */
	public function getAllowinbalanceValue($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("allowinbalance"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$allowinbalance="0";
		if(!empty($result['allowinbalance'])){
			$allowinbalance=$result['allowinbalance'];
		}
		return $allowinbalance;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::clearOneTableStatus()
	 */
	public function clearOneTableStatus($tabid, $tabstatus) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("\$set"=>array("tabstatus"=>$tabstatus));
		DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::saveUpdateTabStatus()
	 */
	public function saveUpdateTabStatus($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$change_tabstatus_record)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getPayRecordData()
	 */
	public function getPayRecordData($inputarr) {
		// TODO Auto-generated method stub
		$monitoronedal=new MonitorOneDAL();
		$qarr=array("shopid"=>$inputarr['shopid'], "gmt_payment"=>array("\$gte"=>strtotime($inputarr['starttime']),"\$lte"=>strtotime($inputarr['endtime'])));;
		$result=DALFactory::createInstanceCollection(self::$payrecord)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(strstr($val['paytype'], $inputarr['paytype'])==false){continue;}
			$billnum="";
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$billarr=$monitoronedal->getOneBillDataByBillid($val['billid']);
			if(empty($tabname)){
				$tabname="";
				$billnum=$billarr['billnum'];//小堂倌—单号
			}
			$arr[]=array(
					"shopid"=>$val['shopid'],
					"billid"=>$val['billid'],
					"tabid"=>$val['tabid'],
					"uid"=>$billarr['uid'],
					"paytype"=>"alipay",
					"tabname"=>$tabname,
					"billnum"=>$billnum,
					"gmt_payment"=>$val['gmt_payment'],//交易付款时间
					"buyer_email"    =>$val['buyer_email'],//顾客支付宝账户
					"trade_no"=>$val['trade_no'],//交易号
					"total_fee"=>$val['total_fee'],//付款总额
					"gmt_create"=>$val['gmt_create'],//交易创建时间
					"out_trade_no"=>$val['out_trade_no'],//网站商户唯一订单号
					"subject"=>$val['subject'],//商品名称
					"trade_status"=>$val['trade_status'],//交易状态
					"downtime"=>$billarr['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getTablenameByTabid()
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
	 * @see IMonitorFiveDAL::updateShopinfoData()
	 */
	public function updateShopinfoData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($inputarr['shopid']));
		$oparr=array("\$set"=>array(
				"briefinfo"=>$inputarr['briefinfo'],
				"province"=>$inputarr['province'],
				"city"=>$inputarr['city'],
				"district"=>$inputarr['district'],
				"road"=>$inputarr['road'],
				"lon"=>$inputarr['lon'],
				"lat"=>$inputarr['lat'],
				"loc"=>$inputarr['loc'],
				"avgpay"=>$inputarr['avgpay'],
				"opentime"=>$inputarr['opentime'],
				"takeoutswitch"=>$inputarr['takeoutswitch'],
				"servicephone"=>$inputarr['servicephone'],
				"manager"=>$inputarr['manager'],
				"alipayaccount"=>$inputarr['alipayaccount'],
				"storetag"=>$inputarr['storetag'],
				"favfoodid"=>$inputarr['favfoodid'],
		));
		DALFactory::createInstanceCollection(self::$shopinfo)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getMyShopinfoData()
	 */
	public function getMyShopinfoData($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array(
				"shopname"=>1,
				"briefinfo"=>1,
				"province"=>1,
				"city"=>1,
				"district"=>1,
				"road"=>1,
				"lon"=>1,
				"lat"=>1,
				"avgpay"=>1,
				"opentime"=>1,
				"takeoutswitch"=>1,
				"servicephone"=>1,
				"takeoutswitch"=>1,
				"manager"=>1,
				"alipayaccount"=>1,
				"storetag"=>1,
				"favfoodid"=>1,
				"managerphoto"=>1,
				"homepic"=>1,
				"logo"=>1,
		);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['managerphoto'])){$managerphoto=$result['managerphoto'];}else{$managerphoto="http://jfoss.meijiemall.com/userphoto/default_userphoto.jpg";}
			if(!empty($result['homepic'])){$homepic=$result['homepic'];}else{$homepic="http://jfoss.meijiemall.com/userphoto/default_userphoto.jpg";}
			if(!empty($result['logo'])){$logo=$result['logo'];}else{$logo="http://jfoss.meijiemall.com/userphoto/default_userphoto.jpg";}
			if($result['takeoutswitch']=="1"){$takeoutswitch="1";}else{$takeoutswitch="0";}
			foreach ($result['favfoodid'] as $foodid){
				$foodinfo=$this->getFoodinfoByFoodid($foodid);
				if(empty($foodinfo)){continue;}
				$favfoodarr[]=$foodinfo['foodname'];
			}
			$arr=array(
					"shopname"=>$result['shopname'],
					"briefinfo"=>$result['briefinfo'],
					"province"=>$result['province'],
					"city"=>$result['city'],
					"district"=>$result['district'],
					"road"=>$result['road'],
					"lon"=>$result['lon'],
					"lat"=>$result['lat'],
					"takeoutswitch"=>$takeoutswitch,
					"avgpay"=>$result['avgpay'],
					"opentime"=>$result['opentime'],
					"servicephone"=>$result['servicephone'],
					"manager"=>$result['manager'],
					"alipayaccount"=>$result['alipayaccount'],
					"storetag"=>$result['storetag'],
					"favfood"=>$favfoodarr,
					"favfoodid"=>$result['favfoodid'],
					"managerphoto"=>$managerphoto,
					"homepic"=>$homepic,
					"logo"=>$logo,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getShopImgUpTime()
	 */
	public function getShopImgUpTime($shopid,$op) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		if($op=="managerphoto"){
			$oparr=array("up_managerphoto_time"=>1);
		}elseif($op=="homepic"){
			$oparr=array("up_homepic_time"=>1);
		}
		$uptime=time();
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			if($op=="managerphoto"){
				$uptime=$result['up_managerphoto_time'];
			}elseif($op=="homepic"){
				$uptime=$result['up_homepic_time'];
			}
		}
		return $uptime;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updateShopimgData()
	 */
	public function updateShopimgData($shopid, $newshopimgpic, $timestamp, $op) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		if($op=="managerphoto"){
			$oparr=array("\$set"=>array(
					$op=>$newshopimgpic,
					"up_managerphoto_time"=>$timestamp,
			));
		}elseif($op=="homepic"){
			$oparr=array("\$set"=>array(
					$op=>$newshopimgpic,
					"up_homepic_time"=>$timestamp,
			));
		}
		DALFactory::createInstanceCollection(self::$shopinfo)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::upArticleData()
	 */
	public function upArticleData($shopid, $htmlData) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$result=DALFactory::createInstanceCollection(self::$article)->findOne($qarr);
		if(!empty($result)){
			$oparr=array("\$set"=>array("article"=>$htmlData));
			DALFactory::createInstanceCollection(self::$article)->update($qarr,$oparr);
		}else{
			$oparr=array("shopid"=>$shopid,"article"=>$htmlData);
			DALFactory::createInstanceCollection(self::$article)->save($qarr,$oparr);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getArticleByshopid()
	 */
	public function getArticleByshopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("article"=>1);
		$result=DALFactory::createInstanceCollection(self::$article)->findOne($qarr,$oparr);
		$str="";
		if(!empty($result['article'])){
			$str=$result['article'];
		}
		return $str;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getFoodnameByFoodid()
	 */
	public function getFoodinfoByFoodid($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return array();}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodname"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"foodname"=>$result['foodname'],	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updateTakeoutData()
	 */
	public function updateTakeoutData($billid, $uid, $cusphone, $cusaddress) {
		// TODO Auto-generated method stub
		if(empty($billid)){return;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("takeout"=>"1","takeoutaddress"=>$cusaddress,"takeoutphone"=>$cusphone));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		if(empty($uid)){return;}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("\$set"=>array("cusphone"=>$cusphone,"cusaddress"=>$cusaddress));
		DALFactory::createInstanceCollection(self::$customer)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getTakeoutInfo()
	 */
	public function getTakeoutInfo($uid) {
		// TODO Auto-generated method stub
		if(empty($uid)){return array();}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("cusphone"=>1,"cusaddress"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("cusphone"=>$result['cusphone'],"cusaddress"=>$result['cusaddress']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getMyBillsDataByUid()
	 */
	public function getMyBillsDataByUid($uid,$shopid) {
		// TODO Auto-generated method stub
		$qarr=array("customerid"=>$uid,"shopid"=>$shopid, "timestamp"=>array("\$gte"=>time()-86400*30));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$shopinfo=$this->getMyShopinfoData($val['shopid']);
			if(empty($shopinfo)){continue;}
			if($val['takeout']=="1"){$takeout="1";}else{$takeout="0";}
			$totalmoney=0;
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodprice']*$fval['foodamount'];
				}
			}
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"shopid"=>$val['shopid'],
					"tabid"=>$val['tabid'],
					"cusnum"=>$val['cusnum'],
					"shopname"=>$shopinfo['shopname'],
					"timestamp"=>$val['timestamp'],
					"paystatus"=>$val['paystatus'],
					"billstatus"=>$val['billstatus'],
					"takeout"=>$takeout,
					"totalmoney"=>$totalmoney,
					"tabname"=>$tabname,
					"logo"=>$shopinfo['logo'],
					"from"=>self::$bill,
			);
		}
		return $arr;
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getLogoUpTime()
	 */
	public function getLogoUpTime($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("logouptime"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return $result['logouptime'];
		}else{
			return "";
		}
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updateLogoData()
	 */
	public function updateLogoData($shopid, $logourl, $timestamp) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("\$set"=>array("logo"=>$logourl,"logouptime"=>$timestamp));
		DALFactory::createInstanceCollection(self::$shopinfo)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getPageviewDataByDay()
	 */
	public function getPageviewDataByDay($shopid,  $datearr) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		foreach ($datearr as $day){
			$viewnum=0;
			$qarr=array("shopid"=>$shopid,"pagename"=>"shophome","date"=>$day);
			$oparr=array("viewnum"=>1);
			$result=DALFactory::createInstanceCollection(self::$pageview)->findOne($qarr,$oparr);
			if(!empty($result)){
				$viewnum=$result['viewnum'];
			}
			$arr[$day]=$viewnum;
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
		return $lineChartarr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getTotalPageviewnum()
	 */
	public function getTotalPageviewnum($shopid, $startdate,$enddate) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"pagename"=>"shophome","date"=>array("\$gte"=>$startdate,"\$lte"=>$enddate));
		$oparr=array("viewnum"=>1);
		$result=DALFactory::createInstanceCollection(self::$pageview)->find($qarr,$oparr);
		$viewnum=0;
		foreach ($result as $key=>$val){
			$viewnum+=$val['viewnum'];
		}
		return $viewnum;
	}
	public function getTabstatusByTabid($tabid){
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabstatus"=>1);
		$tabstatus="";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result)){
			$tabstatus=$result['tabstatus'];
		}
		return $tabstatus;
	}
	
	public function getTakeoutsheetData($shopid,$theday,$openhour,$op){
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		if($op=="unsure"){
			$qarr=array("shopid"=>$shopid,"takeout"=>"1","paystatus"=>"paid", "takeoutstatus"=>array("\$nin"=>array("sure","invalid")), "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		}elseif($op=="sure"){
			$qarr=array("shopid"=>$shopid,"takeout"=>"1","paystatus"=>"paid", "takeoutstatus"=>"sure", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		}elseif($op=="invalid"){
			$qarr=array("shopid"=>$shopid,"takeout"=>"1","paystatus"=>"paid","takeoutstatus"=>"invalid", "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		}
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"nickname"=>$val['nickname'],
					"alipay"=>$val['alipay'],
					"timestamp"=>$val['timestamp'],
					"takeoutphone"=>$val['takeoutphone'],
					"takeoutaddress"=>$val['takeoutaddress'],
					"orderrequest"=>$val['orderrequest'],
					"food"=>$val['food'],
			);
		}
		return $arr;
	}
	
	public function updateTakeoutSheet($billid,$op){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("takeoutstatus"=>$op));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getMyOrdersByUid()
	 */
	public function getMyOrdersByUid($uid,$op) {
		// TODO Auto-generated method stub
		if($op=="unpay"){
			$qarr=array("customerid"=>$uid,"paystatus"=>"unpay","billstatus"=>"done", "timestamp"=>array("\$gte"=>time()-86400*30));
		}elseif($op=="paid"){
			$qarr=array("customerid"=>$uid,"paystatus"=>"paid","billstatus"=>"done", "timestamp"=>array("\$gte"=>time()-86400*30));
		}
		
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$shopinfo=$this->getMyShopinfoData($val['shopid']);
			if(empty($shopinfo)){continue;}
			if($val['takeout']=="1"){$takeout="1";}else{$takeout="0";}
			$totalmoney=0;
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodprice']*$fval['foodamount'];
				}
			}
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"shopid"=>$val['shopid'],
					"tabid"=>$val['tabid'],
					"cusnum"=>$val['cusnum'],
					"shopname"=>$shopinfo['shopname'],
					"timestamp"=>$val['timestamp'],
					"paystatus"=>$val['paystatus'],
					"billstatus"=>$val['billstatus'],
					"takeout"=>$takeout,
					"totalmoney"=>$totalmoney,
					"tabname"=>$tabname,
					"logo"=>$shopinfo['logo'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::switchBookFlag()
	 */
	public function switchBookFlag($tabid, $status) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("\$set"=>array("bookflag"=>$status));
		DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::postBookData()
	 */
	public function postBookData($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$booklist)->save($inputarr);
		$this->updateCusinfo($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updateCusinfo()
	 */
	public function updateCusinfo($inputarr) {
		// TODO Auto-generated method stub
		if(empty($inputarr['uid'])){return ;}
		$qarr=array("_id"=>new MongoId($inputarr['uid']));
		$oparr=array("\$set"=>array("cusname"=>$inputarr['cusname'],"cusphone"=>$inputarr['cusphone']));
		DALFactory::createInstanceCollection(self::$customer)->update($qarr,$oparr);
		
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getCusinfoByuid()
	 */
	public function getCusinfoByuid($uid) {
		// TODO Auto-generated method stub
		if(empty($uid)){return array();}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("cusname"=>1,"cusphone"=>1);
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("cusname"=>$result['cusname'],"cusphone"=>$result['cusphone']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::generPrintBookOrderContent()
	 */
	public function generPrintBookOrderContent($deviceno, $devicekey, $inputarr) {
		// TODO Auto-generated method stub
		global $newphonekey;
		$phonecrypt = new CookieCrypt($newphonekey);
		$deviceno=$phonecrypt->decrypt($deviceno);
		$phonecrypt = new CookieCrypt($newphonekey);
		$devicekey=$phonecrypt->decrypt($devicekey);
		
		$orderInfo='';
		$orderInfo .= '<CB>新预定</CB><BR>';
		$orderInfo.='预定顾客：'.$inputarr['cusname'].'<BR>';
		$orderInfo.='预定人数：'.$inputarr['cusnum'].'<BR>';
		$orderInfo.='联系方式：'.$inputarr['cusphone'].'<BR>';
		$orderInfo.='到店时间：'.$inputarr['bookdate'].' '.$inputarr['booktime'].'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='请您在后台处理订单，我们会以短信的方式通知此顾客~<BR>';
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
	 * @see IMonitorFiveDAL::getBooklistSheet()
	 */
	public function getBooklistSheet($shopid, $theday, $op) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"bookstatus"=>$op, "timestamp"=>array("\$gte"=>strtotime($theday),"\$lte"=>strtotime($theday)+86400));
		$result=DALFactory::createInstanceCollection(self::$booklist)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr[]=array(
					"bookid"=>strval($val['_id']),
					"shopid"=>$val['shopid'],
					"uid"=>$val['uid'],
					"cusname"=>$val['cusname'],
					"cusnum"	=>$val['cusnum'],
					"cusphone"=>$val['cusphone'],
					"tabname"=>$tabname,
					"bookdate"=>$val['bookdate'],
					"booktime"=>$val['booktime'],
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updateBookStatusData()
	 */
	public function updateBookStatusData($bookid, $op) {
		// TODO Auto-generated method stub
		if(empty($bookid)){return ;}
		$qarr=array("_id"=>new MongoId($bookid));
		$oparr=array("\$set"=>array("bookstatus"=>$op));
		DALFactory::createInstanceCollection(self::$booklist)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getTodayBookData()
	 */
	public function getTodayBookData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"bookstatus"=>"accept","bookdate"=>date("Y-m-d",time()));
		$result=DALFactory::createInstanceCollection(self::$booklist)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr[]=array(
					"bookid"=>strval($val['_id']),
					"shopid"=>$val['shopid'],
					"uid"=>$val['uid'],
					"cusname"=>$val['cusname'],
					"cusnum"	=>$val['cusnum'],
					"cusphone"=>$val['cusphone'],
					"tabname"=>$val['tabname'],
					"bookdate"=>$val['bookdate'],
					"booktime"=>$val['booktime'],
					"timestamp"=>$val['timestamp'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getBookids()
	 */
	public function getBookids($shopid, $theday) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid, "timestamp"=>array("\$gte"=>strtotime($theday),"\$lte"=>strtotime($theday)+86400));
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$booklist)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=strval($val['_id']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getAvilableTabs()
	 */
	public function getAvilableTabs($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"bookflag"=>"1");
		$oparr=array("_id"=>1,"tabname"=>1,"seatnum"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"tabid"=>strval($val['_id']),
					"tabname"=>$val['tabname'],
					"seatnum"=>$val['seatnum'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::ensureBookTab()
	 */
	public function ensureBookTab($bookid, $tabid,$op) {
		// TODO Auto-generated method stub
		if(empty($bookid)){return ;}
		$qarr=array("_id"=>new MongoId($bookid));
		$oparr=array("\$set"=>array("bookstatus"=>$op,"tabid"=>$tabid));
		DALFactory::createInstanceCollection(self::$booklist)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getOneBookinfo()
	 */
	public function getOneBookinfo($bookid) {
		// TODO Auto-generated method stub
		if(empty($bookid)){return array();}
		$qarr=array("_id"=>new MongoId($bookid));
		$result=DALFactory::createInstanceCollection(self::$booklist)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"bookid"=>strval($result['_id']),
					"shopid"=>$result['shopid'],
					"uid"=>$result['uid'],
					"cusname"=>$result['cusname'],
					"cusnum"	=>$result['cusnum'],
					"cusphone"=>$result['cusphone'],
					"bookdate"=>$result['bookdate'],
					"booktime"=>$result['booktime'],
					"timestamp"=>$result['timestamp'],
			);
		}
		return $arr;
	}
	
	public function getMyBeforeOrdersByUid($uid){
		$qarr=array("uid"=>$uid, "timestamp"=>array("\$gte"=>time()-86400*30));
		$result=DALFactory::createInstanceCollection(self::$beforebill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$shopinfo=$this->getMyShopinfoData($val['shopid']);
			if(empty($shopinfo)){continue;}
			if($val['takeout']=="1"){$takeout="1";}else{$takeout="0";}
			$totalmoney=0;
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodprice']*$fval['foodamount'];
				}
			}
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"shopid"=>$val['shopid'],
					"tabid"=>$val['tabid'],
					"cusnum"=>$val['cusnum'],
					"shopname"=>$shopinfo['shopname'],
					"timestamp"=>$val['timestamp'],
					"paystatus"=>$val['paystatus'],
					"billstatus"=>$val['billstatus'],
					"takeout"=>$takeout,
					"totalmoney"=>$totalmoney,
					"tabname"=>$tabname,
					"logo"=>$shopinfo['logo'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getMyBeforeBillsDataByUid()
	 */
	public function getMyBeforeBillsDataByUid($uid, $shopid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid, "timestamp"=>array("\$gte"=>time()-86400*30));
		$result=DALFactory::createInstanceCollection(self::$beforebill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$shopinfo=$this->getMyShopinfoData($val['shopid']);
			if(empty($shopinfo)){continue;}
			if($val['takeout']=="1"){$takeout="1";}else{$takeout="0";}
			$totalmoney=0;
			foreach ($val['food'] as $fkey=>$fval){
				if(empty($fval['present'])){
					$totalmoney+=$fval['foodprice']*$fval['foodamount'];
				}
			}
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"shopid"=>$val['shopid'],
					"tabid"=>$val['tabid'],
					"cusnum"=>$val['cusnum'],
					"shopname"=>$shopinfo['shopname'],
					"timestamp"=>$val['timestamp'],
					"paystatus"=>$val['paystatus'],
					"billstatus"=>$val['billstatus'],
					"takeout"=>$takeout,
					"totalmoney"=>$totalmoney,
					"tabname"=>$tabname,
					"logo"=>$shopinfo['logo'],
					"from"=>self::$beforebill,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updateShopSwitch()
	 */
	public function updateShopSwitch($shopid,$op, $status) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return ;}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("\$set"=>array($op=>$status));
		DALFactory::createInstanceCollection(self::$shopinfo)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::getShopSetData()
	 */
	public function getShopSetData($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return ;}
		$qarr=array("_id"=>new MongoId($shopid));
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			if(isset($result['doublesheet'])){
				$doublesheet=$result['doublesheet'];
			}else{
				$doublesheet="0";
			}
			if(isset($result['tabdata'])){
				$tabdata=$result['tabdata'];
			}else{
				$tabdata="1";
			}
			if(isset($result['bycusnum'])){
			    $bycusnum=$result['bycusnum'];
			}else{
			    $bycusnum="0";
			}
			if(isset($result['topclearmoney'])){$topclearmoney=$result['topclearmoney'];}else{$topclearmoney="0";}
			if(isset($result['depositmoney'])){$depositmoney=$result['depositmoney'];}else{$depositmoney="0";}
			if(isset($result['menumoney'])){$menumoney=$result['menumoney'];}else{$menumoney="1";}	
			if(isset($result['openhour'])){$openhour=$result['openhour'];}else{$openhour="0";}
			if(isset($result['alipay_switch'])){$alipay_switch=$result['alipay_switch'];}else{$alipay_switch="0";}
			if(isset($result['wechatpay_switch'])){$wechatpay_switch=$result['wechatpay_switch'];}else{$wechatpay_switch="0";}
			if(isset($result['directpay_switch'])){$directpay_switch=$result['directpay_switch'];}else{$directpay_switch="0";}
			if(isset($result['vipdiscount'])){$vipdiscount=$result['vipdiscount'];}else{$vipdiscount="100";}
			if(isset($result['takeoutswitch'])){$takeoutswitch=$result['takeoutswitch'];}else{$takeoutswitch="100";}
			if(isset($result['distance'])){$distance=$result['distance'];}else{$distance="0";}
			if(isset($result['notice'])){$notice=$result['notice'];}else{$notice=" ";}
			if(isset($result['startmoney'])){$startmoney=$result['startmoney'];}else{$startmoney=" ";}
			$arr=array(
					"doublesheet"=>$doublesheet,
					"tabdata"=>$tabdata,
					"topclearmoney"=>$topclearmoney,
					"depositmoney"=>$depositmoney,
					"menumoney"=>$menumoney,
					"openhour"=>$openhour,
					"alipay_switch"=>$alipay_switch,
					"wechatpay_switch"=>$wechatpay_switch,
					"directpay_switch"=>$directpay_switch,
			         "bycusnum"=>$bycusnum,
					"vipdiscount"=>$vipdiscount,
			         "takeoutswitch" => $takeoutswitch,
			         "distance"  => $distance,
			         "notice" => $notice,
			         "startmoney" => $startmoney
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorFiveDAL::updatePicAddress()
	 */
	public function updatePicAddress($foodid, $foodpic) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return ;}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("\$set"=>array("foodpic"=>$foodpic));
		DALFactory::createInstanceCollection(self::$food)->update($qarr,$oparr);
	}
	/*
	 * 获取时间段配置
	 */
	public function shopTimeSlot($shopid){
	    //表名 timeslot 
	    
	       $qarr = array('shopid' => $shopid);
	       $res = DALFactory::createInstanceCollection(self::$timeslot)->find($qarr);
	        
	       return $res;
	    
	}
	/*
	 * 时间段配置修改
	 */
	public function editShopTimeSlot($shopid,$id=NULL,$name=NULL,$starttime=NULL,$overtime=NULL){
	    if(!empty($id)){
	        $qarr = array("_id"=>new MongoId($id));
	        $oparr = array("\$set" => array('name' => $name,'starttime'=>$starttime,'overtime'=>$overtime));
	        DALFactory::createInstanceCollection(self::$timeslot)->update($qarr,$oparr);
	    }else{
	        $arr = array('shopid'=>$shopid,'name' =>$name,'starttime'=>$starttime,'overtime'=>$overtime );
	        DALFactory::createInstanceCollection(self::$timeslot)->save($arr);
	    }
	}
	public function delOneTimeSlot($id){
	    $qarr = array("_id"=>new MongoId($id));
	    DALFactory::createInstanceCollection(self::$timeslot)->remove($qarr);
	}
	public function getOneTimeSlot($id) {
	    // TODO Auto-generated method stub
	    if(empty($id)){return 1;}
	    $qarr=array("_id"=>new MongoId($id));
	    
	    $arr=array();
	    $result=DALFactory::createInstanceCollection(self::$timeslot)->findOne($qarr);
	    if(!empty($result)){
	        $arr=array("id"=>strval($result['_id']),
	            "name"=>$result['name'],
	            'starttime' => $result['starttime'],
	            'overtime'=>$result['overtime']);
	    }
	    return $arr;
	}
	public function getShopTakeout($shopid,$date)
	{
	    $monitoronedal=new MonitorOneDAL();
	    $openhour= $monitoronedal->getOpenHourByShopid($shopid);
	    $starttime=strtotime($date." ".$openhour.":0:0");
	    $endtime =strtotime($date." ".$openhour.":0:0")+86400;//"paystatus"=>"paid",
	   //该qarr查询不到结果
	    $qarr=array("shopid"=>$shopid,"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
	    
	    //$qarr = array('shopid'=>$shopid);
	    $result = DALFactory::createInstanceCollection(self::$bill)->find($qarr);
 	   // echo json_encode($qarr);
// 	    echo time();
	    $arr=array();
	   
	    foreach ($result as $v)
	    {
	           
	           $data = array();
	           $qarr = array('billid'=> strval($v['_id']));
	           $bill_receive_status=$this->getBillReceivedStatus(strval($v['_id']));
	           $res =  DALFactory::createInstanceCollection(self::$prebillshopinfo)->findOne($qarr);
	           if(empty($res)){continue;}
	           $foodarr=array();
	           foreach ($v['food'] as $fkey=>$fval){
	               $foodarr[]=array(
	                   "foodid"=>$fval['foodid'],
	                   "foodname"=>$fval['foodname'],
	                   "foodunit"=>$fval['foodunit'],
	                   "foodprice"=>$fval['foodprice'],
	                   "foodamount"=>$fval['foodamount'],
	               );
	           }
	           $data =  array(
	               'id' => $res['billid'],
	               'billnum' => $v['billnum'],
	               'author' => $res['author'],
	               'addr' => $res['prov'].$res['city'].$res['dist'].$res['road'],
	               'phone' => $res['phone'],
	               'orderrequest' => $res['orderrequest'],
	               'food' =>$foodarr,
	               'orderno' => $v['orderno'],
	               'tradeno' => $v['tradeno'],
	           );
	           
	           $arr[$bill_receive_status][]=$data;
	    }
	    
	    return $arr;
	    
	}
    /**
     * {@inheritDoc}
     * @see IMonitorFiveDAL::getBillReceivedStatus()
     */
    public function getBillReceivedStatus($billid)
    {
        // TODO Auto-generated method stub
        $qarr=array("billid"=>$billid);
        $oparr=array("status"=>1);
        $result=DALFactory::createInstanceCollection(self::$bill_receive_status)->findOne($qarr,$oparr);
        $status="unreceive";
        if(!empty($result)){
            $status=$result['status'];
        }
        
        return $status;
    }
    public function changeBillReceivedStatus($billid,$status)
    {
        if(empty($billid)){ return ;}
        $qarr=array("billid"=>$billid);
        $res = DALFactory::createInstanceCollection(self::$bill_receive_status)->findOne($qarr);
        
        if($res){
            $oparr = array( '\$set' => array('status' => $status));
            DALFactory::createInstanceCollection(self::$bill_receive_status)->update($qarr,$oparr);
            return 'update';
        }
        else
        {
            $oparr = array('billid' => $billid,'status'=>$status,'timestamp'=>time(),);
            DALFactory::createInstanceCollection(self::$bill_receive_status)->insert($oparr);
            return 'add';
        }
        
    }
    //优惠
    public function getDiscount($shopid)
    {
        if(empty($shopid)){return ;}
        $qarr = array('shopid' => $shopid);
        $result = DALFactory::createInstanceCollection(self::$takeoutdiscount)->find($qarr);
        return $result;
    }
   
    public function addDiscount($shopid,$data){
        if(empty($shopid)){return ;}
        $data['shopid'] = $shopid;
        DALFactory::createInstanceCollection(self::$takeoutdiscount)->insert($data);
    }
    public function delDiscount($discountid){
        if(empty($discountid)){ return ;}
       // $qarr['shopid'] = $shopid;
        $qarr = array('_id' => new MongoId($discountid));
        DALFactory::createInstanceCollection(self::$takeoutdiscount)->remove($qarr);
    }
    //卖家配送费用设置
    public function getFare($shopid){
        if(empty($shopid)){return ;}
        $qarr = array('shopid' => $shopid);
        $result = DALFactory::createInstanceCollection(self::$takeoutfare)->find($qarr);
        return $result;
    }
    public function addFare($shopid,$data){
        if(empty($shopid)){return ;}
        $data['shopid'] = $shopid;
        DALFactory::createInstanceCollection(self::$takeoutfare)->insert($data);
    }
    
    public function delFare($fareid){
        if(empty($fareid)){ return ;}
        $qarr = array('_id' => new MongoId($fareid));
        DALFactory::createInstanceCollection(self::$takeoutfare)->remove($qarr);
    }

}
?>