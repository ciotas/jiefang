<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IHandleDAL.php');
// require_once ('/var/www/html/DALFactory.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/DALFactory.php');
class HandleDAL implements IHandleDAL{
	private static $coll="bill";
	private static $billrecord="billrecord";
	private static $food="food";
	private static $cus="customer";
	private static $cusvip="cusvip";
	private static $printer="printer";
	private static $zone="zone";
	private static $table="table";
	private static $usercouponlib="usercouponlib";
	private static $shopinfo="shopinfo";
	private static $cuspoints="cuspoints";
	private static $remark="remark";
	private static $servers="servers";
	private static $queue="queue";
	private static $pointset="pointset";
	private static $shoppoints="shoppoints";
	private static $paymoneyset="paymoneyset";
	
	/* (non-PHPdoc)
	 * @see IHandleDAL::getType()
	 */
	public function getType($json) {
		// TODO Auto-generated method stub
		$arr=json_decode($json,true);
		if(is_array($arr)){
			if(!empty($arr)){
				return key($arr);
			}
		}
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::intoConsumeRecord()
	 */
	public function intoConsumeRecord($inputdarr) {
		// TODO Auto-generated method stub
		$arr=array(
				"orderno"=>$inputdarr['orderno'],
				"tradeno"=>$inputdarr['tradeno'],
				"uid"=>$inputdarr['uid'],
				"shopid"=>$inputdarr['shopid'],
				"nickname"=>$inputdarr['nickname'],
				"shopname"=>$inputdarr['shopname'],
				"wait"=>$inputdarr['wait'],
				"tabid"=>$inputdarr['tabid'],
				"takeout"=>$inputdarr['takeout'],
				"invoice"=>$inputdarr['invoice'],//发票
// 				"takeoutaddress"=>$inputdarr['takeoutaddress'],
				"orderrequest"=>$inputdarr['orderrequest'],//整单备注
				"discountype"=>$inputdarr['discountype'],
				"paytype"=>$inputdarr['paytype'],
				"paystatus"=>$inputdarr['paystatus'],
				"tabname"=>$inputdarr['tabname'],
				"cusnum"=>$inputdarr['cusnum'],
				"totalmoney"=>$inputdarr['totalmoney'],
				"disacountfoodmoney"=>$inputdarr['disacountfoodmoney'],
				"paymoney"=>$inputdarr['paymoney'],
				"timestamp"=>$inputdarr['timestamp'],//下单时间
				"billstatus"=>$inputdarr['billstatus'],
				"food"=>$inputdarr['food'],
				
		);
		PRINT_DALFactory::createInstanceCollection(self::$coll)->insert($arr);
		return strval($arr['_id']);
	}
	
	/* (non-PHPdoc)
	 * @see IHandleDAL::getTodayCuslist()
	 */
	public function getTodayCuslist($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"timestamp"=>array("\$gte"=>strtotime(date("Y-m-d",time()-0*60*60)),"\$lte"=>strtotime(date("Y-m-d",time()))+24*60*60));
// 		$qarr=array("shopid"=>$shopid);//正式时注释掉
// 		print_r($qarr);exit;
		$oparr=array("_id"=>1,"uid"=>1,"tabname"=>1,"paytype"=>1,"paystatus"=>1, "cusnum"=>1,"wait"=>1, "timestamp"=>1,"billstatus"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$userinfo=$this->getCusinfo($val['uid'],$shopid);
			if(floor((time()-$val['timestamp'])/7200)>0){
// 				$distime=strval(floor((time()-$val['time'])/3600))."小时前";
				$distime=date("m-d H:i",$val['timestamp']);
			}else{//2小时内显示分钟
				$distime=strval(floor((time()-$val['timestamp'])/60))."分钟前";
			}
			$remark=$this->getNameRemark($userinfo['uid'], $shopid);
			if(!empty($remark)){
				$remark="(".$remark.")";
			}
			if(!empty($val['paystatus'])){
			    $paystatus=$val['paystatus'];
			    if($paystatus=="finish_onlinepay"){
			        $paystatus="alipay";
			    }
			}else {$paystatus="alipay";}
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"uid"=>$userinfo['uid'],
					"nickname"=>$userinfo['nickname'].$remark,
					"sex" =>$userinfo['sex'],
					"photo"=>$userinfo['photo'],
					"time"=>$distime,
					"tabname"=>$val['tabname'],
					"cusnum"=>$val['cusnum'],
					"paytype"=>$paystatus,//alipay(green),finish_offlinepay,(green),offlinepay(red)
					"wait"=>$val['wait'],
// 					"billstatuscode"=>$billstatuscode,
// 					"billstatus"=>$billstatus,
			);
		}
		$arr=$this->array_sort($arr,"tabname","asc");
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getCusinfo()
	 */
	public function getCusinfo($uid,$shopid) {
		// TODO Auto-generated method stub
		global $square100;
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("nickname"=>1,"sex"=>1,"photo"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$cus)->findOne($qarr,$oparr);
		if(!empty($result['nickname'])){$nickname=$result['nickname'];}else{$nickname="保密";}
		if(!empty($result['sex'])){$sex=$result['sex'];}else{$sex="";}
		if(!empty($result['photo'])){$photo=$result['photo'];}else{$photo="http://jfoss.meijiemall.com/userphoto/default_userphoto.jpg";}
		$serverarr=$this->judgeTheServer($shopid, $uid);
// 		print_r($serverarr);exit;
		if(!empty($serverarr)){
			$nickname=$serverarr['servername']."[本店服务员]";
		}
		$arr=array(
				"uid"=>$uid,
				"nickname"=>$nickname,
				"sex"=>$sex,
				"photo"=>$photo.$square100
		);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getVipname()
	 */
	public function getVipname($shopid, $uid) {
		// TODO Auto-generated method stub
// 		$uid="53731884828a87162e8b456";
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("viptype"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$cusvip)->findOne($qarr,$oparr);
		if(is_array($result)&&!empty($result)){
			$viptype=$result['viptype'];
		}else{
			$viptype="";
		}
		switch (strval($viptype)){
			case "1":$vipname="普通会员";break;
			case "2":$vipname="白金会员";break;
			case "3":$vipname="黄金会员";break;
			default:$vipname="";break;
		}
		return $vipname;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getBillData()
	 */
	public function getBillData($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr);
// 		var_dump($result);exit;
		$arr=array();
		if(!empty($result)){
			if($result['wait']=="1"){$wait="等叫";}else{$wait="即食";}
			$thumblogo=$this->getthumbLogo($result['shopid']);
			foreach ($result['service']['service'] as $foodkey=>$foodval){
				if($foodval['chargestyle']=="1"){//按人计价
					$result['service']['service'][$foodkey]['num']=$result['cusnum'];
				}else{
					$result['service']['service'][$foodkey]['num']="1";
				}
			}
			$foodarr=$this->reOrderFoodList($result['food']['food']);
			$servicearr=$this->reOrderService($result['service']['service']);
// 			print_r($servicearr);exit;
			$shopaddressarr=$this->getShopAddress($result['shopid']);
			if(empty($shopaddressarr)){return array();}
// 			$paymoney=$this->calcPayMoney($result['food']['food'], $result['service']['service'], $result['discountzong']);
			$paymoney="0";
			$paysettype=$this->getPayMoneySet($result['shopid']);
			if($paysettype=="1"){
			    $paymoney=floor($paymoney);
			}elseif($paysettype=="2"){
			    $paymoney=round($paymoney);
			}
			$shopinoarr=$this->getShopBriefInfo($result['shopid']);
			//里面的参数都有用
			$arr=array(
					"billid"=>$billid,
					"shopid"=>$result['shopid'],
					"shopname"=>$result['shopname']." ".$result['branchname'],
					"address"=>$shopaddressarr['city']." ".$shopaddressarr['district']." ".$shopaddressarr['road'],
					"thumblogo"=>$thumblogo,
					"tradeno"=>$result['tradeno'],
					"wait"=>$wait,
					"nickname"=>$result['nickname'],
					"tabname"=>$result['tabname'],
					"cusnum"=>$result['cusnum'],
					"type"=>$result['type'],
					"paytype"=>$result['paytype'],
			        "paystatus"=>$result['paystatus'],
// 					"discountexplain"=>$discountexplain,
			        "discountexplain"=>$result['discountdesc'],
					"discounttitle"=>$result['discounttitle'],
					"discountvalue"=>$result['discountvalue'],
					"totalmoney"=>$result['totalmoney'],
					"disacountmoney"=>$result['disacountmoney'],
					"disacountfoodmoney"=>$result['disacountfoodmoney'],
					"paymoney"=>sprintf("%.0f",$result['totalmoney']),
					"tradeno"	=>$result['tradeno'],
					"time" =>date("Y-m-d H:i:s",$result['timestamp']),
					"thumblogo"=>$shopinoarr['thumblogo'],
					"food"=>$foodarr,
					"service"=>$servicearr,
			       "allfood"=>$result['food']['food'],
			);
		}
// 		//修改paymoney
// 		if(!empty($paymoney)){
// 		    $oparr=array("\$set"=>array("paymoney"=>$paymoney));
// 		    PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
// 		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getBillDataByView()
	 */
	public function getBillDataByView($uid, $shopid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid,"time"=>array("\$gte"=>strtotime(date("Y-m-d",time()))-2*60*60,"\$lte"=>strtotime(date("Y-m-d",time()))+2*60*60));
		$oparr=array("tradeno"=>1,"timestamp"=>1,"tabname"=>1,"cusnum"=>1,"wait"=>1,"food"=>1,"service"=>1,"points"=>1,"viprate"=>1,"couponnum"=>1,"coupon"=>1,"totalmoney"=>1,"disacountmoney"=>1,"paymoney"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if($result['wait']=="1"){$wait="等叫";}else{$wait="";}
			$arr=array(
					"tradeno"	=>$result['tradeno'],
					"time" =>date("Y-m-d H:i:s",$result['timestamp']),
					"tabname"=>$result['tabname'],
					"cusnum"=>$result['cusnum'],
					"wait"  =>$wait,
					"points"=>$result['points'],
					"viprate"=>$result['viprate'],
					"couponnum"=>$result['couponnum'],
					"coupon"=>$result['coupon'],
					"totalmoney"=>$result['totalmoney'],
					"disacountmoney"=>$result['disacountmoney'],
					"paymoney"=>$result['paymoney'],
					"food"=>$result['food'],
					"service"=>$result['service']
			);
		}
		return json_encode($arr);
	}

	/* (non-PHPdoc)
	 * @see IHandleDAL::switchTable()
	 */
	public function switchTable($billid, $tabname) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("tabname"=>$tabname,"wait"=>"0"));
		PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::ChangeTableSheetContent()
	 */
	public function ChangeTableSheetContent($inputarr) {
		// TODO Auto-generated method stub
		$shopprinters=$this->getShopAllPrinters($inputarr['shopid']);
		$arr=array();
		foreach ($shopprinters as $val){
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"outputtype"=>"8",
					'msg'=>$this->getPrintContent($inputarr,$val)
			);
		}
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IHandleDAL::getApiCodeAry()
	 * 换台时所有打印机均出单
	 */
	public function getApiCodeAry($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"outputtype"=>array("\$in"=>array("3","4","5","6")));
		$oparr=array("deviceid"=>1);
		$arr=array();
		$result=PRINT_DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			if(!in_array($val['deviceid'], $arr)){
				$arr[]=$val['deviceid'];
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getPrintContent()
	 */
	public function getPrintContent($inputarr,$arr) {
		// TODO Auto-generated method stub	
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<NCB>换台单</NCB>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='顾客：'.$inputarr['nickname'].'<BR>';
		$orderInfo.='人数：'.$inputarr['cusnum'].' <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.="<NB>台号 ".$inputarr['oldtabname']." 换为 ".$inputarr['newtabname'].'</NB><BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'clientCode'=>$arr['deviceno'],
				'printInfo'=>$orderInfo,
				'apitype'=>'php',
				'key'=>$arr['devicekey'],
				'printTimes'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getSureTableSheetContent()
	*/
	public function getSureTableSheetContent($inputarr) {
		// TODO Auto-generated method stub
		$shopprinters=$this->getShopAllPrinters($inputarr['shopid']);
		$arr=array();
		foreach ($shopprinters as $val){
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"outputtype"=>"9",
					'msg'=>$this->getSureTabPrintContent($inputarr,$val)
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getPrinterZoneName()
	 */
	public function getPrinterZoneName($shopid,$deviceno) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"deviceno"=>$deviceno);
		$oparr=array("pos"=>1);
		$pos=PRINT_DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(is_array($pos)&&!empty($pos)){
			$zoneid=$pos['pos'];
			$qarr=array("_id"=>new MongoId($zoneid));
			$oparr=array("zonename"=>1);
			$result=PRINT_DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
			if(is_array($result)&&!empty($result)){
				return $result['zonename'];
			}else{
				return "";
			}
		}else{
			return "";
		}
		
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getLastBillData()
	 */
	public function getLastBillData($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"timestamp"=>array("\$gte"=>strtotime(date("Y-m-d",time())),"\$lte"=>strtotime(date("Y-m-d",time()))+24*60*60));
		$oparr=array("_id"=>1,"timestamp"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$billid="";
		foreach ($result as $key=>$val){
			$billid=strval($val['_id']);
			break;
		}
// 		echo $billid;exit;
// 		$billid="546579bf16c1090a058b45f8";
		if(!empty($billid)){
			return $this->getBillData($billid);
		}else{
			return array();
		}
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::returnCoupon()
	 */
	public function returnCoupon($uid, $shopid, $couponvalue, $couponnum) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid,"couponvalue"=>$couponvalue);
		PRINT_DALFactory::createInstanceCollection(self::$usercouponlib)->update($qarr,array("\$inc"=>array("couponnum"=>-intval($couponnum))));
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getthumbLogo()
	 */
	public function getthumbLogo($shopid) {
		// TODO Auto-generated method stub
		global $square100;
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("thumblogo"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['thumblogo'])){$thumblogo=$result['thumblogo'];}else{$thumblogo="http://jfoss.meijiemall.com/logo/default_shop_logo.jpg".$square100;}
		}
		return $thumblogo;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::reOrderFoodList()
	 */
	public function reOrderFoodList($arr) {
		// TODO Auto-generated method stub
// 		print_r($arr);exit;
		$sendarr=array();
		foreach ($arr as $key=>$val){
			if( $val['cooktype']=="普通" &&empty($val['foodrequest'])){
				$remark="";
			}elseif($val['cooktype']!="普通" &&empty($val['foodrequest'])){
				$remark="(".$val['cooktype'].")";
			}elseif ( $val['cooktype']=="普通" &&!empty($val['foodrequest'])){
				$remark="(".$val['foodrequest'].")";
			}else{
				$remark="(".$val['cooktype']."、".$val['foodrequest'] .")";
			}
			$calcmoney=strval($val['foodamount']).$val['foodunit']."*".strval(sprintf("%.2f",$val['foodprice']))."=".sprintf("%.2f", floatval($val['foodprice'])*floatval($val['foodamount']))."元";
			$sendarr[]=array(
					"foodid"=>$val['foodid'],
					"foodname"=>$val['foodname'],
					"foodprice"=>$val['foodprice'],
					"foodunit"=>$val['foodunit'],
					"remark"=>$remark,
					"calcmoney"	=>$calcmoney,
			);
		}
		return $sendarr;
	}

	/* (non-PHPdoc)
	 * @see IHandleDAL::reOrderService()
	 */
	public function reOrderService($arr) {
		// TODO Auto-generated method stub
// 		print_r($arr);exit;
		$sendarr=array();
		foreach ($arr as $key=>$val){
			$sendarr[]=array(
					"servicename"=>$val['itemname'],
					"serviceunit"=>"份",
					"servicemoney" =>strval($val['num'])."份*".strval(sprintf("%.2f",$val['itemprice']))."=".sprintf("%.2f",$val['num']*$val['itemprice'])."元",
			);
		}
		return $sendarr;	
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getShopBriefInfo()
	 */
	public function getShopBriefInfo($shopid) {
		// TODO Auto-generated method stub
		global $square50;
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1,"logo"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['shopname'])){$shopname=$result['shopname'];}else{$shopname="";}
			if(!empty($result['logo'])){$logo=$result['logo'];}else{$logo="jfoss.meijiemall.com/logo/default_shop_logo.jpg";}
			$arr=array(
					"shopname"=>$shopname,
					"thumblogo"=>$logo.$square50
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getShopAllPrinters()
	 */
	public function getShopAllPrinters($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"openorclose"=>"open");
		$oparr=array("deviceno"=>1,"devicekey"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		$devicenoarr=array();
		foreach ($result as $key=>$val){
		    if(!in_array($val['deviceno'], $devicenoarr)){
		        $arr[]=array(
					"deviceno"	=>$val['deviceno'],
					"devicekey"=>$val['devicekey']
			     );
		        $devicenoarr[]=$val['deviceno'];
		    }
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getShopAddress()
	 */
	public function getShopAddress($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("city"=>1,"district"=>1,"road"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['city'])){$city=$result['city'];}else{$city="";}
			if(!empty($result['district'])){$district=$result['district'];}else{$district="";}
			if(!empty($result['road'])){$road=$result['road'];}else{$road="";}
			$arr=array(
					"city"=>$city,	
					"district"=>$district,
					"road"=>$road
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::addPointsToUser()
	 */
	public function addPointsToUser($uid, $shopid,$paymoney) {
		// TODO Auto-generated method stub
		$pointsetarr=$this->getPointSetByShopid($shopid);
		if(empty($pointsetarr)){
			$point=ceil($paymoney);
		}else{
			$point=ceil((ceil($paymoney)*$pointsetarr['point'])/$pointsetarr['money']);
		}
		$point=intval($point);
		$qarr=array("uid"=>$uid,"shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$cuspoints)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$inc"=>array("points"=>$point));
			PRINT_DALFactory::createInstanceCollection(self::$cuspoints)->update($qarr,$oparr);
		}else{
			$arr=array("uid"=>$uid,"shopid"=>$shopid, "points"=>$point);
			PRINT_DALFactory::createInstanceCollection(self::$cuspoints)->insert($arr);
		}
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getNameRemark()
	 */
	public function getNameRemark($uid, $shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"remarklist.uid"=>$uid);
		$oparr=array("remarklist.$.remark"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$remark)->findOne($qarr,$oparr);
		if(!empty($result)){
			return trim($result['remarklist'][0]['remark']);
		}else{
			return "";
		}
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getAllShopId()
	 */
	public function getAllShopId() {
		// TODO Auto-generated method stub
		$qarr=array();
		$oparr=array("_id"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$shopinfo)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=strval($val['_id']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::intoBillRecord()
	 */
	public function intoBillRecord($inputdarr, $billstatus) {
		// TODO Auto-generated method stub
		$arr=array(
				"uid"=>$inputdarr['uid'],
				"nickname"=>$inputdarr['nickname'],
				"shopid"=>$inputdarr['shopid'],
				"shopname"=>$inputdarr['shopname'],
				"branchname"=>$inputdarr['branchname'],
				"wait"=>$inputdarr['wait'],
				"type"=>$inputdarr['type'],
				"paytype"=>$inputdarr['paytype'],
				"tabname"=>$inputdarr['tabname'],
				"cusnum"=>$inputdarr['cusnum'],
				"discountvalue"=>$inputdarr['discountvalue'],
				"discounttitle"=>$inputdarr['discounttitle'],
				"totalmoney"	=>$inputdarr['totalmoney'],
				"disacountfoodmoney"=>$inputdarr['disacountfoodmoney'],//可优惠部分
				"disacountmoney"=>$inputdarr['disacountmoney'],
				"paymoney" =>$inputdarr['paymoney'],
				"time" =>intval($inputdarr['time']),
				"billstatus"=>$billstatus,
				"tradeno"=>$inputdarr['tradeno'],
				"food"=>$inputdarr['food'],
				"service"=>$inputdarr['service']
		);
		PRINT_DALFactory::createInstanceCollection(self::$billrecord)->insert($arr);
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getSureTabPrintContent()
	 */
	public function getSureTabPrintContent($inputarr, $arr) {
		// TODO Auto-generated method stub
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>通知单</CB>';
		$orderInfo .= '!0a1d@!<BR>';
// 		$orderInfo.='顾客：'.$inputarr['nickname'].'<BR>';
// 		$orderInfo.='人数：'.$inputarr['cusnum'].' <BR>';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='<B>顾客（'.$inputarr['nickname'].'）已在'.$inputarr['newtabname'].'就坐，可以上菜了。</B><BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'clientCode'=>$arr['deviceno'],
				'printInfo'=>$orderInfo,
				'apitype'=>'php',
				'key'=>$arr['devicekey'],
				'printTimes'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::finishPayStatus()
	 */
	public function finishPayStatus($billid, $paymoney,$paytype,$coupontype,$coupontypevalue,$coupontypenum,$status) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		if(!empty($coupontypenum)&&!empty($coupontypevalue)){
			$oparr=array("\$set"=>array(
				"paymoney"=>$paymoney,
				"disacountmoney"=>sprintf("%.2f",$coupontypevalue*$coupontypenum),
				"discounttitle"=>$coupontype,
				"discountvalue"=>strval($coupontypevalue."*".$coupontypenum),
		        "paytype"=>$paytype,
			    "paystatus"=>$status,
				"discountzong"=>sprintf("%.2f",$coupontypevalue*$coupontypenum),
			    "discountdesc"=>$coupontypevalue."元×".$coupontypenum,
				"type"=>"coupon",
// 				"paytype"=>$status,
				"timestamp"=>time()
			));
		}else{
			$oparr=array("\$set"=>array(
				"paymoney"=>$paymoney,
				"paytype"=>$paytype,
			    "paystatus"=>$status,
				"timestamp"=>time()
			));
		}
		
		PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::RmOneBillData()
	 */
	public function RmOneBillData($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		PRINT_DALFactory::createInstanceCollection(self::$coll)->remove($qarr);
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::updateBillPayStatus()
	 */
	public function updateBillPayStatus($billid, $status,$paystatus) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		if(empty($status)){
		    $oparr=array("\$set"=>array("paystatus"=>$paystatus));
		}else{
		    $oparr=array("\$set"=>array("paytype"=>$status,"paystatus"=>$paystatus));
		}
		PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getOneBillById()
	 */
	public function getOneBillById($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::judgeTheServer()
	 */
	public function judgeTheServer($shopid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("telphone"=>1);
		$cusresult=PRINT_DALFactory::createInstanceCollection(self::$cus)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($cusresult)){
			$telphone=$cusresult['telphone'];
			$qarr=array("serverphone"=>$telphone,"shopid"=>$shopid);
			$oparr=array("serverphone"=>1,"servername"=>1);
			$serresult=PRINT_DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
			if(!empty($serresult)){
				$arr=array(
						"servername"=>$serresult['servername'],
				);
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::updateTabStatus()
	 */
	public function updateTabStatus($shopid,$tabname, $usestatus) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"tabname"=>$tabname);
		$oparr=array("\$set"=>array("usestatus"=>$usestatus));
		PRINT_DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IHandleDAL::getPointSetByShopid()
	 */
	public function getPointSetByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("money"=>1,"point"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$pointset)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("money"=>$result['money'],"point"=>$result['point']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
     * @see IHandleDAL::getShopPointSet()
     */
    public function getShopPointSet($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("type"=>1,"points"=>1,"num"=>1,"thing"=>1);
        $result=PRINT_DALFactory::createInstanceCollection(self::$shoppoints)->findOne($qarr,$oparr);
        $arr=array();
        if(!empty($result['points'])){
            $arr=array(
                "type"=>$result['type'],
                "points"=>$result['points'],
                "num"=>$result['num'],
                "thing"=>$result['thing'],
            );
        }
        return $arr;
    }

	/* (non-PHPdoc)
     * @see IHandleDAL::getFoodnameByFid()
     */
    public function getPoints($shopid,$uid)
    {
        // TODO Auto-generated method stub
       $qarr=array("uid"=>$uid,"shopid"=>$shopid);
		$oparr=array("points"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$cuspoints)->findOne($qarr,$oparr);
		$points=0;
		if(!empty($result)){
			$points= $result['points'];
		}
		return $points;
        
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::MinusMyPoint()
     */
    public function MinusMyPoint($uid, $shopid, $minuspoint)
    {
        // TODO Auto-generated method stub
        $qarr=array("uid"=>$uid,"shopid"=>$shopid);
        $oparr=array("\$inc"=>array("points"=>-intval($minuspoint)));
        PRINT_DALFactory::createInstanceCollection(self::$cuspoints)->update($qarr,$oparr);
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::getFoodnameByFid()
     */
    public function getFoodnameByFid($foodid)
    {
        // TODO Auto-generated method stub
        $qarr=array("_id"=>new MongoId($foodid));
        $oparr=array("foodname"=>1,"foodunit"=>1);
        $result=PRINT_DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
        $arr=array();
        if(!empty($result)){
            $arr=array("foodname"=>$result['foodname'],"foodunit"=>$result['foodunit']);
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::comBineBill()
     */
    public function comBineBill($billid,$uid)
    {
        // TODO Auto-generated method stub
        $qarr=array("uid"=>$uid,"paystatus"=>"billdone", "timestamp"=>array("\$gte"=>strtotime(date("Y-m-d",time()))+4*3600,"\$lte"=>strtotime(date("Y-m-d",time()))+24*60*60+4*3600));//凌晨四点到第二天凌晨四点
        $oparr=array("_id"=>1,"timestamp"=>1);
        $result=PRINT_DALFactory::createInstanceCollection(self::$coll)->find($qarr,$oparr);
        $oldbillid="";
        foreach ($result as $key=>$val){
            if($billid!=strval($val['_id'])){
                $oldbillid=strval($val['_id']);break;
            }
        }
        if(!empty($oldbillid)){
            $this->comBineBillTogether($billid, $oldbillid);
        }
       
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::comBineBillTogether()
     */
    public function comBineBillTogether($billid,$oldbillid)
    {
        // TODO Auto-generated method stub
        $qarr1=array("_id"=>new MongoId($billid));
        $oparr=array("totalmoney"=>1,"disacountfoodmoney"=>1,
            "disacountmoney"=>1,"discountzong"=>1,"paymoney"=>1,"food"=>1,"service"=>1);
        $result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr1,$oparr);
        $arr=array();
        if(!empty($result)){
            $arr=array(
                "totalmoney"  =>$result['totalmoney'],
                "disacountfoodmoney"=>$result['disacountfoodmoney'],
                "disacountmoney"=>$result['disacountmoney'],
                "discountzong"=>$result['discountzong'],
                "paymoney"=>$result['paymoney'],
                "food"=>$result['food'],
                "service"=>$result['service']
            );
        }
        $this->IntoTheOldBill($billid, $oldbillid, $arr);
       
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::IntoTheOldBill()
     */
    public function IntoTheOldBill($billid,$oldbillid, $dataarr)
    {
        // TODO Auto-generated method stub
        if(empty($dataarr)){return ;}
        $qarr1=array("_id"=>new MongoId($billid));
        $qarr2=array("_id"=>new MongoId($oldbillid));
        $oparr=array("totalmoney"=>1,"disacountfoodmoney"=>1,
            "disacountmoney"=>1,"discountzong"=>1,"paymoney"=>1,"food"=>1,"service"=>1);
        
        $result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr2,$oparr);
        if(!empty($result)){
//             print_r($result['food']);exit;
            $resultfoodarr=array();
            $resultserverarr=array();
            foreach ($result['food']['food'] as $val1){
                $resultfoodarr['food'][]=$val1;
            }
            foreach ($dataarr['food']['food'] as $val2){
                $resultfoodarr['food'][]=$val2;
            }
            foreach ($result['service']['service'] as $val3){
                $resultserverarr['service'][]=$val3;
            }
            foreach ($dataarr['service']['service'] as $val4){
                $resultserverarr['service'][]=$val4;
            }
            
//             $resultfoodarr['food']=array_merge($result['food']['food'],$dataarr['food']['food']);
//             $resultserverarr['service']=array_merge($result['service']['service'],$dataarr['service']['service']);
//             print_r($resultserverarr);exit;
            $oparr=array(
                "\$set"=>array(
                    "totalmoney"  =>strval(floatval($result['totalmoney'])+floatval($dataarr['totalmoney'])),
                    "disacountfoodmoney"=>strval(floatval($result['disacountfoodmoney'])+floatval($dataarr['disacountfoodmoney'])),
                    "disacountmoney"=>strval(floatval($result['disacountmoney'])+floatval($dataarr['disacountmoney'])),
                    "discountzong"=>strval(floatval($result['discountzong'])+floatval($dataarr['discountzong'])),
                    "paymoney"=>strval(floatval($result['paymoney'])+floatval($dataarr['paymoney'])),
                    "food"=>$resultfoodarr,
                    "service"   =>$resultserverarr,
                )
            );
        }
//         print_r($qarr2);exit;
        PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr2,$oparr);
        PRINT_DALFactory::createInstanceCollection(self::$coll)->remove($qarr1);
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::calcPayMoney()
     */
    public function calcPayMoney($foodarr,$servicearr,$discountmoney)
    {
        // TODO Auto-generated method stub
//         print_r($servicearr);exit;
        $foodtotalmoney=0;
        $servicetotalmoney=0;
        foreach ($foodarr as $fkey=>$fval){
            $foodtotalmoney+=$fval['foodprice']*$fval['foodamount'];
        }
        foreach ($servicearr as $skey=>$sval){
            if($sval['chargestyle']=="1"){
                $servicetotalmoney+=$sval['itemprice']*$sval['num'];
            }else{
                $servicetotalmoney+=$sval['itemprice']*1;
            }
        }
        if($discountmoney<=0.1){
            $paymoney=$foodtotalmoney+$servicetotalmoney;
        }else{
            $paymoney=$foodtotalmoney+$servicetotalmoney-$discountmoney;
        }
        
        return strval($paymoney);
    }
    
    public function changeBillStatus($billid,$paystatus,$paytype){
        $qarr=array("_id"=>new MongoId($billid));
        $oparr=array("\$set"=>array("paystatus"=>$paystatus,"paytype"=>$paytype));
        PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
    }
	/* (non-PHPdoc)
     * @see IHandleDAL::getPayMoneySet()
     */
    public function getPayMoneySet($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("type"=>1);
        $arr=array();
        $type="1";//默认抹零
        $result=PRINT_DALFactory::createInstanceCollection(self::$paymoneyset)->findOne($qarr,$oparr);
        if(!empty($result)){
            $type=$result['type'];
        }
        return $type;
    }
    /* (non-PHPdoc)
     * @see IHandleDAL::updateBillFoodByBillid()
     */
    public function updateBillFoodByBillid($billid,$totalmoney,$paymoney,$disacountfoodmoney,$oldfoodarr,$foodarr){
        $pushfoodarr=array();
//         print_r($oldfoodarr);exit;
        foreach ($foodarr['food'] as $key=>$val){
            $totalmoney+=$val['foodamount']*$val['foodprice'];
            $paymoney+=$val['foodamount']*$val['foodprice'];
            if($val['fooddisaccount']=="1"){
                $disacountfoodmoney+=$val['foodamount']*$val['foodprice'];
            }
            $pushfoodarr[]=array(
                "foodrequest"=>$val['foodrequest'],
                "printerid"=>$val['printerid'],
                "fooddisaccount"=>$val['fooddisaccount'],
                "foodamount"=>$val['foodamount'],
                "cooktype"=>$val['cooktype'],
                "zonename"=>$val['zonename'],
                "foodguqing"=>$val['foodguqing'],
                "foodprice"=>$val['foodprice'],
                "foodname"=>$val['foodname'],
                "foodunit"=>$val['foodunit'],
                "foodid"=>$val['foodid']
            );
        }
        foreach ($oldfoodarr as $arrkey=>$arrval){
            $pushfoodarr[]=$arrval;
        }
//         print_r($pushfoodarr);exit;
        $qarr=array("_id"=>new MongoId($billid));
        $oparr=array(
            "\$set"=>array(
                "totalmoney"=>strval(sprintf("%.2f",$totalmoney)),
                "paymoney"=>strval(sprintf("%.2f",$paymoney)),
                "disacountfoodmoney"=>strval(sprintf("%.2f",$disacountfoodmoney)),
                "food.food"=>$pushfoodarr
            ),
               
        );
//         print_r($oparr);exit;
        PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
    }
    
    public function intoBillFood($billid,$foodarr){
    	$addarr=array();
    	foreach ($foodarr as $key=>$val){
    		$foodinfoarr=$this->getFoodnameByFid($val['foodid']);
    		//     		print_r($foodinfoarr);exit;
    		if($val['isfree']=="0"){$foodprice=$foodinfoarr['foodprice'];}else{$foodprice="0";}
    		$zonename=$this->getZoneNameByZid($foodinfoarr['foodzone']);
    		//     		echo $zonename;exit;
    		$addarr[]=array(
    				"foodid"=>$val['foodid'],
    				"cooktype"=>"",
    				"foodamount"=>$val['foodnum'],
    				"foodguqing"=>"0",
    				"foodname"=>$foodinfoarr['foodname'],
    				"foodprice"=>$foodprice,
    				"foodrequest"=>"",
    				"foodunit"=>$foodinfoarr['foodunit'],
    				"printerid"=>$foodinfoarr['printerid'],
    				"zonename" =>$zonename,
    		);
    	}
    	//     	print_r($addarr);exit;
    	$qarr=array("_id"=>new MongoId($billid));
    	$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr);
    	//     	print_r($result);exit;
    	$newfooarr=array_merge($result['food']['food'],$addarr);
    	//     	print_r($arr);exit;
    	$this->updateFoodBill($billid, $newfooarr);
    }
    public function getZoneNameByZid($zid){
    	$qarr=array("_id"=>new MongoId($zid));
    	$oparr=array("zonename"=>1);
    	$zonename="";
    	$result=PRINT_DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
    	if(!empty($result)){
    		$zonename=$result['zonename'];
    	}
    	return $zonename;
    }
    public function updateFoodBill($billid,$arr){
    	$qarr=array("_id"=>new MongoId($billid));
    	$oparr=array("\$set"=>array("food"=>array("food"=>$arr)));
    	PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
    }
    public function getPrinters($shopid){
    	$qarr=array("shopid"=>$shopid);
    	$oparr=array("deviceno"=>1,"devicekey"=>1,"outputtype"=>1);
    	$arr=array();
    	$result=PRINT_DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
    	foreach ($result as $key=>$val){
    		$arr[]=array("deviceno"=>$val['deviceno'],"devicekey"=>$val['devicekey'],"outputtype"=>$val['outputtype']);
    	}
    	return $arr;
    }
    public function array_sort($arr, $keys, $type = 'asc')
    {
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
	 * @see IHandleDAL::getOldBillidByTabname()
	 */
	public function getTwoBillidByTabname($tabname,$shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid, "tabname"=>$tabname, "timestamp"=>array("\$gte"=>time()-2*3600));//
		$oparr=array("_id"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(2);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=strval($val['_id']);
		}
		if(count($arr)==2){
			$this->comBineBillTogether($arr[0], $arr[1]);
		}
	}


}
?>