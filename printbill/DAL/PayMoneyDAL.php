<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IPayMoneyDAL.php');
require_once ('/var/www/html/DALFactory.php');
class PayMoneyDAL implements IPayMoneyDAL{
	private static $bill="bill";
	private static $antibill="antibill";
	private static $sellpackage="sellpackage";
	private static $stock="stock";
	private static $food="food";
	private static $autostock="autostock";
	private static $balance = "wechat_balance";
	private static $monthstock = "month_stock";
	/* (non-PHPdoc)
	 * @see IPayMoneyDAL::updateCommonPayData()
	 */
	public function updateCommonPayData($inputarr){
		// TODO Auto-generated method stub
		if(!empty($inputarr['qrcode'])){$qrcode=$inputarr['qrcode'];}else{$qrcode="";}
		if(empty($inputarr['billid'])){return ;}
		$qarr=array("_id"=>new MongoId($inputarr['billid']));
		$oparr=array(
				"\$set"=>array(
						"paymethod"=>$inputarr['paymethod'],
						"cuspay"=>$inputarr['cuspay'], 
						"clearmoney"=>$inputarr['clearmoney'],
						"othermoney"=>$inputarr['othermoney'],
						"cashmoney"=>$inputarr['cashmoney'],
						"unionmoney"=>$inputarr['unionmoney'],
						"vipmoney"=>$inputarr['vipmoney'],
						"discountval"=>$inputarr['discountval'],
    				    "serverfee"=>$inputarr['serverfee'],
    				    "servermoney"=>$inputarr['servermoney'],
						"discountmode"=>$inputarr['discountmode'],
						"ticketval"=>$inputarr['ticketval'],
						"ticketnum"=>$inputarr['ticketnum'],
						"ticketway"=>$inputarr['ticketway'],
						"meituanpay"=>$inputarr['meituanpay'],
						"dazhongpay"=>$inputarr['dazhongpay'],
						"nuomipay"=>$inputarr['nuomipay'],
						"otherpay"=>$inputarr['otherpay'],
						"alipay"=>$inputarr['alipay'],
						"wechatpay"=>$inputarr['wechatpay'],
						"paytype"=>$inputarr['paytype'],
						"signername"=>"",
						"signerunit"=>"",
						"signmoney"=>"0",
						"freename"=>"",
						"freereason"=>"",
						"freemoney"=>"0",
						"returndepositmoney"=>$inputarr['returndepositmoney'],
						"paystatus"=>"paid",
						"billstatus"=>"done",
						"serverid"=>$inputarr['serverid'],
						"cashierman"=>$inputarr['cashierman'],
				        "paidorderrequest"=>$inputarr['paidorderrequest'],
						"qrcode"=>$qrcode,
						"buytime"=>time(),
				));
		$antibillexists=$this->judgeOneAntiBillExistByBillid($inputarr['billid']);//存不存在反结
		$billarr=$this->getOneBillInfoByBillid($inputarr['billid']);
		if(!$antibillexists && $billarr['paystatus']=="unpay"){
			$this->updateSelfStock($inputarr['billid']);
		}
		
		if(!$antibillexists && $billarr['paystatus']=="paid" && $inputarr['paystate']!="1"){//不存在反结，已买单
			$billarr['antitime']=time();
			DALFactory::createInstanceCollection(self::$antibill)->save($billarr);
		}
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		//更新到记录里
		
	}
	/* (non-PHPdoc)
	 * @see IPayMoneyDAL::updateSignPayData()
	 */
	public function updateSignPayData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($inputarr['billid']));
		$oparr=array(
				"\$set"=>array(
						"paymethod"=>$inputarr['paymethod'],
						"clearmoney"=>"0",
						"othermoney"=>"0",
						"cashmoney"=>"0",
						"unionmoney"=>"0",
						"vipmoney"=>"0",
						"discountval"=>"100",
						"discountmode"=>$inputarr['discountmode'],
						"ticketval"=>"0",
						"ticketnum"=>"0",
						"ticketway"=>"0",
						"meituanpay"=>"0",
						"dazhongpay"=>"0",
						"nuomipay"=>"0",
						"alipay"=>"0",
						"wechatpay"=>"0",
						"signername"=>$inputarr['signername'],
						"signerunit"=>$inputarr['signerunit'],
						"signmoney"=>$inputarr['signmoney'],
						"freename"=>"",
						"freereason"=>"",
						"freemoney"=>"0",
						"returndepositmoney"=>"0",
						"paystatus"=>"paid",
						"serverid"=>$inputarr['serverid'],
						"cashierman"=>$inputarr['cashierman'],
						"qrcode"=>"",
						"buytime"=>time(),
				));
		$billarr=$this->getOneBillInfoByBillid($inputarr['billid']);
		if($billarr['paystatus']=="unpay"){
			$this->updateSelfStock($inputarr['billid']);
		}
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		
	}

	/* (non-PHPdoc)
	 * @see IPayMoneyDAL::updateFreePayData()
	 */
	public function updateFreePayData($inputarr) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($inputarr['billid']));
		$oparr=array(
				"\$set"=>array(
						"paymethod"=>$inputarr['paymethod'],
						"clearmoney"=>"0",
						"othermoney"=>"0",
						"cashmoney"=>"0",
						"unionmoney"=>"0",
						"vipmoney"=>"0",
						"discountval"=>"100",
						"ticketval"=>"0",
						"discountmode"=>$inputarr['discountmode'],
						"ticketnum"=>"0",
						"ticketway"=>"0",
						"meituanpay"=>"0",
						"dazhongpay"=>"0",
						"nuomipay"=>"0",
						"alipay"=>"0",
						"wechatpay"=>"0",
						"signername"=>"0",
						"signerunit"=>"0",
						"signmoney"=>"0",
						"freename"=>$inputarr['freename'],
						"freereason"=>$inputarr['freereason'],
						"freemoney"=>$inputarr['freemoney'],
						"returndepositmoney"=>"0",
						"paystatus"=>"paid",
						"serverid"=>$inputarr['serverid'],
						"cashierman"=>$inputarr['cashierman'],
						"qrcode"=>"",
						"buytime"=>time(),
				));
		$billarr=$this->getOneBillInfoByBillid($inputarr['billid']);
		if($billarr['paystatus']=="unpay"){
			$this->updateSelfStock($inputarr['billid']);
		}
		DALFactory::createInstanceCollection(self::$bill)->update($qarr,$oparr);
		
	}
	
	public function judgeOneAntiBillExistByBillid($billid){
		if(empty($billid)){return false;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$antibill)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;//存在
		}else{
			return false;
		}
	}
	
	public function getOneBillInfoByBillid($billid){
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
		}
		return $result;
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
	
	public function updateSelfStock($billid){
		if(empty($billid)){return ;}
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("food"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		if(!empty($result)){
			foreach ($result['food'] as $key=>$val){
				$auto=$this->judgeAutoStock($val['foodid']);
				if($auto=="1"){
					$this->updateSelfStocknum($val['foodid'], $val['foodamount']);
				}
			}
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
	public function changeMonthStock($foodid,$num){
	    $qarr=array("foodid"=>$foodid,"month"=>date('Y-m'));
	    $oparr=array("_id"=>1,"num"=>1);
	    $result=DALFactory::createInstanceCollection(self::$monthstock)->findOne($qarr,$oparr);
	    if(!empty($result)){
	        $oparr=array("\$set"=>array("num"=>$num));
	        DALFactory::createInstanceCollection(self::$monthstock)->update($qarr,$oparr);
	    }else{
	        $shopid=$this->getShopidByFoodid($foodid);
	        if(!empty($shopid)){
	            $arr['foodid'] =$foodid;
	            $arr['shopid'] =$shopid;
	            $arr['num'] = $num;
	            $arr['month'] = date('Y-m');
	            $arr['timestamp'] = time();
	            DALFactory::createInstanceCollection(self::$monthstock)->save($arr);
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
			$this->changeMonthStock($foodid, $num);
		}
	}
	public function addBalance($shopid,$paymoney){
	    // 如果为新店时，应该当使用save方法
	    $qarr = array('shopid' => $shopid);
	    $res = DALFactory::createInstanceCollection(self::$balance)->findOne($qarr);
	   if(empty($res))
	   {
	      $arr = array('shopid' => $shopid,'money'=>$paymoney);
	      DALFactory::createInstanceCollection(self::$balance)->save($arr);
	   }
	   else
	   {
	      $money = $res['money']+$paymoney;
	      $oparr = array("\$set"=> array("money"=>$money));
	       DALFactory::createInstanceCollection(self::$balance)->update($qarr,$oparr);
	   }
	}
	public function getShopidByFoodid($foodid)
	{
	    if(empty($foodid)){return "";}
	   $qarr=array("_id"=>new MongoId($foodid));
	   $res = DALFactory::createInstanceCollection(self::$food)->findOne($qarr);
	   $shopid="";
	   if(!empty($res)){
	       $shopid = $res['shopid'];
	   }
	   return $shopid;
	}
}
?>