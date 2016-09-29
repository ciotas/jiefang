<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/bill/global.php');
require_once (DOCUMENT_ROOT.'IDAL/IBillCountDAL.php');
// require_once ('/var/www/html/DALFactory.php');
require_once (DOCUMENT_ROOT.'Factory/DALFactory.php');
class BillCountDAL implements IBillCountDAL{
	private static $bill="bill";
	/* (non-PHPdoc)
	 * @see IBillCountDAL::getBillCountData()
	 */
	public function getBillCountData($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid);
		$oparr=array("shopid"=>1,"disacountfoodmoney"=>1, "shopname"=>1,"paymoney"=>1,"type"=>1,
		    "clearmoney"=>1,"othermoney"=>1,"couponvalue"=>1,"couponnum"=>1,"discountval"=>1,"ticketfee"=>1,
		    "cashiertype"=>1,
		    "discountvalue"=>1, "disacountmoney"=>1,"timestamp"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1));
		$totalmoney=0;
		$savemoney=0;
		$shoparr=array();
		$shopnum=0;
		$consumenum=0;
		$lasttime=date("Y-m-d H:i",time());
		$maxmoney=0;
		$minmoney=100000000;
		$arr=array();
		foreach ($result as $key=>$val){
		    $everpay=0;
			if($consumenum==0){$lasttime=date("Y-m-d H:i",$val['timestamp']);}
			if($val['cashiertype']=="freepay"){continue;}
			$totalmoney+=$val['paymoney'];
			$everpay+=$val['paymoney'];
			if($val['type']=="coupon"){
			    $couponvaleu_num=explode("*", $val['discountvalue']);
			    $couponvalue=$couponvaleu_num[0];
			    $couponnum=$couponvaleu_num[1];
			    $totalmoney+=$couponvalue*$couponnum;
			    $everpay+=$couponvalue*$couponnum;
			}
			$savemoney+=$val['disacountmoney'];
			if(!empty($val['clearmoney'])){
			    $totalmoney+=$val['clearmoney'];
			    $savemoney+=$val['clearmoney'];
			    $everpay+=$val['clearmoney'];
			}
			if(!empty($val['othermoney'])){
			    $totalmoney+=$val['othermoney'];
			    $savemoney+=$val['othermoney'];
			    $everpay+=$val['othermoney'];
			}
			if(!empty($val['couponnum'])){
			    $totalmoney+=$val['couponvalue']*$val['couponnum'];
			    $everpay+=$val['couponvalue']*$val['couponnum'];
			}
			if(!empty($val['discountval'])){
			    $totalmoney+=$val['disacountfoodmoney']*(1-$val['discountval/100']);
			    $savemoney+=$val['disacountfoodmoney']*(1-$val['discountval/100']);
			    $everpay+=$val['disacountfoodmoney']*(1-$val['discountval/100']);
			}
			if(!empty($val['ticketfee'])){
			    $totalmoney+=$val['ticketfee'];
			    $savemoney+=$val['ticketfee'];
			    $everpay+=$val['ticketfee'];
			}
			
			if(!in_array($val['shopid'], $shoparr)){
				$shopnum++;
				$shoparr[]=$val['shopid'];
			}
			$consumenum++;
			$maxmoney=$maxmoney>$everpay?$maxmoney:$everpay;
			$minmoney=$minmoney<$everpay?$minmoney:$everpay;
		}
		if($totalmoney!=0&&$consumenum!=0){
			$rank=$this->getConsumeRank($totalmoney);
			$avgmoney=sprintf("%.2f",$totalmoney/$consumenum);
			$arr=array(
					"totalmoney"=>sprintf("%.2f",$totalmoney),
					"savemoney"=>sprintf("%.2f",$savemoney),
					"rank"=>$rank,
					"shopnum"=>$shopnum,
					"consumenum"=>$consumenum,
					"lasttime"=>$lasttime,
					"maxmoney"=>sprintf("%.2f",$maxmoney),
					"minmoney"=>sprintf("%.2f",$minmoney),
					"avgmoney"=>$avgmoney,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IBillCountDAL::getConsumeRank()
	 */
	public function getConsumeRank($totalmoney) {
		// TODO Auto-generated method stub
		$qarr=array();	
		$oparr=array("uid"=>1,"type"=>1,"discountvalue"=>1, "paymoney"=>1,
		    "clearmoney"=>1,"othermoney"=>1,"couponnum"=>1,"couponvalue"=>1,"discountval"=>1,
		    "disacountfoodmoney"=>1,"ticketfee"=>1,"cashiertype"=>1
		);	
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr);
		$totalmoneyarr=array();
		$evertotalmoney=0;
		foreach ($result as $key=>$val){
		    $evertotalmoney=$val['paymoney'];
		    if($val['type']=="coupon"){
		        $couponvaleu_num=explode("*", $val['discountvalue']);
		        $couponvalue=$couponvaleu_num[0];
		        $couponnum=$couponvaleu_num[1];
		        $evertotalmoney+=$couponvalue*$couponnum;
		    }
		    if(!empty($val['clearmoney'])){
		        $evertotalmoney+=$val['clearmoney'];
		    }
		    if(!empty($val['othermoney'])){
		        $evertotalmoney+=$val['othermoney'];
		    }
		    if(!empty($val['couponnum'])){
		        $evertotalmoney+=$val['couponvalue']*$val['couponnum'];
		    }
		    if(!empty($val['discountval'])){
		        $evertotalmoney+=$val['disacountfoodmoney']*(1-$val['discountval/100']);
		    }
		    if(!empty($val['ticketfee'])){
		        $evertotalmoney+=$val['ticketfee'];
		    }
			if(array_key_exists($val['uid'],$totalmoneyarr)){
				$totalmoneyarr[$val['uid']]+=floatval($evertotalmoney);
			}else{
				$totalmoneyarr[$val['uid']]=floatval($evertotalmoney);
			}
		}
// 		print_r($totalmoneyarr);exit;

		$i=1;
		if(!empty($totalmoneyarr)){
			arsort($totalmoneyarr);//逆向排序
			foreach ($totalmoneyarr as $uid=>$money){
// 				var_dump(strval($totalmoney)==strval($money));exit;
				if(strval($totalmoney)==strval($money)){
					break;
				}else{
					$i++;
					continue;
				}
			}
		}
		return strval($i);
	}
}
?>