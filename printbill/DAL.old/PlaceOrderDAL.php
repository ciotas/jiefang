<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once ('/var/www/html/HttpClient.class.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IPlaceOrderDAL.php');
// require_once ('/var/www/html/DALFactory.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/DALFactory.php');
class PlaceOrderDAL implements IPlaceOrderDAL{
	private static $coll="bill"; 
	private static $coupon="coupon";
	private static $usercouponlib="usercouponlib";
	private static $shoppoints="shoppoints";
	private static $cuspoints="cuspoints";
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getBillById()
	 */
	public function getBillById($billid) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("uid"=>1,"nickname"=>1,"shopid"=>1, "shopname"=>1,"branchname"=>1,
				 "wait"=>1,"type"=>1, "tabname"=>1,"cusnum"=>1,"discountdesc"=>1,
				"discountvalue"=>1,"discounttitle"=>1,"tradeno"=>1,"disacountfoodmoney"=>1,
				"totalmoney"=>1,"disacountmoney"=>1,"paymoney"=>1,"timestamp"=>1,"billstatus"=>1, "food"=>1,"service"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$coll)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			
			$arr=array(
					"uid"=>$result['uid'],
					"nickname"=>$result['nickname'],
					"shopid"=>$result['shopid'],
					"shopname"=>$result['shopname'],
					"branchname"=>$result['branchname'],
					"wait"=>$result['wait'],
					"type"=>$result['type'],
					"tabname"=>$result['tabname'],
					"cusnum"=>$result['cusnum'],
					"discountvalue"=>$result['discountvalue'],
					"discounttitle"=>$result['discounttitle'],
			        "discountdesc"=>$result['discountdesc'],
					"totalmoney"	=>$result['totalmoney'],
					"disacountmoney"=>$result['disacountmoney'],
			        "disacountfoodmoney"=>$result['disacountfoodmoney'],
					"paymoney" =>$result['paymoney'],
					"timestamp" =>$result['timestamp'],
					"billstatus"=>$result['billstatus'],
					"tradeno"=>$result['tradeno'],
					"food"=>$result['food'],
					"service"=>$result['service']
			);
		}
		return $arr;	
	}
	
	function rolling_curl($urls, $delay) {
		if(empty($urls)){return array();}
// 		print_r($urls);exit;
		$queue = curl_multi_init();
		$map = array();
		foreach ($urls as $key=>$url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url['url']);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Vega-API-Token: 798cb96d26159c793519baa881e14acc","Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $url['html']);
			curl_setopt($ch, CURLOPT_NOSIGNAL, true);
			curl_multi_add_handle($queue, $ch);
			$map[strval($ch)] = array("printid"=>$url['printid'], "deviceid"=>$url['deviceid'],"outputtype"=>$url['outputtype']);//$ch 标记句柄，与下面得到失败type呼应
		}
		$responses = array();
		$responsearr=array();
		$active=null;
		do {
			while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;
			if ($code != CURLM_OK) { break; }
			// a request was just completed -- find out which one
			while ($done = curl_multi_info_read($queue)){
				$info = curl_getinfo($done['handle']);//借助info内部的信息判断是否打印失败
				//可以筛选的参数有total_time、connect_time、size_upload、speed_upload、upload_content_length、starttransfer_time
// 				var_dump($info);exit;
				$error = curl_error($done['handle']);
				$command_id= curl_multi_getcontent($done['handle']);//得到command_id
// 				$results =callback(curl_multi_getcontent($done['handle']), $delay);
				usleep($delay);
// 				echo $command_id;exit;
				foreach ($map as $ch=>$v1){
					if(strcmp($ch, strval($done['handle']))==0){
// 					$info['header_size']==0 || $info['request_size']==0 || $info['total_time']==0 || $info['content_type']==null
// 					$info['connect_time']==0 || $info['pretransfer_time']==0 || $info['size_upload']==0 || $info['size_download']==0
// 					$info['speed_download']==0 || $info['speed_upload']==0 || $info['starttransfer_time']==0
						if($info['size_upload']==0 || $info['http_code']==0){
							$responsearr[]=array(
									"printid"=>$v1['printid'],
									"deviceid"=>strval($v1['deviceid']),
									"outputtype"=>$v1['outputtype']
							);//得到打印失败的printid，此printid为标记
						}
					}
// 					if(!empty($responsearr)){print_r($responsearr);exit;}
				}
				compact('info', 'error', 'results');
				curl_multi_remove_handle($queue, $done['handle']);
				curl_close($done['handle']);
			}
			// Block for data in / output; error handling is done by curl_multi_exec
			if ($active > 0) {
				$ret=curl_multi_select($queue,30);
			}
		} while ($active);
	
		curl_multi_close($queue);
		if(empty($responsearr)){return array();}
		return $responsearr;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getUrlsArr()
	 */
	public function getUrlsArr($json) {
		// TODO Auto-generated method stub
		$arr=json_decode($json,true);
		if(empty($arr)){return array();}
		$resultarr=array(); 
		foreach ($arr as $k1=>$v1){
			foreach ($v1 as $k2=>$v2){
				$resultarr[]=array(
						"printid"=>$v2['printid'],
						"deviceno"=>$v2['deviceno'],
						"devicekey"=>$v2['devicekey'],
						"outputtype"=>$v2['outputtype'],
// 						"url"=>$v2['url'],
						"msg"=>$v2['msg']	
				);
			}
		}
		return $resultarr;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::callback()
	 */
	public function callback($data, $delay) {
		// TODO Auto-generated method stub
		preg_match_all('/<h3>(.+)<\/h3>/iU', $data, $matches);
		usleep($delay);
		return compact('data', 'matches');
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::rePrint()
	 */
	public function getRePrintContent($nullarr, $urls) {
		// TODO Auto-generated method stub
		$reprinturls=array();
		if(empty($nullarr)){return array();}
		foreach ($nullarr as $printid_type_deviceno_devicekey=>$status){
			$temparr=explode('|', $printid_type_deviceno_devicekey);
			$printid=$temparr[0];
			if(strval($status)!="0"){//打印不成功
				foreach ($urls as $val){
					if(strcmp(strval($printid), strval($val['printid']))==0){
						$reprinturls[]=array(
								"printid"=>$val['printid'],
								"deviceno"=>strval($val['deviceno']),
								"devicekey"=>strval($val['devicekey']),
								"outputtype"=>$val['outputtype'],
// 								"url"=>$val['url'],
								"msg"=>$val['msg']
						);
					}
				}
			}
		}
		return $reprinturls;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::updateBillStatus()
	 */
	public function updateBillStatus($billid, $billstatus) {
		// TODO Auto-generated method stub
		$qarr=array("_id"=>new MongoId($billid));
		$oparr=array("\$set"=>array("billstatus"=>$billstatus));
		PRINT_DALFactory::createInstanceCollection(self::$coll)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::sendFreeMessage()
	 */
	public function sendFreeMessage($url) {
		// TODO Auto-generated method stub
// 		print_r($url);exit;
		if(empty($url)){return array();}
		$statusarr=array();
		foreach ($url as $key=>$val) {
			$resultcode=$this->sendMessage($val['msg']);
			if($resultcode=="0"){continue;}
			$statusarr[$val['printid'].'|'.$val['outputtype'].'|'.$val['deviceno'].'|'.$val['devicekey']]=$resultcode ;
		}
// 		print_r($statusarr);exit;
		return $statusarr;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::sendMessage()
	 */
	public function sendMessage($msgInfo) {
		// TODO Auto-generated method stub
		$client = new HttpClient(IP,PORT);
		if(!$client->post(HOSTNAME.'/printOrderAction',$msgInfo)){
			echo 'error';
		}
		else{
			$result= $client->getContent();//{"reslutCode":0,"msg":"success"}
			$rearr=json_decode($result,true);
			return $rearr['responseCode'];
		}
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getSendMsg()
	 */
	public function getSendMsg($shopid,$uid,$totalmoney,$paytype,$disacountfoodmoney,$discountvalue,$type,$discountzong,$paymoney,$time) {
		// TODO Auto-generated method stub
		$sendcont="";
		$sendcont.="本次消费总额为￥".sprintf("%.2f",$totalmoney)."元，其中可优惠的商品￥".sprintf("%.2f",$disacountfoodmoney)."元。\r\n";
		switch ($type){
			case "act_discount":
				$range_discount=explode("*", $discountvalue);
				$range=$range_discount[0];
				$discount=$range_discount[1];
				if($range=="all"){
					$rangedesc="全部商品";
				}else{
					$rangedesc="可优惠的商品";
				}
				$sendcont.="您参加了本店折扣优惠活动，".$rangedesc."享受".rtrim(rtrim($discount, '0'), '.')."折优惠，优惠总额为￥".sprintf("%.2f",$discountzong)."元。\r\n";
				break;
			case "act_minus":
				$fullmoney_minus=explode("*", $discountvalue);
				$fullmoney=$fullmoney_minus[0];
				$minus=$fullmoney_minus[1];
				$desc="";
				$sendcont.="您参加了本店满￥".$fullmoney."减￥".$minus."的活动，";
				if($totalmoney<$fullmoney){
					$sendcont.="由于您消费总额不足￥".$fullmoney."元，无法享受此活动优惠。";
				}else{
					$sendcont.="优惠总额为￥".sprintf("%.2f",$discountzong)."元，";
				}
				break;
			case "act_donate":
				$fullmoney_donate=explode("*", $discountvalue);
				$fullmoney=$fullmoney_donate[0];
				$donate=$fullmoney_donate[1];
				$sendcont.="您参加了本店满".$fullmoney."减".$donate."的活动，";
				if($totalmoney<$fullmoney){
					$sendcont.="由于您消费总额不足￥".$fullmoney."元，无法享受此活动优惠。";
				}else{
					$sendcont.="我们将赠送您".$donate."。";
				}
				break;
			case "coupon":
				$value_num=explode("*", $discountvalue);
				$couponvalue=$value_num[0];
				$couponnum=$value_num[1];
				$couponlimit=$this->getCouponLimit($shopid);//一次最多可使用张数
// 				$owncouponnum=$this->getOwnCouponNum($uid, $shopid, $time);//拥有张数
				$sendcont.="本店一次最多使用".strval($couponlimit)."张代金券，本次消费可使用".$couponnum."张代金券，优惠总额为￥".$discountzong."元。";
				break;
			case "vip":
				$sendcont.="您是本店会员，可优惠商品部分打".rtrim(rtrim($discountvalue, '0'), '.')."折，共计优惠￥".sprintf("%.2f",$discountzong)."元。";
				break;
			case "point":
				$shoppoint=$this->getShopPoint($shopid);
				$ownpoint=$this->getOwnPoint($uid);
				$sendcont.="本店规定一次最多可使用积分数为".$shoppoint."，您拥有的积分数为".$ownpoint."，可使用积分数为".$discountvalue."，合计￥".sprintf("%.2f",$discountzong)."元(100积分=￥1元)。";
				break;
		}
		$sendcont.="应付金额为￥".sprintf("%.2f",$paymoney)."元。";
		if($paytype!="offlinepay"){
			$sendcont.="实际付款金额为￥".sprintf("%.2f",$paymoney)."元。\r\n";
			$sendcont.="此次消费获得".ceil($paymoney)."积分\r\n";
		}else{
			$sendcont.="实际付款金额为￥0 元，请用完餐后买单。\r\n";
		}
		$sendcont.="如需了解账单详细，请在\"我的最新账单\"中查看，如有问题请您及时联系本店服务人员，谢谢您的使用！";
		return $sendcont;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getCouponLimit()
	 */
	public function getCouponLimit($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("couponlimit"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$coupon)->findOne($qarr,$oparr);
		$couponlimit=0;
		if(!empty($result)){
			$couponlimit=intval($result['couponlimit']);
		}
		return $couponlimit;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getOwnCouponNum()
	 */
	public function getOwnCouponNum($uid, $shopid,$time) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"shopid"=>$shopid,"starttime"=>array("\$lte"=>$time),"endtime"=>array("\$gte"=>$time));
		$oparr=array("couponnum"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$usercouponlib)->findOne($qarr,$oparr);
		$couponnum=0;
		if(!empty($result)){
			$couponnum=$result['couponnum'];
		}
		return $couponnum;
	}
	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getShopPoint()
	 */
	public function getShopPoint($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("points"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$shoppoints)->findOne($qarr,$oparr);
		$points="0";
		if(!empty($result)){
			$points=$result['points'];
		}
		return $points;
	}

	/* (non-PHPdoc)
	 * @see IPlaceOrderDAL::getOwnPoint()
	 */
	public function getOwnPoint($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid);
		$oparr=array("points"=>1);
		$result=PRINT_DALFactory::createInstanceCollection(self::$cuspoints)->findOne($qarr,$oparr);
		$points="0";
		if(!empty($result)){
			$points=$result['points'];
		}
		return $points;
	}

}
?>