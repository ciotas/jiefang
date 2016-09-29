<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
require_once ('/var/www/html/weshop/wechatjssdk.php');
class OneBill{
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function getPreBillByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getPreBillByBillid($billid);
	}
	public function getTabstatusByTabid($tabid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getTabstatusByTabid($tabid);
	}
}
$onebill=new OneBill();
$billarr=array();
if(isset($_REQUEST['billid'])){
	$billid=$_REQUEST['billid'];
// 	$op=$_REQUEST['op'];
	$from=$_REQUEST['from'];
	$uid=$_REQUEST['uid'];
	$takeout=$_REQUEST['takeout'];
	$paystatus=$_REQUEST['paystatus'];
	$billstatus=$_REQUEST['billstatus'];
	if($takeout=="1"){
		$type="takeout";
	}else{
		$type="inhouse";
	}
	$billarr=$onebill->getOneBillInfoByBillid($billid);
	foreach ($billarr['food'] as $fkey=>$fval){
		$foodnum+=$fval['foodamount'];
		if(empty($fval['present'])){
			$totalmoney+=$fval['foodamount']*$fval['foodprice'];
			if($fval['fooddisaccount']=="1"){
				$fooddisaccountmoney+=$fval['foodamount']*$fval['foodprice'];
			}
		}
	}
	$tabstatus=$onebill->getTabstatusByTabid($billarr['tabid']);
	$prearr=$onebill->getPreBillByBillid($billid);
	$tdisaccountmoney="0";
// 	print_r($prearr);exit;
	if(!empty($prearr)){
		$shouldpay=$prearr['shouldpay'];
		if($prearr['allcount']=="1"){
			$tdisaccountmoney=ceil($totalmoney*(1-$prearr['discountval']/100));
		}else{
			$tdisaccountmoney=ceil($fooddisaccountmoney*(1-$prearr['discountval']/100));
		}
		if(!empty($prearr['ticketval'])&&!empty($prearr['ticketnum'])){
			$ticketmoney=$prearr['ticketval']*$prearr['ticketnum'];
		}
		$discountmoney=$tdisaccountmoney+$ticketmoney+$prearr['clearmoney']+$prearr['returndepositmoney'];
		// 	$shouldpay=$totalmoney-$discountmoney;
	}else{
		$discountmoney="0";
		$shouldpay=$totalmoney;
	}
	//判断是否有优惠
	$isdiscount=false;
	if(!empty($prearr['ticketnum'])&&!empty($prearr['ticketval'])){$isdiscount=true;}
	if(!empty($prearr['discountval'])&&$prearr['discountval']<100 && $tdisaccountmoney>0){$isdiscount=true;}
	if(!empty($prearr['returndepositmoney'])){$isdiscount=true;}
	if(!empty($prearr['clearmoney'])){$isdiscount=true;}
}
$jssdk = new JSSDK("wxc5b83fb82bad0b65", "75986a12121b79e429e8b359aa8aab0a");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="./media/css/public.css">
    <link rel="stylesheet" href="./media/css/huichi.css">
    <title><?php echo $billarr['shopname'];?></title>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
<script type="text/javascript" src="./media/js/md5.js"></script>

<script>
var xmlHttp

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
function stateChanged1() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
	data=xmlHttp.responseText;
	data1=eval("("+data+")");
 	if(data1.status=="ok"){
 		window.location.href='http://shop.meijiemall.com/houtai/shop/donesuccess.php?shopid='+data1.shopid+'&type='+data1.type;
 	}else{
 		alert('非法下单，请重试！');
 	}
 }
}

wx.ready(function () {
document.querySelector('#scanQRCode').onclick = function () {
    //wx.scanQRCode({desc: 'scanQRCode desc'});
	wx.scanQRCode({
	      needResult: 1,
	      desc: 'scanQRCode desc',
	      success: function (res) {
	    	  str=eval("("+JSON.stringify(res)+")");
	    	  str = str.resultStr;
	    	  var strarr = str.split('&deskno=');
	    	  var billid = document.getElementById('billid').value;
	    	  var type = document.getElementById('type').value;
	    	  tabid=strarr[1];
	    	  timestamp=new Date().getTime();
	    	  signature=hex_md5(billid+tabid+timestamp+"560ffb637cc109");
	    	  
	    	  xmlHttp=GetXmlHttpObject()
	    		if (xmlHttp==null)
	    		  {
	    		  alert ("Browser does not support HTTP Request")
	    		  return
	    		  } 
	    		var url="http://shop.meijiemall.com/printbill/interface/scanprint.php"
	    		url=url+"?billid="+billid
	    		url=url+"&type="+type
	    		url=url+"&tabid="+tabid
	    		url=url+"&timestamp="+timestamp
	    		url=url+"&signature="+signature
	    		url=url+"&sid="+Math.random()
	    		xmlHttp.onreadystatechange=stateChanged1
	    		xmlHttp.open("GET",url,true)
	    		xmlHttp.send(null)
	      }
	    });
 };
});
wx.config({
	beta: true,
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $signPackage["appId"];?>', // 必填，公众号的唯一标识
    timestamp: <?php echo $signPackage["timestamp"];?>, // 必填，生成签名的时间戳
    nonceStr: '<?php echo $signPackage["nonceStr"];?>', // 必填，生成签名的随机串
    signature: '<?php echo $signPackage["signature"];?>',// 必填，签名
    jsApiList: ['scanQRCode'] // 必填，需要使用的JS接口列表
});
</script>
</head>
<body class="menuDetailbg" >
    <div class="menuDetail" id="orderDetailbg" style="display: block;">
        <div class="orderDetail" id="orderDetail">
        <div class="companyinfo">  <span class="companyName">  
        <?php if( ($billarr['paystatus']=="unpay" && $billarr['billstatus']=="undone") || ($billarr['paystatus']=="unpay"&&$billarr['billstatus']=="done" &&($tabstatus=="start" || $tabstatus=="online"))){?>
        <a class="tel1" href="<?php echo $wechat_url."index.php?m=Admin&c=Index&a=index&type=$type&shopid=".$billarr['shopid']."&uid=".$billarr['uid']."&billid=$billid";?>" ><label>加 菜</label></a>
        <?php }?>
        <?php if($billarr['billstatus']=="undone" || ($billarr['paystatus']=="unpay"&& $tabstatus=="empty") ){?> 
          <a class="tel2" href="./interface/delonebillbybillid.php?billid=<?php echo $billid;?>&shopid=<?php echo $billarr['shopid'];?>&from=<?php echo $from;?>&uid=<?php echo $uid;?>" onclick="return confirm('确定要删除？');" ><label>删除订单</label></a> 
          <?php }?>
         
           <span class="name txtover"><?php if(!empty($billarr)){echo $billarr['shopname'];}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <?php if($billarr['takeout']=="1"){echo "外卖单";}elseif($billarr['takeout']=="0" && empty($billarr['tabid'])){echo "台号：待定";}else{echo "台号：".$billarr['tabname'];}?></span></span>
             <span class="company_info">  
              <span class="item companyaddress" id="companyaddress">
              <label>人数：</label>
              <span><?php echo $billarr['cusnum'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;下单人：<?php echo $billarr['nickname'];?>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("Y-m-d H:i:s",$billarr['timestamp']);?>
              </span>
              </span></span>

                </div>
                  <div class="dishinfo">  
                <div class="title">   
                 <span class="tit">已点菜品</span>    
                   <span class="num" id="numCount"><label></label><?php echo $foodnum;?></span>     </div> 
                     <div class="dishlist" id="dishlist">
                     <?php foreach ($billarr['food'] as $key=>$val){
                    	 if(!empty($val['cooktype'])){$cooktype="(".$val['cooktype'].")";}else{$cooktype="";}
                     	?>
                    <div class="dish">
                       <span class="dishname txtover"><?php echo $val['foodname'].$cooktype;?></span>  
                         <?php  if($billarr['billstatus']=="undone"){?>
                         <span class="dishnum"><label></label><?php echo $val['foodamount']."×￥".$val['foodprice'];?></span>
                          <span class="dishprice"><label></label><a style="color: red"  onclick="return confirm('确定要删除？');" href="./interface/returnbeforefood.php?billid=<?php echo $billid;?>&returnnum=<?php echo $val['foodamount'];?>&foodid=<?php echo $val['foodid'];?>&foodnum=<?php echo $val['foodamount'];?>&cooktype=<?php echo $val['cooktype'];?>&loc=onebill&op=<?php echo $type;?>&paystatus=<?php echo $paystatus;?>&billstatus=<?php echo $billstatus;?>">删除</a></span>
                          <?php }else{?>
                           <span class="dishprice"><label></label><?php echo $val['foodamount']."×￥".$val['foodprice'];?></span>                          
                          <?php }?>
                         </div>
                     <?php }?>
                                </div> 
                            </div>
                            <?php if($isdiscount==true){?>
                            <div class='orderinfo'>
					           <div class='title'>
					               <span class='tit'>优惠：
					               <span style="color:red;"><?php echo "￥".$discountmoney." ";?></span>
										<?php if(!empty($prearr['ticketnum'])&&!empty($prearr['ticketval'])){echo $prearr['ticketname']."：￥".$prearr['ticketnum']*$prearr['ticketval']." ";}?>
										<?php if(!empty($prearr['discountval'])&&$prearr['discountval']<100 && $tdisaccountmoney>0){echo "折扣￥".$tdisaccountmoney." ";}?>
										<?php if(!empty($prearr['returndepositmoney'])){echo "退押金￥".$prearr['returndepositmoney']." ";}?>
										<?php if(!empty($prearr['clearmoney'])){echo "抹零￥".$prearr['clearmoney']."";}?>
					               </span>
					            </div>
					           </div>
					           <?php }?>
                    </div>
                    
                    			<?php if(($takeout=="1"&&$paystatus=="unpay") || ($takeout=="0" && $billstatus=="done" && $paystatus=="unpay" &&($tabstatus=="start" || $tabstatus=="online") ) ){?>
                   					 <form action="./wappay/alipayapi.php" method="post" target="_blank" style="margin:0;padding:0">
										<!-- 商品订单号 -->
											<input type="hidden" name="WIDout_trade_no" value="<?php echo time().mt_rand(1000, 9999);?>" />
											<!-- 商品名称 -->
											<input type="hidden" name="WIDsubject"  value="<?php echo $billarr['nickname']."的账单"?>"/>
											<!-- 付款金额 -->
										 	 <input type="hidden" name="WIDtotal_fee" value="<?php echo $shouldpay;?>"/> 
											<!--<input type="hidden" name="WIDtotal_fee" value="0.01"/> -->
											<!-- 商品展示地址 -->
											<input type="hidden" name="WIDshow_url"  value="<?php echo "";?>"/>
											<!-- 订单描述 -->
											<input type="hidden" name="WIDbody"  value="<?php echo $billid."|*|*|*|*|*|*|".$billarr['nickname'];?>"/>
											<!-- 超时时间 -->
											<input type="hidden" name="WIDit_b_pay"  value="30m"/>
											<!-- 钱包token -->
											<input type="hidden" name="WIDextern_token"  value=""/>
											
											<button  type="submit"  class="btn_bg"><span >买单</span> <label style="color: red;font-size:26px;"><?php echo "￥".$shouldpay;?></label></button>
										</form>
				<?php }?>
    </div>

</body></html>