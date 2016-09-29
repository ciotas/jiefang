<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IPrintBillDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
class PrintBillDAL implements IPrintBillDAL{
	private static $printer="printer";
	private static $shopinfo="shopinfo";
	private static $bill="bill";
	private static $table="table";
	private static $zone="zone";
	private static $foodtype="foodtype";
	private static $prebill="prebill";
	private static $food="food";
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::tobeRunner()
	 */ 
	public function tobeRunner($inputdarr) {
		// TODO Auto-generated method stub
		$result=$this->findThePrinter($inputdarr['shopid'], "pass");
		$arr=array();
		$tabname=$this->getTabnameByTabid($inputdarr['tabid']);
// 		echo $tabname;exit;
		$foodarr=array();
		foreach ($inputdarr['food'] as $fkey=>$fval){
			$autostock=$this->judgeAutoStock($fval['foodid']);
			if($autostock!="1"){
				$foodarr[]=$fval;
			}
		}
		foreach ($result as $key=>$val){
			$arr[]=array(
// 					"billid"=>$inputdarr['billid'],
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"printertype"=>$val['printertype'],
					"uid"=>$inputdarr['uid'],
					"shopid"=>$inputdarr['shopid'],
					"nickname"=>$inputdarr['nickname'],
					"shopname"=>$inputdarr['shopname'],
					"wait"=>$inputdarr['wait'],
					"tabid"=>$inputdarr['tabid'],
					"takeout"=>$inputdarr['takeout'],
					"invoice"=>$inputdarr['invoice'],
					"takeoutaddress"=>$inputdarr['takeoutaddress'],
					"orderrequest"=>$inputdarr['orderrequest'],//整单备注
					"discountype"=>$inputdarr['discountype'],
					"paytype"=>$inputdarr['paytype'],
					"paystatus"=>$inputdarr['paystatus'],
					"tabname"=>$inputdarr['tabname'],
					"cusnum"=>$inputdarr['cusnum'],
					"timestamp"=>$inputdarr['timestamp'],//下单时间
					"billstatus"=>$inputdarr['billstatus'],
					"food"=>$foodarr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::tobeCusList()
	 */
	public function tobeCusList($inputdarr) {
		// TODO Auto-generated method stub
// 		$result=$this->findTheChuanCaiPrinter($inputdarr['tabid'],"menu");
		$printerid=$this->getPrinteridByTabid($inputdarr['tabid']);
		$printerarr=array();
		if(!empty($printerid)){
			$oneprinterarr=$this->getPrinterInfoByPid($printerid);
			if(!empty($oneprinterarr)){$printerarr[]=$oneprinterarr;}
		}else{
			$printerarr=$this->getPrinterInfoByType($inputdarr['shopid'], "menu");
		}
// 		$getexistbillnum=$this->getTheBillSortNum($inputdarr['shopid']);
		$depositmoney=$this->getDepositmoney($inputdarr['shopid']);
		foreach ($printerarr as $key=>$val){
			$arr[]=array(
			// 				"billid"=>$inputdarr['billid'],
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"printertype"=>$val['printertype'],
					"uid"=>$inputdarr['uid'],
					"shopid"=>$inputdarr['shopid'],
					"nickname"=>$inputdarr['nickname'],
					"shopname"=>$inputdarr['shopname'],
					"branchname"=>$inputdarr['branchname'],
					"wait"=>$inputdarr['wait'],
					"tabid"=>$inputdarr['tabid'],
					"takeout"=>$inputdarr['takeout'],
					"invoice"=>$inputdarr['invoice'],
					"takeoutaddress"=>$inputdarr['takeoutaddress'],
					"orderrequest"=>$inputdarr['orderrequest'],//整单备注
					"discountype"=>$inputdarr['discountype'],
					"paytype"=>$inputdarr['paytype'],
					"paystatus"=>$inputdarr['paystatus'],
					"tabname"=>$inputdarr['tabname'],
					"cusnum"=>$inputdarr['cusnum'],
					"paymoney"=>$inputdarr['paymoney'],
					"timestamp"=>$inputdarr['timestamp'],//下单时间
					"billstatus"=>$inputdarr['billstatus'],
					"deposit"=>$inputdarr['deposit'],
					"depositmoney"=>$depositmoney,
					"food"=>$inputdarr['food'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::findThePrinter()
	 */
	public function findThePrinter($shopid, $outputtype) {
		// TODO Auto-generated method stub
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
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::tobeConsumeList()
	 */
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney) {
		// TODO Auto-generated method stub
		$result=$this->getPrinterInfoByType($inputdarr['shopid'], "checkout");
		if(empty($result)){return array();}
		if(!empty($inputdarr['cuspay'])){$cuspay=$inputdarr['cuspay'];}else{$cuspay="0";}
		if(!empty($inputdarr['clearmoney'])){$clearmoney=$inputdarr['clearmoney'];}else{$clearmoney="0";}
		if(!empty($inputdarr['othermoney'])){$othermoney=$inputdarr['othermoney'];}else{$othermoney="0";}
		if(!empty($inputdarr['discountval'])){$discountval=$inputdarr['discountval'];}else{$discountval="100";}
		if(!empty($inputdarr['cashmoney'])){$cashmoney=$inputdarr['cashmoney'];}else{$cashmoney="0";}
		if(!empty($inputdarr['unionmoney'])){$unionmoney=$inputdarr['unionmoney'];}else{$unionmoney="0";}
		if(!empty($inputdarr['vipmoney'])){$vipmoney=$inputdarr['vipmoney'];}else{$vipmoney="0";}
		if(!empty($inputdarr['meituanpay'])){$meituanpay=$inputdarr['meituanpay'];}else{$meituanpay="0";}
		if(!empty($inputdarr['dazhongpay'])){$dazhongpay=$inputdarr['dazhongpay'];}else{$dazhongpay="0";}
		if(!empty($inputdarr['nuomipay'])){$nuomipay=$inputdarr['nuomipay'];}else{$nuomipay="0";}
		if(!empty($inputdarr['otherpay'])){$otherpay=$inputdarr['otherpay'];}else{$otherpay="0";}
		if(!empty($inputdarr['alipay'])){$alipay=$inputdarr['alipay'];}else{$alipay="0";}
		if(!empty($inputdarr['wechatpay'])){$wechatpay=$inputdarr['wechatpay'];}else{$wechatpay="0";}
		if(!empty($inputdarr['discountmode'])){$discountmode=$inputdarr['discountmode'];}else{$discountmode="part";}
		//赠券和代金券
		if(!empty($inputdarr['ticketval'])){$ticketval=$inputdarr['ticketval'];}else{$ticketval="0";}
		if(!empty($inputdarr['ticketnum'])){$ticketnum=$inputdarr['ticketnum'];}else{$ticketnum="0";}
		if(!empty($inputdarr['ticketway'])){$ticketway=$inputdarr['ticketway'];}else{$ticketway="";}
		//押金
		if(!empty($inputdarr['deposit'])){$deposit=$inputdarr['deposit'];}else{$deposit="0";}
		$depositmoney=$this->getDepositmoney($inputdarr['shopid']);
		if(!empty($inputdarr['returndepositmoney'])){$returndepositmoney=$inputdarr['returndepositmoney'];}else{$returndepositmoney=$depositmoney;}
		
		//签单免单
		if(!empty($inputdarr['signername'])){$signername=$inputdarr['signername'];}else{$signername="";}
		if(!empty($inputdarr['signerunit'])){$signerunit=$inputdarr['signerunit'];}else{$signerunit="";}
		if(!empty($inputdarr['freename'])){$freename=$inputdarr['freename'];}else{$freename="";}
		//其他
		if(!empty($inputdarr['takeoutaddress'])){$takeoutaddress=$inputdarr['takeoutaddress'];}else{$takeoutaddress="";}
		if(!empty($inputdarr['orderrequest'])){$orderrequest=$inputdarr['orderrequest'];}else{$orderrequest="";}
		if(!empty($inputdarr['takeoutphone'])){$takeoutphone=$inputdarr['takeoutphone'];}else{$takeoutphone="";}
		if(!empty($inputdarr['billnum'])){$billnum=$inputdarr['billnum'];}else{$billnum="0";}
		$prebillarr=$this->getPrebillByBillid(strval($inputdarr['_id']));
		foreach ($result as $key=>$val){
			$tabname=$this->getTabnameByTabid($inputdarr['tabid']);
			$arr[]=array(
					"billid"=>strval($inputdarr['_id']),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"printertype"=>$val['printertype'],
					"uid"=>$inputdarr['uid'],
					"shopid"=>$inputdarr['shopid'],
					"nickname"=>$inputdarr['nickname'],
					"shopname"=>$inputdarr['shopname'],
					"wait"=>$inputdarr['wait'],
					"tabid"=>$inputdarr['tabid'],
					"takeout"=>$inputdarr['takeout'],
					"invoice"=>$inputdarr['invoice'],
					"takeoutaddress"=>$inputdarr['takeoutaddress'],
					"orderrequest"=>$inputdarr['orderrequest'],//整单备注
					"paytype"=>$inputdarr['paytype'],
					"paystatus"=>$inputdarr['paystatus'],
					"deposit"=>$deposit,
					"tabname"=>$tabname,
					"paymethod"=>$paymethod,
					"paymoney"=>$paymoney,
					"cuspay"=>$cuspay,
					"clearmoney"=>$clearmoney,
					"othermoney"=>$othermoney,
					"discountval"=>$discountval,
					"discountmode"=>$discountmode,
					"cashmoney"=>$cashmoney,
					"unionmoney"=>$unionmoney,
					"vipmoney"=>$vipmoney,
					"meituanpay"=>$meituanpay,
					"dazhongpay"=>$dazhongpay,
					"nuomipay"=>$nuomipay,
					"otherpay"=>$otherpay,
					"wechatpay"=>$wechatpay,
					"alipay"=>$alipay,
					"ticketval"=>$ticketval,
					"ticketnum"=>$ticketnum,
					"ticketway"=>$ticketway,
					"returndepositmoney"=>$returndepositmoney,
					"signername"=>$signername,
					"signerunit"=>$signerunit,
					"freename"=>$freename,
					"qrcode"=>$prebillarr['qrcode'],
					"shouldpay"=>$prebillarr['shouldpay'],
					"cusnum"=>$inputdarr['cusnum'],
					"billnum"=>$billnum,
					"timestamp"=>$inputdarr['timestamp'],//下单时间
					"billstatus"=>$inputdarr['billstatus'],
					"cashierman"=>$inputdarr['cashierman'],
					"orderrequest"=>$inputdarr['orderrequest'],
					"returndepositmoney"=>$inputdarr['returndepositmoney'],
					"takeoutaddress"=>$takeoutaddress,
					"takeoutphone"=>$takeoutphone,
					"food"=>$inputdarr['food'],
			);
		}
		
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::orderByprinterid()
	 */
	public function orderByprinterid($inputdarr) {
		// TODO Auto-generated method stub
		$resultarr=array();
		$arr=array();
		foreach ($inputdarr['food'] as $key=>$val){
			if(!empty($inputdarr['printerid'])){
				$printerid=$inputdarr['printerid'];
			}else{
				$printerid=$this->getPrinteridByFtid($val['ftid']);
			}
			if(empty($printerid)){continue;}
			$arr[$printerid][]=array(
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
			);
		}
		$resultarr=array(
				"billid"=>strval($inputdarr['_id']),
				"uid"=>$inputdarr['uid'],
				"shopid"=>$inputdarr['shopid'],
				"nickname"=>$inputdarr['nickname'],
				"shopname"=>$inputdarr['shopname'],
				"branchname"=>$inputdarr['branchname'],
				"wait"=>$inputdarr['wait'],
				"tabid"=>$inputdarr['tabid'],
				"takeout"=>$inputdarr['takeout'],
				"invoice"=>$inputdarr['invoice'],
				"takeoutaddress"=>$inputdarr['takeoutaddress'],
				"orderrequest"=>$inputdarr['orderrequest'],//整单备注
				"discountype"=>$inputdarr['discountype'],
				"paytype"=>$inputdarr['paytype'],
				"paystatus"=>$inputdarr['paystatus'],
				"tabname"=>$inputdarr['tabname'],
				"cusnum"=>$inputdarr['cusnum'],
				"billno"=>$inputdarr['billno'],
// 				"paymoney"=>$inputdarr['paymoney'],
				"timestamp"=>$inputdarr['timestamp'],//下单时间
				"billstatus"=>$inputdarr['billstatus'],
				"food"=>$arr,
		);
		return $resultarr;
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::toBeWaiting()
	 */
	public function toBeWaiting($arr) {
		// TODO Auto-generated method stub
		$result=$this->findThePrinter($arr['shopid'], "waiting");
		$rearr=array();
		foreach ($result as $key=>$val){
			$rearr["waiting"][]=array(
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"cusnum"=>$arr['cusnum'],
					"queuesortno"=>$arr['queuesortno'],
					"waitstr" =>$arr['waitstr'],
					"shopname"=>$arr['shopname'],
					"timestamp"=>$arr['timestamp'],
					"jhcontent"=>$arr['jhcontent'],
					"QRC"=>$arr['QRC'],
					"systips"=>$arr['systips'],
					
			);
		}
		return $rearr;
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::getTheBillSortNum()
	 */
	public function getTheBillSortNum($shopid) {
		// TODO Auto-generated method stub
		
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::findTheChuanCaiPrinter()
	 */
	public function findTheChuanCaiPrinter($tabid,$outputtype) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return array();}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("zoneid"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$printerarr=array();
		$arr=array();
		if(!empty($result['zoneid'])){
			$printerarr=$this->getPrinterIdByZoneid($result['zoneid'],$outputtype);
		}
		
		return $printerarr;
	}
	
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::getPrinterIdByZoneid()
	 */
	public function getPrinterIdByZoneid($zoneid,$printertype) {
		// TODO Auto-generated method stub
		$qarr=array("zoneid"=>$zoneid,"outputtype"=>$printertype);
		$oparr=array("_id"=>1, "deviceno"=>1,"devicekey"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"printerid"=>strval($val['_id']),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey']
			);
		}
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::getPrinterInfoByPid()
	 */
	public function getPrinterInfoByPid($pid) {
		// TODO Auto-generated method stub
		if(empty($pid)){return array();}
		$qarr=array("_id"=>new MongoId($pid));
		$oparr=array("deviceno"=>1,"devicekey"=>1,"printertype"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['printertype'])){$printertype=$result['printertype'];}else{$printertype="80";}
			$arr=array("deviceno"=>$result['deviceno'],"devicekey"=>$result['devicekey'],"printertype"=>$printertype);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPrintBillDAL::getTabnameByTabid()
	 */
	public function getTabnameByTabid($tabid) {
		// TODO Auto-generated method stub
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("tabname"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$tabname="";
		if(!empty($result)){
			$tabname=$result['tabname'];
		}	
		return $tabname;
	}

	
	public function getPrinteridByFtid($ftid){
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
	public function getPrinteridByTabid($tabid){
		if(empty($tabid)){return "";}
		$motherid=$this->isTherVirtualTab($tabid);
		if(!empty($motherid)){
			$tabid=$motherid;
		}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("printerid"=>1);
		$printerid="";
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		if(!empty($result['printerid'])){
			$printerid=$result['printerid'];
		}
		return $printerid;
	}
	
	public function getPrinterInfoByType($shopid,$outputtype){
		$qarr=array("shopid"=>$shopid,"outputtype"=>$outputtype);
		$oparr=array("_id"=>1,"deviceno"=>1,"devicekey"=>1,"printertype"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(!empty($val['printertype'])){$printertype=$val['printertype'];}else{$printertype="80";}
			$arr[]=array(
					"printerid"=>strval($val['_id']),
					"deviceno"	=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"printertype"=>$printertype,
			);
		}
		return $arr;
	}
	
	public function isTherVirtualTab($tabid){
		if(empty($tabid)){return "";}
		$qarr=array("_id"=>new MongoId($tabid));
		$oparr=array("motherid"=>1,"tag"=>1);
		$result=DALFactory::createInstanceCollection(self::$table)->findOne($qarr,$oparr);
		$motherid="";
		if(!empty($result['tag'])){
			$motherid=$result['motherid'];//虚拟台号
		}
		return $motherid;
	}
	public function getPrebillByBillid($billid){
		$qarr=array("billid"=>$billid);
		$oparr=array("qrcode"=>1,"shouldpay"=>1);
		$result=DALFactory::createInstanceCollection(self::$prebill)->findOne($qarr,$oparr);
		$qrcode="";
		$shouldpay="0";
		if(!empty($result['qrcode'])){
			$qrcode=$result['qrcode'];
			$shouldpay=$result['shouldpay'];
		}
		return array("qrcode"=>$qrcode,"shouldpay"=>$shouldpay);
	}
	
	public function getOneDesposit($billid){
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
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$depositmoney="0";
		if(!empty($result['depositmoney'])){
			$depositmoney=$result['depositmoney'];
		}
		return $depositmoney;
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
}
?>