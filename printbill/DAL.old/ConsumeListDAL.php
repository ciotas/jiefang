<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IConsumeListDAL.php');
require_once (_ROOT.'DALFactory.php');
require_once (_ROOT.'des.php');
require_once ('WorkerTwoDAL.php');

class ConsumeListDAL implements IConsumeListDAL{ 
	private static $bill="bill";
	private static $coupontype="coupontype";
	private static $shopinfo="shopinfo";
	private static $donateticket_rule="donateticket_rule";
	private static $donateticket="donateticket";
	private static $foodtype="foodtype";
	private static $prebill="prebill";
	private static $receiptadv="receiptadv";
	
	/* (non-PHPdoc)
	 * @see IConsumeListDAL::PrintData()
	 *
	 */
	public function printConsumeListData($json) {
		// TODO Auto-generated method stub
		global $phonekey;
		$consumelistkeyarr=json_decode($json,true);
		if(empty($consumelistkeyarr)){return array();}
		foreach ($consumelistkeyarr as $key=>$val){
			$phonecrypt = new CookieCrypt($phonekey);
			$deviceno=$phonecrypt->decrypt($val['deviceno']);
			$phonecrypt = new CookieCrypt($phonekey);
			$devicekey=$phonecrypt->decrypt($val['devicekey']);
			$printertype=$val['printertype'];
			//在这里判断活动
			$in=$this->isInDonateticketTable($val['shopid']);
			if($in){
				if($printertype=="58"){
					$msg=$this->createDonateticketSmallContentHtml($val,$deviceno,$devicekey);
				}else{
					$msg=$this->createDonateticketContentHtml($val,$deviceno,$devicekey);
				}
			}else{
				if($printertype=="58"){
					$msg=$this->createSmallContentHtml($val,$deviceno,$devicekey);
				}else{
					$msg=$this->createContentHtml($val,$deviceno,$devicekey);
				}
				
			}
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$deviceno,
					"devicekey"=>$devicekey,
					"printertype"=>$printertype,
					"outputtype"=>"checkout",
					'msg'=>$msg,
			);
		}
		return $arr;
	}

	/* (non-PHPdoc)
	 * @see IConsumeListDAL::createContentHtml()
	 */
	public function createContentHtml($arr,$deviceno,$devicekey) {
		// TODO Auto-generated method stub
		$worktwodal=new WorkerTwoDAL();
		$oneshopinfo=$worktwodal->getOneBillShopinfo("billshopinfo", $arr['billid']);
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if(isset($valf['inpack']) && $valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 20);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 8);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 8) ;
			$foodlist.=$foodname.$foodamount.$foodprice.sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//有几张单子
		$billno=$worktwodal->getBillNum($arr['billid']);
// 		$billnum=$this->getBillnumToday($shopid);
			//优惠详细
			//总金额、可优惠金额计算
			$totalmoney=0;
			$discountfoodmoney=0;
			foreach ($arr['food'] as $foodkey=>$foodval){
				if(empty($foodval['present'])){
					$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
					if($foodval['fooddisaccount']=="1"){
						$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
					}
				}
			}
			$totalmoney+=$arr['othermoney'];
			
			$disaccountdetails="";
			$discountmoney=0;
			if(!empty($arr['clearmoney'])){$discountmoney+=$arr['clearmoney'];$disaccountdetails.="抹零￥".$arr['clearmoney']."，";}
			if($arr['discountval']!="100"){
				if($arr['discountval']>100){
					if($arr['discountmode']=="all"){
						$disaccountdetails.="服务费收￥".floor($totalmoney*(($arr['discountval']/100)-1))."，";
					}else{
						$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($arr['discountval']/100)-1))."，";
					}
				
				}else{
					if($arr['discountmode']=="all"){
						$discountmoney+=ceil($totalmoney*(1-$arr['discountval']/100));
						$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$arr['discountval']/100))."，";
					}else{
						$discountmoney+=ceil($discountfoodmoney*(1-$arr['discountval']/100));
						$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$arr['discountval']/100))."，";
					}
				}
			}
			//优惠券
			if(!empty($arr['ticketway'])&&!empty($arr['ticketnum'])&&!empty($arr['ticketval'])){
				$ticketname=$this->getOneCounponType($arr['ticketway']);
				$discountmoney+=$arr['ticketnum']*$arr['ticketval'];
				$disaccountdetails.=$ticketname."￥".($arr['ticketval']."*".$arr['ticketnum']);
			}
			//押金
			$depositmoney=$this->getDepositmoney($arr['shopid']);
			if($arr['deposit']=="1" && !empty($depositmoney)){
				$totalmoney+=$depositmoney;
			}
			$returndepositmoney="";
			if(!empty($arr['returndepositmoney']) || $arr['returndepositmoney']=="0"){
				$returndepositmoney=$arr['returndepositmoney'];
			}
			//开钱箱
			$str="";
			$box=array();
			$box[0]=0x1b;
			$box[1]=0x64;
			$box[2]=0x01;
			$box[3]=0x1b;
			$box[4]=0x70;
			$box[5]=0x30;
			$box[6]=0x1e;
			$box[7]=0x78;
			$str="";
// 			if($arr['openbox']=="1"){
// 				foreach ($box as $val){
// 					$str.=chr($val);
// 				}
// 			}
			foreach ($box as $val){
				$str.=chr($val);
			}
			$paytypestr="";
			if($arr['paystatus']=="unpay"){
				$title="预结单";
			}else{
				$title="结账单";
			}
			if(!empty($arr['cashmoney'])){//&&!empty($arr['cuspay'])
				if(!empty($arr['cuspay'])){
					$paytypestr.="现金付款：￥".sprintf("%.0f",$arr['cashmoney'])."";
					if($arr['cuspay']!=$arr['cashmoney']+$arr['unionmoney']+$arr['vipmoney']+$arr['meituanpay']+$arr['wechatpay']+$arr['alipay']){
						$paytypestr.="(实收：￥".$arr['cuspay']."，找零：￥".($arr['cuspay']-$arr['cashmoney']).")";
					}
				}else{
					$paytypestr.="实收现金：￥".$arr['cashmoney']." ";
				}
			}
			if(!empty($arr['unionmoney'])){
				$paytypestr.="银联卡付款：￥".$arr['unionmoney']." ";
			}
			if(!empty($arr['vipmoney'])){
				$paytypestr.="会员卡付款：￥".$arr['vipmoney']." ";
			}
			if(!empty($arr['meituanpay'])){
				$paytypestr.="美团账户付款：￥".$arr['meituanpay']." ";
			}
			if(!empty($arr['dazhongpay'])){
				$paytypestr.="大众账户付款：￥".$arr['dazhongpay']." ";
			}
			if(!empty($arr['nuomipay'])){
				$paytypestr.="糯米账户付款：￥".$arr['nuomipay']." ";
			}
			if(!empty($arr['otherpay'])){
				$paytypestr.="其他：￥".$arr['otherpay']." ";
			}
			if(!empty($arr['alipay'])){
				$paytypestr.="支付宝付款：￥".$arr['alipay']." ";
			}
			if(!empty($arr['wechatpay'])){
				$paytypestr.="微信支付：￥".$arr['wechatpay']."";
			}
			elseif($arr['paystatus']=="unpay"){
				if(!empty($arr['qrcode'])){
					$paytypestr.="应付金额：￥".$arr['shouldpay']." <BR>
						用支付宝客户端扫一扫，立即买单！<BR><QRcode>https://qr.alipay.com/".$arr['qrcode']."</QRcode>";
					$title="预结单";
				}
			}elseif($arr['cashmoney']=="0"&&empty($arr['alipay'])&&empty($arr['signername'])&&empty($arr['freename'])){
				$paytypestr.="实收现金：￥0<BR>";
			}
			if(!empty($arr['tabname'])){$tabname=$arr['tabname'];}else{$tabname="";}
			if($arr['takeout']=="1"){$takeoutstr="<B>[外送]</B>";}else{$takeoutstr="";}
			$orderInfo='';
			$orderInfo .= '<C>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</C><BR>';
			if(!empty($billno)){
				$orderInfo .= '<C><B>单号：'.$billno.'</B></C><BR>';
			}

			if(isset($oneshopinfo['porttype'])){
			    if(!empty($oneshopinfo['porttype'])){
			        $orderInfo.='<CB>出库单</CB><BR>';
			    }else{
			        $orderInfo.='<CB>送货单</CB><BR>';
			    }
			}
			if(isset($oneshopinfo['distribution'])){
			    if(!empty($oneshopinfo['distribution'])){
			        $orderInfo.='<CB>配送</CB><BR>';
			    }else{
			        $orderInfo.='<CB>自提</CB><BR>';
			    }
			}
			$orderInfo.='台号：'.$tabname.'   人数：'.$arr['cusnum'].'<BR>';
			$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
			$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
			$orderInfo .='---------------------------------------------<BR>';
			$itemname=$this->getStableLenStr("项目名称", 20);
			$itemnum=$this->getStableLenStr("数量", 8);
			$itemprice=$this->getStableLenStr("单价", 8);
			$itempsum="金额";
			$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
			$orderInfo.=$foodlist;
			$orderInfo .='---------------------------------------------<BR>';
			$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
			if(!empty($arr['cashierman'])){
				$orderInfo.='收银人员：'.$arr['cashierman'].'<BR>';
			}
		
			if(!empty($arr['othermoney'])){
				$orderInfo.='其他费用：￥'.sprintf('%.2f',$arr['othermoney']).' <BR>';
			}
			$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
			if($arr['deposit']=="1" && !empty($depositmoney)){
				$orderInfo.="(包含押金：￥".$depositmoney.")";
			}
			$orderInfo.="<BR>";
			if(!empty($disaccountdetails)){
				$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
				$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
			}
			if(!empty($returndepositmoney)){
				$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
			}
			$orderInfo .='---------------------------------------------<BR>';
			$orderInfo.=''.$paytypestr.'<BR><BR>';
			if(!empty($arr['takeoutphone'])){
				$orderInfo.="<B>联系方式：".$arr['takeoutphone'].'</B><BR>';
				$orderInfo.='客户已提前支付点餐，到店后请在后台确认，会自动下单到厨房！<BR>';
			}
			if(!empty($arr['takeoutaddress'])){
				//$orderInfo.="<B>外送地址：".$arr['takeoutaddress'].'</B><BR>';
			}
			if(!empty($oneshopinfo)){
			    $orderInfo.='收货地址：'.$oneshopinfo['prov'].$oneshopinfo['city'].$oneshopinfo['dist'].$oneshopinfo['road'].'<BR>';
			    if(!empty($oneshopinfo['shopname'])){
			        $orderInfo.='店名：'.$oneshopinfo['shopname'].'<BR>';
			    }
			    if(!empty($oneshopinfo['author'])){
			        $orderInfo.='下单人：'.$oneshopinfo['author'].'<BR>';
			    }
			    $orderInfo.='联系人：'.$oneshopinfo['contact'].'<BR>';
			    $orderInfo.='电话：'.$oneshopinfo['phone'].'<BR>';
			    if(!empty($oneshopinfo['carno'])){
			        $orderInfo.='车牌号：'.$oneshopinfo['carno'].'<BR>';
			    }
			    $orderInfo.='提送时间：'.$oneshopinfo['picktime'].'<BR>';
			    $orderInfo.="备注：".$oneshopinfo['orderrequest'].'<BR>';
			}
			if(empty($oneshopinfo['orderrequest'])){
				if(!empty($arr['orderrequest'])){
					$orderInfo.="备注：".$arr['orderrequest'].'<BR>';
				}
			}
			
			$orderInfo.='<BR>';
			if(!empty($arr['signername'])){
				$orderInfo.='签单人：'.$arr['signername'].'<BR>';
				$orderInfo.='签单单位：'.$arr['signerunit'].'<BR>';
			}
			if(!empty($arr['freename'])){
				$orderInfo.='免单人：'.$arr['freename'].'<BR>';
			}
			$orderInfo .='---------------------------------------------<BR>';
			$cusadvarr=$this->getCusSheetAdvData($arr['shopid']);
			if(!empty($cusadvarr)){
				foreach ($cusadvarr as $key=>$val){
					$orderInfo.=''.$val['content'].'<BR>';
					if(!empty($val['advurl'])){
						$orderInfo.='<QRcode>'.$val['advurl'].'</QRcode><BR>';
					}
				}
			}
			$orderInfo.='power by 街坊科技<BR>';
			$orderInfo.=$str;
			$times="1";
			$doublesheet=$this->getPaySheetPrintnum($arr['shopid']);
			if($doublesheet){
				$times="2";
			}
			$selfMessage = array(
					'sn'=>$deviceno,
					'printContent'=>$orderInfo,
					'key'=>$devicekey,
					'times'=>$times
			);
			return $selfMessage;
	}
	
	public function createSmallContentHtml($arr,$deviceno,$devicekey){
		//得到商家信息
		$worktwodal=new WorkerTwoDAL();
		$oneshopinfo=$worktwodal->getOneBillShopinfo("billshopinfo", $arr['billid']);
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if(isset($valf['inpack']) && $valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodlength=(strlen($valf['foodname'].$donatestr) + mb_strlen($valf['foodname'].$donatestr,'UTF8'))/2;
			if($foodlength>12){
				$foodname=$valf['foodname'].$donatestr."<BR>";
			}else{
				$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 12);
			}
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 5);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 6) ;
			$foodlist.=$foodname.$foodamount.$foodprice.sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//有几张单子
		$billno=$worktwodal->getBillNum($arr['billid']);
		// 		$billnum=$this->getBillnumToday($shopid);
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
			
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($arr['clearmoney'])){$discountmoney+=$arr['clearmoney'];$disaccountdetails.="抹零￥".$arr['clearmoney']."，";}
		if($arr['discountval']!="100"){
			if($arr['discountval']>100){
				if($arr['discountmode']=="all"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($arr['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($arr['discountval']/100)-1))."，";
				}
			
			}else{
				if($arr['discountmode']=="all"){
					$discountmoney+=ceil($totalmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$arr['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$arr['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($arr['ticketway'])&&!empty($arr['ticketnum'])&&!empty($arr['ticketval'])){
			$ticketname=$this->getOneCounponType($arr['ticketway']);
			$discountmoney+=$arr['ticketnum']*$arr['ticketval'];
			$disaccountdetails.=$ticketname."￥".($arr['ticketval']."*".$arr['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($arr['returndepositmoney']) || $arr['returndepositmoney']=="0"){
			$returndepositmoney=$arr['returndepositmoney'];
		}
		//开钱箱
		$str="";
		$box=array();
		$box[0]=0x1b;
		$box[1]=0x64;
		$box[2]=0x01;
		$box[3]=0x1b;
		$box[4]=0x70;
		$box[5]=0x30;
		$box[6]=0x1e;
		$box[7]=0x78;
		$str="";
		// 			if($arr['openbox']=="1"){
		// 				foreach ($box as $val){
		// 					$str.=chr($val);
		// 				}
		// 			}
		foreach ($box as $val){
			$str.=chr($val);
		}
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
				$title="预结单";
			}else{
				$title="结账单";
			}
		if(!empty($arr['cashmoney'])){//&&!empty($arr['cuspay'])
			if(!empty($arr['cuspay'])){
				$paytypestr.="现金付款：￥".sprintf("%.0f",$arr['cashmoney'])."";
				if($arr['cuspay']!=$arr['cashmoney']+$arr['unionmoney']+$arr['vipmoney']+$arr['meituanpay']+$arr['wechatpay']+$arr['alipay']){
					$paytypestr.="(实收：￥".$arr['cuspay']."，找零：￥".($arr['cuspay']-$arr['cashmoney']).")";
				}
			}else{
				$paytypestr.="实收现金：￥".$arr['cashmoney']." ";
			}
		}
		if(!empty($arr['unionmoney'])){
			$paytypestr.="银联卡付款：￥".$arr['unionmoney']." ";
		}
		if(!empty($arr['vipmoney'])){
			$paytypestr.="会员卡付款：￥".$arr['vipmoney']." ";
		}
		if(!empty($arr['meituanpay'])){
			$paytypestr.="美团账户付款：￥".$arr['meituanpay']." ";
		}
		if(!empty($arr['dazhongpay'])){
			$paytypestr.="大众账户付款：￥".$arr['dazhongpay']." ";
		}
		if(!empty($arr['nuomipay'])){
			$paytypestr.="糯米账户付款：￥".$arr['nuomipay']." ";
		}
		if(!empty($arr['otherpay'])){
			$paytypestr.="其他：￥".$arr['otherpay']." ";
		}
		if(!empty($arr['alipay'])){
			$paytypestr.="支付宝付款：￥".$arr['alipay']." ";
		}
		if(!empty($arr['wechatpay'])){
			$paytypestr.="微信支付：￥".$arr['wechatpay']."";
		}
		elseif($arr['paystatus']=="unpay"){
			if(!empty($arr['qrcode'])){
				$paytypestr.="应付金额：￥".$arr['shouldpay']." <BR>
						用支付宝客户端扫一扫，立即买单！<BR><QRcode>https://qr.alipay.com/".$arr['qrcode']."</QRcode>";
				$title="预结单";
			}
		}elseif($arr['cashmoney']=="0"&&empty($arr['alipay'])&&empty($arr['signername'])&&empty($arr['freename'])){
			$paytypestr.="实收现金：￥0<BR>";
		}
		if(!empty($arr['tabname'])){$tabname=$arr['tabname'];}else{$tabname="";}
		if($arr['takeout']=="1"){$takeoutstr="<B>[外送]</B>";}else{$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<C>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</C><BR>';
		if(!empty($billno)){
			$orderInfo .= '<C><B>单号：'.$billno.'</B></C><BR>';
		}
		
		if(isset($oneshopinfo['porttype'])){
		    if(!empty($oneshopinfo['porttype'])){
		        $orderInfo.='<CB>出库单</CB><BR>';
		    }else{
		       $orderInfo.='<CB>送货单</CB><BR>';
		    }
		}
		if(isset($oneshopinfo['distribution'])){
		    if(!empty($oneshopinfo['distribution'])){
		        $orderInfo.='<CB>配送</CB><BR>';
		    }else{
		        $orderInfo.='<CB>自提</CB><BR>';
		    }
		}
		$orderInfo.='台号：'.$tabname.'   人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 12);
		$itemnum=$this->getStableLenStr("数量", 5);
		$itemprice=$this->getStableLenStr("单价", 6);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		if(!empty($arr['cashierman'])){
				$orderInfo.='收银人员：'.$arr['cashierman'].'<BR>';
			}
		if(!empty($arr['othermoney'])){
			$orderInfo.='其他费用：￥'.sprintf('%.2f',$arr['othermoney']).' <BR>';
		}
		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=''.$paytypestr.'<BR>';
		if(!empty($oneshopinfo)){
			$orderInfo.='收货地址：'.$oneshopinfo['prov'].$oneshopinfo['city'].$oneshopinfo['dist'].$oneshopinfo['road'].'<BR>';
			if(!empty($oneshopinfo['shopname'])){
			    $orderInfo.='店名：'.$oneshopinfo['shopname'].'<BR>';
			}
			if(!empty($oneshopinfo['author'])){
			    $orderInfo.='下单人：'.$oneshopinfo['author'].'<BR>';
			}
			$orderInfo.='联系人：'.$oneshopinfo['contact'].'<BR>';
			$orderInfo.='电话：'.$oneshopinfo['phone'].'<BR>';
			if(!empty($oneshopinfo['carno'])){
			    $orderInfo.='车牌号：'.$oneshopinfo['carno'].'<BR>';
			}
			$orderInfo.='提送时间：'.$oneshopinfo['picktime'].'<BR>';
			$orderInfo.="备注：".$oneshopinfo['orderrequest'].'<BR>';
		}
		if(empty($oneshopinfo['orderrequest'])){
			if(!empty($arr['orderrequest'])){
				$orderInfo.="备注：".$arr['orderrequest'].'<BR>';
			}
		}
		if(!empty($arr['takeoutphone'])){
			$orderInfo.="<B>联系方式：".$arr['takeoutphone'].'</B><BR>';
			$orderInfo.='客户已提前支付点餐，到店后请在后台确认，会自动下单到厨房！<BR>';
		}
		if(!empty($arr['takeoutaddress'])){
			//$orderInfo.="<B>外送地址：".$arr['takeoutaddress'].'</B><BR>';
		}
		if(!empty($arr['signername'])){
			$orderInfo.='签单人：'.$arr['signername'].'<BR>';
			$orderInfo.='签单单位：'.$arr['signerunit'].'<BR>';
		}
		if(!empty($arr['freename'])){
			$orderInfo.='免单人：'.$arr['freename'].'<BR>';
		}
		
		$orderInfo .='--------------------------------<BR>';
		$cusadvarr=$this->getCusSheetAdvData($arr['shopid']);
		if(!empty($cusadvarr)){
			foreach ($cusadvarr as $key=>$val){
				$orderInfo.=''.$val['content'].'<BR>';
				if(!empty($val['advurl'])){
					$orderInfo.='<QRcode>'.$val['advurl'].'</QRcode><BR>';
				}
			}
		}
		$orderInfo.='power by 街坊科技<BR>';
		$orderInfo.=$str;
		$times="1";
		$doublesheet=$this->getPaySheetPrintnum($arr['shopid']);
		if($doublesheet){
			$times="2";
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>$times
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IConsumeListDAL::getStableLenStr()
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
	/* (non-PHPdoc)
	 * @see IConsumeListDAL::getCounponTypeData()
	 */
	public function getOneCounponType($ctid) {
		// TODO Auto-generated method stub
		if(empty($ctid)){return "";}
		$qarr=array("_id"=>new MongoId($ctid));
		$oparr=array("coupontype"=>1);
		$arr=array();
		$result=DALFactory::createInstanceCollection(self::$coupontype)->findOne($qarr,$oparr);
		$coupontype="";
		if(!empty($result['coupontype'])){
			$coupontype=$result['coupontype'];
		}
		return $coupontype;
	}

	public function getDepositmoney($shopid){
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("depositmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$depositmoney="0";
		if(!empty($result['depositmoney'])){
			$depositmoney=$result['depositmoney'];
		}
		return $depositmoney;
	}
	
	public function isInDonateticketTable($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket)->findOne($qarr,$oparr);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	
	public function createDonateticketContentHtml($arr,$deviceno,$devicekey){
		// TODO Auto-generated method stub
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if($valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 20);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 8);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 8) ;
			$foodlist.=$foodname.$foodamount.$foodprice.
			sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
		
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($arr['clearmoney'])){$discountmoney+=$arr['clearmoney'];$disaccountdetails.="抹零￥".$arr['clearmoney']."，";}
		if($arr['discountval']!="100"){
			if($arr['discountval']>100){
				if($arr['discountmode']=="all"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($arr['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($arr['discountval']/100)-1))."，";
				}
			
			}else{
				if($arr['discountmode']=="all"){
					$discountmoney+=ceil($totalmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$arr['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$arr['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($arr['ticketway'])&&!empty($arr['ticketnum'])&&!empty($arr['ticketval'])){
			$ticketname=$this->getOneCounponType($arr['ticketway']);
			$discountmoney+=$arr['ticketnum']*$arr['ticketval'];
			$disaccountdetails.=$ticketname."￥".($arr['ticketval']."*".$arr['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($arr['returndepositmoney']) || $arr['returndepositmoney']=="0"){
			$returndepositmoney=$arr['returndepositmoney'];
		}
		//开钱箱
		$str="";
		$box=array();
		$box[0]=0x1b;
		$box[1]=0x64;
		$box[2]=0x01;
		$box[3]=0x1b;
		$box[4]=0x70;
		$box[5]=0x30;
		$box[6]=0x1e;
		$box[7]=0x78;
		$str="";
		// 		if($arr['openbox']=="1"){
		// 			foreach ($box as $val){
		// 				$str.=chr($val);
		// 			}
		// 		}
		foreach ($box as $val){
			$str.=chr($val);
		}
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
				$title="预结单";
			}else{
				$title="结账单";
			}
		if(!empty($arr['cashmoney'])){//&&!empty($arr['cuspay'])
			if(!empty($arr['cuspay'])){
				$paytypestr.="现金付款：￥".sprintf("%.0f",$arr['cashmoney'])."";
				if($arr['cuspay']!=$arr['cashmoney']+$arr['unionmoney']+$arr['vipmoney']+$arr['meituanpay']+$arr['wechatpay']+$arr['alipay']){
					$paytypestr.="(实收：￥".$arr['cuspay']."，找零：￥".($arr['cuspay']-$arr['cashmoney']).")";
				}
			}else{
				$paytypestr.="实收现金：￥".$arr['cashmoney']." ";
			}
		}
		if(!empty($arr['unionmoney'])){
			$paytypestr.="银联卡付款：￥".$arr['unionmoney']." ";
		}
		if(!empty($arr['vipmoney'])){
			$paytypestr.="会员卡付款：￥".$arr['vipmoney']." ";
		}
		if(!empty($arr['meituanpay'])){
			$paytypestr.="美团账户付款：￥".$arr['meituanpay']." ";
		}
		if(!empty($arr['dazhongpay'])){
			$paytypestr.="大众账户付款：￥".$arr['dazhongpay']." ";
		}
		if(!empty($arr['nuomipay'])){
			$paytypestr.="糯米账户付款：￥".$arr['nuomipay']." ";
		}
		if(!empty($arr['otherpay'])){
			$paytypestr.="其他：￥".$arr['otherpay']." ";
		}
		if(!empty($arr['alipay'])){
			$paytypestr.="支付宝付款：￥".$arr['alipay']." ";
		}
		if(!empty($arr['wechatpay'])){
			$paytypestr.="微信支付：￥".$arr['wechatpay']."";
		}
		elseif($arr['paystatus']=="unpay"){
			if(!empty($arr['qrcode'])){
				$paytypestr.="应付金额：￥".$arr['shouldpay']." <BR>
						用支付宝客户端扫一扫，立即买单！<BR><QRcode>https://qr.alipay.com/".$arr['qrcode']."</QRcode>";
				$title="预结单";
			}
		}elseif($arr['cashmoney']=="0"&&empty($arr['alipay'])&&empty($arr['signername'])&&empty($arr['freename'])){
			$paytypestr.="实收现金：￥0<BR>";
		}
		if($arr['takeout']=="1"){$takeoutstr="<B>[外卖]</B>";}else{$takeoutstr="";}
		$orderInfo='';
// 		$orderInfo.='<CB>'.$arr['shopname'].'</CB><BR>';
		$orderInfo .= '<C>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</C><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='台号：'.$arr['tabname'].'   人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 20);
		$itemnum=$this->getStableLenStr("数量", 8);
		$itemprice=$this->getStableLenStr("单价", 8);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		if(!empty($arr['cashierman'])){
				$orderInfo.='收银人员：'.$arr['cashierman'].'<BR>';
			}
		
		if(!empty($arr['othermoney'])){
			$orderInfo.='其他费用：￥'.sprintf('%.2f',$arr['othermoney']).' <BR>';
		}
		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=''.$paytypestr.'<BR><BR>';
		if(!empty($arr['takeoutphone'])){
			$orderInfo.="<B>外送电话：".$arr['takeoutphone'].'</B><BR>';
		}
		if(!empty($arr['takeoutaddress'])){
			$orderInfo.="<B>外送地址：".$arr['takeoutaddress'].'</B><BR>';
		}
		if(!empty($arr['signername'])){
			$orderInfo.='签单人：'.$arr['signername'].'<BR>';
			$orderInfo.='签单单位：'.$arr['signerunit'].'<BR>';
		}
		if(!empty($arr['freename'])){
			$orderInfo.='免单人：'.$arr['freename'].'<BR>';
		}
		
		$fullmoney=$this->getDonateticketFullmoney($arr['shopid'], $arr['food']);
		$rulearr=$this->getDonateticketRule($arr['shopid']);
		$sendmoney=0;
		foreach ($rulearr as $rkey=>$rval){
			if($fullmoney>=$rval['fullmoney']){
				$sendmoney=$rval['sendmoney'];
			}
		}
		$tipscontentarr=$this->getDonateticketTips($arr['shopid']);
		if(!empty($sendmoney)){
			$orderInfo .='---------------------------------------------<BR>';
			$orderInfo.='<B>送券：￥'.$sendmoney.'</B><BR><BR>';
			foreach ($tipscontentarr as $key=>$val){
				if($val['tipswitch']=="1"){
					$orderInfo.='<B>'.$val['tipcontent'].'</B><BR>';
				}else{
					$orderInfo.=''.$val['tipcontent'].'<BR>';
				}
			}
		}
// 		$orderInfo.='<C>'.$arr['shopname'].'</C><BR><BR>';
		$orderInfo.=$str;
		$times="1";
		$doublesheet=$this->getPaySheetPrintnum($arr['shopid']);
		if($doublesheet){
			$times="2";
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>$times
		);
		return $selfMessage;
	}
	
	
	public function createDonateticketSmallContentHtml($arr,$deviceno,$devicekey){
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if($valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodlength=(strlen($valf['foodname'].$donatestr) + mb_strlen($valf['foodname'].$donatestr,'UTF8'))/2;
			if($foodlength>12){
				$foodname=$valf['foodname'].$donatestr."<BR>";
			}else{
				$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 12);
			}
// 			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 12);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 5);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 6) ;
			$foodlist.=$foodname.$foodamount.$foodprice.
			sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
		
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($arr['clearmoney'])){$discountmoney+=$arr['clearmoney'];$disaccountdetails.="抹零￥".$arr['clearmoney']."，";}
		if($arr['discountval']!="100"){
			if($arr['discountval']>100){
				if($arr['discountmode']=="all"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($arr['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($arr['discountval']/100)-1))."，";
				}
			
			}else{
				if($arr['discountmode']=="all"){
					$discountmoney+=ceil($totalmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$arr['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$arr['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($arr['ticketway'])&&!empty($arr['ticketnum'])&&!empty($arr['ticketval'])){
			$ticketname=$this->getOneCounponType($arr['ticketway']);
			$discountmoney+=$arr['ticketnum']*$arr['ticketval'];
			$disaccountdetails.=$ticketname."￥".($arr['ticketval']."*".$arr['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($arr['returndepositmoney']) || $arr['returndepositmoney']=="0"){
			$returndepositmoney=$arr['returndepositmoney'];
		}
		//开钱箱
		$str="";
		$box=array();
		$box[0]=0x1b;
		$box[1]=0x64;
		$box[2]=0x01;
		$box[3]=0x1b;
		$box[4]=0x70;
		$box[5]=0x30;
		$box[6]=0x1e;
		$box[7]=0x78;
		$str="";
		// 		if($arr['openbox']=="1"){
		// 			foreach ($box as $val){
		// 				$str.=chr($val);
		// 			}
		// 		}
		foreach ($box as $val){
			$str.=chr($val);
		}
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
				$title="预结单";
			}else{
				$title="结账单";
			}
		if(!empty($arr['cashmoney'])){//&&!empty($arr['cuspay'])
			if(!empty($arr['cuspay'])){
				$paytypestr.="现金付款：￥".sprintf("%.0f",$arr['cashmoney'])."";
				if($arr['cuspay']!=$arr['cashmoney']+$arr['unionmoney']+$arr['vipmoney']+$arr['meituanpay']+$arr['wechatpay']+$arr['alipay']){
					$paytypestr.="(实收：￥".$arr['cuspay']."，找零：￥".($arr['cuspay']-$arr['cashmoney']).")";
				}
			}else{
				$paytypestr.="实收现金：￥".$arr['cashmoney']." ";
			}
		}
		if(!empty($arr['unionmoney'])){
			$paytypestr.="银联卡付款：￥".$arr['unionmoney']." ";
		}
		if(!empty($arr['vipmoney'])){
			$paytypestr.="会员卡付款：￥".$arr['vipmoney']." ";
		}
		if(!empty($arr['meituanpay'])){
			$paytypestr.="美团账户付款：￥".$arr['meituanpay']." ";
		}
		if(!empty($arr['dazhongpay'])){
			$paytypestr.="大众账户付款：￥".$arr['dazhongpay']." ";
		}
		if(!empty($arr['nuomipay'])){
			$paytypestr.="糯米账户付款：￥".$arr['nuomipay']." ";
		}
		if(!empty($arr['otherpay'])){
			$paytypestr.="其他：￥".$arr['otherpay']." ";
		}
		if(!empty($arr['alipay'])){
			$paytypestr.="支付宝付款：￥".$arr['alipay']." ";
		}
		if(!empty($arr['wechatpay'])){
			$paytypestr.="微信支付：￥".$arr['wechatpay']."";
		}
		elseif($arr['paystatus']=="unpay"){
			if(!empty($arr['qrcode'])){
				$paytypestr.="应付金额：￥".$arr['shouldpay']." <BR>
						用支付宝客户端扫一扫，立即买单！<BR><QRcode>https://qr.alipay.com/".$arr['qrcode']."</QRcode>";
				$title="预结单";
			}
		}elseif($arr['cashmoney']=="0"&&empty($arr['alipay'])&&empty($arr['signername'])&&empty($arr['freename'])){
			$paytypestr.="实收现金：￥0<BR>";
		}
		if($arr['takeout']=="1"){$takeoutstr="<B>[外卖]</B>";}else{$takeoutstr="";}
		$orderInfo='';
		// 		$orderInfo.='<CB>'.$arr['shopname'].'</CB><BR>';
		$orderInfo .= '<C>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</C><BR>';
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='台号：'.$arr['tabname'].'   人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 12);
		$itemnum=$this->getStableLenStr("数量", 5);
		$itemprice=$this->getStableLenStr("单价", 6);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		if(!empty($arr['cashierman'])){
				$orderInfo.='收银人员：'.$arr['cashierman'].'<BR>';
			}
		
		if(!empty($arr['othermoney'])){
			$orderInfo.='其他费用：￥'.sprintf('%.2f',$arr['othermoney']).' <BR>';
		}
		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=''.$paytypestr.'<BR>';
		if(!empty($arr['takeoutphone'])){
			$orderInfo.="<B>外送电话：".$arr['takeoutphone'].'</B><BR>';
		}
		if(!empty($arr['takeoutaddress'])){
			$orderInfo.="<B>外送地址：".$arr['takeoutaddress'].'</B><BR>';
		}
		if(!empty($arr['signername'])){
			$orderInfo.='签单人：'.$arr['signername'].'<BR>';
			$orderInfo.='签单单位：'.$arr['signerunit'].'<BR>';
		}
		if(!empty($arr['freename'])){
			$orderInfo.='免单人：'.$arr['freename'].'<BR>';
		}
		
		$fullmoney=$this->getDonateticketFullmoney($arr['shopid'], $arr['food']);
		$rulearr=$this->getDonateticketRule($arr['shopid']);
		$sendmoney=0;
		foreach ($rulearr as $rkey=>$rval){
			if($fullmoney>=$rval['fullmoney']){
				$sendmoney=$rval['sendmoney'];
			}
		}
		$tipscontentarr=$this->getDonateticketTips($arr['shopid']);
		if(!empty($sendmoney)){
			$orderInfo .='--------------------------------<BR>';
			$orderInfo.='<B>送券：￥'.$sendmoney.'</B><BR>';
			foreach ($tipscontentarr as $key=>$val){
				if($val['tipswitch']=="1"){
					$orderInfo.='<B>'.$val['tipcontent'].'</B><BR>';
				}else{
					$orderInfo.=''.$val['tipcontent'].'<BR>';
				}
			}
		}
		$orderInfo.=$str;
		$times="1";
		$doublesheet=$this->getPaySheetPrintnum($arr['shopid']);
		if($doublesheet){
			$times="2";
		}
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>$times
		);
		return $selfMessage;
	}
	public function getDonateticketFullmoney($shopid,$foodarr){
		$donateticketftarr=$this->getDonateticketFtarr($shopid);
		$fullmoney=0;
		if(!empty($donateticketftarr)){
			foreach ($foodarr as $key=>$val){
				if(in_array($val['ftid'], $donateticketftarr)){
					$fullmoney+=$val['foodprice']*$val['foodamount'];
				}
			}
		}
		return $fullmoney;
	}
	public function getDonateticketFtarr($shopid){
		$qarr=array("shopid"=>$shopid,"donateticket"=>"1");
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$foodtype)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=strval($val['_id']);
		}
		return $arr;
	}
	
	public function getDonateticketRule($shopid){
		$qarr=array("shopid"=>$shopid);
		$oparr=array("fullmoney"=>1,"sendmoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket_rule)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("fullmoney"=>$val['fullmoney'],"sendmoney"=>$val['sendmoney']);
		}
		$arr=$this->array_sort($arr, "fullmoney","asc");
		return $arr;
	}
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
	 * @see IConsumeListDAL::getDonateticketTips()
	 */
	public function getDonateticketTips($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("content"=>1);
		$result=DALFactory::createInstanceCollection(self::$donateticket)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result['content'])){
			foreach ($result['content'] as $key=>$val){
				$arr[]=array("tipcontent"=>$val['tipcontent'],"tipswitch"=>$val['tipswitch'],"sortno"=>$val['sortno']);
			}
		}
		$arr=$this->array_sort($arr, "sortno","asc");
		return $arr;
	}
	
	public function getBillnumToday($shopid){
		$theday=$this->getTheday($shopid);
		$openhour=$this->getOpenHourByShopid($shopid);
		$starttime=strtotime($theday." ".$openhour.":0:0");
		$endtime=strtotime($theday." ".$openhour.":0:0")+86400;
		$qarr=array("shopid"=>$shopid, "timestamp"=>array("\$gte"=>$starttime,"\$lte"=>$endtime));
		return DALFactory::createInstanceCollection(self::$bill)->count($qarr);
	}
	
	public function getOpenHourByShopid($shopid){
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("openhour"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$openhour="0";
		if(!empty($result['openhour'])){
			$openhour=$result['openhour'];
		}
		return $openhour;
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
	
	public function printPrePaySheet($json,$op){
		global $phonekey;
		$consumelistkeyarr=json_decode($json,true);
		if(empty($consumelistkeyarr)){return array();}
		foreach ($consumelistkeyarr as $key=>$val){
			$phonecrypt = new CookieCrypt($phonekey);
			$deviceno=$phonecrypt->decrypt($val['deviceno']);
			$phonecrypt = new CookieCrypt($phonekey);
			$devicekey=$phonecrypt->decrypt($val['devicekey']);
			$printertype=$val['printertype'];
			//在这里判断活动
			if($printertype=="58"){
				$msg=$this->createPrepaySmallContentHtml($val,$deviceno,$devicekey,$val['shopid'],$op);
			}else{
				$msg=$this->createPrepayContentHtml($val,$deviceno,$devicekey,$val['shopid'],$op);
			}
			
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$deviceno,
					"devicekey"=>$devicekey,
					"outputtype"=>"checkout",
					'msg'=>$msg,
			);
		}
		return $arr;
	}
	
	public function createPrepayContentHtml($arr,$deviceno,$devicekey,$shopid,$op){
		$worktwodal=new WorkerTwoDAL();
		$billno=$worktwodal->getBillNum($arr['billid']);
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if(isset($valf['inpack']) && $valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 20);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 8);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 8) ;
			$foodlist.=$foodname.$foodamount.$foodprice.sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
			
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($arr['clearmoney'])){$discountmoney+=$arr['clearmoney'];$disaccountdetails.="抹零￥".$arr['clearmoney']."，";}
		if($arr['discountval']!="100"){
			if($arr['discountval']>100){
				if($arr['discountmode']=="all"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($arr['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($arr['discountval']/100)-1))."，";
				}
			
			}else{
				if($arr['discountmode']=="all"){
					$discountmoney+=ceil($totalmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$arr['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$arr['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($arr['ticketway'])&&!empty($arr['ticketnum'])&&!empty($arr['ticketval'])){
			$ticketname=$this->getOneCounponType($arr['ticketway']);
			$discountmoney+=$arr['ticketnum']*$arr['ticketval'];
			$disaccountdetails.=$ticketname."￥".($arr['ticketval']."*".$arr['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($arr['returndepositmoney']) || $arr['returndepositmoney']=="0"){
			$returndepositmoney=$arr['returndepositmoney'];
		}
		//开钱箱
		$str="";
		$box=array();
		$box[0]=0x1b;
		$box[1]=0x64;
		$box[2]=0x01;
		$box[3]=0x1b;
		$box[4]=0x70;
		$box[5]=0x30;
		$box[6]=0x1e;
		$box[7]=0x78;
		$str="";
		// 		if($arr['openbox']=="1"){
		// 			foreach ($box as $val){
		// 				$str.=chr($val);
		// 			}
		// 		}
		foreach ($box as $val){
			$str.=chr($val);
		}
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
				$title="预结单";
			}else{
				$title="结账单";
			}
		if(!empty($arr['cashmoney']) && $op=="cashmoney"){//&&!empty($arr['cuspay'])
			if(!empty($arr['cuspay'])){
				$paytypestr.="现金付款：￥".sprintf("%.0f",$arr['cashmoney'])."";
				if($arr['cuspay']!=$arr['cashmoney']+$arr['unionmoney']+$arr['vipmoney']+$arr['meituanpay']+$arr['wechatpay']+$arr['alipay']){
					$paytypestr.="(实收：￥".$arr['cuspay']."，找零：￥".($arr['cuspay']-$arr['cashmoney']).")";
				}
			}else{
				$paytypestr.="实收现金：￥".$arr['cashmoney']." ";
			}
		}
		if(!empty($arr['unionmoney'])&&$op=="unionmoney"){
			$paytypestr.="银联卡付款：￥".$arr['unionmoney']." ";
		}
		if(!empty($arr['vipmoney'])&&$op=="vipmoney"){
			$paytypestr.="会员卡付款：￥".$arr['vipmoney']." ";
		}
		if(!empty($arr['meituanpay'])&&$op=="meituanpay"){
			$paytypestr.="美团账户付款：￥".$arr['meituanpay']." ";
		}
		if(!empty($arr['alipay']) && $op=="alipay"){
			$paytypestr.="支付宝付款：￥".$arr['alipay']." ";
		}
		if(!empty($arr['wechatpay']) && $op=="wechatpay"){
			$paytypestr.="微信支付：￥".$arr['wechatpay']."";
		}
		if(!empty($arr['dazhongpay']) && $op=="dazhongpay"){
			$paytypestr.="大众账户支付：￥".$arr['dazhongpay']."";
		}
		if(!empty($arr['nuomipay']) && $op=="nuomipay"){
			$paytypestr.="糯米账户支付：￥".$arr['nuomipay']."";
		}
		elseif($arr['paystatus']=="unpay"){
			if(!empty($arr['qrcode'])){
				$paytypestr.="应付金额：￥".$arr['shouldpay']." <BR>
						用支付宝客户端扫一扫，立即买单！<BR><QRcode>https://qr.alipay.com/".$arr['qrcode']."</QRcode>";
				$title="预结单";
			}
		}elseif($arr['cashmoney']=="0"&&empty($arr['alipay'])&&empty($arr['signername'])&&empty($arr['freename'])){
			$paytypestr.="实收现金：￥0<BR>";
		}
		if($arr['takeout']=="1"){$takeoutstr="[外卖]";}else{$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<C>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</C><BR>';
		if(!empty($billno)){
			$orderInfo.='<CB>序号：'.$billno.'</CB><BR>';
		}
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='台号：'.$arr['tabname'].'   人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 20);
		$itemnum=$this->getStableLenStr("数量", 8);
		$itemprice=$this->getStableLenStr("单价", 8);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
	
		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.=''.$paytypestr.'<BR><BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$cusadvarr=$this->getCusSheetAdvData($arr['shopid']);
		if(!empty($cusadvarr)){
			foreach ($cusadvarr as $key=>$val){
				$orderInfo.=''.$val['content'].'<BR>';
				if(!empty($val['advurl'])){
					$orderInfo.='<QRcode>'.$val['advurl'].'</QRcode><BR>';
				}
			}
		}
		$orderInfo.='power by 街坊科技<BR>';
		$orderInfo.=$str;
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function createPrepaySmallContentHtml($arr,$deviceno,$devicekey,$shopid,$op){
		$worktwodal=new WorkerTwoDAL();
		$billno=$worktwodal->getBillNum($arr['billid']);
		
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if(isset($valf['inpack']) && $valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
// 			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 12);
			$foodlength=(strlen($valf['foodname'].$donatestr) + mb_strlen($valf['foodname'].$donatestr,'UTF8'))/2;
			if($foodlength>12){
				$foodname=$valf['foodname'].$donatestr."<BR>";
			}else{
				$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 12);
			}
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 5);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 6) ;
			$foodlist.=$foodname.$foodamount.$foodprice.sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
		
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($arr['clearmoney'])){$discountmoney+=$arr['clearmoney'];$disaccountdetails.="抹零￥".$arr['clearmoney']."，";}
		if($arr['discountval']!="100"){
			if($arr['discountval']>100){
				if($arr['discountmode']=="all"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($arr['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($arr['discountval']/100)-1))."，";
				}
			
			}else{
				if($arr['discountmode']=="all"){
					$discountmoney+=ceil($totalmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$arr['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$arr['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$arr['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($arr['ticketway'])&&!empty($arr['ticketnum'])&&!empty($arr['ticketval'])){
			$ticketname=$this->getOneCounponType($arr['ticketway']);
			$discountmoney+=$arr['ticketnum']*$arr['ticketval'];
			$disaccountdetails.=$ticketname."￥".($arr['ticketval']."*".$arr['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($arr['returndepositmoney']) || $arr['returndepositmoney']=="0"){
			$returndepositmoney=$arr['returndepositmoney'];
		}
		//开钱箱
		$str="";
		$box=array();
		$box[0]=0x1b;
		$box[1]=0x64;
		$box[2]=0x01;
		$box[3]=0x1b;
		$box[4]=0x70;
		$box[5]=0x30;
		$box[6]=0x1e;
		$box[7]=0x78;
		$str="";
		// 		if($arr['openbox']=="1"){
		// 			foreach ($box as $val){
		// 				$str.=chr($val);
		// 			}
		// 		}
		foreach ($box as $val){
			$str.=chr($val);
		}
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
				$title="预结单";
			}else{
				$title="结账单";
			}
		if(!empty($arr['cashmoney']) && $op=="cashmoney"){//&&!empty($arr['cuspay'])
			if(!empty($arr['cuspay'])){
				$paytypestr.="现金付款：￥".sprintf("%.0f",$arr['cashmoney'])."";
				if($arr['cuspay']!=$arr['cashmoney']+$arr['unionmoney']+$arr['vipmoney']+$arr['meituanpay']+$arr['wechatpay']+$arr['alipay']){
					$paytypestr.="(实收：￥".$arr['cuspay']."，找零：￥".($arr['cuspay']-$arr['cashmoney']).")";
				}
			}else{
				$paytypestr.="实收现金：￥".$arr['cashmoney']." ";
			}
		}
		if(!empty($arr['unionmoney'])&&$op=="unionmoney"){
			$paytypestr.="银联卡付款：￥".$arr['unionmoney']." ";
		}
		if(!empty($arr['vipmoney'])&&$op=="vipmoney"){
			$paytypestr.="会员卡付款：￥".$arr['vipmoney']." ";
		}
		if(!empty($arr['meituanpay'])&&$op=="meituanpay"){
			$paytypestr.="美团账户付款：￥".$arr['meituanpay']." ";
		}
		if(!empty($arr['alipay']) && $op=="alipay"){
			$paytypestr.="支付宝付款：￥".$arr['alipay']." ";
		}
		if(!empty($arr['wechatpay']) && $op=="wechatpay"){
			$paytypestr.="微信支付：￥".$arr['wechatpay']."";
		}
		if(!empty($arr['dazhongpay']) && $op=="dazhongpay"){
			$paytypestr.="大众账户支付：￥".$arr['dazhongpay']."";
		}
		if(!empty($arr['nuomipay']) && $op=="nuomipay"){
			$paytypestr.="糯米账户支付：￥".$arr['nuomipay']."";
		}
		elseif($arr['paystatus']=="unpay"){
			if(!empty($arr['qrcode'])){
				$paytypestr.="应付金额：￥".$arr['shouldpay']." <BR>
						用支付宝客户端扫一扫，立即买单！<BR><QRcode>https://qr.alipay.com/".$arr['qrcode']."</QRcode>";
				$title="预结单";
			}
		}elseif($arr['cashmoney']=="0"&&empty($arr['alipay'])&&empty($arr['signername'])&&empty($arr['freename'])){
			$paytypestr.="实收现金：￥0<BR>";
		}
		if($arr['takeout']=="1"){$takeoutstr="[外卖]";}else{$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<C>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</C><BR>';
		if(!empty($billno)){
			$orderInfo.='<CB>序号：'.$billno.'</CB><BR>';
		}
		// 		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo.='台号：'.$arr['tabname'].' 人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 12);
		$itemnum=$this->getStableLenStr("数量", 5);
		$itemprice=$this->getStableLenStr("单价", 6);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		
		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.=''.$paytypestr.'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$cusadvarr=$this->getCusSheetAdvData($arr['shopid']);
		if(!empty($cusadvarr)){
			foreach ($cusadvarr as $key=>$val){
				$orderInfo.=''.$val['content'].'<BR>';
				if(!empty($val['advurl'])){
					$orderInfo.='<QRcode>'.$val['advurl'].'</QRcode><BR>';
				}
			}
		}
		$orderInfo.='power by 街坊科技<BR>';
		$orderInfo.=$str;
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>1
		);
		return $selfMessage;
	}
	
	public function getPaySheetPrintnum($shopid){
		if(empty($shopid)){return false;}
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("doublesheet"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$doublesheet=false;
		if($result['doublesheet']=="1"){
			$doublesheet=true;
		}
		return $doublesheet;
	}
	
	public function printPrediscountHtml($json){
		global $phonekey;
		$consumelistkeyarr=json_decode($json,true);
		if(empty($consumelistkeyarr)){return array();}
		foreach ($consumelistkeyarr as $key=>$val){
			$phonecrypt = new CookieCrypt($phonekey);
			$deviceno=$phonecrypt->decrypt($val['deviceno']);
			$phonecrypt = new CookieCrypt($phonekey);
			$devicekey=$phonecrypt->decrypt($val['devicekey']);
			$printertype=$val['printertype'];
			//在这里判断活动
			if($printertype=="58"){
				$msg=$this->createPrediscountSmallHtml($val,$deviceno,$devicekey);
			}else{
				$msg=$this->createPrediscountHtml($val,$deviceno,$devicekey);
			}
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$deviceno,
					"devicekey"=>$devicekey,
					"printertype"=>$printertype,
					"outputtype"=>"checkout",
					'msg'=>$msg,
			);
		}
		return $arr;
	}
	
	public function createPrediscountHtml($arr,$deviceno,$devicekey){
		$worktwodal=new WorkerTwoDAL();
		$billno=$worktwodal->getBillNum($arr['billid']);
		
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if(isset($valf['inpack']) && $valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 20);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 8);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 8) ;
			$foodlist.=$foodname.$foodamount.$foodprice.sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//有几张单子
		// 		$billnum=$this->getBillnumToday($shopid);
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
		$predata=$this->getPrediscountData($arr['billid']);
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($predata['clearmoney'])){$discountmoney+=$predata['clearmoney'];$disaccountdetails.="抹零￥".$predata['clearmoney']."，";}
		if($predata['discountval']!="100"){
			if($predata['discountval']>100){
				if($predata['allcount']=="1"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($predata['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($predata['discountval']/100)-1))."，";
				}
		
			}else{
				if($predata['allcount']=="1"){
					$discountmoney+=ceil($totalmoney*(1-$predata['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$predata['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$predata['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$predata['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($predata['ticketway'])&&!empty($predata['ticketnum'])&&!empty($predata['ticketval'])){
			$ticketname=$this->getOneCounponType($predata['ticketway']);
			$discountmoney+=$predata['ticketnum']*$predata['ticketval'];
			$disaccountdetails.=$ticketname."￥".($predata['ticketval']."*".$predata['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($predata['returndepositmoney']) || $predata['returndepositmoney']=="0"){
			$returndepositmoney=$predata['returndepositmoney'];
		}
		
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
			$title="预结单";
		}else{
			$title="结账单";
		}
		
		if(!empty($arr['tabname'])){$tabname=$arr['tabname'];}else{$tabname="";}
		if($arr['takeout']=="1"){$takeoutstr="<B>[外送]</B>";}else{$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<CB>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</CB><BR>';
		if(!empty($billno)){
			$orderInfo .= '<CB>'.$billno.'</CB><BR>';
		}
		$orderInfo.='台号：'.$tabname.'   人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 20);
		$itemnum=$this->getStableLenStr("数量", 8);
		$itemprice=$this->getStableLenStr("单价", 8);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		if(!empty($arr['cashierman'])){
			$orderInfo.='收银人员：'.$arr['cashierman'].'<BR>';
		}

		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo.='应付金额￥'.$predata['shouldpay'].'<BR>';
// 		$orderInfo.='<QRcode></QRcode>';
		
		$orderInfo.='<BR>';
		$orderInfo.='power by 街坊科技<BR>';
		$times="1";
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>$times
		);
		return $selfMessage;
	}
	
	public function createPrediscountSmallHtml($arr,$deviceno,$devicekey){
		$worktwodal=new WorkerTwoDAL();
		$billno=$worktwodal->getBillNum($arr['billid']);
		
		$foodlist="";
		foreach ($arr['food'] as $kf=>$valf){
			if(isset($valf['inpack']) && $valf['inpack']=="1"){continue;}
			$donatestr="";
			if($valf['present']=="1"){
				$donatestr="[赠]";
			}
			$foodname=$this->getStableLenStr($valf['foodname'].$donatestr, 12);
			$foodamount=$this->getStableLenStr($valf['foodamount'].$valf['foodunit'], 6);
			$foodprice=$this->getStableLenStr(sprintf('%.2f',$valf['foodprice']), 6) ;
			$foodlist.=$foodname.$foodamount.$foodprice.sprintf('%.2f',floatval($valf['foodamount'])*floatval($valf['foodprice'])).'<BR>';
		}
		//有几张单子
		// 		$billnum=$this->getBillnumToday($shopid);
		//优惠详细
		//总金额、可优惠金额计算
		$totalmoney=0;
		$discountfoodmoney=0;
		foreach ($arr['food'] as $foodkey=>$foodval){
			if(empty($foodval['present'])){
				$totalmoney+=$foodval['foodamount']*$foodval['foodprice'];
				if($foodval['fooddisaccount']=="1"){
					$discountfoodmoney+=$foodval['foodamount']*$foodval['foodprice'];
				}
			}
		}
		$totalmoney+=$arr['othermoney'];
		$predata=$this->getPrediscountData($arr['billid']);
		$disaccountdetails="";
		$discountmoney=0;
		if(!empty($predata['clearmoney'])){$discountmoney+=$predata['clearmoney'];$disaccountdetails.="抹零￥".$predata['clearmoney']."，";}
		if($predata['discountval']!="100"){
			if($predata['discountval']>100){
				if($predata['allcount']=="1"){
					$disaccountdetails.="服务费收￥".floor($totalmoney*(($predata['discountval']/100)-1))."，";
				}else{
					$disaccountdetails.="服务费收￥".floor($discountfoodmoney*(($predata['discountval']/100)-1))."，";
				}
		
			}else{
				if($predata['allcount']=="1"){
					$discountmoney+=ceil($totalmoney*(1-$predata['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($totalmoney*(1-$predata['discountval']/100))."，";
				}else{
					$discountmoney+=ceil($discountfoodmoney*(1-$predata['discountval']/100));
					$disaccountdetails.="折扣优惠￥".ceil($discountfoodmoney*(1-$predata['discountval']/100))."，";
				}
			}
		}
		//优惠券
		if(!empty($predata['ticketway'])&&!empty($predata['ticketnum'])&&!empty($predata['ticketval'])){
			$ticketname=$this->getOneCounponType($predata['ticketway']);
			$discountmoney+=$predata['ticketnum']*$predata['ticketval'];
			$disaccountdetails.=$ticketname."￥".($predata['ticketval']."*".$predata['ticketnum']);
		}
		//押金
		$depositmoney=$this->getDepositmoney($arr['shopid']);
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$totalmoney+=$depositmoney;
		}
		$returndepositmoney="";
		if(!empty($predata['returndepositmoney']) || $predata['returndepositmoney']=="0"){
			$returndepositmoney=$predata['returndepositmoney'];
		}
		
		$paytypestr="";
		if($arr['paystatus']=="unpay"){
			$title="预结单";
		}else{
			$title="结账单";
		}
		
		if(!empty($arr['tabname'])){$tabname=$arr['tabname'];}else{$tabname="";}
		if($arr['takeout']=="1"){$takeoutstr="<B>[外送]</B>";}else{$takeoutstr="";}
		$orderInfo='';
		$orderInfo .= '<CB>'.$arr['shopname'].$arr['branchname'].' '.$title.$takeoutstr.'</CB><BR>';
		if(!empty($billno)){
			$orderInfo .= '<CB>'.$billno.'</CB><BR>';
		}
		$orderInfo.='台号：'.$tabname.'   人数：'.$arr['cusnum'].'<BR>';
		$orderInfo .='下单人：'.$arr['nickname'].'<BR>';
		$orderInfo.='下单时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='--------------------------------<BR>';
		$itemname=$this->getStableLenStr("项目名称", 12);
		$itemnum=$this->getStableLenStr("数量", 6);
		$itemprice=$this->getStableLenStr("单价",6);
		$itempsum="金额";
		$orderInfo.=$itemname.$itemnum.$itemprice.$itempsum.'<BR>';
		$orderInfo.=$foodlist;
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='打印时间：'.date('Y-m-d H:i:s',time()).'<BR>';
		if(!empty($arr['cashierman'])){
			$orderInfo.='收银人员：'.$arr['cashierman'].'<BR>';
		}
		
		$orderInfo.='消费总额：￥'.sprintf('%.0f',$totalmoney).'';
		if($arr['deposit']=="1" && !empty($depositmoney)){
			$orderInfo.="(包含押金：￥".$depositmoney.")";
		}
		$orderInfo.="<BR>";
		if(!empty($disaccountdetails)){
			$orderInfo.='费用详细：'.$disaccountdetails.'<BR>';
			$orderInfo.='优惠总额：￥'.$discountmoney.'<BR><BR>';
		}
		if(!empty($returndepositmoney)){
			$orderInfo.='返还押金：￥'.$returndepositmoney.'<BR>';
		}
		$orderInfo .='--------------------------------<BR>';
		$orderInfo.='应付金额￥'.$predata['shouldpay'].'<BR>';
// 		$orderInfo.='<QRcode></QRcode>';
		$orderInfo.='<BR>';
		$orderInfo.='power by 街坊科技<BR>';
		$times="1";
		$selfMessage = array(
				'sn'=>$deviceno,
				'printContent'=>$orderInfo,
				'key'=>$devicekey,
				'times'=>$times
		);
		return $selfMessage;
	}
	
	public function getPrediscountData($billid){
		$qarr=array("billid"=>$billid);
		$result=DALFactory::createInstanceCollection(self::$prebill)->findOne($qarr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"billid"	=>$billid,
					"shopid"=>$result['shopid'],
					"ticketway"=>$result['ticketway'],
					"ticketval"=>$result['ticketval'],
					"ticketnum"=>$result['ticketnum'],
					"discountval"=>$result['discountval'],
					"allcount"=>$result['allcount'],
					"returndepositmoney"=>$result['returndepositmoney'],
					"clearmoney"=>$result['clearmoney'],
					"shouldpay"=>$result['shouldpay'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IMonitorOneDAL::getCusSheetAdvData()
	*/
	public function getCusSheetAdvData($shopid) {
		// TODO Auto-generated method stub
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1, "content"=>1,"advurl"=>1);
		$result=DALFactory::createInstanceCollection(self::$receiptadv)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr[]=array("advid"=>strval($val['_id']),"content"=>$val['content'],"advurl"=>$val['advurl']);
		}
		return $arr;
	}
}
?>