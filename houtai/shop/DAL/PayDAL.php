<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'IDAL/IPayDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once ('/var/www/html/des.php');
class PayDAL implements IPayDAL{
	private static $billid="billid";
	private static $payrecord="payrecord";
	private static $shopinfo="shopinfo";
	private static $table="table";
	private static $buy_goods_record="buy_goods_record";
	/* (non-PHPdoc)
	 * @see IPayDAL::addPayRecord()
	 */
	public function addPayRecord($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$payrecord)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IPayDAL::getPayRecordData()
	 */
	public function getPayRecordData($trade_no,$billid) {
		// TODO Auto-generated method stub
		$qarr=array("trade_no"=>$trade_no, "billid"=>$billid);
		$oparr=array(
				"out_trade_no"=>1,
				"trade_no"=>1,
				"billid"	=>1,
				"shopid"=>1,
				"uid"=>1,
				"buyer"=>1,
				"tabid"=>1,
				"paymoney"=>1,
				"downtime"=>1,
				"buytime"=>1,
				"buyemail"=>1,
		);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$payrecord)->findOne($qarr,$oparr);
		if(!empty($result)){
			$shoparr=$this->getShopInfo($result['shopid']);
			$shopname="";
			if(!empty($shoparr)){
				$shopname=$shoparr['shopname'];
			}
			$tabname=$this->getTablenameByTabid($result['tabid']);
			$arr=array(
					"out_trade_no"=>$result['out_trade_no'],
					"trade_no"=>$result['trade_no'],
					"billid"	=>$result['billid'],
					"shopid"=>$result['shopid'],
					"shopname"=>$shopname,
					"uid"=>$result['uid'],
					"buyer"=>$result['buyer'],
					"tabid"=>$result['tabid'],
					"tabname"=>$tabname,
					"paytype"=>$result['paytype'],
					"paymoney"=>$result['paymoney'],
					"downtime"=>$result['downtime'],
					"buytime"=>$result['buytime'],
					"buyemail"=>$result['buyemail'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPayDAL::getShopInfo()
	 */
	public function getShopInfo($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1,"branchname"=>1,"logo"=>1,"depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		
		$arr=array();
		if(!empty($result)){
			if(!empty($result['logo'])){$logo=$result['logo'];}else{$logo="http://jfoss.meijiemall.com/food/default_food.png";}
			$arr=array(
					"shopid"=>strval($result['_id']),
					"shopname"=>$result['shopname']." ".$result['branchname'],
					"logo"=>$logo,
					"depositmoney"=>$result['depositmoney'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IPayDAL::getTablenameByTabid()
	 */
	public function getTablenameByTabid($tabid) {
		// TODO Auto-generated method stub
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
	 * @see IPayDAL::getYearfeeMoney()
	 */
	public function getYearfeeMoney($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return 0;}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("yearfee"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$yearfee=0;
		if(!empty($result['yearfee'])){
			$yearfee=$result['yearfee'];
		}
		return $yearfee;
	}
	/* (non-PHPdoc)
	 * @see IPayDAL::addBuyGoodsRecord()
	 */
	public function addBuyGoodsRecord($inputarr) {
		// TODO Auto-generated method stub
		DALFactory::createInstanceCollection(self::$buy_goods_record)->save($inputarr);
	}
	/* (non-PHPdoc)
	 * @see IPayDAL::getBuyGoodsRecordData()
	 */
	public function getOneBuyGoodsRecordData($id) {
		// TODO Auto-generated method stub
		if(empty($id)){return array();}
		$qarr=array("_id"=>new MongoId($id));
		$result=DALFactory::createInstanceCollection(self::$buy_goods_record)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
			$arr['id']=strval($result['_id']);
		}
		return $arr;
	}


	
}

?>