<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IWorkerDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
class WorkerDAL implements IWorkerDAL{
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
	private static $wechat_user_info="wechat_user_info";
	private static $returnfoodrecord="returnfoodrecord";
	private static $switchfoodrecord="switchfoodrecord";
	private static $switchtabrecord="switchtabrecord";
	private static $replacefoodrecord="replacefoodrecord";
	private static $change_tabstatus_record="change_tabstatus_record";
    private static $monthstock="monthstock";
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getTablenameByTabid()
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
	 * @see IWorkerDAL::getTheTabPaid()
	 */
	public function getTheTabPaid($tabid, $shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabstatus"=>1);
		$tabstatus="empty";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result['tabstatus'])){
			if($result['tabstatus']!="start"){
				return "0";
			}else{
				return "1";
			}
		}
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::createVirtualTab()
	 */
	public function createVirtualTab($tabid) {
		// TODO Auto-generated method stub
		$tabarr=$this->getTabInfoByTabid($tabid);
		$tmparr=$this->getVirtualTabnum($tabid);
		if($tmparr['status']=="use"){
			$motherid=$tmparr['motherid'];
			$mothertabname=$this->getTabInfoByTabid($motherid);
			$tabname=$mothertabname['tabname']."-".($tmparr['vnum']+1);
			
		}elseif ($tmparr['status']=="nouse"){
			$mothertabname=$this->getTabInfoByTabid($motherid);
			$tabname=$mothertabname['tabname']."-1";
			$motherid=$tabid;
		}
		$arr=array(
				"shopid"=>$tabarr['shopid'],
				"tabname"=>$tabname,
				"seatnum"=>$tabarr['seatnum'],
				"tabstatus"=>"start",//开台
				"tabswitch"=>"1",
				"tablowest"=>$tabarr['tablowest'],	
				"zoneid"=>$tabarr['zoneid'],
				"motherid"=>$motherid,
				"tag"=>"tmp",//暂时的
		);
		DALFactory::createInstanceCollection(self::$table)->save($arr);
		return array("tabid"=>strval($arr['_id']),"tabname"=>$tabname);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getTabInfoByTabid()
	 */
	public function getTabInfoByTabid($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabname"=>1,"shopid"=>1,"seatnum"=>1,"tablowest"=>1,"zoneid"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array(
					"shopid"	=>$result['shopid'],
					"tabname"=>$result['tabname'],
					"seatnum"=>$result['seatnum'],
					"tablowest"=>$result['tablowest'],
					"zoneid"=>$result['zoneid'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getVirtualTabnum()
	 */
	public function getVirtualTabnum($tabid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($tabid), "tabstatus"=>array("\$in"=>array("online","start","book")));
		$oparr=array("_id"=>1,"tag"=>1,"motherid"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result)){//已经有人用
			if(empty($result['tag'])){
				$mothertabid=$tabid;
			}elseif($result['tag']=="tmp"){
				$mothertabid=$result['motherid'];
			}
			$qarr=array("motherid"=>$mothertabid,"tag"=>"tmp");
			$vnum= DALFactory::createInstanceCollection(self::$table)->count($qarr);
			return array("status"=>"use","vnum"=>$vnum,"motherid"=>$mothertabid);
		}else{
			return array("status"=>"nouse","vnum"=>0);
		}
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOneBillByBillid()
	 */
	public function getOneBillByBillid($billid,$token) {
		// TODO Auto-generated method stub
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			if($result['wait']=="1"){$waitstr="等叫";}else{$waitstr="即食";}
			if($result['takeout']=="1"){$takeoutstr="外卖单";}else{$takeoutstr="";}
			$tabname=$this->getTablenameByTabid($result['tabid']);
			$totalarr=$this->getTotalmoneyAndFoodDiscountmoney($billid);
			$deposit=$this->getOneDesposit($billid);
			$depositmoney="0";
			if($deposit=="1"){
				$depositmoney=$this->getDepositmoney($result['shopid']);
			}
			
			$arr=array(
					"billid"=>$billid,
					"nickname"=>$result['nickname'],
					"takeout"=>$takeoutstr,
					"tabname"=>$tabname,
					"cusnum"=>$result['cusnum'],
					"wait"=>$waitstr,
					"orderrequest"=>$result['orderrequest'],
					"totalmoney"=>$totalarr['totalmoney']+$depositmoney,
					"depositmoney"=>$depositmoney,
					"timestamp"=>date("Y-m-d H:i:s",$result['timestamp']),
					"token"=>$token,
					"food"=>$result['food'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getCusInfo()
	 */
	public function getCusInfo($uid,$shopid) {
		// TODO Auto-generated method stub
		global $square100;
		if(empty($uid)){return array();}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("nickname"=>1,"photo"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['photo'])){$photo=$result['photo'].$square100;}else{$photo="";}
// 			$isserver=$this->isServer($uid, $shopid);
// 			if($isserver){$nickname=$result['nickname'];}else{$nickname=$result['nickname'];}
			$nickname=$this->emoji2str($result['nickname']);
			$arr=array(
					"nickname"=>$nickname,
					"photo"=>$photo,
			);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IWorkerDAL::getShopInfo()
	 */
	public function getShopInfo($shopid) {
		// TODO Auto-generated method stub
		global $square100;
		global $phonekey;
		$qarr=array("_id"=>new MongoId($shopid));
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['logo'])){$logo=$result['logo'].$square100;}else{$logo="";}
			$mobilphone=$result['mobilphone'];
			$phonecrypt = new CookieCrypt($phonekey);
			$mobilphone=$phonecrypt->decrypt($mobilphone);
			$arr=array(
			    "mobilphone"=>$mobilphone,
			    "shopname"=>$result['shopname'],
			    "logo"=>$logo,
			    "loc"=>$result['loc'],
			    "province"=>$result['province'],
			    "city"=>$result['city'],
			    "district"=>$result['district'],
			    "road"=>$result['road'],
			    "servicephone"=>$result['servicephone'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::isServer()
	 */
	public function isServer($uid,$shopid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::updateBillFood()
	 */
	public function updateBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype) {
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
				$returnamount=($val['foodamount']/$val['foodnum'])*$returnnum;
				if($val['foodnum']>$returnnum &&$val['foodamount']>$returnamount ){
					$foodarr[$key]['foodamount']=strval($val['foodamount']-$returnamount);
					$foodarr[$key]['foodnum']=strval($val['foodnum']-$returnnum);
				}else{
					unset($foodarr[$key]);//完全退，则删除此美食
				}
				$paystatus=$this->getPayStatusByBillid($billid);
				if($paystatus=="paid"){
					$this->updateSelfStockByReturnFood($foodid, $returnnum);
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
	 * @see IWorkerDAL::printReturnOrder()
	 */
	public function printReturnOrder($inputarr) {
		// TODO Auto-generated method stub
		global $phonekey;
		$printerarr=$this->getTheShopPrinters($inputarr['foodid']);
		if(empty($printerarr)){return array();}
		$phonecrypt = new CookieCrypt($phonekey);
		$deviceno=$phonecrypt->decrypt($printerarr['deviceno']);
		$phonecrypt = new CookieCrypt($phonekey);
		$devicekey=$phonecrypt->decrypt($printerarr['devicekey']);
		$printertype=$printerarr['printertype'];
		if($printertype=="58"){
			$msg=$this->getPrintSmallContent($inputarr,$deviceno,$devicekey);
		}else{
			$msg=$this->getPrintContent($inputarr,$deviceno,$devicekey);
		}
		$arr[]=array(
				"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
				"deviceno"=>$deviceno,
				"devicekey"=>$devicekey,
				'msg'=>$msg
		);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getTheShopPrinters()
	 */
	public function getTheShopPrinters($foodid) {
		// TODO Auto-generated method stub
		$ftid=$this->getFtidByFoodid($foodid);
		if(empty($ftid)){return array();}
		$printerid=$this->getPrinteridByFtid($ftid);
		if(empty($printerid)){return array();}
		$qarr=array("_id"=>new MongoId($printerid));
		$oparr=array("deviceno"=>1,"devicekey"=>1,"outputtype"=>1,"printertype"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['printertype'])){$printertype=$result['printertype'];}else{$printertype="80";}
			$arr=array(
					"deviceno"=>$result['deviceno'],
					"devicekey"=>$result['devicekey'],
					"outputtype"=>$result['outputtype'],
					"printertype"=>$printertype,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPrinterInfoByZonid()
	 */
	public function getPrinterInfoByZonid($zoneid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("printerid"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		$printerid="";
		if(!empty($result)){
			$printerid=$result['printerid'];
		}
		return $printerid;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPrinterInfoByPid()
	 */
	public function getPrinterInfoByPid($pid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("deviceno"=>1,"devicekey"=>1,"outputtype"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array(
					"deviceno"=>$result['deviceno'],
					"devicekey"=>$result['devicekey'],
					"outputtype"=>$result['outputtype'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPrintContent()
	 */
	public function getPrintContent($inputarr, $deviceno,$devicekey) {
		// TODO Auto-generated method stub
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>退菜单</CB><BR>';
		$orderInfo.='<B>台号：'.$inputarr['tabname'].'</B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='顾客：'.$inputarr['nickname'].'   人数：'.$inputarr['cusnum'].' <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.="<B>-".$inputarr['returnnum']." ".$inputarr['orderunit']." × ".$inputarr['foodname'].'</B><BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function getPrintSmallContent($inputarr, $deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>退菜单</CB><BR>';
		$orderInfo.='<B>台号：'.$inputarr['tabname'].'</B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='顾客：'.$inputarr['nickname'].'   人数：'.$inputarr['cusnum'].' <BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.="<B>-".$inputarr['returnnum']." ".$inputarr['orderunit']." × ".$inputarr['foodname'].'</B><BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::addToReturnBill()
	 */
	public function addToReturnBill($inputarr) {
		// TODO Auto-generated method stub
		$billarr=$this->getOneBillInfoByBillid($inputarr['billid']);
		if(empty($billarr)){return ;}
		$tabname=$this->getTablenameByTabid($billarr['tabid']);
		$qarr=array(
				"billid"=>$inputarr['billid'],
				"shopid"=>$billarr['shopid'],
				"uid"=>$inputarr['uid'],
				"tabname"=>$inputarr['tabname'],
				"nickname"=>$inputarr['nickname'],
				"foodid"=>$inputarr['foodid'],
				"returnnum"=>$inputarr['returnnum'],
				"orderunit"=>$inputarr['orderunit'],
				"returntime"=>time(),
				"timestamp"=>$billarr['timestamp'],
		);
		DALFactory::createInstanceCollection(self::$returnbill)->save($qarr);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOneFoodInBill()
	 */
	public function getOneFoodInBill($billid,$foodid) {
		// TODO Auto-generated method stub
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$tabname=$this->getTablenameByTabid($result['tabid']);
			foreach ($result['food'] as $key=>$val){
				if($foodid==$val['foodid']){
					$foodname=$val['foodname'];
					$foodnum=$val['foodnum'];
					$foodamount=$val['foodamount'];
					$orderunit=$val['orderunit'];
					$foodunit=$val['foodunit'];
					$isweight=$val['isweight'];
					$ispack=$val['ispack'];
					
					$arr=array(
							"shopid"=>$result['shopid'],
							"tabname"=>$tabname,
							"cusnum"=>$result['cusnum'],
							"nickname"=>$result['nickname'],
							"uid"=>$result['uid'],
							"foodname"	=>$foodname,
							"foodamount"=>$foodamount,
							"foodnum"=>$foodnum,
							"orderunit"=>$orderunit,
							"foodunit"=>$foodunit,
							"foodprice"=>$val['foodprice'],
							"tabid"=>$result['tabid'],
							"isweight"=>$isweight,
							"ispack"=>$ispack,
							"billnum"=>$result['billnum'],
							"paystatus"=>$result['paystatus'],
							"food"=>$result['food'],
					);
				}
			}
			
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::confrimFoodAmount()
	 */
	public function confrimFoodAmount($billid, $foodid, $foodamount,$foodnum,$cooktype) {
		// TODO Auto-generated method stub
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				if($foodid==$val['foodid'] &&$foodnum==$val['foodnum']&&$cooktype==$val['cooktype']){//不是赠送的
					$result['food'][$key]['foodamount']=$foodamount;
				}else{
					$result['food'][$key]=$val;
				}
			}		
		}
		$arr=$result['food'];
		$oparr=array("\$set"=>array("food"=>$arr));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::doDonateFood()
	 */
	public function doDonateFood($billid, $foodid, $donatenum,$foodnum,$cooktype) {
		// TODO Auto-generated method stub
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		$flag=true;
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				if($foodid==$val['foodid']&&empty($val['present'])&&$foodnum==$val['foodnum']&&$cooktype==$val['cooktype']&&$flag){
					$flag=false;
					if(intval($donatenum)==intval($val['foodnum'])){//全退完
						$arr[]=array(
								"foodid"=>$val['foodid'],
								"foodname"=>$val['foodname'],
								"foodprice"=>$val['foodprice'],
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
								"present"=>"1",
								"confrimweight"=>$val['ispack'],//默认已确认
						);
						
					}else{//退部分
						$arr[]=array(
								"foodid"=>$val['foodid'],
								"foodname"=>$val['foodname'],
								"foodprice"=>$val['foodprice'],
								"foodunit"=>$val['foodunit'],
								"orderunit"=>$val['orderunit'],
								"foodnum"=>$donatenum,
								"foodamount"=>($val['foodamount']/$val['foodnum'])*$donatenum,
								"ftid"=>$val['ftid'],
								"zoneid"=>$val['zoneid'],
								"zonename"=>$val['zonename'],
								"fooddisaccount"=>$val['fooddisaccount'],
								"cooktype"=>$val['cooktype'],
								"foodrequest"=>$val['foodrequest'],
								"isweight"=>$val['isweight'],
								"ishot"=>$val['ishot'],
								"ispack"=>$val['ispack'],
								"present"=>"1",
								"confrimweight"=>$val['ispack'],//默认已确认
						);
						$arr[]=array(
								"foodid"=>$val['foodid'],
								"foodname"=>$val['foodname'],
								"foodprice"=>$val['foodprice'],
								"foodunit"=>$val['foodunit'],
								"orderunit"=>$val['orderunit'],
								"foodnum"=>$val['foodnum']-$donatenum,
								"foodamount"=>$val['foodamount']-($val['foodamount']/$val['foodnum'])*$donatenum,
								"ftid"=>$val['ftid'],
								"zoneid"=>$val['zoneid'],
								"zonename"=>$val['zonename'],
								"fooddisaccount"=>$val['fooddisaccount'],
								"cooktype"=>$val['cooktype'],
								"foodrequest"=>$val['foodrequest'],
								"isweight"=>$val['isweight'],
								"ishot"=>$val['ishot'],
								"ispack"=>$val['ispack'],
								"present"=>"0",
								"confrimweight"=>$val['ispack'],//默认已确认
						);
					}
					
				}else{
					$arr[]=$val;
				}
			}
			$oparr=array("\$set"=>array("food"=>$arr));
			DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		}
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::updateOneTabStatus()
	 */
	public function updateOneTabStatus($tabid, $tabstatus) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return ;}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("\$set"=>array("tabstatus"=>$tabstatus));
		DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
		/*
		if($tabstatus=="empty"){
			$oparr=array("tag"=>1);
			$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
			if(!empty($result)){
				if($result['tag']=="tmp"){
					DALFactory::createInstanceCollection(self::$table)->remove($qarr);
				}else{
					$oparr=array("\$set"=>array("tabstatus"=>$tabstatus));
					DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
				}
			}
		}else{
			$oparr=array("\$set"=>array("tabstatus"=>$tabstatus));
			DALFactory::createInstanceCollection(self::$table)->update($qarr,$oparr);
		}
		*/
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::swithTable()
	 */
	public function swithTable($billid,$tabid, $newtabname) {
		// TODO Auto-generated method stub
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("tabname"=>$newtabname,"tabid"=>$tabid,"timestamp"=>time()));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::updateTabStatus()
	 */
	public function updateTabStatus($newtabid,$oldtabid,$oldtabstatus) {
		// TODO Auto-generated method stub
		if(empty($newtabid)){return ;}
		$qarr1=array("_id"=>new MongoId($newtabid));
		$oparr1=array("\$set"=>array("tabstatus"=>$oldtabstatus));
		DALFactory::createInstanceCollection(self::$table)->update($qarr1,$oparr1);
		if(empty($oldtabid)){return ;}
		$qarr2=array("_id"=>new MongoId($oldtabid));
		$oparr2=array("\$set"=>array("tabstatus"=>"empty"));
		DALFactory::createInstanceCollection(self::$table)->update($qarr2,$oparr2);
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPrinterListByShopid()
	 */
	public function getPrinterListByShopid($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"printername"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("pid"=>strval($val['_id']),"printername"=>$val['printername']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPrintersInfoByPrinterarr()
	 */
	public function getPrintersInfoByPrinterarr($printerarr) {
		// TODO Auto-generated method stub
		$pidarr=array();
		foreach ($printerarr as $pid){
			$pidarr[]=new MongoId($pid);
		}
		$qarr=array("_id"=>array("\$in"=>$pidarr));
		$oparr=array("_id"=>1, "deviceno"=>1,"devicekey"=>1,"outputtype"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $pkey=>$pval){
			$arr[]=array(
					"printerid"=>strval($pval['_id']),
					"deviceno"=>$pval['deviceno'],
					"devicekey"=>$pval['devicekey'],
					"outputtype"=>$pval['outputtype'],	
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOneBillInfoByBillid()
	 */
	public function getOneBillInfoByBillid($billid) {
		// TODO Auto-generated method stub
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		$conpkarr=array();
		$packarr=array();
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				if($val['ispack']=="1"){
					$packarr[]=$this->getPackHistoryData($billid,$val['foodid']);
				}
			}
			foreach ($packarr as $pkey=>$pval){
				foreach ($pval as $pkey1=>$pval1){
					$conpkarr[]=$pval1;
				}
			}
			$arr=array_merge($result['food'],$conpkarr);
			$result['food']=$arr;
			$result['tabname']=$this->getTablenameByTabid($result['tabid']);
		}
		return $result;
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::intoConsumeRecord()
	 */
	public function intoConsumeRecord($inputdarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$bill)->insert($inputdarr);
		$billid=strval($inputdarr['_id']);
		$this->addBillRecordData($inputdarr);
		return $billid;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getDonateInfoByShopid()
	 */
	public function getDonateInfoByShopid($shopid,$foodarr) {
		// TODO Auto-generated method stub
		$foodidarr=array();
		foreach ($foodarr as $fdkey=>$fdval){
			$foodidarr[]=$fdval['foodid'];
		}
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "orderfood"=>1,"donatefood"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$selfdonate)->find($qarr,$oparr);
		$donatefood=array();
		foreach ($result as $key=>$val){
			$ordernum="0";
			foreach ($val['orderfood'] as $ofoodid){
				if(in_array($ofoodid, $foodidarr)){
					//得到所点菜品份数
					foreach ($foodarr as $fkey=>$fval){
						if($ofoodid==$fval['foodid']){
							$ordernum=$fval['foodnum'];
						}
					}
				}
			}
			
			if(!empty($ordernum)){
				foreach ($val['donatefood'] as $dfoodid){
					$fdarr=$this->getFoodInfoByFoodid($dfoodid);
					$arr[]=array(
							"foodid"=>$dfoodid,
							"foodname"=>$fdarr['foodname'],
							"foodprice"=>$fdarr['foodprice'],
							"foodunit"=>$fdarr['foodunit'],
							"orderunit"=>$fdarr['orderunit'],
							"foodnum"=>$ordernum,
							"foodamount"=>$ordernum,
							"ftid"=>$fdarr['foodtypeid'],
							"zoneid"=>$fdarr['zoneid'],
							"zonename"=>$fdarr['zonename'],
							"fooddisaccount"=>$fdarr['fooddisaccount'],
							"cooktype"=>"",
							"foodrequest"=>"",
							"isweight"=>$fdarr['isweight'],
							"ishot"=>$fdarr['ishot'],
							"ispack"=>$fdarr['ispack'],
							"present"=>"1",
							"confrimweight"=>"1",//默认已确认
					);
				}
			}
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getFoodInfoByFoodid()
	 */
	public function getFoodInfoByFoodid($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return array();}
		$qarr=array("_id"=>new MongoId($foodid));
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr);
		if(!empty($result)){
			$zonename=$this->getZoneNameByZoneid($result['zoneid']);
			$arr=$result;
			$arr['zonename']=$zonename;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getZoneNameByZoneid()
	 */
	public function getZoneNameByZoneid($zoneid) {
		// TODO Auto-generated method stub
		if(empty($zoneid)){return "";}
		$qarr=array("_id"=>new MongoId($zoneid));
		$oparr=array("zonename"=>1);
		$result=DALFactory::createInstanceCollection(self::$zone)->findOne($qarr,$oparr);
		$zonename="";
		if(!empty($result)){
			$zonename=$result['zonename'];
		}
		return $zonename;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getBillPackarr()
	 */
	public function getBillPackarr($billid, $package) {
		// TODO Auto-generated method stub
		$arr=array();
		foreach ($package as $pkey=>$pval){
			$thepacknum=$this->getPackNum($billid, $pval['foodid']);
			$thepackname=$this->getPacknameByPkid($pval['foodid']);
			if(empty($thepacknum)){return array();}
			$pckarr=array();
			foreach ($pval['pack'] as $pkfoodid){
				$tmparr=array();
				$foodarr=$this->getFoodInfoByFoodid($pkfoodid);
				$zonename=$this->getZoneNameByZoneid($foodarr['zoneid']);
				$packfoodnum=$this->getPackFoodNum($pval['foodid'], $pkfoodid);
				
				$arr[]=array(
						"foodid"=>$pkfoodid,
						"foodname"=>$foodarr['foodname'],
						"thepackname"=>$thepackname,
						"foodprice"=>"0",
						"foodunit"=>$foodarr['foodunit'],
						"orderunit"=>$foodarr['orderunit'],
						"foodnum"=>strval($thepacknum*$packfoodnum),
						"foodamount"=>strval($thepacknum*$packfoodnum),
						"ftid"=>$foodarr['foodtypeid'],
						"zoneid"=>$foodarr['zoneid'],
						"zonename"=>$zonename,
						"fooddisaccount"=>$foodarr['fooddisaccount'],
						"cooktype"=>"",
						"foodrequest"=>"",
						"isweight"=>$foodarr['isweight'],
						"ishot"=>$foodarr['ishot'],
						"ispack"=>"0",
						"present"=>"0",//是否赠送
						"confrimweight"=>"1",
						"inpack"=>"1",
				);
				
			}
		}
		
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPackNum()
	 */
	public function getPackNum($billid, $pkid) {
		// TODO Auto-generated method stub
		if(empty($pkid)){return 0;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$packnum=0;
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				if($val['ispack']=="1" && $pkid==$val['foodid']){
					$packnum=$val['foodnum'];
					break;
				}
			}
		}
		return $packnum;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPackFoodNum()
	 */
	public function getPackFoodNum($pkid, $foodid) {
		// TODO Auto-generated method stub
		$qarr=array("pkid"=>$pkid);
		$oparr=array("packagefood"=>1);
		$result=DALFactory::createInstanceCollection(self::$package)->findOne($qarr,$oparr);
		$num=0;
		if(!empty($result)){
			foreach ($result['packagefood'] as $key=>$val){
				if($foodid==$val['foodid']){
					$num=$val['foodnum'];break;
				}
			}
			return $num;
		}
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPayPageData()
	 */
	public function getPayPageData($billid, $shopid) {
		// TODO Auto-generated method stub
		$calcfood=$this->getTotalmoneyAndFoodDiscountmoney($billid);
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"coupontype"=>1);
		$result=DALFactory::createInstanceCollection(self::$coupontype)->find($qarr,$oparr);
		foreach ($result as $key=>$val){
			$ctypearr[]=array("ctypeid"=>strval($val['_id']),"coupontype"=>$val['coupontype']);
		}
		$deposit=$this->getOneDesposit($billid);
		$depositmoney=$this->getDepositmoney($shopid);
		$getdepositmoney="0";
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

	/* (non-PHPdoc)
	 * @see IWorkerDAL::getTotalmoneyAndFoodDiscountmoney()
	 */
	public function getTotalmoneyAndFoodDiscountmoney($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		$totalmoney=0;
		$fooddisaccountmoney=0;
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				if(empty($val['present'])){
					$totalmoney+=$val['foodamount']*$val['foodprice'];
					$fooddisaccount=$this->judgeTheFoodDisaccount($val['foodid']);
					if($fooddisaccount=="1"){
						$fooddisaccountmoney+=$val['foodamount']*$val['foodprice'];
					}
				}
			}
		}
		return array("totalmoney"=>$totalmoney,"fooddisaccountmoney"=>$fooddisaccountmoney);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::judgeTheFoodDisaccount()
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
	 * @see IWorkerDAL::getPackHistoryData()
	 */
	public function getPackHistoryData($billid,$pkid) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$billid,"pkid"=>$pkid);
		$oparr=array("packfood"=>1);
		$result=DALFactory::createInstanceCollection(self::$sellpackage)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=$result['packfood'];
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getBillidByTabid()
	 */
	public function getBillidByTabid($shopid,$tabid,$token) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return array();}
		$tabstatus=$this->getTabStatusByTabid($tabid);
		if($tabstatus!="start" && $tabstatus!="online"){
			return array("billid"=>"","cusnum"=>"","paystatus"=>"", "token"=>$token);
		}
		$qarr=array("shopid"=>$shopid, "tabid"=>$tabid);
		$oparr=array("_id"=>1,"cusnum"=>1,"paystatus"=>1,"billstatus"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$billid="";
		$cusnum="1";
		foreach ($result as $key=>$val){
			$billid=strval($val['_id']);
			$cusnum=$val['cusnum'];
			$paystatus=$val['paystatus'];
			$billstatus=$val['billstatus'];
			break;
		}
		return array("billid"=>$billid,"cusnum"=>$cusnum,"billstatus"=>$billstatus, "paystatus"=>$paystatus, "token"=>$token);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::addOrderToOldBill()
	 */
	public function addOrderToOldBill($oldbillid, $foodarr) {
		// TODO Auto-generated method stub
		if(empty($oldbillid)){return ;}
		$qarr=array("_id"=>new MongoId($oldbillid));
		$billarr=$this->getOneBillInfoByBillid($oldbillid);
		$shopid=$billarr['shopid'];
		foreach ($foodarr as $key=>$val){
			$oparr=array("\$push"=>array("food"=>$val));
			DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		}
		DALFactory::createInstanceCollection(self::$otherbill)->save(array("oldbillid"=>$oldbillid,"shopid"=>$shopid, "food"=>$foodarr,"timestamp"=>time()));
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOldTabstatusByBillid()
	 */
	public function getOldTabstatusByTabid($tabid) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return "empty";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabstatus"=>1);
		$tabstatus="empty";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result)){
			$tabstatus=$result['tabstatus'];
		}
		return $tabstatus;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getPacknameByPkid()
	 */
	public function getPacknameByPkid($pkid) {
		// TODO Auto-generated method stub
		if(empty($pkid)){return "";}
		$qarr=array("_id"=>new MongoId($pkid));
		$oparr=array("foodname"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$foodname="";
		if(!empty($result['foodname'])){
			$foodname=$result['foodname'];
		}
		return $foodname;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::updateFoodsToBill()
	 */
	public function updateFoodsToBill($billid, $foodarr) {
		// TODO Auto-generated method stub
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		foreach ($foodarr as $onefood){
			$oparr=array("\$push"=>array("food"=>$onefood));
			DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		}
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getBillTypeData()
	 */
	public function getBillTypeData($billid, $outputtype) {
		// TODO Auto-generated method stub
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr);
		$arr=array();
		$foodarr=array();
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				$printerid=$this->getPrinteridByFtid($val['ftid']);
				if(empty($printerid)){continue;}
				$searchoutputtype=$this->getOutputypeByPid($printerid);
				if(empty($searchoutputtype)){continue;}
				if($outputtype==$searchoutputtype){
					$foodarr[]=$val;
				}
			}
		}
		$result['food']=$foodarr;
		return $result;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOutputtypeByFtid()
	 */
	public function getPrinteridByFtid($ftid) {
		// TODO Auto-generated method stub
		if(empty($ftid)){return "";}
		$qarr=array("_id"=>new MongoId($ftid));
		$oparr=array("printerid"=>1);
		$printerid="";
		$result=DALFactory::createInstanceCollection(self::$foodtype)->findOne($qarr,$oparr);
		if(!empty($result['printerid'])){
			$printerid=$result['printerid'];
		}
		return $printerid;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOutputypeByPid()
	 */
	public function getOutputypeByPid($pid) {
		// TODO Auto-generated method stub
		if(empty($pid)){return "menu";}
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("outputtype"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		$outputtype="";
		if(!empty($result['outputtype'])){
			$outputtype=$result['outputtype'];
		}
		return $outputtype;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getFtidByFoodid()
	 */
	public function getFtidByFoodid($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return "";}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodtypeid"=>1);
		$foodtypeid="";
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		if(!empty($result['foodtypeid'])){
			$foodtypeid=$result['foodtypeid'];
		}
		return $foodtypeid;
	}
	
	public function getTabStatusByTabid($tabid){
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
	

	public function ChangeTableSheetContent($shopid,$oldtabname,$newtabname){
		$shopprinters=$this->getShopAllPrinters($shopid);
		$arr=array();
		foreach ($shopprinters as $val){
			if($val['printertype']=="58"){
				$msg=$this->getChangeTabSmallPrintContent($oldtabname,$newtabname,$val);
			}else{
				$msg=$this->getChangeTabPrintContent($oldtabname,$newtabname,$val);
			}
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"outputtype"=>"changetab",
					'msg'=>$msg
			);
		}
		return $arr;
	}
	
	public function getChangeTabPrintContent($oldtabname,$newtabname,$printerarr) {
		// TODO Auto-generated method stub
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>换台单</CB>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.="<B>台号 ".$oldtabname." 换为 ".$newtabname.'</B><BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='<BR>';
	
		$selfMessage = array(
				'sn'=>$printerarr['deviceno'],
				'printContent'=>$orderInfo,
// 				'apitype'=>'php',
				'key'=>$printerarr['devicekey'],
				'times'=>1
		);
		return $selfMessage;
	}
	public function getChangeTabSmallPrintContent($oldtabname,$newtabname,$printerarr){
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>换台单</CB>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.="<B>台号 ".$oldtabname." 换为 ".$newtabname.'</B><BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'sn'=>$printerarr['deviceno'],
				'printContent'=>$orderInfo,
				'key'=>$printerarr['devicekey'],
				'times'=>1
		);
		return $selfMessage;
	}
	public function getShopAllPrinters($shopid) {
		// TODO Auto-generated method stub
		global $phonekey;
		$qarr=array("shopid"=>$shopid);
		$oparr=array("deviceno"=>1,"devicekey"=>1,"printertype"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		$devicenoarr=array();
		foreach ($result as $key=>$val){
			if(!in_array($val['deviceno'], $devicenoarr)){
				$phonecrypt = new CookieCrypt($phonekey);
				$deviceno=$phonecrypt->decrypt($val['deviceno']);
				$phonecrypt = new CookieCrypt($phonekey);
				$devicekey=$phonecrypt->decrypt($val['devicekey']);
				if(!empty($val['printertype'])){$printertype=$val['printertype'];}else{$printertype="80";}
				$arr[]=array(
						"deviceno"	=>$deviceno,
						"devicekey"=>$devicekey,
						"printertype"=>$printertype,
				);
				$devicenoarr[]=$val['deviceno'];
			}
		}
		return $arr;
	}
	public function getShopidByBillid($billid){
		if(empty($billid)){return "";}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("shopid"=>1);
		$shopid="";
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result['shopid'])){
			$shopid=$result['shopid'];
		}
		return $shopid;
	}
	
	public function addDespositData($billid,$depositmoney){
		if(empty($billid)){return "";}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("depositmoney"=>$depositmoney));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function getOneDesposit($billid){
		if(empty($billid)){return "0";}
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
	public function switchOneFoodBehavior($foodid,$foodamount,$cooktype,$oldbillid,$tabid){
		if(empty($oldbillid)){return ;}
		$billid=$this->getStartBillidByTabid($tabid);
		//得到转移的美食信息
		if(empty($oldbillid)){return ;}
		$qarr=array("_id"=>new MongoId($oldbillid));
		$oparr=array("tabid"=>1, "food"=>1);
		$thefoodarr=array();
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$oldtabstatus="empty";
		if(!empty($result)){
			$oldtabstatus=$this->getTabStatusByTabid($result['tabid']);
			foreach ($result['food'] as $key=>$val){
				if($foodid==$val['foodid']&&$foodamount==$val['foodamount']&&$cooktype==$val['cooktype']){
					$thefoodarr=array(
						"foodid"=>$val['foodid'],
						"foodname"=>$val['foodname'],
						"foodprice"=>$val['foodprice'],
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
						"confrimweight"=>$val['confrimweight'],//默认未确认
					);
					break;
				}
			}
		}
		//从原单撤出
// 		print_r($thefoodarr);exit;
		if(!empty($thefoodarr) && $oldtabstatus=="start"){
			$oparr=array("\$pull"=>array("food"=>$thefoodarr));
			DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
			//加入到新单
			if(empty($billid)){//新开台
				$oldbillarr=$this->getOneBillInfoByBillid($oldbillid);
				$tabname=$this->getTablenameByTabid($tabid);
				$inputarr=array(
						"orderno"=>time().mt_rand(1000, 9999),
						"tradeno"=>"",
						"uid"=>$oldbillarr['uid'],
						"shopid"=>$oldbillarr['shopid'],
						"nickname"=>$oldbillarr['nickname'],
						"shopname"=>$oldbillarr['shopname'],
						"wait"=>$oldbillarr['wait'],
						"tabid"=>$tabid,
						"takeout"=>"0",
						"deposit"=>$oldbillarr['deposit'],
						"invoice"=>"",
						"takeoutaddress"=>"",
						"orderrequest"=>"",//整单备注
						"discountype"=>"",
						"paytype"=>$oldbillarr['paytype'],
						"paymoney"=>"0",
						"paystatus"=>"unpay",
						"tabname"=>$tabname,
						"cusnum"=>$oldbillarr['cusnum'],
						"timestamp"=>time(),//下单时间
						"billstatus"=>"done",
						"food"=>array(0=>$thefoodarr),
				);
				DALFactory::createInstanceCollection(self::$bill)->save($inputarr);
				$this->updateOneTabStatus($tabid, "start");
			}else{
				$qarr=array("_id"=>new MongoId($billid));
				$oparr=array("\$push"=>array("food"=>$thefoodarr));
				DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
			}
		}
	}
	
	public function getStartBillidByTabid($tabid){
		$qarr=array("tabid"=>$tabid);//"paystatus"=>"unpay"
		$tabstatus=$this->getTabStatusByTabid($tabid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$billid="";
		foreach ($result as $key=>$val){
			if($tabstatus=="start"){
				$billid=strval($val['_id']);
			}
			break;
		}
		return $billid;
		
	}
	
	public function addSwitchFoodRecordData($oldbillid,$foodid,$foodamount,$tabid,$timestamp){
		$billarr=$this->getOneBillInfoByBillid($oldbillid);
		$foodarr=$this->getFoodInfoByFoodid($foodid);
		if(!empty($foodarr)){$foodname=$foodarr['foodname'];}else{$foodname="";}
		$oldtabname=$this->getTablenameByTabid($billarr['tabid']);
		$newtabname=$this->getTablenameByTabid($tabid);
		$arr=array(
				"shopid"	=>$billarr['shopid'],
				"foodid"=>$foodid,
				"foodname"=>$foodname,
				"foodamount"=>$foodamount,
				"oldtabname"=>$oldtabname,
				"newtabname"=>$newtabname,
				"timestamp"=>$billarr['timestamp'],
				"addtime"=>$timestamp,
		);
		DALFactory::createInstanceCollection(self::$switchfoodrecord)->save($arr);
	}
	
	public function getBillPaystatusByBillid($billid){
		if(empty($billid)){return "unpay";}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("paystatus"=>1);
		$paystatus="unpay";
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result['paystatus'])){
			$paystatus=$result['paystatus'];
		}
		return $paystatus;
	}
	
	public function intoChangeTabRecord($record){
		DALFactory::createInstanceCollection(self::$switchtabrecord)->save($record);		
	}
	
	public function getUrgeContentData($inputarr){
		global $phonekey;
		$printerarr=$this->getTheShopPrinters($inputarr['foodid']);
		if(empty($printerarr)){return array();}
		$phonecrypt = new CookieCrypt($phonekey);
		$deviceno=$phonecrypt->decrypt($printerarr['deviceno']);
		$phonecrypt = new CookieCrypt($phonekey);
		$devicekey=$phonecrypt->decrypt($printerarr['devicekey']);
		$printertype=$printerarr['printertype'];
		if($printertype=="58"){
			$msg=$this->getUrgeSmallContent($inputarr,$deviceno,$devicekey);
		}else{
			$msg=$this->getUrgeContent($inputarr,$deviceno,$devicekey);
		}
		$arr[]=array(
				"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
				"deviceno"=>$deviceno,
				"devicekey"=>$devicekey,
				"printertype"=>$printertype,
				'msg'=>$msg
		);
		return $arr;
	}
	
	public function getUrgeContent($inputarr,$deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .= '<CB>催菜单</CB><BR>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.="<B>台号".$inputarr['tabname']."的 ".$inputarr['foodname']." 已过".sprintf("%.0f",(time()-$inputarr['donetime'])/60)."分钟，请留意 谢谢！</B><BR>";
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$inputarr['donetime']).'<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$orderInfo.='<BR>';
				$selfMessage = array(
					'sn'=>$deviceno,
					'printContent'=>$orderInfo,
					'key'=>$devicekey,
					'times'=>1
		);
		return $selfMessage;
	}
	public function getUrgeSmallContent($inputarr,$deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .= '<CB>催菜单</CB><BR>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.="<B>台号".$inputarr['tabname']."的 ".$inputarr['foodname']." 已过".sprintf("%.0f",(time()-$inputarr['donetime'])/60)."分钟，请留意 谢谢！</B><BR>";
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$inputarr['donetime']).'<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$orderInfo.='<BR>';
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	public function updateBillDeposit($billid,$newdeposit){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("deposit"=>$newdeposit));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function saveUpdateTabStatus($inputarr){
		DALFactory::createInstanceCollection(self::$change_tabstatus_record)->save($inputarr);
	}
	
	public function getShopidByTabid($tabid){
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("shopid"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$shopid="";
		if(!empty($result['shopid'])){
			$shopid=$result['shopid'];
		}
		return $shopid;
	}
	
	public function updateSelfStockByReturnFood($foodid,$returnnum){
		$autostock=$this->judgeAutoStock($foodid);
		if($autostock=="1"){
			$this->updateSelfStocknum($foodid, -$returnnum);
		}
	}
	
	public function judgeAutoStock($foodid){
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
	
	public function getLastbillidByTabid($shopid,$tabid){
		$qarr=array("shopid"=>$shopid,"tabid"=>$tabid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$billid="";
		foreach ($result as $key=>$val){
			$billid=strval($val['_id']);
		}
		return $billid;
	}
	
	public function isTheShopServerRoleidByUid($shopid,$uid){
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("_id"=>1,"roleid"=>1,"servername"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$roleid="0";
		if(!empty($result)){
			$roleid=$result['roleid'];
		}
		return $roleid;
	}
	
	public function getRoleFunsByRoleid($roleid){
		if(empty($roleid)){return array();}
		$qarr=array("_id"=>new MongoId($roleid));
		$result=DALFactory::createInstanceCollection(self::$role)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"empty"	=>$result['empty'],
					"start"=>$result['start'],
					"online"=>$result['online'],
					"book"=>$result['book'],
					"pay"=>$result['pay'],
					"returnfood"=>$result['returnfood'],
			);
		}
		return $arr;
	}
	public function addBillRecordData($inputarr){
		DALFactory::createInstanceCollection(self::$billrecord)->save($inputarr);
	}
	
	public function getMustOrderMenuData($shopid,$cusnum){
		$qarr=array("shopid"=>$shopid,"mustorder"=>"1");
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr);
		$arr=array();
		foreach ($result as $key=>$val){
			if($val['orderbynum']=="1"){
				$foodnum=$cusnum;
				$foodamount=$cusnum;
			}else{
				$foodnum="1";
				$foodamount="1";
			}
			$zonename=$this->getZoneNameByZoneid($val['zoneid']);
			$arr[]=array(
					"foodid"=>strval($val['_id']),
					"foodname"=>$val['foodname'],
					"foodprice"=>$val['foodprice'],
					"foodunit"=>$val['foodunit'],
					"orderunit"=>$val['orderunit'],
					"foodnum"=>$foodnum,
					"foodamount"=>$foodamount,
					"ftid"=>$val['foodtypeid'],
					"zoneid"=>$val['zoneid'],
					"zonename"=>$zonename,
					"fooddisaccount"=>$val['fooddisaccount'],
					"cooktype"=>"",
					"foodrequest"=>"",
					"isweight"=>"0",
					"ishot"=>"0",
					"ispack"=>"0",
					"present"=>"0",
					"confrimweight"=>"1",//默认未确认
			);
		}
		return $arr;
	}
	
	public function getPacksData($shopid,$foodarr){
		$arr=array();
		foreach ($foodarr as $fkey=>$fval){
			if($fval['ispack']!="1"){continue;}
			$qarr=array("shopid"=>$shopid, "pkid"=>$fval['foodid']);
			$oparr=array("packagefood"=>1);
			$result=DALFactory::createInstanceCollection(self::$package)->findOne($qarr,$oparr);
			if(!empty($result)){
				foreach ($result['packagefood'] as $pkey=>$pval){
					$foodarr=$this->getFoodInfoByFoodid($pval['foodid']);
					$zonename=$this->getZoneNameByZoneid($foodarr['zoneid']);
					$arr[]=array(
							"foodid"=>$pval['foodid'],
							"foodname"=>$foodarr['foodname'],
							"thepackname"=>$fval['foodname'],
							"foodprice"=>"0",
							"foodunit"=>$foodarr['foodunit'],
							"orderunit"=>$foodarr['orderunit'],
							"foodnum"=>strval($fval['foodamount']*$pval['foodnum']),
							"foodamount"=>strval($fval['foodamount']*$pval['foodnum']),
							"ftid"=>$foodarr['foodtypeid'],
							"zoneid"=>$foodarr['zoneid'],
							"zonename"=>$zonename,
							"fooddisaccount"=>$foodarr['fooddisaccount'],
							"cooktype"=>"",
							"foodrequest"=>"",
							"isweight"=>$foodarr['isweight'],
							"ishot"=>$foodarr['ishot'],
							"ispack"=>"0",
							"present"=>"0",//是否赠送
							"confrimweight"=>"1",
							"inpack"=>"1",
					);
				}
			}
		}
		return $arr;
	}
	
	public function addUidToBill($billid,$uid){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		if(empty($uid)){return ;}
		$oparr=array("\$addToSet"=>array("customerid"=>$uid));
		$result=DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function getBeforeBillData($shopid,$uid,$type){
		if($type=="takeout"){
			$qarr=array("shopid"=>$shopid,"uid"=>$uid,"takeout"=>"1", "timestamp"=>array("\$gte"=>time()-86400));
		}else{
			$qarr=array("shopid"=>$shopid,"uid"=>$uid,"takeout"=>"0", "timestamp"=>array("\$gte"=>time()-86400));
		}
		
		$result=DALFactory::createInstanceCollection(self::$beforbill)->find($qarr)->sort(array("timestamp"=>-1))->limit(1);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr=$val;
			$arr['beforebillid']=strval($val['_id']);
			break;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::intoBeforebillConsumeRecord()
	 */
	public function intoBeforebillConsumeRecord($inputdarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$beforbill)->insert($inputdarr);
		$beforebillid=strval($inputdarr['_id']);
		return $beforebillid;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::addUidToBeforeBill()
	 */
	public function addUidToBeforeBill($beforebillid, $uid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($beforebillid));
		$oparr=array("\$addToSet"=>array("customerid"=>$uid));
		$result=DALFactory::createInstanceCollection(self::$beforbill)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::updateFoodsToBeforeBill()
	 */
	public function updateFoodsToBeforeBill($beforebillid, $packarr) {
		// TODO Auto-generated method stub
		if(empty($beforebillid)){return ;}
		$qarr=array("_id"=>new MongoId($beforebillid));
		foreach ($packarr as $onefood){
			$oparr=array("\$push"=>array("food"=>$onefood));
			DALFactory::createInstanceCollection(self::$beforbill)->update($qarr,$oparr);
		}
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::addOrderToOldBeforeBill()
	 */
	public function addOrderToOldBeforeBill($oldbeforebillid, $foodarr) {
		// TODO Auto-generated method stub
		if(empty($oldbeforebillid)){return ;}
		$qarr=array("_id"=>new MongoId($oldbeforebillid));
		foreach ($foodarr as $key=>$val){
			$oparr=array("\$push"=>array("food"=>$val));
			DALFactory::createInstanceCollection(self::$beforbill)->update($qarr,$oparr);
		}		
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::getOneBillInfoByBeforeBillid()
	 */
	public function getOneBillInfoByBeforeBillid($oldbeforebillid) {
		// TODO Auto-generated method stub
		if(empty($oldbeforebillid)){return ;}
		$qarr=array("_id"=>new MongoId($oldbeforebillid));
		$result=DALFactory::createInstanceCollection(self::$beforbill)->findOne($qarr);
		$arr=array();
		$conpkarr=array();
		$packarr=array();
		if(!empty($result)){
			$userinfo=$this->getCusInfo($result['uid'], "");
			foreach ($result['food'] as $key=>$val){
				if($val['ispack']=="1"){
					$packarr[]=$this->getPackHistoryData($oldbeforebillid,$val['foodid']);
				}
			}
			foreach ($packarr as $pkey=>$pval){
				foreach ($pval as $pkey1=>$pval1){
					$conpkarr[]=$pval1;
				}
			}
			$arr=array_merge($result['food'],$conpkarr);
			$result['food']=$arr;
			if(!empty($userinfo['nickname'])){$nickname=$userinfo['nickname'];}else{$nickname=$result['nickname'];}
			$result['nickname']=$nickname;
			
		}
		return $result;
	}

	public function getBillData($shopid,$uid,$type){
		if($type=="takeout"){
			$qarr=array("shopid"=>$shopid,"uid"=>$uid,"paystatus"=>"unpay", "billstatus"=>"undone","takeout"=>"1", "timestamp"=>array("\$gte"=>time()-86400));
		}else{
			$qarr=array("shopid"=>$shopid,"uid"=>$uid,"paystatus"=>"unpay","takeout"=>"0", "timestamp"=>array("\$gte"=>time()-86400));
		}
		
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1));
		$arr=array();
		foreach ($result as $key=>$val){
			$arr=$val;
			$arr['billid']=strval($val['_id']);
			break;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerDAL::updateBillData()
	 */
	public function updateBillData($inputarr) {
		// TODO Auto-generated method stub
		if(empty($inputarr['billid'])){return ;}
		$qarr=array("_id"=>new MongoId($inputarr['billid']));
		$oparr=array("\$set"=>array("billstatus"=>$inputarr['billstatus'],"tabid"=>$inputarr['tabid'],"timestamp"=>time()));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function getOnefoodinfoByFoodid($foodid){
		if(empty($foodid)){return array();}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array(
				"foodname"=>1,"foodprice"=>1,"foodunit"=>1,
				"orderunit"=>1,"foodtypeid"=>1,"zoneid"=>1,"fooddisaccount"=>1,
				"isweight"=>1,"ishot"=>1,"ispack"=>1,
		);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$zonename=$this->getZoneNameByZoneid($result['zoneid']);
			$arr=array(
					"foodid"=>strval($result['_id']),
					"foodname"=>$result['foodname'],
					"foodprice"=>$result['foodprice'],
					"foodunit"=>$result['foodunit'],
					"orderunit"=>$result['orderunit'],
					"ftid"=>$result['foodtypeid'],
					"zoneid"=>$result['zoneid'],
					"zonename"=>$zonename,
					"fooddisaccount"=>$result['fooddisaccount'],
					"isweight"=>$result['isweight'],
					"ishot"=>$result['ishot'],
					"ispack"=>$result['ispack'],
					"confrimweight"=>"1",
			);
		}
		return $arr;
	}
	
	public function printReplaceOrder($inputarr){
		global $phonekey;
		$oldprinterarr=$this->getTheShopPrinters($inputarr['oldfoodid']);
		if(empty($oldprinterarr)){return array();}
		$phonecrypt = new CookieCrypt($phonekey);
		$olddeviceno=$phonecrypt->decrypt($oldprinterarr['deviceno']);
		$phonecrypt = new CookieCrypt($phonekey);
		$olddevicekey=$phonecrypt->decrypt($oldprinterarr['devicekey']);
		// 		echo $devicekey;exit;
		$printertype=$oldprinterarr['printertype'];
		if($printertype=="58"){
			$msg=$this->getReplaceFoodSmallContent($inputarr,$olddeviceno,$olddevicekey);
		}else{
			$msg=$this->getReplaceFoodContent($inputarr,$olddeviceno,$olddevicekey);
		}
		$arr[]=array(
				"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
				"deviceno"=>$olddeviceno,
				"devicekey"=>$olddevicekey,
				'msg'=>$msg,
		);
		$newprinterarr=$this->getTheShopPrinters($inputarr['newfoodid']);
		if(empty($newprinterarr)){return array();}
		$phonecrypt = new CookieCrypt($phonekey);
		$newdeviceno=$phonecrypt->decrypt($newprinterarr['deviceno']);
		$phonecrypt = new CookieCrypt($phonekey);
		$newdevicekey=$phonecrypt->decrypt($newprinterarr['devicekey']);
		$printertype=$newprinterarr['printertype'];
		if($printertype=="58"){
			$msg=$this->getReplaceFoodSmallContent($inputarr,$newdeviceno,$newdevicekey);
		}else{
			$msg=$this->getReplaceFoodContent($inputarr,$newdeviceno,$newdevicekey);
		}
		
		if($olddeviceno==$newdeviceno){
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$newdeviceno,
					"devicekey"=>$newdevicekey,
					'msg'=>$msg,
			);
		}
		return $arr;
	}
	
	public function getReplaceFoodContent($inputarr,$deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>换菜单</CB><BR>';
		$orderInfo.='<B>台号：'.$inputarr['tabname'].'</B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='顾客：'.$inputarr['nickname'].'   人数：'.$inputarr['cusnum'].' <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.="<B>将".$inputarr['oldreturnamount'].$inputarr['oldfoodunit'].$inputarr['oldfoodname']."换成".$inputarr['newfoodamount'].$inputarr['newfoodunit'].$inputarr['newfoodname'].'</B><BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	public function getReplaceFoodSmallContent($inputarr,$deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>换菜单</CB><BR>';
		$orderInfo.='<B>台号：'.$inputarr['tabname'].'</B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='顾客：'.$inputarr['nickname'].'   人数：'.$inputarr['cusnum'].' <BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.="<B>将".$inputarr['oldreturnamount'].$inputarr['oldfoodunit'].$inputarr['oldfoodname']."换成".$inputarr['newfoodamount'].$inputarr['newfoodunit'].$inputarr['newfoodname'].'</B><BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	public function addReplacefoodRecord($inputarr){
		DALFactory::createInstanceCollection(self::$replacefoodrecord)->save($inputarr);
	}
	
	public function addOnefoodToBill($billid,$onefood){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$push"=>array("food"=>$onefood));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function getBillAndPayStatus($billid){
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("billstatus"=>1,"paystatus"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("billstatus"=>$result['billstatus'],"paystatus"=>$result['paystatus']);
		}
		return $arr;
	}
	
	public function getTabidByBillid($billid){
		if(empty($billid)){return "";}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("tabid"=>1);
		$tabid="";
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result)){
			$tabid=$result['tabid'];
		}
		return $tabid;
	}
	
	public function getBillinfoByTabid($tabid){
		$qarr=array("tabid"=>$tabid);
		$oparr=array("_id"=>1,"billstatus"=>1,"paystatus"=>1,"cusnum"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr=array("billid"=>strval($val['_id']),"cusnum"=>$val['cusnum']);
			break;
		}
		return $arr;
	}
	public function delOneBillByBillid($billid){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		DALFactory::createInstanceCollection(self::$bill)->remove($qarr);
	}
	
	public function getShopidByTabidData($tabid){
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("shopid"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$shopid="";
		if(!empty($result)){
			$shopid=$result['shopid'];
		}
		return $shopid;
	}
	
	public function hasNewbillbyTabid($billid,$tabid){
		$tabstatus=$this->getTabStatusByTabid($tabid);
		$flag=false;
		if($tabstatus=="start" || $tabstatus=="online"){
			$qarr=array("tabid"=>$tabid,"paystatus"=>"unpay");
			$oparr=array("_id"=>1);
			$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
			foreach ($result as $key=>$val){
				if($billid==strval($val['_id'])){
					$flag= true;
				}
			}
		}
		return $flag;
	}
	
	public function updateFoodStatus($billid,$foodarr,$foodid,$foodstatus,$foodamount,$cooktype){
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		foreach ($foodarr as $key=>$val){
			if($foodid==$val['foodid']&&$foodamount==$val['foodamount']&&$cooktype==$val['cooktype']){
				$foodarr[$key]['foodstatus']=$foodstatus;
				break;
			}
		}
		$newfoodarr=array();
		foreach ($foodarr as $fval){
			$newfoodarr[]=$fval;
		}
		$oparr=array("\$set"=>array("food"=>$newfoodarr));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		return array("status"=>"1");
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
	
	public function getPayRight($uid,$shopid){
		$roleid=$this->isTheShopServerRoleidByUid($shopid, $uid);
		$result=$this->getRoleFunsByRoleid($roleid);
		if($result['pay']=="1" && $result['returnfood']=="1"){
			$roler="manager";
		}else{
			$roler="cashier";
		}
		return $roler;
	}
	
	public function updateBillCusnum($billid,$cusnum){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("cusnum"=>$cusnum));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function updateSelfStock($foodarr){
		foreach ($foodarr as $key=>$val){
			$autostock=$this->judgeAutoStock($val['foodid']);
			if($autostock=="1"){
				$this->updateSelfStocknum($val['foodid'], $val['foodamount']);
			}
		}
	}
	
	public function updateSelfStocknum($foodid,$foodamount){
		$qarr=array("foodid"=>$foodid);
		$oparr=array("num"=>1);
		$result=DALFactory::createInstanceCollection(self::$autostock)->findOne($qarr,$oparr);
		if(!empty($result['num'])){
			$num=$result['num']-$foodamount;
			if($num<0){$num="0";}
			$oparr=array("\$set"=>array("num"=>$num));
			DALFactory::createInstanceCollection(self::$autostock)->update($qarr,$oparr);
			$data = $result;
			$data['num'] = $num;
			
			$this->addStockin($data);
			
		}
	}
	public function addStockin($data)
	{
	
	    $qarr=array("foodid"=>$data['foodid'],"month"=>date('Y-m'));
	    $oparr=array("_id"=>1,"num"=>1);
	    $result=DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr,$oparr);
	    if(!empty($result)){
	        $num=$data['num'];
	        $oparr=array("\$set"=>array("num"=>$num,'timestamp'=>time()));
	        DALFactory::createInstanceCollection(self::$monthstock)->update($qarr,$oparr);
	    }else{
	        $arr['foodid'] =$data["foodid"];
	        $arr['shopid'] = $data["shopid"];
	        $arr['num'] = $data['num'];
	        $arr['month'] = date('Y-m');
	        $arr['timestamp'] = time();
	        DALFactory::createInstanceCollection(self::$monthstock)->save($arr);
	    }
	}
	public function getPayStatusByBillid($billid){
		if(empty($billid)){return "unpay";}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("paystatus"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$paystatus="unpay";
		if(!empty($result)){
			$paystatus=$result['paystatus'];
		}
		return $paystatus;
	}
	
	public function updateBeforeBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype){
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
		DALFactory::createInstanceCollection(self::$beforbill)->update($qarr,$oparr);
	}
	public function getOneFoodInBeforeBill($billid,$foodid){
		if(empty($billid)){return array();}
		$qarr=array("_id"=>new MongoId($billid));
		$result=DALFactory::createInstanceCollection(self::$beforbill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$tabname=$this->getTablenameByTabid($result['tabid']);
			foreach ($result['food'] as $key=>$val){
				if($foodid==$val['foodid']){
					$foodname=$val['foodname'];
					$foodnum=$val['foodnum'];
					$foodamount=$val['foodamount'];
					$orderunit=$val['orderunit'];
					$foodunit=$val['foodunit'];
					$isweight=$val['isweight'];
					$ispack=$val['ispack'];
						
					$arr=array(
							"tabname"=>$tabname,
							"cusnum"=>$result['cusnum'],
							"nickname"=>$result['nickname'],
							"uid"=>$result['uid'],
							"foodname"	=>$foodname,
							"foodamount"=>$foodamount,
							"foodnum"=>$foodnum,
							"orderunit"=>$orderunit,
							"foodunit"=>$foodunit,
							"tabid"=>$result['tabid'],
							"isweight"=>$isweight,
							"ispack"=>$ispack,
							"food"=>$result['food'],
					);
				}
			}
				
		}
		return $arr;
	}
	
	public function delOneBerforeBill($beforebillid){
		if(empty($beforebillid)){return ;}
		$qarr=array("_id"=>new MongoId($beforebillid));
		DALFactory::createInstanceCollection(self::$beforbill)->remove($qarr);
	}
	
	public function removeEmoji($text) {
	
		$clean_text = "";
	
		// Match Emoticons
		$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$clean_text = preg_replace($regexEmoticons, '', $text);
	
		// Match Miscellaneous Symbols and Pictographs
		$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$clean_text = preg_replace($regexSymbols, '', $clean_text);
	
		// Match Transport And Map Symbols
		$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
		$clean_text = preg_replace($regexTransport, '', $clean_text);
	
		// Match Miscellaneous Symbols
		$regexMisc = '/[\x{2600}-\x{26FF}]/u';
		$clean_text = preg_replace($regexMisc, '', $clean_text);
	
		// Match Dingbats
		$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);
	
		return $clean_text;
	}
	
	public function SetNewPrice($billid,$foodid,$foodarr,$cooktype,$foodnum,$newprice){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		foreach ($foodarr as $key=>$val){
			if($foodid==$val['foodid']){//&&$foodnum==$val['foodnum']
				$foodarr[$key]['foodprice']=$newprice;
			}
		}
		$newfoodarr=array();
		foreach ($foodarr as $fval){
			$newfoodarr[]=$fval;
		}
		$oparr=array("\$set"=>array("food"=>$newfoodarr));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function printSwitchSheet($inputarr){
		global $phonekey;
		$printerarr1[]=$this->getTheShopPrinters($inputarr['foodid']);
		$printerarr2=$this->findThePrinter($inputarr['shopid'], "pass");
		$printerarr=array_merge($printerarr1,$printerarr2);
		$arr=array();
		foreach ($printerarr as $key=>$val){
			$phonecrypt = new CookieCrypt($phonekey);
			$deviceno=$phonecrypt->decrypt($val['deviceno']);
			$phonecrypt = new CookieCrypt($phonekey);
			$devicekey=$phonecrypt->decrypt($val['devicekey']);
			$printertype=$val['printertype'];
			if($printertype=="58"){
				$msg=$this->generSwitchSmallSheet($inputarr,$deviceno,$devicekey);
			}else{
				$msg=$this->generSwitchSheet($inputarr,$deviceno,$devicekey);
			}
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$deviceno,
					"devicekey"=>$devicekey,
					'msg'=>$msg
			);
		}
		return $arr;
	}
	
	public function generSwitchSheet($inputarr,$deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>转菜单</CB><BR>';
		$orderInfo.='<B>将台号'.$inputarr['oldtabname'].'的'.$inputarr['foodname'].'转到台号'.$inputarr['newtabname'].'</B>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function generSwitchSmallSheet($inputarr,$deviceno,$devicekey){
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>转菜单</CB><BR>';
		$orderInfo.='<B>将台号'.$inputarr['oldtabname'].'的'.$inputarr['foodname'].'转到台号'.$inputarr['newtabname'].'</B>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
		$orderInfo.='<BR>';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function findThePrinter($shopid, $outputtype){
		$qarr=array("shopid"=>$shopid,"outputtype"=>strval($outputtype));
		$oparr=array("_id"=>1,"deviceno"=>1,"devicekey"=>1,"printertype"=>1);
		$restult=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		foreach ($restult as $key=>$val){
			if(!empty($val['printertype'])){$printertype=$val['printertype'];}else{$printertype="80";}
			$arr[]=array(
					"printerid"=>strval($val['_id']),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"printertype"=>$printertype,
			);
		}
		return $arr;
	}
	
	public function getMatchFoodInfo($foodarr,$foodid,$foodamount,$cooktype){
		$arr=array();
		foreach ($foodarr as $key=>$val){
			if($val['cooktype']==null || $val['cooktype']=="" || $cooktype==null || $cooktype==""){
				$cooktypeflag=true;
			}elseif($cooktype==$val['cooktype']){
				$cooktypeflag=true;
			}else{
				$cooktypeflag=false;
			}
			
			if($foodid==$val['foodid']&&$foodamount==$val['foodamount']&& $cooktypeflag){
				$arr[]=$foodarr[$key];
				break;
			}
		}
		return $arr;
	}
	
	public function addBillnumData($billid,$billnum){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("billnum"=>$billnum));
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
	}
	
	public function getMyBillData($tabid,$shopid){
		$qarr=array("tabid"=>$tabid,"shopid"=>$shopid,"timestamp"=>array("\$gte"=>time()-86400*30));
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr)->sort(array("timestamp"=>-1))->limit(1);
		$arr=array();
		$foodarr=array();
		$newfoodarr=array();
		foreach ($result as $key=>$val){
			$arr=$val;
			$arr['billid']=strval($val['_id']);
			$tabname=$this->getTablenameByTabid($val['tabid']);
			$arr['tabname']=$tabname;
			$to_disarr=$this->getTotalmoneyAndFoodDiscountmoney(strval($val['_id']));
			$arr['totalmoney']=$to_disarr['totalmoney'];
			$arr['fooddisaccountmoney']=$to_disarr['fooddisaccountmoney'];
			foreach ($arr['food'] as $fkey=>$fval){
				if($fval['inpack']=="1"){continue;}
				if(array_key_exists($fval['foodid'], $foodarr)){
					$foodarr[$fval['foodid']]['foodnum']+=$fval['foodnum'];
					$foodarr[$fval['foodid']]['foodamount']+=$fval['foodamount'];
				}else{
					$foodarr[$fval['foodid']]=$fval;
				}
			}
			foreach ($foodarr as $key=>$val){
				$newfoodarr[]=$val;
			}
			$arr['food']=$newfoodarr;
			break;
		}
		return $arr;
	}
	
	public function getServeridByUid($shopid,$uid){
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$servers)->findOne($qarr,$oparr);
		$serverid="";
		if(!empty($result)){
			$serverid=strval($result['_id']);
		}
		return $serverid;
	}
	
	public function updateMoneyWhenReturnfood($billid,$returnmoney){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("cashmoney"=>1,"unionmoney"=>1,"vipmoney"=>1,"meituanpay"=>1,"dazhongpay"=>1,"nuomipay"=>1,"otherpay"=>1,"alipay"=>1,"wechatpay"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result)){
			$newcashmoney=$result['cashmoney']-$returnmoney;
			$updateoparr=array("\$set"=>array("cashmoney"=>$newcashmoney));
			DALFactory::createInstanceCollection(self::$bill)->update($qarr,$updateoparr);
		}
		
	}
	
	public function getWechatUserinfo($uid){
		$arr=array();
		$qarr=array("uid"=>$uid);
		$oparr=array("nickname"=>1);
		$result=DALFactory::createInstanceCollection(self::$wechat_user_info)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("nickname"=>$result['nickname']);
		}
		return $arr;
	}
	
	public function emoji2str($str){
		$strEncode = '';
		
		$length = mb_strlen($str,'utf-8');
		
		for ($i=0; $i < $length; $i++) {
			$_tmpStr = mb_substr($str,$i,1,'utf-8');
			if(strlen($_tmpStr) >= 4){
				$strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
			}else{
				$strEncode .= $_tmpStr;
			}
		}
		
		return $strEncode;
	}
}
?>