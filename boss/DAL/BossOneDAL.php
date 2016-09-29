<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'IDAL/IBossOneDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
require_once (_ROOT.'houtai/shop/DAL/MonitorOneDAL.php');

class BossOneDAL implements IBossOneDAL{
	private static $bossaccount="bossaccount";
	private static $subaccount="subaccount";
	private static $shopinfo="shopinfo";
	private static $bill="bill";
	private static $table="table";
	private static $vipcard="vipcard";
	private static $viptag="viptag";
	private static $goods="goods";
	private static $buy_goods_record="buy_goods_record";
	private static $boss_foodtype="boss_foodtype";
	private static $boss_food="boss_food";
	private static $foodtype="foodtype";
	private static $food="food";
	private static $printer="printer";
	/* (non-PHPdoc)
	 * @see IAdminOneDAL::DoLogin()
	 */
	public function DoLoginData($bossphone, $password) {
		// TODO Auto-generated method stub
		$qarr=array("bossphone"=>$bossphone,"passwd"=>$password);	
		$oparr=array("_id"=>1,"bossname"=>1,"bosslogo"=>1);
		$result=DALFactory::createInstanceCollection(self::$bossaccount)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['bosslogo'])){$bosslogo=$result['bosslogo'];}else{$bosslogo="http://jfoss.meijiemall.com/food/default_food.png";}
			return array("bossid"=>strval($result['_id']),"bossname"=>$result['bossname'],"bosslogo"=>$bosslogo);
		}else{
			return array("bossid"=>"","bossname"=>"");
		}
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::regBossAccount()
	 */
	public function regBossAccount($bossphone, $checkcode, $bossname, $passwd, $addtime) {
		// TODO Auto-generated method stub
		global $token;
		global $checkcodeurl;
		$signature=strtoupper(md5($bossphone.$addtime.$token));
		$params=array("phone"=>$bossphone,"checkcode"=>$checkcode, "timestamp"=>$addtime,"signature"=>$signature);
		$result=$this->post_curl($checkcodeurl, $params);
		$statusarr=json_decode($result,true);
		if($statusarr['status']=="1"){//验证通过，注册
			$qarr=array("bossphone"=>$bossphone);
			$oparr=array("_id"=>1);
			$result=DALFactory::createInstanceCollection(self::$bossaccount)->findOne($qarr,$oparr);
			if(!empty($result)){
				return array("status"=>"registered","bossid"=>"");
			}else{
				$arr=array("bossphone"=>$bossphone,"passwd"=>$passwd,"bossname"=>$bossname,"addtime"=>$addtime);
				DALFactory::createInstanceCollection(self::$bossaccount)->insert($arr);
				return array("status"=>"ok","bossid"=>strval($arr['_id']));
			}
		}else{
			return array("status"=>"codeerror","bossid"=>"");
		}
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::isPhoneUse()
	 */
	public function isPhoneUse($phone) {
		// TODO Auto-generated method stub
		$qarr=array("bossphone"=>$phone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$bossaccount)->findOne($qarr,$oparr);
		if(!empty($result)){
			return false;
		}else{
			return true;
		}
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::isShopPhonereg()
	 */
	public function isShopPhonereg($shopphone) {
		// TODO Auto-generated method stub
		$qarr=array("mobilphone"=>$shopphone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::bindShopPhoneData()
	 */
	public function bindShopPhoneData($inputarr) {
		// TODO Auto-generated method stub
		global $token;
		global $checkcodeurl;
		$signature=strtoupper(md5($inputarr['shopphone'].$inputarr['timestamp'].$token));
		$params=array("phone"=>$inputarr['shopphone'],"checkcode"=>$inputarr['checkcode'], "timestamp"=>$inputarr['timestamp'],"signature"=>$signature);
		$result=$this->post_curl($checkcodeurl, $params);
		$statusarr=json_decode($result,true);
// 		var_dump($statusarr);exit;
		if($statusarr['status']=="1"){//验证通过，注册
			$shopid=$this->getShopidbyPhone($inputarr['shopphone']);
			if(!empty($shopid)){
				$qarr=array("bossid"=>$inputarr['bossid'],"shopid"=>$shopid);
				$oparr=array("_id"=>1);
				$result=DALFactory::createInstanceCollection(self::$subaccount)->findOne($qarr,$oparr);
				if(empty($result)){
					DALFactory::createInstanceCollection(self::$subaccount)->save($qarr);
					return array("status"=>"ok");
				}else{
					return array("status"=>"reged");
				}
			}else{
				return array("status"=>"notreg");
			}
		}else{
			return array("status"=>"codeerror");
		}
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::post_curl()
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
	 * @see IBossOneDAL::getShopidbyPhone()
	 */
	public function getShopidbyPhone($phone) {
		// TODO Auto-generated method stub
		$qarr=array("mobilphone"=>$phone);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			return strval($result['_id']);
		}else{
			return "";
		}
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::delOneShopData()
	 */
	public function delOneShopData($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return ;}
		$qarr=array("shopid"=>$shopid);
		DALFactory::createInstanceCollection(self::$subaccount)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getMyShoplistData()
	 */
	public function getMyShoplistData($bossid) {
		// TODO Auto-generated method stub
		$qarr=array("bossid"=>$bossid);
		$oparr=array("shopid"=>1);
		$result=DALFactory::createInstanceCollection(self::$subaccount)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$shopinfo=$this->getShopinfoByShopid($val['shopid']);
			if(empty($shopinfo)){continue;}
			$arr[]=array(
					"shopid"=>$val['shopid'],
					"mobilphone"=>$shopinfo['mobilphone'],
					"shopname"=>$shopinfo['shopname'],	
					"address"=>$shopinfo['address'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getShopinfoByShopid()
	 */
	public function getShopinfoByShopid($shopid) {
		// TODO Auto-generated method stub
		global $phonekey;
		global $pwdkey;
		if(empty($shopid)){return ;}
		$qarr=array("_id"=>new MongoId($shopid));
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['mobilphone'])){
				$phonecrypt = new CookieCrypt($phonekey);
				$mobilphone=$phonecrypt->decrypt($result['mobilphone']);
				
				$pwdcrypt = new CookieCrypt($pwdkey);
				$passwd=$pwdcrypt->decrypt($result['passwd']);
			}else{
				$mobilphone="未知";
			}
			$arr=array(
			        "shopid"=>$shopid,
					"mobilphone"=>$mobilphone,
					"passwd"=>$passwd,
					"shopname"=>$result['shopname'],
			        "district"=>$result['district'],
					"address"=>$result['city']." ".$result['district']." ".$result['road'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getOpenHourByShopid()
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
	 * @see IBossOneDAL::getOneShopRundata()
	 */
	public function getOneShopRundata($shopid, $startdate, $enddate, $datearr, $thehour) {
		// TODO Auto-generated method stub
		$arr=array();
		$lineChartarr=array();
		$newdatearr=array();
		$weekarray=array("日","一","二","三","四","五","六");
		$shopinfo=$this->getShopinfoByShopid($shopid);
		foreach ($datearr as $day){
			$cashmoney=0;
			$unionmoney=0;
			$vipmoney=0;
			$meituanpay=0;
			$dazhongpay=0;
			$nuomipay=0;
			$alipay=0;
			$wechatpay=0;
			$otherpay=0;
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
				$otherpay+=$val['otherpay'];
				$wechatpay+=$val['wechatpay'];
				if(!empty($val['ticketway'])&&!empty($val['ticketval'])&&!empty($val['ticketnum'])){
					$ticketmoney+=$val['ticketval']*$val['ticketnum'];
				}
			}
			$newdatearr[]=date("m-d",strtotime($day))." "."周".$weekarray[date("w",strtotime($day))];
			$arr[$day]=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;//$signmoney
		}
		if(!empty($arr)){
			$datasets[]=array(
					"label"=>"应收款",
					"fillColor"=>"rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",0.2)",
					"strokeColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointColor"=> "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"pointStrokeColor"=> "#fff",
					"pointHighlightFill" => "#fff",
					"pointHighlightStroke" => "rgba(".mt_rand(0, 255).",".mt_rand(0, 255).",".mt_rand(0, 255).",1)",
					"data" =>$arr,
			);
			$lineChartarr=array(
					"shopname"=>$shopinfo['shopname'],
					"labels"=>$newdatearr,
					"datasets"=>$datasets,
			);
		}
// 		print_r($lineChartarr);exit;
		return $lineChartarr;
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getShopPercentData()
	 */
	public function getShopPercentData($shoparr, $op) {
		// TODO Auto-generated method stub
		$piearr=array();
		foreach ($shoparr as $key=>$val){
			$cashmoney=0;
			$unionmoney=0;
			$vipmoney=0;
			$meituanpay=0;
			$dazhongpay=0;
			$nuomipay=0;
			$alipay=0;
			$wechatpay=0;
			$ticketmoney=0;
			$otherpay=0;
			$shouldmoney=0;
			if($op=="today"){
				$starttime=strtotime($val['theday']." ".$val['openhour'].":0:0");
				$endtime=strtotime($val['theday']." ".$val['openhour'].":0:0")+86400;
			}elseif($op=="yestoday"){
				$starttime=strtotime($val['theday']." ".$val['openhour'].":0:0")-86400;
				$endtime=strtotime($val['theday']." ".$val['openhour'].":0:0");
			}
			
			$qarr=array("shopid"=>$val['shopid'],"paystatus"=>"paid","timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
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
				$otherpay+=$val['otherpay'];
				if(!empty($val['ticketway'])&&!empty($val['ticketval'])&&!empty($val['ticketnum'])){
					$ticketmoney+=$val['ticketval']*$val['ticketnum'];
				}
			}
			$shouldmoney=$cashmoney+$unionmoney+$vipmoney+$meituanpay+$dazhongpay+$nuomipay+$otherpay+$alipay+$wechatpay+$ticketmoney;
			$color="rgba(".mt_rand(50, 255).",".mt_rand(50, 255).",".mt_rand(50, 255).",1)";
			$piearr[]=array(
					"value"=>$shouldmoney,
					"color"=>	$color,
					"highlight"=>$color,
					"label"=>$val['shopname'],
			);
		}
	
		return $piearr;
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getTheday()
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
	public function getTableRunStatus($shoparr){
		foreach ($shoparr as $key=>$val){
			$op="empty";
			$qarr=array("shopid"=>$val['shopid'],"tabstatus"=>$op);
			$emptynum=DALFactory::createInstanceCollection(self::$table)->count($qarr);
			
			$op="start";
			$qarr=array("shopid"=>$val['shopid'],"tabstatus"=>$op);
			$startnum=DALFactory::createInstanceCollection(self::$table)->count($qarr);
			
			$op="book";
			$qarr=array("shopid"=>$val['shopid'],"tabstatus"=>$op);
			$booknum=DALFactory::createInstanceCollection(self::$table)->count($qarr);
			
			$op="online";
			$qarr=array("shopid"=>$val['shopid'],"tabstatus"=>$op);
			$onlinenum=DALFactory::createInstanceCollection(self::$table)->count($qarr);
			$arr[$val['shopid']]=array(
					"shopname"=>$val['shopname'],
					"emptynum"=>$emptynum,
					"startnum"=>$startnum,
					"booknum"=>$booknum,
					"onlinenum"=>$onlinenum,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::saveVipCard()
	 */
	public function saveVipCard($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array(
				"bossid"=>$inputarr['bossid'],
				"cardname"=>$inputarr['cardname'],
				"cardrate"=>$inputarr['cardrate'],
				"carddiscount"=>$inputarr['carddiscount'],
				"cardlimit"=>$inputarr['cardlimit'],
				"pointfactor"=>$inputarr['pointfactor'],
		);
		DALFactory::createInstanceCollection(self::$vipcard)->save($qarr);
	}

	/* (non-PHPdoc)
	 * @see IBossOneDAL::updateOneVcd()
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
				));
		DALFactory::createInstanceCollection(self::$vipcard)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getVipcardList()
	 */
	public function getVipcardList($bossid) {
		// TODO Auto-generated method stub
		$qarr=array("bossid"=>$bossid);
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
	 * @see IBossOneDAL::delOneVcd()
	 */
	public function delOneVcd($vcid) {
		// TODO Auto-generated method stub
		if(empty($vcid)){return ;}
		$qarr=array("_id"=>new MongoId($vcid));
		DALFactory::createInstanceCollection(self::$vipcard)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getOneVcdData()
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
	 * @see IBossOneDAL::getViptagsData()
	 */
	public function getViptagsData($bossid) {
		// TODO Auto-generated method stub
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
	 * @see IBossOneDAL::saveOneTag()
	 */
	public function saveOneTag($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$viptag)->save($inputarr);
	}

	/* (non-PHPdoc)
	 * @see IBossOneDAL::updateOneTag()
	 */
	public function updateOneTag($viptagid, $inputarr) {
		// TODO Auto-generated method stub
		if(empty($viptagid)){return ;}
		$qarr=array("_id"=>new MongoId($viptagid));
		$oparr=array("\$set"=>array("tagname"=>$inputarr['tagname']));
		DALFactory::createInstanceCollection(self::$viptag)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getOneTagByTagid()
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
	 * @see IBossOneDAL::delOneTagByTagid()
	 */
	public function delOneTagByTagid($viptagid) {
		// TODO Auto-generated method stub
		if(empty($viptagid)){return ;}
		$qarr=array("_id"=>new MongoId($viptagid));
		DALFactory::createInstanceCollection(self::$viptag)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getBuyGoodsRecord()
	 */
	public function getBuyGoodsRecord($id,$type,$theday) {
		// TODO Auto-generated method stub
		$qarr=array("via"=>$type,"value"=>$id);
		$result=DALFactory::createInstanceCollection(self::$buy_goods_record)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
// 			$goodsinfo=$this->getOneGoodsInfo($val['goodsid']);
			$nowdate=date("Y-m-d",strtotime($val['buytime']));
			if($theday==$nowdate){
				$arr[]=array(
						"out_trade_no"=>$val['out_trade_no'],
						"trade_no"=>$val['trade_no'],
						"goodsid"=>$val['goodsid'],
						"goodsname"=>$val['goodsname'],
						"soldamount"=>$val['soldamount'],
						"soldunit"=>$val['soldunit'],
						"total_fee"=>$val['total_fee'],
						"buytime"=>$val['buytime'],
						"paytype"=>$val['paytype'],
							
				);
			}
		}
		return $arr;
		
	}
	/* (non-PHPdoc)
	 * @see IBossOneDAL::getOneGoodsInfo()
	 */
	public function getOneGoodsInfo($goodsid) {
		// TODO Auto-generated method stub
		if(empty($goodsid)){return array();}
		$qarr=array("_id"=>new MongoId($goodsid));
		$oparr=array("goodsname"=>1);
		$result=DALFactory::createInstanceCollection(self::$goods)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("goodsname"=>$result['goodsname']);
		}
		return $arr;
	}
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::saveBossFtype()
     */
    public function saveBossFtype($inputarr)
    {
        // TODO Auto-generated method stub
        DALFactory::createInstanceCollection(self::$boss_foodtype)->save($inputarr);
        $this->addBossFtypeToShopFtype($inputarr);
    }

    /**
     * {@inheritDoc}
     * @see IBossOneDAL::updateBossFtype()
     */
    public function updateBossFtype($ftid, $inputarr)
    {
        // TODO Auto-generated method stub
        $qarr=array("_id"=>new MongoId($ftid));
        $oparr=array("\$set"=>
            array(
                "ftname"=>$inputarr['ftname'],
                "ftcode"=>$inputarr['ftcode'],
                "sortno"=>$inputarr['sortno'],
            )
        );
        DALFactory::createInstanceCollection(self::$boss_foodtype)->update($qarr,$oparr);
        $this->updateToShopFtype($inputarr);
    }

    /**
     * {@inheritDoc}
     * @see IBossOneDAL::getBossFtypes()
     */
    public function getBossFtypes($bossid)
    {
        // TODO Auto-generated method stub
        $qarr=array("bossid"=>$bossid);
        return DALFactory::createInstanceCollection(self::$boss_foodtype)->find($qarr);        
    }

    /**
     * {@inheritDoc}
     * @see IBossOneDAL::delOneBOssFtype()
     */
    public function delOneBOssFtype($ftid,$ftcode,$bossid)
    {
        // TODO Auto-generated method stub
        $qarr=array("ftid"=>$ftid);
        $oparr=array("_id"=>1);
        $num=DALFactory::createInstanceCollection(self::$boss_food)->count($qarr);
        if($num>0){
            return array("status"=>"error");
        }else{
            $qarr=array("_id"=>new MongoId($ftid));
            DALFactory::createInstanceCollection(self::$boss_foodtype)->remove($qarr);
            $this->delSubshopFtype($ftcode,$bossid);
            return array("status"=>"ok");
        }
        
    }
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::addBossFtypeToShopFtype()
     */
    public function addBossFtypeToShopFtype($inputarr)
    {
        // TODO Auto-generated method stub
        $shopidarr=$this->getSubShopIds($inputarr['bossid']);
        foreach ($shopidarr as $shopid){
            $printerid=$this->getShopPrinter($shopid);
            $arr=array(
                "shopid"=>$shopid,
                "foodtypename"=>$inputarr['ftname'],
                "foodtypecode"=>$inputarr['ftcode'],
                "sortno"=>$inputarr['sortno'],
                "printerid"=>$printerid,
            );
            DALFactory::createInstanceCollection(self::$foodtype)->save($arr);
        }
    }

    /**
     * {@inheritDoc}
     * @see IBossOneDAL::updateToShopFtype()
     */
    public function updateToShopFtype($inputarr)
    {
        // TODO Auto-generated method stub
        $shopidarr=$this->getSubShopIds($inputarr['bossid']);
        foreach ($shopidarr as $shopid){
            $qarr=array("shopid"=>$shopid, "foodtypecode"=>$inputarr['ftcode']);
            $oparr=array("\$set"=>
             array(
                    "ftname"=>$inputarr['ftname'],
                    "ftcode"=>$inputarr['ftcode'],
                    "sortno"=>$inputarr['sortno'],
                )
            );
            DALFactory::createInstanceCollection(self::$foodtype)->update($qarr,$oparr);
        }
    }
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::getSubShopIds()
     */
    public function getSubShopIds($bossid)
    {
        // TODO Auto-generated method stub
        $qarr=array("bossid"=>$bossid);
        $oparr=array("shopid"=>1);
        $result=DALFactory::createInstanceCollection(self::$subaccount)->find($qarr,$oparr);
        $arr=array();
        foreach ($result as $key=>$val){
            $arr[]=$val['shopid'];
        }
        return $arr;
    }
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::getShopPrinter()
     */
    public function getShopPrinter($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr);
        $printerid="";
        if(!empty($result)){
            $printerid=strval($result['_id']);
        }
        return $printerid;
    }
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::delSubshopFtype()
     */
    public function delSubshopFtype($ftcode,$bossid)
    {
        // TODO Auto-generated method stub
        $shopidarr=$this->getSubShopIds($bossid);
        foreach ($shopidarr as $shopid){
            $qarr=array("shopid"=>$shopid, "foodtypecode"=>$ftcode);
            $oparr=array("_id"=>1);
            $result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
            if(!empty($result)){
                $foodtypeid=strval($result['_id']);
                $foodqarr=array("shopid"=>$shopid, "foodtypeid"=>$foodtypeid);
                $num=DALFactory::createInstanceCollection(self::$food)->count($foodqarr);
                if(empty($num)){
                    DALFactory::createInstanceCollection(self::$foodtype)->remove($qarr);
                }
            }
        }
       
        
    }
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::getPuhongShopZone()
     */
    public function getPuhongShopZone($bossid)
    {
        // TODO Auto-generated method stub
        $qarr=array("bossid"=>$bossid);
        $oparr=array("shopid"=>1);
        $result=DALFactory::createInstanceCollection(self::$subaccount)->find($qarr,$oparr);
        $arr=array();
        foreach ($result as $key=>$val){
            $shopinfo=$this->getShopinfoByShopid($val['shopid']);
            if(empty($shopinfo)){continue;}
            $arr[$shopinfo['district']][]=array(
                "shopid"=>$shopinfo['shopid'],
                "shopname"=>$shopinfo['shopname'],
            );
        }
        return $arr;
    }
    /**
     * {@inheritDoc}
     * @see IBossOneDAL::getFoodSoldnumByDist()
     */
    public function getFoodSoldnumByDist($shopidarr,$startdate,$enddate)
    {
        // TODO Auto-generated method stub
        $var=new MonitorOneDAL();
        $oneshopfoodarr=array();
        $arr=array();
        foreach ($shopidarr as $shopid){
            $oneshopfoodarr=$var->getFoodDataByFtid($shopid, array(), $startdate, $startdate);
            foreach ($oneshopfoodarr['data'] as $key=>$val){
                if(array_key_exists($val['foodname'], $arr)){
                     $newnumarr=array_merge($arr[$val['foodname']],$val['shopname']);
                     $arr[$val['foodname']]=array_unique($newnumarr);
                }else{
                    $arr[$val['foodname']]=$val['shopname'];
                }
            }
        }
        return $arr;
    }

}
?>