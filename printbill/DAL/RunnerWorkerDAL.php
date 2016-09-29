<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IRunnerWorkerDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
class RunnerWorkerDAL implements IRunnerWorkerDAL{
	private static $table="table";
	/* (non-PHPdoc)
	 * @see IRunnerWorkerDAL::printData()
	 */
	public function printChuanCaiData($json){
		// TODO Auto-generated method stub
		global $phonekey;
		$deliciousarr=json_decode($json,true);
		if(empty($deliciousarr)){return array();}
		$arr=array();
		foreach ($deliciousarr as $key=>$val){
			$devicecrypt = new CookieCrypt($phonekey);
			$deviceno=$devicecrypt->decrypt($val['deviceno']);
			$devicecrypt = new CookieCrypt($phonekey);
			$devicekey=$devicecrypt->decrypt($val['devicekey']);
			if($val['printertype']=="58"){
				$html=$this->createSmallContentHtml($val,$deviceno,$devicekey);
			}else{
				$html=$this->createContentHtml($val,$deviceno,$devicekey);
			}
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),//标识此url ，相当于此打印单的id
					"outputtype"=>"pass",
					"deviceno"=>$deviceno,
					"devicekey"=>$devicekey,
					"printertype"=>$val['printertype'],
					"msg"=>$html,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IRunnerWorkerDAL::createContentHtml()
	 */
	public function createContentHtml($arr,$deviceno,$devicekey) {
		// TODO Auto-generated method stub
		$wait=$arr['wait'];
		if($wait=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外卖]";}else{$takeoutstr="";}
		$foodlist="";
		foreach ($arr['food'] as $key=>$val){
			$donatestr="";
			if($val['present']=="1"){
				$donatestr="[赠]";
			}
			$cooktype="";
			if(!empty($val['cooktype'])){
			    $cooktype="(".$val['cooktype'].")";
			}
			$foodrequest="";
			if(!empty($val['foodrequest'])){
				$foodrequest="(".$val['foodrequest'].")";
			}
			if(empty($val['ispack'])){//不是套餐的
				$foodlist.=$val['foodamount']." ".$val['foodunit']."×".$val['foodname'].$cooktype.$foodrequest.$donatestr.'<BR>';
			}
		}
		if(empty($foodlist)){return array();}//空的不打印
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>传菜单'.$takeoutstr.'</CB>';
		
		$orderInfo .= '!0a1d@!<BR>';
		if(!empty($arr['tabname'])){$orderInfo .='台号：'.$arr['tabname'].'<BR>';}
		if(!empty($arr['billnum'])){
			$orderInfo .= '<C><B>单号：'.($arr['billnum']+1).'</B></C><BR>';
		}
		
		$orderInfo .='人数：'.$arr['cusnum'].'   @'.$waitstr.'@<BR>';
		$orderInfo .='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .="<B>".$foodlist."</B>";
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .='<BR>';
		$orderInfo .= '!0a1d@!';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
// 				'apitype'=>'php',
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IRunnerWorkerDAL::outPutHtml()
	 */
	public function outPutHtml($deviceid, $html) {
		// TODO Auto-generated method stub
		global $printer_url;
		$url=$printer_url.$deviceid."/print";
		$html='html='.$html;
		$cmd=" curl -X POST -d '$html' $url";
		$result=shell_exec($cmd);
		return  $result;
// 		$html='html='.$html;
// 		$ch = curl_init();//初始化curl
// 		curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
// 		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
// 		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
// 		curl_setopt($ch, CURLOPT_TIMEOUT,300);
// 		curl_setopt($ch, CURLOPT_POSTFIELDS, $html);
// 		$data = curl_exec($ch);//运行curl
// 		curl_close($ch);
// 		return $data;
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
	
	public function createSmallContentHtml($arr,$deviceno,$devicekey){
		$wait=$arr['wait'];
		if($wait=="1"){$waitstr="等叫";}else{$waitstr="即食";}
		if($arr['takeout']=="1"){$takeoutstr="[外卖]";}else{$takeoutstr="";}
		$foodlist="";
		foreach ($arr['food'] as $key=>$val){
			$donatestr="";
			if($val['present']=="1"){
				$donatestr="[赠]";
			}
			$cooktype="";
			if(!empty($val['cooktype'])){
			    $cooktype="(".$val['cooktype'].")";
			}
			$foodrequest="";
			if(!empty($val['foodrequest'])){
				$foodrequest="(".$val['foodrequest'].")";
			}
			if(empty($val['ispack'])){//不是套餐的
				$foodlist.=$val['foodamount']." ".$val['foodunit']."×".$val['foodname'].$cooktype.$foodrequest.$donatestr.'<BR>';
			}
		}
		if(empty($foodlist)){return array();}//空的不打印
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>传菜单'.$takeoutstr.'</CB>';
		
		$orderInfo .= '!0a1d@!<BR>';
	   if(!empty($arr['tabname'])){$orderInfo .='台号：'.$arr['tabname'].'<BR>';}
		if(!empty($arr['billnum'])){
			$orderInfo .= '<C><B>单号：'.($arr['billnum']+1).'</B></C><BR>';
		}
		$orderInfo .='人数：'.$arr['cusnum'].'   @'.$waitstr.'@<BR>';
		$orderInfo .='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .="<B>".$foodlist."</B>";
		$orderInfo .='--------------------------------<BR>';
		$orderInfo .='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
// 		$orderInfo .='<BR>';
// 		$orderInfo .= '!0a1d@!';
		
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				// 				'apitype'=>'php',
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
}
?>