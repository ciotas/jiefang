<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IWorkerTwoDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');

require_once ('WorkerDAL.php');

class WorkerTwoDAL implements IWorkerTwoDAL{
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
	private static $payrecord="payrecord";
	private static $shop_info="shop_info";
	private static $billshopinfo="billshopinfo";
	private static $prebillshopinfo="prebillshopinfo";
	private static $sheetno="sheetno";
	private static $wechat_user_info="wechat_user_info";
	private static $wechat_shop="wechat_shop";
	private static $returnfoodrecord="returnfoodrecord";
	private static $switchfoodrecord="switchfoodrecord";
	private static $switchtabrecord="switchtabrecord";
	private static $replacefoodrecord="replacefoodrecord";
	private static $change_tabstatus_record="change_tabstatus_record";
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getHistoryLists()
	 */
	public function getHistoryLists($shopid,$uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid);
		$result=DALFactory::createInstanceCollection(self::$payrecord)->find($qarr);
		$arr=array();
		$workerdal=new WorkerDAL();
		foreach ($result as $key=>$val){
			$shopinfo=$workerdal->getShopInfo($val['shopid']);
			$arr[]=array(
					"billid"=>$val['billid'],
					"time"=>$val['gmt_payment'],
					"orderno"=>$val['orderno'],
					"paymoney"=>$val['total_fee'],	
					"shopname"=>$shopinfo['shopname'],
			);
		}
		$arr=$this->array_sort($arr, "time",desc);
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::array_sort()
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
	 * @see IWorkerTwoDAL::intoShop_infoData()
	 */
	public function intoShop_infoData($inputarr) {
		// TODO Auto-generated method stub
		if(!empty($inputarr['shopid'])){
			$qarr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid']);
			$oparr=array("_id"=>1);
			$result=DALFactory::createInstanceCollection(self::$shop_info)->findOne($qarr,$oparr);
			if(!empty($result)){
				$oparr=array(
					"\$set"=>array(
							"prov"=>$inputarr['prov'],
							"city"=>$inputarr['city'],
							"dist"=>$inputarr['dist'],
							"road"=>$inputarr['road'],
							"shopname"=>$inputarr['shopname'],
							"contact"=>$inputarr['contact'],
							"phone"=>$inputarr['phone'],
							"picktime"=>$inputarr['picktime'],
							"orderrequest"=>$inputarr['orderrequest'],
					)
				);
				DALFactory::createInstanceCollection(self::$shop_info)->update($qarr,$oparr);
			}else{
				DALFactory::createInstanceCollection(self::$shop_info)->save($inputarr);
			}
		}
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getOneShop_infoData()
	 */
	public function getOneShop_infoData($shopid,$uid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid,"uid"=>$uid);
		$result=DALFactory::createInstanceCollection(self::$shop_info)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=$result;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::intoBillShopinfo()
	 */
	public function intoBillShopinfo($inputarr,$tab) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$inputarr['billid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection($tab)->findOne($qarr,$oparr);
		if(!empty($result)){//修改
			$oparr=array(
				"\$set"=>array(
						"prov"=>$inputarr['prov'],
						"city"=>$inputarr['city'],
						"dist"=>$inputarr['dist'],
						"road"=>$inputarr['road'],
						"shopname"=>$inputarr['shopname'],
						"contact"=>$inputarr['contact'],
						"phone"=>$inputarr['phone'],
						"picktime"=>$inputarr['picktime'],	
				)	
			);
			DALFactory::createInstanceCollection($tab)->update($qarr,$oparr);
		}else{
			DALFactory::createInstanceCollection($tab)->save($inputarr);
		}
		
	}
	
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getOneBillShopinfo()
	 */
	public function getOneBillShopinfo($tab, $billid) {
		// TODO Auto-generated method stub
		$qarr=array("billid"=>$billid);
		return DALFactory::createInstanceCollection($tab)->findOne($qarr);
		
	}
	/* (non-PHPdoc)
	 * @see IWorkerTwoDAL::getVipdiscount()
	 */
	public function getVipdiscount($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("vipdiscount"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$vipdiscount=100;
		if(!empty($result['vipdiscount'])){
			$vipdiscount=$result['vipdiscount'];
		}
		return $vipdiscount;
	}
	
	public function addBillNum($shopid,$billid,$theday){
		$qarr=array("shopid"=>$shopid,"theday"=>$theday);
		$oparr=array("no"=>1);
		$result=DALFactory::createInstanceCollection(self::$sheetno)->find($qarr,$oparr)->sort(array("no"=>-1))->limit(1);
		if(!empty($result)){
			foreach ($result as $key=>$val){
				$no=strval($val['no']);break;
			}
			$no+=1;
		}else{
			$no=1;
		}
		$arr=array("shopid"=>$shopid,"billid"=>$billid, "theday"=>$theday,"no"=>$no);
		DALFactory::createInstanceCollection(self::$sheetno)->save($arr);
	}
	
	public function getBillNum($billid){
		$qarr=array("billid"=>$billid);
		$oparr=array("no"=>1);
		$result=DALFactory::createInstanceCollection(self::$sheetno)->findOne($qarr,$oparr);
		$no="";
		if(!empty($result)){
			$no=$result['no'];
		}
		return $no;
	}
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::isBindShopByOpenid()
     */
    public function isBindShopByOpenid($openid)
    {
        // TODO Auto-generated method stub
        $qarr=array("openid"=>$openid);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$wechat_shop)->findOne($qarr,$oparr);
        if(!empty($result)){
            return "1";
        }else{
            return "0";
        }
    }
    public function intoInnerShop_infoData($inputarr){
        if(!empty($inputarr['shopid'])){
            $qarr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid']);
            $oparr=array("_id"=>1);
            $result=DALFactory::createInstanceCollection(self::$shop_info)->findOne($qarr,$oparr);
            if(!empty($result)){
                $oparr=array(
                    "\$set"=>array(
                        "distribution"=>$inputarr['distribution'],
                        "porttype"=>$inputarr['porttype'],
                        "carno"=>$inputarr['carno'],
                        "author"=>$inputarr['author'],
                        "prov"=>$inputarr['prov'],
                        "city"=>$inputarr['city'],
                        "dist"=>$inputarr['dist'],
                        "road"=>$inputarr['road'],
                        "shopname"=>$inputarr['shopname'],
                        "contact"=>$inputarr['contact'],
                        "phone"=>$inputarr['phone'],
                        "picktime"=>$inputarr['picktime'],
                        "orderrequest"=>$inputarr['orderrequest'],
                    )
                );
                DALFactory::createInstanceCollection(self::$shop_info)->update($qarr,$oparr);
            }else{
                DALFactory::createInstanceCollection(self::$shop_info)->save($inputarr);
            }
        }
    }
    /**
     * {@inheritDoc}
     * @see IWorkerTwoDAL::intoBillInnerShopinfo()
     */
    public function intoBillInnerShopinfo($inputarr, $tab)
    {
        // TODO Auto-generated method stub
        $qarr=array("billid"=>$inputarr['billid']);
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection($tab)->findOne($qarr,$oparr);
        if(!empty($result)){//修改
            $oparr=array(
                  "distribution"=>$inputarr['distribution'],
                        "porttype"=>$inputarr['porttype'],
                        "carno"=>$inputarr['carno'],
                        "author"=>$inputarr['author'],
                        "prov"=>$inputarr['prov'],
                        "city"=>$inputarr['city'],
                        "dist"=>$inputarr['dist'],
                        "road"=>$inputarr['road'],
                        "shopname"=>$inputarr['shopname'],
                        "contact"=>$inputarr['contact'],
                        "phone"=>$inputarr['phone'],
                        "picktime"=>$inputarr['picktime'],
                        "orderrequest"=>$inputarr['orderrequest'],
                
            );
            DALFactory::createInstanceCollection($tab)->update($qarr,$oparr);
        }else{
            DALFactory::createInstanceCollection($tab)->save($inputarr);
        }
    }


}

?>