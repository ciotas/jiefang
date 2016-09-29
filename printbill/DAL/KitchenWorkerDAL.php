<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IKitchenWorkerDAL.php');
require_once ('/var/www/html/DALFactory.php');
class KitchenWorkerDAL implements IKitchenWorkerDAL{
	private static $table="table";
	private static $bill="bill";
	private static $coupontype="coupontype";
	private static $shopinfo="shopinfo";
	private static $foodtype="foodtype";
	/* (non-PHPdoc)
	 * @see IKitchenWorkerDAL::PrintData()
	 */
	public function PrintKitchenData($json) {
		// TODO Auto-generated method stub
		$kitchenkeyarr=json_decode($json,true);
		if(empty($kitchenkeyarr)){return array();}
		$arr=array();
		foreach ($kitchenkeyarr as $key=>$val){
			foreach ($val as $printerid=>$ary){
				foreach ($ary as $keynum=>$food_type){
					$type=$food_type['type'];
					$devicekey=$food_type['devicekey'];
					$deviceno=$food_type['deviceno'];
					$printertype=$food_type['printertype'];
					$msg=array();
					switch ($type){
						case "single":
							if($printertype=="58"){
								$msg=$this->create_F_SmallContentHtml($deviceno,$devicekey,$food_type['food']);
							}else{
								$msg=$this->create_F_ContentHtml($deviceno,$devicekey,$food_type['food']);
							}
						$resultarr[]=array(
								"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
								"deviceno"=>$deviceno,
								"devicekey"=>$devicekey,
								"printertype"=>$printertype,
								"outputtype"=>"single",
								"msg"=>$msg
						);break;
						case "subtotal":
							if(key_exists("zong", $food_type)){
								if($printertype=="58"){
									$msg=$this->create_FZ_SmallContentHtml($deviceno,$devicekey,$food_type['zong']);
								}else{
									$msg=$this->create_FZ_ContentHtml($deviceno,$devicekey,$food_type['zong']);
								}
								
							}else{
								if($printertype=="58"){
									$msg=$this->create_F_SmallContentHtml($deviceno,$devicekey,$food_type['food']);
								}else{
									$msg=$this->create_F_ContentHtml($deviceno,$devicekey,$food_type['food']);
								}
								
							}
							$resultarr[]=array(
									"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
									"deviceno"=>$deviceno,
									"devicekey"=>$devicekey,
									"printertype"=>$printertype,
									"outputtype"=>"subtotal",
									"msg"=>$msg
							);break;
						case "double":
							if($printertype=="58"){
								$msg=$this->create_F_SmallContentHtml($deviceno,$devicekey,$food_type['food']);
							}else{
								$msg=$this->create_F_ContentHtml($deviceno,$devicekey,$food_type['food']);
							}
							
						$resultarr[]=array(
								"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
								"deviceno"=>$deviceno,
								"devicekey"=>$devicekey,
								"printertype"=>$printertype,
								"outputtype"=>"double",
								"msg"=>$msg
						);break;
						case "total":
							if($printertype=="58"){
								$msg=$this->create_Total_SmallContentHtml($food_type['zong'], $deviceno, $devicekey);
							}else{
								$msg=$this->create_Total_ContentHtml($food_type['zong'], $deviceno, $devicekey);
							}
							
							$resultarr[]=array(
									"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
									"deviceno"=>$deviceno,
									"devicekey"=>$devicekey,
									"printertype"=>$printertype,
									"outputtype"=>"total",
									"msg"=>$msg
							);
							break;
						default:if(key_exists("zong", $food_type)){
							$msg=$this->create_FZ_ContentHtml($deviceno,$devicekey,$food_type['zong']);}else{
								$msg=$this->create_F_ContentHtml($deviceno,$devicekey,$food_type['food']);
							}
							$resultarr[]=array(
									"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
									"deviceno"=>$deviceno,
									"devicekey"=>$devicekey,
									"outputtype"=>"subtotal",
									"msg"=>$msg
							);break;
					}
				}		
			}
		}
		return $resultarr;
	}
	
	
	/* (non-PHPdoc)
	 * @see IKitchenWorkerDAL::create_F_ContentHtml()
	 */
	public function create_F_ContentHtml($deviceno,$devicekey,$arr) {
		// TODO Auto-generated method stub
// 		$tablename=$this->getTabnameByTabid($arr['tabid']);
		$donatestr="";
		if($arr['present']=="1"){
			$donatestr="[赠]";
		}
		if($arr['ispack']=="1"){return array();}//套餐不打印
		
		if($arr['wait']=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外送]";}elseif ($arr['takeout']=="0"){$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<CB>'.$arr['zonename'].$takeoutstr.'</CB><BR>';
		if(!empty($arr['billno'])){
			$orderInfo .= '<CB>单号：'.$arr['billno'].'</CB><BR>';
		}
		if(!empty($arr['tabname'])){
			$orderInfo.='<B>台号：'.$arr['tabname'].'<B>';
		}
		$orderInfo.='<B>@'.$waitstr.'@<B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='下单人：'.$arr['nickname'].'  人数：'.$arr['cusnum'].' <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$cooktype="";
		$thepackname="";
		if(!empty($arr['cooktype'])){
			$cooktype="(".$arr['cooktype'].")";
		}
		if(!empty($arr['thepackname'])){
// 			$thepackname="(".$arr['thepackname'].")";
			$thepackname="(套餐)";
		}
		$orderInfo.='<B>'.$arr['foodamount']."".$arr['foodunit'].'×'.$arr['foodname'].$thepackname.$cooktype.$donatestr.'</B><BR>';
		if(!empty($arr['foodrequest'])){
			$orderInfo.='<B>'.$arr['foodrequest'].'</B><BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
// 				'apitype'=>'php',
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function create_F_SmallContentHtml($deviceno,$devicekey,$arr){
		$donatestr="";
		if($arr['present']=="1"){
			$donatestr="[赠]";
		}
		if($arr['ispack']=="1"){return array();}//套餐不打印
		
		if($arr['wait']=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外送]";}elseif ($arr['takeout']=="0"){$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<CB>'.$arr['zonename'].$takeoutstr.'</CB><BR>';
		if(!empty($arr['billno'])){
			$orderInfo .= '<CB>单号：'.$arr['billno'].'</CB><BR>';
		}
		if(!empty($arr['tabname'])){
			$orderInfo.='<B>台号：'.$arr['tabname'].'<B>';
		}
		$orderInfo.='<B>@'.$waitstr.'@<B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='下单人：'.$arr['nickname'].'  人数：'.$arr['cusnum'].' <BR>';
		$orderInfo .='--------------------------------<BR>';
		$cooktype="";
		$thepackname="";
		if(!empty($arr['cooktype'])){
			$cooktype="(".$arr['cooktype'].")";
		}
		if(!empty($arr['thepackname'])){
			// 			$thepackname="(".$arr['thepackname'].")";
			$thepackname="(套餐)";
		}
		$orderInfo.='<B>'.$arr['foodamount']."".$arr['foodunit'].'×'.$arr['foodname'].$thepackname.$cooktype.$donatestr.'</B><BR>';
		if(!empty($arr['foodrequest'])){
			$orderInfo.='<B>'.$arr['foodrequest'].'</B><BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	/* (non-PHPdoc)
	 * @see IKitchenWorkerDAL::create_FZ_ContentHtml()
	 */
	public function create_FZ_ContentHtml($deviceno,$devicekey,$arr) {
		// TODO Auto-generated method stub
// 		$tablename=$this->getTabnameByTabid($arr['tabid']);
		if($arr['wait']=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外送]";}elseif ($arr['takeout']=="0"){$takeoutstr="";}
		$foodlist="";
		foreach ($arr['details'] as $key=>$onefood){
			$thepackname="";
			if(!empty($onefood['thepackname'])){
// 				$thepackname="(".$onefood['thepackname'].")";
				$thepackname="(套餐)";
			}
			if(empty($onefood['ispack'])){
				$foodlist.='<B>'.$onefood['foodamount']." ".$onefood['foodunit']." × ".$onefood['foodname'].'</B><BR>';
			}
		}
		if(empty($foodlist)){return array();}
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>'.$arr['zonename'].$takeoutstr.'</CB><BR>';
		if(!empty($arr['billno'])){
			$orderInfo .= '<CB>单号：'.$arr['billno'].'</CB><BR>';
		}
		if(!empty($arr['tabname'])){
			$orderInfo.='<B>台号：'.$arr['tabname'].'<B>';
		}
		$orderInfo.='<B>@'.$waitstr.'@<B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='下单人：'.$arr['nickname'].'  人数：'.$arr['cusnum'].' <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$orderInfo.='<BR>';
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	
	public function create_FZ_SmallContentHtml($deviceno,$devicekey,$arr){
		if($arr['wait']=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外送]";}elseif ($arr['takeout']=="0"){$takeoutstr="";}
		$foodlist="";
		foreach ($arr['details'] as $key=>$onefood){
			$thepackname="";
			if(!empty($onefood['thepackname'])){
				// 				$thepackname="(".$onefood['thepackname'].")";
				$thepackname="(套餐)";
			}
			if(empty($onefood['ispack'])){
				$foodlist.='<B>'.$onefood['foodamount']." ".$onefood['foodunit']." × ".$onefood['foodname'].'</B><BR>';
			}
		}
		if(empty($foodlist)){return array();}
		$orderInfo='';
		$orderInfo .='<BR>';
		$orderInfo .= '<CB>'.$arr['zonename'].$takeoutstr.'</CB><BR>';
		if(!empty($arr['billno'])){
			$orderInfo .= '<CB>单号：'.$arr['billno'].'</CB><BR>';
		}
		if(!empty($arr['tabname'])){
			$orderInfo.='<B>台号：'.$arr['tabname'].'<B>';
		}
		$orderInfo.='<B>@'.$waitstr.'@<B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='下单人：'.$arr['nickname'].'  人数：'.$arr['cusnum'].' <BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
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
	/* (non-PHPdoc)
	 * @see IKitchenWorkerDAL::create_Total_ContentHtml()
	 */
	public function create_Total_ContentHtml($arr, $deviceno, $devicekey) {
		// TODO Auto-generated method stub
		$wait=$arr['wait'];
		if($wait=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外送]";}else{$takeoutstr="";}
		$foodlist="";
		foreach ($arr['details'] as $key=>$val){
			$donatestr="";
			$cooktype="";
			$foodrequest="";
			$thepackname="";
			if($val['present']=="1"){
				$donatestr="[赠]";
			}
			if(!empty($val['cooktype'])){
				$cooktype="(".$val['cooktype'].")";
			}
			if(!empty($val['foodrequest'])){
				$foodrequest="(".$val['foodrequest'].")";
			}
			if(!empty($val['thepackname'])){
// 				$thepackname="(".$val['thepackname'].")";
				$thepackname="(套餐)";
			}
			if(empty($val['ispack'])){
				$foodlist.=$val['foodamount']."".$val['foodunit']."×".$val['foodname'].$thepackname.$donatestr.$cooktype.$foodrequest.'<BR>';
			}
		}
		if(empty($foodlist)){return array();}
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>'.$arr['zonename'].$takeoutstr.'</CB><BR>';
		if(!empty($arr['billno'])){
			$orderInfo .= '<CB>单号：'.$arr['billno'].'</CB><BR>';
		}
		if(!empty($arr['tabname'])){
			$orderInfo.='<B>台号：'.$arr['tabname'].'  @'.$waitstr.'@<B>';
		}		
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='下单人：'.$arr['nickname'].'  人数：'.$arr['cusnum'].' <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .="<B>".$foodlist."</B>";
		$orderInfo .='---------------------------------------------<BR>';
// 		$orderInfo .='<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo .='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}

	public function create_Total_SmallContentHtml($arr,$deviceno,$devicekey){
		$wait=$arr['wait'];
		if($wait=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外送]";}else{$takeoutstr="";}
		$foodlist="";
		foreach ($arr['details'] as $key=>$val){
			$donatestr="";
			$cooktype="";
			$foodrequest="";
			$thepackname="";
			if($val['present']=="1"){
				$donatestr="[赠]";
			}
			if(!empty($val['cooktype'])){
				$cooktype="(".$val['cooktype'].")";
			}
			if(!empty($val['foodrequest'])){
				$foodrequest="(".$val['foodrequest'].")";
			}
			if(!empty($val['thepackname'])){
				// 				$thepackname="(".$val['thepackname'].")";
				$thepackname="(套餐)";
			}
			if(empty($val['ispack'])){
				$foodlist.=$val['foodamount']."".$val['foodunit']."×".$val['foodname'].$thepackname.$donatestr.$cooktype.$foodrequest.'<BR>';
			}
		}
		if(empty($foodlist)){return array();}
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>'.$arr['zonename'].$takeoutstr.'</CB><BR>';
		if(!empty($arr['billno'])){
			$orderInfo .= '<CB>单号：'.$arr['billno'].'</CB><BR>';
		}
		if(!empty($arr['tabname'])){
			$orderInfo.='<B>台号：'.$arr['tabname'].'<B>';
		}
		$orderInfo.='<B>@'.$waitstr.'@<B>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='下单人：'.$arr['nickname'].'  人数：'.$arr['cusnum'].' <BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .="<B>".$foodlist."</B>";
		$orderInfo .='--------------------------------<BR>';
// 		$orderInfo .='<BR>';
		$orderInfo .= '!0a1d@!';
		$orderInfo .='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function getBillnumToday($shopid){
		$theday=$this->getTheday($shopid);
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid, "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		return  DALFactory::createInstanceCollection(self::$bill)->count($qarr);
	}
	
	public function getTheday($shopid){
		$openhour=$this->getOpenHourByShopid($shopid);
		$newhour=date("H",time());
		if($newhour>=$openhour){//说明是前一天
			$theday=date("Y-m-d",time());
		}else{//说明是后一天
			$theday=date("Y-m-d",strtotime(date("Y-m-d",time()))-86400);
		}
		return $theday;
	}
	
	public function getOpenHourByShopid($shopid){
		if(empty($shopid)){$openhour="5";}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("openhour"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$openhour="0";
		if(!empty($result['openhour'])){
			$openhour=$result['openhour'];
		}
		return $openhour;
	}
}
?>