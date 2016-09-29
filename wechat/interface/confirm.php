<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
require_once (_ROOT.'des.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class Confirm{
	public function getOneBillInfoByBeforeBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($billid);
	}
	public function getWechatUserinfo($uid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getWechatUserinfo($uid);
	}
	public function getPaySwitch($shopid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getPaySwitch($shopid);
	}
}
$confirm=new Confirm();
$paymoney=0;
if(isset($_REQUEST['billid'])){
	$billid=$_REQUEST['billid'];
	$crypt = new CookieCrypt(ParamKey);
	$billid=$crypt->decrypt($billid);
	$billinfo=$confirm->getOneBillInfoByBeforeBillid($billid);
	foreach ($billinfo['food'] as $fkey=>$fval){
		$paymoney+=$fval['foodprice']*$fval['foodnum'];
	}
	$uid=$billinfo['uid'];
	$wechatinfo=$confirm->getWechatUserinfo($uid);
	$openid="";
	if(!empty($wechatinfo)){
		$openid=$wechatinfo['openid'];
	}
	$switcharr=$confirm->getPaySwitch($billinfo['shopid']);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
<link rel="stylesheet" type="text/css" href="../media/css/public.css">
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black-translucent" name="apple-mobile-web-app-status-bar-style"/>
<title>订单确认</title>
<script type="text/javascript">
//手机端判断各个平台浏览器及操作系统平台
var browser={
	    versions:function(){
	            var u = navigator.userAgent, app = navigator.appVersion;
	            return {         //移动终端浏览器版本信息
	                 trident: u.indexOf('Trident') > -1, //IE内核
	                presto: u.indexOf('Presto') > -1, //opera内核
	                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
	                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
	                mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
	                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
	                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
	                iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
	                iPad: u.indexOf('iPad') > -1, //是否iPad
	                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
	            };
	         }(),
	         language:(navigator.browserLanguage || navigator.language).toLowerCase()
}
	
	xmlHttp=GetXmlHttpObject();
	var platform;
	function addPlatfrom(){
		if (xmlHttp==null)
		  {
			return;
		  } 
		if(browser.versions.ios){
			platform="ios";
		}else if(browser.versions.android){
			platform="android";
		}else{
			platform="winphone";
		}
		var url="./addplatform.php"
		url=url+"?uid="+"<?php echo $billinfo['uid'];?>"
		url=url+"&platform="+platform
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged 
		xmlHttp.open("GET",url,true)
		console.log(url)
		xmlHttp.send(null);
	}

	function stateChanged() 
	{ 
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		 { 
	 		
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
	addPlatfrom();

	function payClick(payid){
		openid=document.getElementById("openid").value;
		orderno=document.getElementById("orderno").value;
		tabid=document.getElementById("tabid").value;
		billid=document.getElementById("billid").value;
		uid=document.getElementById("uid").value;
		shopid=document.getElementById("shopid").value;
		paymoney=document.getElementById("paymoney").value;
		orderrequest=document.getElementById("orderrequest").value;
		PARAMS={
				'openid':openid,
				'orderno':orderno,
				'tabid':tabid,
				'billid':billid,
				'uid':uid,
				'shopid':shopid,
				'paymoney':paymoney,
				'orderrequest':orderrequest,
		}
		if(payid=="alipay"){
			post('<?php echo ROOTURL;?>wappay/alipayapi.php', PARAMS);
		}else if(payid=="wechatpay"){
			post('./payhand.php',PARAMS);
		}else if(payid=="directpay"){
			if(confirm('确定要下单？')){
				post('./directpay.php',PARAMS);
			}
		}
		
	}
	function post(URL, PARAMS) {      
	    var temp = document.createElement("form");      
	    temp.action = URL;      
	    temp.method = "post";      
	    temp.style.display = "none";      
	    for (var x in PARAMS) {      
	        var opt = document.createElement("textarea");      
	        opt.name = x;      
	        opt.value = PARAMS[x];      
	        // alert(opt.name)      
	        temp.appendChild(opt);      
	    }      
	    document.body.appendChild(temp);      
	    temp.submit();      
	    return temp;      
	}      
</script>

</head>

<body style="background:#fff;">
	
	<div class="confirm-head">订单确认</div>
	<div class="prompt ts">提示：您的主食点了嘛？</div>
	<Ul class="confirm-ul">
		<li><div class="add-bz"></div><textarea name="orderrequest"  id="orderrequest" placeholder="在此输入备注"  rows="3"  style="border:1px dotted grey;width:100%;font-size:16px;"></textarea></li>
	</Ul>
	<div class="prompt"><?php echo $billinfo['shopname'];?></div>
	<div class="foot-table">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<th width="50%" align="left">菜名</th><th width="20%">单价</th><th width="15%">数量</th><th width="15%">总价</th>
			</tr>
			 <?php foreach ($billinfo['food'] as $key=>$val){
			 if(!empty($val['cooktype'])){$cooktype="(".$val['cooktype'].")";}else{$cooktype="";}
			 	?>
              <tr>
                <td><?php echo $val['foodname'].$cooktype;?></td><td>￥<?php echo $val['foodprice'];?></td><td><?php echo $val['foodnum'];?><?php echo $val['foodunit'];?></td><td>￥<?php echo $val['foodprice']*$val['foodamount'];?></td>
               </tr>
              <?php }?>
		
		</table>
	</div>
	<div class="pay-box">
				<div class="pay-count">
						<p class="pay-inform">支付成功后可以到订单中查看</p>
						<p class="pay-mondy">￥<?php echo $paymoney?></p>
						
						<div class="pay">
							<!--  <button class="pay-button">线下支付</button> -->
							 <input type="hidden" name="openid"  id="openid"  value="<?php echo $openid;?>">
							     <input type="hidden" name="orderno"  id="orderno"  value="<?php echo $billinfo['orderno'];?>">
							     <input type="hidden" name="tabid"  id="tabid"  value="<?php echo $billinfo['tabid'];?>">
							     <input type="hidden" name="billid"  id="billid" value="<?php echo $billid;?>">
							     <input type="hidden" name="uid"  id="uid" value="<?php echo $billinfo['uid'];?>">
							     <input type="hidden" name="shopid" id="shopid"  value="<?php echo $billinfo['shopid'];?>">
							     <input type="hidden" name="paymoney"  id="paymoney" value="<?php echo $paymoney;?>">
							     
							     <?php 
							     if(!empty($switcharr)){
									if($switcharr['alipay_switch']=="1"){
							     ?>
							     <button class="pay-button" onclick="payClick('alipay')">支付宝支付</button>
							 <?php }}?>   
							      
						</div>
					<br>
					    <?php 
					     if(!empty($switcharr)){
							if($switcharr['wechatpay_switch']=="1"){
					     ?>
					<div class="pay">
						 <button class="pay-button" style="background-color:rgb(0,200,0)" onclick="payClick('wechatpay')">微信支付</button>
							<br> 
						</div>
						 <?php }}?>   
						 
						  <?php 
						     if(!empty($switcharr)){
								if($switcharr['directpay_switch']=="1"){
						     ?>
						<div class="pay">
							<button class="pay-button" onclick="payClick('directpay');" style="background-color:#3B3B3B">直接下单</button>
						</div>
						 <?php }}?> 
				</div>
			</div>
</body>
</html>