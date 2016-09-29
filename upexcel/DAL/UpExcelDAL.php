<?php 
require_once ('/var/www/html/upexcel/global.php');
require_once (EXCEL_DOCUMENT_ROOT.'IDAL/IUpExcelDAL.php');
require_once ('/var/www/html/DALFactory.php');
class UpExcelDAL implements IUpExcelDAL{
    private static $food="food";
    private static $foodtype="foodtype";
    private static $printer="printer";
    private static $zone="zone";
	/* (non-PHPdoc)
     * @see IUpExcelDAL::getFoodInfo()
     */
    public function getFoodInfo($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array(
           		"_id"=>1,
				"foodname"=>1,
        		"foodcode"=>1,
				"foodprice"=>1,
				"foodunit"=>1,
				"orderunit"=>1,
        		"foodcooktype"=>1,
				"foodtypeid"=>1,
				"zoneid"=>1,
				"fooddisaccount"=>1,
				"isweight"=>1,
				"ishot"=>1,
				"ispack"=>1,
        		"foodintro"=>1,
        );
        $result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr);
        $arr=array();
        foreach ($result as $key=>$val){
            $arr[]=array(
               "foodname"=>$val['foodname'],
        		"foodcode"=>$val['foodcode'],
				"foodprice"=>$val['foodprice'],
				"foodunit"=>$val['foodunit'],
				"orderunit"=>$val['orderunit'],
        		"foodcooktype"=>$val['foodcooktype'],
				"foodtypeid"=>$val['foodtypeid'],
				"zoneid"=>$val['zoneid'],
				"fooddisaccount"=>$val['fooddisaccount'],
				"isweight"=>$val['isweight'],
				"ishot"=>$val['ishot'],
				"ispack"=>$val['ispack'],
        		"foodintro"=>$val['foodintro'],
            );
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IUpExcelDAL::getFoodTypeList()
     */
    public function getFoodTypeList($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("_id"=>1,"foodtypename"=>1);
        $result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr);
        $arr=array();
        foreach ($result as $key=>$val){
            $arr[]=array("foodtypename"=>$val['foodtypename'],"ftid"=>strval($val['_id']));
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IUpExcelDAL::getPrinterList()
     */
    public function getPrinterList($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("remark"=>1,"_id"=>1);
        $arr=array();
        $result=DALFactory::createInstanceCollection(self::$printer)->find($qarr,$oparr);
        foreach ($result as $key=>$val){
            $arr[]=array(
                "printerid"=>strval($val['_id']),
                 "remark"=>$val['remark']
            );
        }
        return $arr;
    }

	/* (non-PHPdoc)
     * @see IUpExcelDAL::getZoneList()
     */
    public function getZoneList($shopid)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid);
        $oparr=array("_id"=>1, "zonename"=>1);
        $result=DALFactory::createInstanceCollection(self::$zone)->find($qarr,$oparr);
        $arr=array();
        foreach ($result as $key=>$val){
            $arr[]=array("zoneid"=>strval($val['_id']),"zonename"=>$val['zonename']);
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IUpExcelDAL::xlsDataToDb()
     */
    public function xlsDataToDb($shopid, $foodarr)
    {
        // TODO Auto-generated method stub
        foreach ($foodarr as $rowkey=>$val){
            if($rowkey==0){continue;}
            $ftid=$val[0];
            $foodname=$val[1];
            $zoneid=$val[2];
            $foodcode=$val[3];
            $foodprice=$val[4];
            $foodunit=$val[5];
            $orderunit=$val[6];
            $cooktype=$val[7];
            if(empty($cooktype)){$cooktype="";}
            $fooddisaccount=$val[8];
            $isweight=$val[9];
            $ishot=$val[10];
            $ispackage=$val[11];
        	 if(!empty($val[12])){$foodintro=$val[12];}else{$foodintro="";}
            if(empty($ftid)){//类别id
            	return array("status"=>"error","code"=>"ftid_error");
            }elseif(empty($foodname)){//商品名
                return array("status"=>"error","code"=>"foodname_error");
            }elseif (empty($zoneid)){//档口id
                return array("status"=>"error","code"=>"zid_error");
            }elseif (empty($foodprice)&&$foodprice!="0"){//单价
                return array("status"=>"error","code"=>"foodprice_error");
            }elseif (empty($foodunit)){//计量单位
                return array("status"=>"error","code"=>"foodunit_error");
            }elseif(empty($orderunit)){//点菜单位
            	return array("status"=>"error","code"=>"orderunit_error");
            }elseif(empty($fooddisaccount)&&$fooddisaccount!="0"){//优惠
            	return array("status"=>"error","code"=>"disacount_error");
            }elseif(empty($isweight)&&$isweight!="0"){//称重
            	return array("status"=>"error","code"=>"weight_error");
            }elseif(empty($ishot)&&$ishot!="0"){//hot
            	return array("status"=>"error","code"=>"hot_error");
            }elseif(empty($ispackage)&&$ispackage!="0"){//套餐
            	return array("status"=>"error","code"=>"package_error");
            }else {
               
                $qarr=array(
                    "shopid"=>$shopid,
	 				"foodname"=>$foodname,
	 				"foodcode"=>strval($foodcode),
	 				"foodprice"=>strval($foodprice),
	 				"foodunit"=>$foodunit,
	 				"orderunit"=>$orderunit,
	 				"foodtypeid"=>$ftid,
	 				"foodcooktype"=>$cooktype,
	 				"zoneid"=>$zoneid,
	 				"fooddisaccount"=>strval($fooddisaccount),
	 				"foodguqing"=>"0",
	 				"isweight"=>strval($isweight),
	 				"ishot"=>strval($ishot),
	 				"ispack"=>strval($ispackage),
	 				"foodpic"=>"",
	 				"foodintro"=>$foodintro,
	 				"addtime"=>time(),
	 				"fooduptime"=>time(),
                );
//             print_r($qarr);exit;
                DALFactory::createInstanceCollection(self::$food)->save($qarr);
            }
        }
        return array("status"=>"ok","code"=>"ok");
    }
	/* (non-PHPdoc)
     * @see IUpExcelDAL::getPrinterIdByZoneId()
     */
    public function getPrinterIdByZoneId($zoneid)
    {
        // TODO Auto-generated method stub
        $qarr=array("pos"=>$zoneid,"outputtype"=>array("\$in"=>array("4","5","6")));
        $oparr=array("_id"=>1);
        $result=DALFactory::createInstanceCollection(self::$printer)->findOne($qarr,$oparr);
        $arr=array();
        if(!empty($result)){
            $arr=array("printerid"=>strval($result['_id']));
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IUpExcelDAL::object_to_array()
     */
    public function object_to_array($obj)
    {
        // TODO Auto-generated method stub
        $_arr = is_object($obj)? get_object_vars($obj) :$obj;
        foreach ($_arr as $key => $val){
            $val=(is_array($val)) || is_object($val) ? object_to_array($val) :$val;
            $arr[$key] = $val;
        }
        return $arr;
    }



	
}
?>