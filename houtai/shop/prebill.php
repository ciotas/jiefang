<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
require_once ('/var/www/html/weshop/wechatjssdk.php');
class PreBill{
	public function getOneBillInfoByBeforeBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($billid);
	}
	public function getTakeoutInfo($uid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getTakeoutInfo($uid);
	}
	public function getTabstatusByTabid($tabid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getTabstatusByTabid($tabid);
	}
}
$prebill=new PreBill();
$beforebillinfo=array();
$totalmoney=0;
$foodnum=0;
$takeoutinfo=array();
$op="inhouse";
if(isset($_REQUEST['billid'])){
	$billid=$_REQUEST['billid'];
	$op=$_REQUEST['op'];
	$food=$_REQUEST['food'];
	$beforebillinfo=$prebill->getOneBillInfoByBeforeBillid($billid);
	foreach ($beforebillinfo['food'] as $fkey=>$fval){
		$foodnum+=$fval['foodamount'];
		if(empty($fval['present'])){
			$totalmoney+=$fval['foodamount']*$fval['foodprice'];
		}
	}
	$tabstatus=$prebill->getTabstatusByTabid($beforebillinfo['tabid']);
	$takeoutinfo=$prebill->getTakeoutInfo($beforebillinfo['uid']);//外卖地址与电话	
}
if($op=="inhouse" || $op=="tab"){
	$takeout="0";
}else{
	$takeout="1";
}

//jssdk
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

	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
    <title>预点详情</title>
    <style type="text/css">
.wap {
	border: 1px solid #e5e5e5;-webkit-appearance: none !important;   
	color: #333333; 		
  outline: 0;
  height: 20px;
	padding: 6px 6px !important;
	margin-top:-6px;
  line-height: 20px;
  font-size: 14px;
	width:70%;
  font-weight: normal;
  vertical-align: top;  
	background-color: #ffffff;
	background-image: none !important;
  filter: none !important;
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
  background: transparent;  
}
</style>
<script type="text/javascript">
var xmlHttp
function addinfo(billid,uid){
	cusphone=document.getElementById("cusphone").value;
	//cusaddress=document.getElementById("cusaddress").value;
	cusaddress="";
	if(cusphone==null || cusphone==""){
		alert("手机号不能为空！");
		return false;
	}
// 	if(cusaddress==null || cusaddress==""){
// 		alert("外卖地址不能为空！");
// 		return false;
// 	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/updatetakeout.php"
	url=url+"?billid="+billid
	url=url+"&uid="+uid
	url=url+"&cusphone="+cusphone
	url=url+"&cusaddress="+cusaddress
	url=url+"&status="+status
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	result=xmlHttp.responseText
 }
}

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
</script>
<script type="text/javascript" src="./media/js/md5.js"></script>
<script>

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
        <?php if( ($beforebillinfo['paystatus']=="unpay"&&$beforebillinfo['billstatus']=="undone") || ($beforebillinfo['paystatus']=="unpay"&&$beforebillinfo['billstatus']=="done"&& ($tabstatus=="start" || $tabstatus=="online")) ){?>
         <a class="tel1" href="<?php echo $wechat_url."index.php?m=Admin&c=Index&a=index&type=$op&shopid=".$beforebillinfo['shopid']."&uid=".$beforebillinfo['uid']."&billid=".$billid;?>" target="_blank"><label>继续点餐</label></a> 
        <?php }?>
        <?php if($beforebillinfo['billstatus']=="undone"|| ($beforebillinfo['paystatus']=="unpay"&& $tabstatus=="empty")){?>
          <a class="tel2" href="./interface/delonebeforebillbybillid.php?billid=<?php echo $billid;?>&shopid=<?php echo $beforebillinfo['shopid'];?>" onclick="return confirm('确定要删除？');"  target="_blank"><label>删除订单</label></a> 
          <?php }?>
          <a class="tel3" href="<?php echo $root_url;?>weshop/shopindex.php?shopid=<?php echo $beforebillinfo['shopid'];?>&type=<?php echo $op;?>" target="_blank"><label>返回首页</label></a>
           <span class="name txtover"><?php if(!empty($beforebillinfo)){echo $beforebillinfo['shopname'];}?></span></span>
             <span class="company_info">  
           		
              <span class="item companyaddress" id="companyaddress">
              <label>人数：</label>
              <span><?php echo $beforebillinfo['cusnum'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <?php if($beforebillinfo['takeout']=="1"){echo "外卖单";}elseif($beforebillinfo['takeout']=="0" && empty($beforebillinfo['tabid'])){echo "台号：待定";}else{echo "台号：".$beforebillinfo['tabname'];}?>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              下单人：<?php echo $beforebillinfo['nickname'];?>
             <br>下单时间：<?php echo date("Y-m-d H:i:s",$beforebillinfo['timestamp']);?>
              </span>
              </span></span>

                </div>
                  <div class="dishinfo">  
                <div class="title">   
                 <span class="tit">已点菜品</span>    
                   <span class="num" id="numCount"><label></label><?php echo $foodnum;?></span>      
                   <span class="money" id="discountPriceCount"><label></label></span>    </div>  
                     <div class="dishlist" id="dishlist">
                     <?php foreach ($beforebillinfo['food'] as $key=>$val){
                    	 if(!empty($val['cooktype'])){$cooktype="(".$val['cooktype'].")";}else{$cooktype="";}
                     	?>
                     <div class="dish">
                       <span class="dishname txtover"><?php echo $val['foodname'].$cooktype;?></span>  
                         
                         <?php  if($beforebillinfo['billstatus']=="undone"){?>
                         <span class="dishnum"><label></label><?php echo $val['foodamount']."×￥".$val['foodprice'];?></span>
                          <span class="dishprice"><label></label><a style="color: red"  onclick="return confirm('确定要删除？');" href="./interface/returnbeforefood.php?billid=<?php echo $billid;?>&returnnum=<?php echo $val['foodamount'];?>&foodid=<?php echo $val['foodid'];?>&foodnum=<?php echo $val['foodamount'];?>&cooktype=<?php echo $val['cooktype'];?>&loc=prebill&op=<?php echo $op;?>">删除</a></span>
                          <?php }else{?>
                          <span class="dishprice"><label></label><?php echo $val['foodamount']."×￥".$val['foodprice'];?></span>
                          <?php }?>
                         </div>
                     <?php }?>
                                </div> 
                            </div>
                            <?php if($op=="takeout"){?>
                                <div class='orderinfo'>
					           <div class='title'>
					               <span class='tit'>手机：</span>
					               <input type="text" name="cusphone" id="cusphone" class="wap" placeholder="手机号" value="<?php if(!empty($takeoutinfo)){echo $takeoutinfo['cusphone'];}?>">
					            </div>
					         <!--    <div class='title'>
					               <span class='tit'>地址：</span>
					               <input type="text" name="cusadress" id="cusaddress" class="wap" placeholder="地址" value="<?php if(!empty($takeoutinfo)){echo $takeoutinfo['cusaddress'];}?>">
					            </div> -->
					           </div>
					           <?php }?>
                    </div>
                    
                    <?php if(($takeout=="1"&&$beforebillinfo['paystatus']=="unpay") || ($takeout=="0" && $beforebillinfo['billstatus']=="done" && $beforebillinfo['paystatus']=="unpay" &&($tabstatus=="start" || $tabstatus=="online")) ){?>
                   					 <form action="./wappay/alipayapi.php" method="post" target="_blank" style="margin:0;padding:0">
										<!-- 商品订单号 -->
											<input type="hidden" name="WIDout_trade_no" value="<?php echo time().mt_rand(1000, 9999);?>" />
											<!-- 商品名称 -->
											<input type="hidden" name="WIDsubject"  value="<?php echo $beforebillinfo['nickname']."的账单"?>"/>
											<!-- 付款金额 -->
										 	 <input type="hidden" name="WIDtotal_fee" value="<?php echo $totalmoney;?>"/> 
											<!--<input type="hidden" name="WIDtotal_fee" value="0.01"/> -->
											<!-- 商品展示地址 -->
											<input type="hidden" name="WIDshow_url"  value="<?php echo "";?>"/>
											<!-- 订单描述 -->
											<input type="hidden" name="WIDbody"  value="<?php echo $billid."|*|*|*|*|*|*|".$beforebillinfo['nickname'];?>"/>
											<!-- 超时时间 -->
											<input type="hidden" name="WIDit_b_pay"  value="30m"/>
											<!-- 钱包token -->
											<input type="hidden" name="WIDextern_token"  value=""/>
											<button  type="submit" onclick="return addinfo('<?php echo $billid;?>','<?php echo $beforebillinfo['uid']?>')" class="btn_bg"><span >支付买单</span> <label style="color: red;font-size:26px;"><?php echo "￥".$totalmoney;?></label></button>
										</form>
				<?php }elseif($takeout=="0" && $beforebillinfo['billstatus']=="undone" && $beforebillinfo['paystatus']=="unpay"){?>
				<input type="hidden"  id="billid"  value="<?php echo $billid;?>"/>
				<input type="hidden"  id="type"  value="<?php echo $op;?>"/>
				<div class="btn_bg">
		            <span id="scanQRCode">扫一扫</span>
		            <label>已到店？扫桌上二维码下单</label>
		        </div>
				<?php }?>
        
    </div>

</body></html>
