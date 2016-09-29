<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IPieceListDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
class PieceListDAL implements IPieceListDAL{
	private static $printer="printer";
	private static $zone="zone";
	/* (non-PHPdoc) 
	 * @see IPieceListDAL::tobePieceList()
	 */
	public function tobePieceList($orderfoodarr) {
		// TODO Auto-generated method stub
		file_put_contents("/var/www/html/log.txt", json_encode($orderfoodarr));
		$arr=array();
		$resultarr=array();
		global $phonekey;
		foreach ($orderfoodarr['food'] as $printerid=>$valarr){
			$printerarr=$this->getOutPutType($printerid);
// 			print_r($printerarr);exit;
			if(empty($printerarr)){continue;}
			$outputtype=$printerarr['outputtype'];
			$devicecrypt = new CookieCrypt($phonekey);
			$deviceno=$devicecrypt->decrypt($printerarr['deviceno']);
			$devicecrypt = new CookieCrypt($phonekey);
			$devicekey=$devicecrypt->decrypt($printerarr['devicekey']);
			$printertype=$printerarr['printertype'];
			switch ($outputtype){
				case "single":
					$signlearr=array();
					$signlearr= $this->fendanList("single",$printerid,$printertype,$deviceno,$devicekey,$orderfoodarr,$valarr); 
					$resultarr[]=$signlearr;
					break;//分单
				case "subtotal"://分总
					$subtotalarr=array();
					$zongarr=array();
					$subtotalarr= $this->fendanList("subtotal",$printerid,$printertype,$deviceno,$devicekey,$orderfoodarr, $valarr);
					$zongarr=$this->fenzongList($deviceno, $devicekey,$printertype,$orderfoodarr, $valarr);
					$subtotalarr[$printerid][]=array("type"=>$outputtype,"deviceno"=>$deviceno, "devicekey"=>$devicekey,"printertype"=>$printertype, "zong"=>$zongarr['zong']);
					$resultarr[]=$subtotalarr;
					break;
				case "double":
					$doublearr=array();
					$doublearr=$this->doubleList("double",$printerid,$deviceno,$devicekey,$printertype, $orderfoodarr, $valarr);
					$resultarr[]=$doublearr;
					break;//二联单
				case "total":
					$totalarr=array();
					$zongarr=array();
					$zongarr=$this->fenzongList($deviceno, $devicekey,$printertype,$orderfoodarr, $valarr);
					$totalarr[$printerid][]=array("type"=>$outputtype,"deviceno"=>$deviceno, "devicekey"=>$devicekey,"printertype"=>$printertype, "zong"=>$zongarr['zong']);
					$resultarr[]=$totalarr;
					break;
			}
		}
// 		print_r($resultarr);exit;
		return $resultarr;
	}
	/* (non-PHPdoc)
	 * @see IPieceListDAL::getOutPutType()
	 */
	public function getOutPutType($printerid) {
		// TODO Auto-generated method stub
		if(empty($printerid)){return array();}
		$qarr=array("_id"=>new MongoId($printerid));
		$oparr=array("deviceno"=>1,"devicekey"=>1,"printername"=>1,"outputtype"=>1,"printertype"=>1);
		$result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['printertype'])){$printertype=$result['printertype'];}else{$printertype="80";}
			$arr=array(
					"deviceno"=>$result['deviceno'],
					"devicekey"=>$result['devicekey'],
					"printername"=>$result['printername'],
					"outputtype"=>$result['outputtype'],
					"printertype"=>$printertype,
			);
		}
		return $arr;
	}
	
	/* (non-PHPdoc)
	 * @see IPieceListDAL::fendanList()
	 */
	public function fendanList($op="single",$printerid,$printertype,$deviceno,$devicekey,$orderfoodarr,$valarr) {
		// TODO Auto-generated method stub
		$resultarr=array();
// 		print_r($valarr);exit;
		foreach ($valarr as $key=>$val){
			$temparr=array(
					"zonename"=>$val['zonename'],
					"uid"=>$orderfoodarr['uid'],
					"shopid"=>$orderfoodarr['shopid'],
					"nickname"=>$orderfoodarr['nickname'],
					"shopname"=>$orderfoodarr['shopname'],
					"branchname"=>$orderfoodarr['branchname'],
					"wait"=>$orderfoodarr['wait'],
					"tabid"=>$orderfoodarr['tabid'],
					"takeout"=>$orderfoodarr['takeout'],
					"invoice"=>$orderfoodarr['invoice'],
// 					"takeoutaddress"=>$orderfoodarr['takeoutaddress'],
					"orderrequest"=>$orderfoodarr['orderrequest'],//整单备注
					"discountype"=>$orderfoodarr['discountype'],
					"paytype"=>$orderfoodarr['paytype'],
					"paystatus"=>$orderfoodarr['paystatus'],
					"tabname"=>$orderfoodarr['tabname'],
					"cusnum"=>$orderfoodarr['cusnum'],
					"billno"=>$orderfoodarr['billno'],
// 					"paymoney"=>$orderfoodarr['paymoney'],
					"timestamp"=>$orderfoodarr['timestamp'],//下单时间
					"billstatus"=>$orderfoodarr['billstatus'],
					"foodname"=>$val['foodname'],
					"foodnum"=>$val['foodnum'],
					"foodamount"=>$val['foodamount'],
					"foodunit" =>$val['foodunit'],
					"orderunit" =>$val['orderunit'],
					"foodrequest"	=>$val['foodrequest'],
					"cooktype" =>$val['cooktype'],
					"present"=>$val['present'],
					"ispack"=>$val['ispack'],
					"wait"  =>$orderfoodarr['wait'],
					"timestamp" =>$orderfoodarr['timestamp'],
			);
			$resultarr[$printerid][]=array("type"=>$op, "deviceno"=>$deviceno,"devicekey"=>$devicekey,"printertype"=>$printertype, "food"=>$temparr);
		}
		return $resultarr;
	}
	/* (non-PHPdoc)
	 * @see IPieceListDAL::fenzongList()
	 */
	public function fenzongList( $deviceno,$devicekey,$printertype,$orderfoodarr, $valarr) {
		// TODO Auto-generated method stub
// 		print_r($valarr);exit;
		$arr['zong']=array(
				"zonename"	=>$valarr[0]['zonename']."总单",
				"uid"=>$orderfoodarr['uid'],
				"shopid"=>$orderfoodarr['shopid'],
				"nickname"=>$orderfoodarr['nickname'],
				"shopname"=>$orderfoodarr['shopname'],
				"branchname"=>$orderfoodarr['branchname'],
				"wait"=>$orderfoodarr['wait'],
				"tabid"=>$orderfoodarr['tabid'],
				"takeout"=>$orderfoodarr['takeout'],
				"invoice"=>$orderfoodarr['invoice'],
				//"takeoutaddress"=>$orderfoodarr['takeoutaddress'],
				"orderrequest"=>$orderfoodarr['orderrequest'],//整单备注
				"discountype"=>$orderfoodarr['discountype'],
				"paytype"=>$orderfoodarr['paytype'],
				"paystatus"=>$orderfoodarr['paystatus'],
				"tabname"=>$orderfoodarr['tabname'],
				"cusnum"=>$orderfoodarr['cusnum'],
				"billno"=>$orderfoodarr['billno'],
// 				"paymoney"=>$orderfoodarr['paymoney'],
				"timestamp"=>$orderfoodarr['timestamp'],//下单时间
				"billstatus"=>$orderfoodarr['billstatus'],
				"details" =>$valarr
		);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPieceListDAL::doubleList()
	 */
	public function doubleList($op="double", $printerid,$deviceno,$devicekey,$printertype, $orderfoodarr,$valarr) {
		// TODO Auto-generated method stub
		$resultarr=array();
// 		print_r($valarr);exit;
		foreach ($valarr as $key=>$val){
			$temparr1=array(
					"zonename"=>$val['zonename'],
					"uid"=>$orderfoodarr['uid'],
					"shopid"=>$orderfoodarr['shopid'],
					"nickname"=>$orderfoodarr['nickname'],
					"shopname"=>$orderfoodarr['shopname'],
					"branchname"=>$orderfoodarr['branchname'],
					"wait"=>$orderfoodarr['wait'],
					"tabid"=>$orderfoodarr['tabid'],
					"takeout"=>$orderfoodarr['takeout'],
					"invoice"=>$orderfoodarr['invoice'],
					//"takeoutaddress"=>$orderfoodarr['takeoutaddress'],
					"orderrequest"=>$orderfoodarr['orderrequest'],//整单备注
					"discountype"=>$orderfoodarr['discountype'],
					"paytype"=>$orderfoodarr['paytype'],
					"paystatus"=>$orderfoodarr['paystatus'],
					"tabname"=>$orderfoodarr['tabname'],
					"cusnum"=>$orderfoodarr['cusnum'],
					"billno"=>$orderfoodarr['billno'],
					"timestamp"=>$orderfoodarr['timestamp'],//下单时间
					"billstatus"=>$orderfoodarr['billstatus'],
					"foodname"=>$val['foodname'],
					"foodamount"=>$val['foodamount'],
					"foodunit" =>$val['foodunit'],
					"orderunit" =>$val['orderunit'],
					"foodrequest"	=>$val['foodrequest'],
					"cooktype" =>$val['cooktype'],
					"present"=>$val['present'],
			);
			$resultarr[$printerid][]=array("type"=>$op,"deviceno"=>$deviceno, "devicekey"=>$devicekey,"printertype"=>$printertype, "food"=>$temparr1);
// 			$resultarr[]=array("type"=>$op,"deviceno"=>$deviceno,"devicekey"=>$devicekey, "food"=>$temparr1);
			$temparr2=array(
					"zonename"=>$val['zonename']."二联单",
					"uid"=>$orderfoodarr['uid'],
					"shopid"=>$orderfoodarr['shopid'],
					"nickname"=>$orderfoodarr['nickname'],
					"shopname"=>$orderfoodarr['shopname'],
					"branchname"=>$orderfoodarr['branchname'],
					"wait"=>$orderfoodarr['wait'],
					"tabid"=>$orderfoodarr['tabid'],
					"takeout"=>$orderfoodarr['takeout'],
					"invoice"=>$orderfoodarr['invoice'],
					//"takeoutaddress"=>$orderfoodarr['takeoutaddress'],
					"orderrequest"=>$orderfoodarr['orderrequest'],//整单备注
					"discountype"=>$orderfoodarr['discountype'],
					"paytype"=>$orderfoodarr['paytype'],
					"paystatus"=>$orderfoodarr['paystatus'],
					"tabname"=>$orderfoodarr['tabname'],
					"cusnum"=>$orderfoodarr['cusnum'],
					"billno"=>$orderfoodarr['billno'],
					"timestamp"=>$orderfoodarr['timestamp'],//下单时间
					"billstatus"=>$orderfoodarr['billstatus'],
					"foodname"=>$val['foodname'],
					"foodamount"=>$val['foodamount'],
					"foodunit" =>$val['foodunit'],
					"orderunit" =>$val['orderunit'],
					"foodrequest"	=>$val['foodrequest'],
					"cooktype" =>$val['cooktype'],
					"present"=>$val['present'],
			);
			$resultarr[$printerid][]=array("type"=>$op,"deviceno"=>$deviceno, "devicekey"=>$devicekey,"printertype"=>$printertype, "food"=>$temparr2);
// 			$resultarr[]=array("type"=>$op,"deviceno"=>$deviceno,"devicekey"=>$devicekey, "food"=>$temparr2);
		}
		return $resultarr;
	}
	
	public function TotalList($op="total",$printerid,$deviceno,$devicekey,$printertype,$orderfoodarr, $valarr){
		$resultarr=array();
		foreach ($valarr as $key=>$val){
			$temparr[]=array(
					"zonename"=>$val['zonename'],
					"uid"=>$orderfoodarr['uid'],
					"shopid"=>$orderfoodarr['shopid'],
					"nickname"=>$orderfoodarr['nickname'],
					"shopname"=>$orderfoodarr['shopname'],
					"branchname"=>$orderfoodarr['branchname'],
					"wait"=>$orderfoodarr['wait'],
					"tabid"=>$orderfoodarr['tabid'],
					"takeout"=>$orderfoodarr['takeout'],
					"invoice"=>$orderfoodarr['invoice'],
					// 					"takeoutaddress"=>$orderfoodarr['takeoutaddress'],
					"orderrequest"=>$orderfoodarr['orderrequest'],//整单备注
					"discountype"=>$orderfoodarr['discountype'],
					"paytype"=>$orderfoodarr['paytype'],
					"paystatus"=>$orderfoodarr['paystatus'],
					"tabname"=>$orderfoodarr['tabname'],
					"cusnum"=>$orderfoodarr['cusnum'],
					"billno"=>$orderfoodarr['billno'],
					// 					"paymoney"=>$orderfoodarr['paymoney'],
					"timestamp"=>$orderfoodarr['timestamp'],//下单时间
					"billstatus"=>$orderfoodarr['billstatus'],
					"foodname"=>$val['foodname'],
					"foodamount"=>$val['foodamount'],
					"foodunit" =>$val['foodunit'],
					"orderunit" =>$val['orderunit'],
					"foodrequest"	=>$val['foodrequest'],
					"cooktype" =>$val['cooktype'],
					"present"=>$val['present'],
					"wait"  =>$orderfoodarr['wait'],
					"timestamp" =>$orderfoodarr['timestamp'],
			);
			
		}
		$resultarr[$printerid][]=array("type"=>$op,"deviceno"=>$deviceno, "devicekey"=>$devicekey,"printertype"=>$printertype, "food"=>$temparr);
		return $resultarr;
	}

}
?>