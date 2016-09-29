<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IToBePointDAL.php');
// require_once ('/var/www/html/DALFactory.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/DALFactory.php');
class ToBePointDAL implements IToBePointDAL{
    private static $businessprinter="businessprinter";
    private static $food="food";
	/* (non-PHPdoc)
     * @see IToBePointDAL::getThePointPrinter()
     */
    public function getThePointPrinter($inputarr,$shopid,$foodid)
    {
        // TODO Auto-generated method stub
        $printerarr=$this->getPrinterInfoByFoodid($foodid);
//         print_r($printerarr);exit;
        if(empty($printerarr)){return array();}
        $printerid=$printerarr['printerid'];
        $printerinfoarr=$this->getApiCodeByPrinterid($printerid);
        if(empty($printerinfoarr)){return array();}
        $arr[]=array(
            "printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
            "deviceno"=>$printerinfoarr['deviceno'],
            "devicekey"=>$printerinfoarr['devicekey'],
            "outputtype"=>"10",
            'msg'=>$this->getPrintContent($inputarr,$printerinfoarr)
        );
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IToBePointDAL::getPrinterInfoByFoodid()
     */
    public function getPrinterInfoByFoodid($foodid)
    {
        // TODO Auto-generated method stub
        $qarr=array("_id"=>new MongoId($foodid));
        $oparr=array("printerid"=>1);
        $result=PRINT_DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
        $arr=array();
        if(!empty($result)){
            $arr=array("printerid"=>$result['printerid']);
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IToBePointDAL::getApiCodeByPrinterid()
     */
    public function getApiCodeByPrinterid($printerid)
    {
        // TODO Auto-generated method stub
        $qarr=array("_id"=>new MongoId($printerid));
        $oparr=array("deviceno"=>1,"devicekey"=>1);
        $result=PRINT_DALFactory::createInstanceCollection(self::$businessprinter)->findOne($qarr,$oparr);
        $arr=array();
        if(!empty($result)){
            $arr=array("deviceno"=>$result['deviceno'],"devicekey"=>$result['devicekey']);
        }
        return $arr;
    }
	/* (non-PHPdoc)
     * @see IToBePointDAL::getPrintContent()
     */
    public function getPrintContent($inputarr, $printerinfoarr)
    {
        // TODO Auto-generated method stub
        $orderInfo='';
        $orderInfo .='<BR>';
        $orderInfo .= '<CB>积分兑换单</CB>';
        $orderInfo .= '!0a1d@!<BR>';
        $orderInfo.='顾客：'.$inputarr['nickname'].'<BR>';
        $orderInfo.='人数：'.$inputarr['cusnum'].' <BR>';
        $orderInfo .='---------------------------------------------<BR>';
        $orderInfo.="<B> ".$inputarr['pointstr'].'</B><BR>';
        $orderInfo .='---------------------------------------------<BR>';
        $orderInfo .= '!0a1d@!';
        $orderInfo.='时间：'.date('Y-m-d H:i:s',$inputarr['timestamp']).'<BR>';
        $orderInfo.='<BR>';
        
        $selfMessage = array(
          	 'sn'=>$printerinfoarr['deviceno'],
			'printContent'=>$orderInfo,
// 			'apitype'=>'php',
			'key'=>$printerinfoarr['devicekey'],
			'times'=>1
        );
        return $selfMessage;
    }
	/* (non-PHPdoc)
     * @see IToBePointDAL::findThePrinter()
     */
    public function findThePrinter($shopid, $outputtype)
    {
        // TODO Auto-generated method stub
        $qarr=array("shopid"=>$shopid,"outputtype"=>strval($outputtype),"openorclose"=>"open");//测试
        //$qarr=array("shopid"=>$shopid,"outputtype"=>strval($outputtype));//正式
        $oparr=array("deviceno"=>1,"devicekey"=>1);
        $restult=PRINT_DALFactory::createInstanceCollection(self::$businessprinter)->find($qarr,$oparr);
        return $restult;
    }
	/* (non-PHPdoc)
     * @see IToBePointDAL::getTobePointOther()
     */
    public function getTobePointOther($inputarr)
    {
        // TODO Auto-generated method stub
        $shopprinters=$this->findThePrinter($inputarr['shopid'], "1");
		$arr=array();
		foreach ($shopprinters as $val){
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"outputtype"=>"9",
					'msg'=>$this->getPrintContent($inputarr,$val)
			);
		}
		return $arr;
    }



    
}
?>