<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/ICusListWorkerDAL.php');
require_once ('/var/www/html/DALFactory.php');
require_once ('/var/www/html/des.php');
class CusListWorkerDAL implements ICusListWorkerDAL{
	private static $customer="customer";
	private static $receiptadv="receiptadv";
	private static $shopinfo="shopinfo";
	/* (non-PHPdoc)
	 * @see ICusListWorkerDAL::PrintData()
	 */
	public function printCuslistData($json) {
		// TODO Auto-generated method stub
		global $phonekey;
		$cuslistarr=json_decode($json,true);
		if(empty($cuslistarr)){return array();}
		$arr=array();
		foreach ($cuslistarr as $key=>$val){
			$devicecrypt = new CookieCrypt($phonekey);
			$deviceno=$devicecrypt->decrypt($val['deviceno']);
			$devicecrypt = new CookieCrypt($phonekey);
			$devicekey=$devicecrypt->decrypt($val['devicekey']);
			if($val['printertype']=="58"){
				$msg=$this->createSmallContentHtml($val,$deviceno,$devicekey);
			}else{
				$msg=$this->createContentHtml($val,$deviceno,$devicekey);
			}
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$deviceno,
					"devicekey"=>$devicekey,
					"printertype"=>$val['printertype'],
					"outputtype"=>"menu",
					"msg"=>$msg,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see ICusListWorkerDAL::createContentHtml()
	 */
	public function createContentHtml($arr,$deviceno,$devicekey) {
		// TODO Auto-generated method stub
        global $advbaseurl;
        $takeoutaddress=$this->geTakeoutAddress($arr['uid']);
        if($arr['takeout']=="1"){$takeoutstr="[外卖]";}else{$takeoutstr="";}
		if(!empty($arr['nickname'])){
			$nickname=$arr['nickname'];
		}
		$foodlist="";
		$totalmoney=0;
		foreach ($arr['food'] as $kf=>$valf){
			$foodrequest="";
			$donatestr="";
			$cootype="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			if(empty($valf['present'])){
				$totalmoney+=$valf['foodamount']*$valf['foodprice'];
			}
			if(!empty($valf['cooktype'])){
				$cootype="(".$valf['cooktype'].")";
			}
			if(!empty($valf['foodrequest'])){
				$foodrequest="(".$valf['foodrequest'].")";
			}
			$foodname=$valf['foodname'].$donatestr.$cootype.$foodrequest;
			$foodname=$this->getStableLenStr($foodname, 22);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 10);
			$foodlist.=''.$foodname.$foodamount.sprintf('%.2f',$valf['foodamount']*$valf['foodprice']).'<BR>';
		}
		$depositmoney=0;
		if($arr['deposit']=="1" && !empty($arr['depositmoney'])){
			$depositmoney=$arr['depositmoney'];
			$foodlist.='押金：￥'.$depositmoney.'<BR>';
		}
		if(empty($foodlist)){return array();}
		if(!empty($arr['paymoney'])){
			$ttotalmoney=$arr['paymoney'];
		}else{
			$ttotalmoney=$totalmoney;
		}
		$ttotalmoney+=$depositmoney;
		$ttotalmoney=sprintf("%.0f",$ttotalmoney);
		$title="划菜单";
		$branchname="";
		if($arr['deposit']=="1" && !empty($arr['depositmoney'])){
			if(!empty($arr['branchname'])){
				$branchname="(".$arr['branchname'].")";
			}
			$title=$arr['shopname'].$branchname;
		}
		$orderInfo='';
// 		$orderInfo.='<BR>';
		$orderInfo.='<CB>'.$title.$takeoutstr.'</CB>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='台号：'.$arr['tabname'].'<BR>';
		$orderInfo.='下单人：'.$nickname.'   人数：'.$arr['cusnum'].'  <BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=''.$foodlist.'';
		$orderInfo .='---------------------------------------------<BR>';
		
		if($arr['takeout']=="1"&& $arr['paystatus']=="unpay"){
			$orderInfo.='货到付款：￥'.$ttotalmoney.'<BR>';
		}
		if($arr['takeout']=="1"&& $arr['paystatus']=="paid"){
			$orderInfo.='已付款：￥'.$ttotalmoney.'<BR>';
		}else{
			$depositstr="";
			$deposittip="";
			if($arr['deposit']=="1" && !empty($arr['depositmoney'])){
				$depositstr.="(包含押金 ￥".$arr['depositmoney'].")";
				$deposittip.="用餐后凭此单退押金，遗失不补，请妥善保管好此单！<BR>祝您用餐愉快，谢谢合作！";
			}
			$menumoney=$this->getMenuMoney($arr['shopid']);
			if($menumoney=="1"){
				$orderInfo.='消费总额：<B>￥'.$ttotalmoney.'</B>'.$depositstr.'<BR>';
				$orderInfo.=''.$deposittip.'<BR>';
			}			
		}
		if(!empty($takeoutaddress)){
			$orderInfo.='外送地址：'.$takeoutaddress.'<BR>';
		}
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$advarr=$this->getCusSheetAdv($arr['shopid']);
		foreach ($advarr as $val){
			if(!empty($val['content'])){
				$orderInfo.=$val['content'].'<BR>';
			}
			if(!empty($val['advurl'])){
				$orderInfo.='<QRcode>'.$advbaseurl.'?advid='.$val['advid'].'</QRcode>';
			}
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
	 * @see ICusListWorkerDAL::outPutHtml()
	 */
	public function outPutHtml($deviceid, $html) {
		// TODO Auto-generated method stub
		global $printer_url;
		$url=$printer_url.$deviceid."/print";
		$html='html='.$html;
		$cmd=" curl -X POST -d '$html' $url";
		$result=shell_exec($cmd);
		return  $result;
		
// 		$ch = curl_init();//初始化curl
// 		curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
// 		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
// 		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
// 		curl_setopt($ch, CURLOPT_TIMEOUT,300);
// 		curl_setopt($ch, CURLOPT_VERBOSE, true);
// 		curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'w'));
// 		curl_setopt($ch, CURLOPT_POSTFIELDS, $html);
// 		$data = curl_exec($ch);//运行curl
// 		curl_close($ch);
// 		return $data;
	}

	/* (non-PHPdoc)
	 * @see ICusListWorkerDAL::getStableLenStr()
	 */
	public function getStableLenStr($str, $len) {
		// TODO Auto-generated method stub
		$strlength=(strlen($str) + mb_strlen($str,'UTF8'))/2;
		if($strlength<$len){
			return $str.str_repeat(" ",($len-$strlength+1));
		}else{
			return $str;
		}
	}
	public function geTakeoutAddress($uid){
		if(empty($uid)){return "";}
		$qarr=array("_id"=>new MongoId($uid));
		$oparr=array("takeoutaddress"=>1);
		$takeoutaddress="";
		$result=DALFactory::createInstanceCollection(self::$customer)->findOne($qarr,$oparr);
		if(!empty($result)){
			if(!empty($result['takeoutaddress'])){$takeoutaddress=$result['takeoutaddress'];}else{$takeoutaddress="";}
			
		}
		return $takeoutaddress;
	}
	public function getCusSheetAdv($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "content"=>1,"advurl"=>1);
		$result=DALFactory::createInstanceCollection(self::$receiptadv)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("advid"=>strval($val['_id']), "content"=> $val['content'],"advurl"=>urldecode($val['advurl']));
		}
		return $arr;
	}
	public function getAdvUrlByAdvid($advid){
		$qarr=array("_id"=>new MongoId($advid));
		$oparr=array("advurl"=>1);
		$advurl="";
		$result=DALFactory::createInstanceCollection(self::$receiptadv)->findOne($qarr,$oparr);
		if(!empty($result['advurl'])){
			$advurl=urldecode($result['advurl']);
		}
		return $advurl;
	}
	
	public function getMenuMoney($shopid){
		if(empty($shopid)){return "0";}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("menumoney"=>1);
		$menumoney="1";
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(isset($result['menumoney'])){
			if($result['menumoney']=="1"){$menumoney="1";}else{$menumoney="0";}
		}
		return $menumoney;
	}
	
	public function createSmallContentHtml($arr,$deviceno,$devicekey){
		global $advbaseurl;
		$takeoutaddress=$this->geTakeoutAddress($arr['uid']);
		if($arr['takeout']=="1"){$takeoutstr="[外卖]";}else{$takeoutstr="";}
		if(!empty($arr['nickname'])){
			$nickname=$arr['nickname'];
		}
		$foodlist="";
		$totalmoney=0;
		foreach ($arr['food'] as $kf=>$valf){
			$foodrequest="";
			$donatestr="";
			$cootype="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			if(empty($valf['present'])){
				$totalmoney+=$valf['foodamount']*$valf['foodprice'];
			}
			if(!empty($valf['cooktype'])){
				$cootype="(".$valf['cooktype'].")";
			}
			if(!empty($valf['foodrequest'])){
				$foodrequest="(".$valf['foodrequest'].")";
			}
			$foodname=$valf['foodname'].$donatestr.$cootype.$foodrequest;
			$foodlength=(strlen($foodname) + mb_strlen($foodname,'UTF8'))/2;
			if($foodlength>18){
				$foodname=$foodname."<BR>";
			}else{
				$foodname=$this->getStableLenStr($foodname, 18);
			}
// 			$foodname=$this->getStableLenStr($foodname, 18);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 5);
			$foodlist.=''.$foodname.$foodamount.sprintf('%.2f',$valf['foodamount']*$valf['foodprice']).'<BR>';
		}
		$depositmoney=0;
		if($arr['deposit']=="1" && !empty($arr['depositmoney'])){
			$depositmoney=$arr['depositmoney'];
			$foodlist.='押金：￥'.$depositmoney.'<BR>';
		}
		if(empty($foodlist)){return array();}
		if(!empty($arr['paymoney'])){
			$ttotalmoney=$arr['paymoney'];
		}else{
			$ttotalmoney=$totalmoney;
		}
		$ttotalmoney+=$depositmoney;
		$ttotalmoney=sprintf("%.0f",$ttotalmoney);
		$title="划菜单";
		$branchname="";
		if($arr['deposit']=="1" && !empty($arr['depositmoney'])){
			if(!empty($arr['branchname'])){
				$branchname="(".$arr['branchname'].")";
			}
			$title=$arr['shopname'].$branchname;
		}
		$orderInfo='';
		// 		$orderInfo.='<BR>';
		$orderInfo.='<CB>'.$title.$takeoutstr.'</CB>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='台号：'.$arr['tabname'].'<BR>';
		$orderInfo.='下单人：'.$nickname.'   人数：'.$arr['cusnum'].'  <BR>';
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=''.$foodlist.'';
		$orderInfo .='--------------------------------<BR>';
		
		if($arr['takeout']=="1"&& $arr['paystatus']=="unpay"){
			$orderInfo.='货到付款：￥'.$ttotalmoney.'<BR>';
		}
		if($arr['takeout']=="1"&& $arr['paystatus']=="paid"){
			$orderInfo.='已付款：￥'.$ttotalmoney.'<BR>';
		}else{
			$depositstr="";
			$deposittip="";
			if($arr['deposit']=="1" && !empty($arr['depositmoney'])){
				$depositstr.="(包含押金 ￥".$arr['depositmoney'].")";
				$deposittip.="用餐后凭此单退押金，遗失不补，请妥善保管好此单！<BR>祝您用餐愉快，谢谢合作！";
			}
			$menumoney=$this->getMenuMoney($arr['shopid']);
			if($menumoney=="1"){
				$orderInfo.='消费总额：<B>￥'.$ttotalmoney.'</B>'.$depositstr.'<BR>';
				$orderInfo.=''.$deposittip.'<BR>';
			}
		}
		if(!empty($takeoutaddress)){
			$orderInfo.='外送地址：'.$takeoutaddress.'<BR>';
		}
		if(!empty($arr['orderrequest'])){
			$orderInfo.='<B>备注：'.$arr['orderrequest'].'</B><BR>';
		}
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		$advarr=$this->getCusSheetAdv($arr['shopid']);
		foreach ($advarr as $val){
			if(!empty($val['content'])){
				$orderInfo.=$val['content'].'<BR>';
			}
			if(!empty($val['advurl'])){
				$orderInfo.='<QRcode>'.$advbaseurl.'?advid='.$val['advid'].'</QRcode>';
			}
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
}
?>