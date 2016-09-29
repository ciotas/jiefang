<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/bill/global.php');
require_once (DOCUMENT_ROOT.'IDAL/IBillDataDAL.php');
// require_once ('/var/www/html/DALFactory.php');
require_once (DOCUMENT_ROOT.'Factory/DALFactory.php');
class BillDataDAL implements IBillDataDAL{
	private static $bill="bill";
	private static $shopinfo="shopinfo";
	/* (non-PHPdoc)
	 * @see IBillDataDAL::getBillViewData()
	 */
	public function getBillViewData($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid);
		$oparr=array("_id"=>1, "paymoney"=>1,"timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array(
					"billid"=>strval($val['_id']),
					"paymoney"	=>$val['paymoney'],
					"time"=>date("Y-m-d",$val['timestamp'])
			);
		}
		if(!empty($arr)){
			$arr=$this->array_sort($arr, "time","desc");
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDataDAL::getBillData()
	 */
	public function getBillData($billid) {
		global $square100;
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array(
				"shopid"=>1,
				"tabname"=>1,
				"cusnum"=>1,
				"totalmoney"=>1,
				"disacountmoney"=>1,
				"paymoney"=>1,
				"discounttitle"=>1,
				"discountvalue"=>1,
				"time"=>1,
				"food"=>1,
				"service"=>1,
				"orderno"=>1
		);
		$result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
		$arr=array();
		if(is_array($result)&&!empty($result)){		
			$shoparr=$this->getShopInfo($result['shopid']);
			if(empty($shoparr)){return array();}
			$arr=array(
					"shopid"=>$result['shopid'],
					"thumblogo"	=>$shoparr['thumblogo'],
					"shopname"=>$shoparr['shopname'],
					"address"=>$shoparr['city'].$shoparr['district'].$shoparr['road'],
					"orderno"=>$result['orderno'],
					"tabname"=>$result['tabname'],
					"cusnum"=>$result['cusnum'],
					"totalmoney"=>$result['totalmoney'],
					"disacountmoney"=>$result['disacountmoney'],
					"paymoney"=>$result['paymoney'],
					"discounttitle"=>$result['discounttitle'],
					"discountvalue"=>$result['discountvalue'],
					"time"=>date("Y-m-d H:i:s",$result['time']),
					"food"=>$result['food'],
					"service"=>$result['service']
				);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillDataDAL::getShopInfo()
	 */
	public function getShopInfo($shopid) {
		// TODO Auto-generated method stub
		global $square100;
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("thumblogo"=>1,"shopname"=>1,"branchname"=>1, "city"=>1,"district"=>1,"road"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$shoparr=array();
		if(is_array($result)&&!empty($result)){
			if(!empty($result['thumblogo'])){$thumblogo=$result['thumblogo'];}else{$thumblogo="http://jfoss.meijiemall.com/logo/default_shop_logo.jpg".$square100;}
			if(!empty($result['shopname'])){$shopname=$result['shopname'];}else{$shopname="";}
			if(!empty($result['city'])){$city=$result['city'];}else{$city="";}
			if(!empty($result['district'])){$district=$result['district'];}else{$district="";}
			if(!empty($result['road'])){$road=$result['road'];}else{$road="";}
			$shoparr=array(
					"thumblogo"=>$thumblogo,
					"shopname"=>$shopname,
					"branchname"=>$result['branchname'],
					"city"=>$city,
					"district"=>$district,
					"road"=>$road
			);
		}
		return $shoparr;
	}
	/* (non-PHPdoc)
	 * @see IBillDataDAL::array_sort()
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
     * @see IBillDataDAL::getBillInfoById()
     */
    public function getBillInfoById($billid)
    {
        // TODO Auto-generated method stub
        $qarr=array("_id"=>new MongoId($billid));
        $oparr=array("disacountfoodmoney"=>1,"totalmoney"=>1,"paymoney"=>1,"discountdesc"=>1);
        $result=DALFactory::createInstanceCollection(self::$bill)->findOne($qarr,$oparr);
        $arr=array();
        if(!empty($result)){
            if(!empty($result['discountdesc'])){$discountdesc=$result['discountdesc'];}else{$discountdesc="未使用其他优惠";}
            $arr=array(
                "totalmoney"  =>$result['totalmoney'],
                "disacountfoodmoney"=>$result['disacountfoodmoney'],
                "paymoney"=>sprintf("%.2f",$result['paymoney']),
                "discountdesc" =>$discountdesc
            );
        }
        return $arr;
    }

   
}
?>