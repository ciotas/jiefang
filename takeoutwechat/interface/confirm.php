<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
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
	public function getOneShop_infoData($shopid,$uid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getOneShop_infoData($shopid,$uid);
	}
	public function getDistanceLimit($shopid){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDistanceLimit($shopid);
	}
	public function getDiscountByOnline($shopid, $paymoney){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDiscountByOnline($shopid, $paymoney);
	}
	public function getDistributeFee($inputarr){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDistributeFee($inputarr);
	}
	public function getDIstance($inputarr){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDIstance($inputarr);
	}
	public function getMyAddress($uid){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getMyAddress($uid);
	}
}
$confirm=new Confirm();
$paymoney=0;
if(isset($_REQUEST['openid'])){
	$openid=$_REQUEST['openid'];
	$food=$_REQUEST['food'];
	$cook=$_REQUEST['cook'];
	$uid=$_REQUEST['uid'];
	$shopid=$_REQUEST['shopid'];
	$tabid=$_REQUEST['tabid'];
	$maxdistance=$confirm->getDistanceLimit($shopid);
	$oneshopinfo=$confirm->getMyAddress($uid);

	if(!empty($oneshopinfo['prov'])){$prov=$oneshopinfo['prov'];}else{$prov="";}
	if(!empty($oneshopinfo['city'])){$city=$oneshopinfo['city'];}else{$city="";}
	if(!empty($oneshopinfo['dist'])){$dist=$oneshopinfo['dist'];}else{$dist="";}
	if(!empty($oneshopinfo['road'])){$road=$oneshopinfo['road'];}else{$road="";}
	
	
	$foodarr=json_decode($food,true);
	$paymoney=0;
	foreach ($foodarr as $fkey=>$fval){
		foreach ($fval as $ffkey=>$vval){
			$paymoney+=$vval['foodNum']*$vval['foodPrice'];
		}
	}
	$cookarr=json_decode($cook,true);
	$newcookarr=array();
	foreach ($cookarr as $ckey=>$cval){
		foreach ($cval as $foodid=>$ccval){
			$newcookarr[$foodid]=implode(",", $ccval['checked']);
		}
	}
	//在线支付立减优惠
	$dicountfee=$confirm->getDiscountByOnline($shopid, $paymoney);
	//配送费
	$input_arr=array(
	    "prov"=>$prov,
	    "city"=>$city,
	    "dist"=>$dist,
	    "road"=>$road,
	    "shopid"=>$shopid,
	);
	$distributefee=$confirm->getDistributeFee($input_arr);
	$paymoney=$paymoney-$dicountfee+$distributefee;
	$dis=0;
	if(!empty($oneshopinfo)){
    	if(!empty($maxdistance)){
    	    //得到距离
    	    $dis=$confirm->getDIstance($input_arr);
    	}
	}
	
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
	<meta content="yes" name="apple-mobile-web-app-capable"/>
	<meta content="black-translucent" name="apple-mobile-web-app-status-bar-style"/>
	<meta content="telephone=no" name="format-detection" />
	<title>订单确认</title>
	<link rel="stylesheet" type="text/css" href="../media/css/public1.css">
	<link rel="stylesheet" type="text/css" href="../media/css/public.css">
	<link rel="stylesheet" type="text/css" href="../media/css/style.css">
	<!-- <link rel="stylesheet" type="text/css" href="../media/css/mdatetimer.css"> -->
	<link href="../media/css/common.css" rel="stylesheet" type="text/css">
	<link href="../media/css/confirm.css" rel="stylesheet" type="text/css">
</head>
<body style="background:#fff;position:relative;">
	<canvas id="canvas"  class="canvasAlert" style="margin:60% 10%;"></canvas>  
	
	<div class="prompt">订单确认</div>
<!-- 	显示的地址 -->
		<div class="main_cf">
			<div  class="bianji_all">
				<div class="contact_cf">
    				<div class="contact_db"><?php if(!empty($oneshopinfo)){echo $oneshopinfo['contact'];}?></div>
    				<div class="phone_db"><?php if(!empty($oneshopinfo)){echo $oneshopinfo['phone'];}?></div>
    			</div>
    			<div class="adr" style="width:88%;height:58%;overflow:scroll;font-size:14px;">
    				<span style="display:none;"><?php echo $prov;?></span>
    				<span ><?php echo $city;?></span>市
    				<span><?php echo $dist;?></span>
    				<span><?php if(!empty($oneshopinfo)){echo $oneshopinfo['road'];}?></span>
    			</div>
    			<div class="bianji_ad"></div>
			</div>
			<div  class="xuanze_all">
				<div class="address_cf">
					<div class="icon_cf">+</div>
					<div style="font-size:24px;display:inline-block;">添加收货地址</div>
				</div>
			</div>
		</div>
<!-- 	编辑地址 -->
		<div class="dizhi_ad">
			<div class="bg_cf" style=""></div>
	<div class="businesses" style="margin-top:20px;">
		<div class="conf_list flex_box" id="city">
			<span style="color:white;">收货地址</span>
			<div class="conf_main flex_a" id="adr">
				<font>
					<select class="prov"></select>
				</font>
				<font>
					<select class="city"></select>
				</font>
				<font>
					<select  class="dist"></select>
				</font>
			</div>
		</div>
		<div class="conf_list flex_box">
			<span></span>
			<div class="conf_main flex_a">
				<textarea placeholder="详细地址"  id="road"></textarea>
			</div>
		</div>	
		<div class="conf_list flex_box">
			<span style="color:white;">联&nbsp;&nbsp;&nbsp;系&nbsp;&nbsp;&nbsp;人</span>
			<div class="conf_main flex_a">
				<input type="text"  id="contact"  value=""/>
			</div>
		</div>	
		<div class="conf_list flex_box">
			<span style="color:white;">电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</span>
			<div class="conf_main flex_a">
				<input type="tel"  id="phone"  value=""/>
			</div>
		</div>
		<div class="btn_cf">
    		<button class="quxiao_ad">取消</button>
    		<button class="queding_cf" onclick="tijiaos()">确定</button>
		</div>
	</div>
		
		</div>
		<div class="conf_list flex_box" style="height: 10%;margin-top: 5%;">
			<span>&nbsp;备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span>
			<div class="conf_main flex_a">
				<textarea id="orderrequest" ><?php if(!empty($oneshopinfo['orderrequest'])){echo $oneshopinfo['orderrequest'];}?></textarea>
			</div>
		</div>
	<div class="foot-table" style="padding:0px 15px 50px;">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<th align="left" colspan="4" style="font-size:16px;">费用明细</th>
			</tr>
			
			 <?php 
			 foreach ($foodarr as $fkey=>$fval){
			 	foreach ($fval as $foodid=>$vval){
					if(!empty($newcookarr[$foodid])){$cooktype="(".$newcookarr[$foodid].")";}else{$cooktype="";}
			 		?>
			 		<tr>
	                <td><?php echo $vval['foodName'].$cooktype;?></td><td><?php echo $vval['foodNum'];?><?php echo $vval['foodUnit'];?></td><td>￥<?php echo $vval['foodPrice']*$vval['foodNum'];?></td>
	               </tr>
			 		<?php
			 	}
			 }
			 ?>
			 <tr><td>优惠</td><td></td><td> - ¥<?php echo $dicountfee;?></td></tr>
			 <tr><td>打包盒和配送费</td><td></td><td> ¥<?php echo $distributefee;?></td></tr>
		</table>
	</div>

	<div class="main-bottom" style="z-index: 0;">
    	<div class="d-ib v-m view-selected">
        <span class="totalPrice d-ib v-m">￥<?php echo $paymoney?></span>
	    </div>
	    <div class="juli" style="color: red;text-indent: 2rem;display:inline;opacity:0;">*超出配送范围</div>
	     <div class="payButton" id="select_pay" >支付</div>
	</div>
	
	
	<div class="wrappBox none">
		<div class="wrappAlert">
			<h1>支付方式<span class="closd" id="closd"></span></h1>
			<div class="alert-main" style="padding:0px;">
        		<div class="make">
        			<div style="width:30%;position:absolute;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/wechatpay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
        			<button value="wechatpay" id="mark">微信支付</button>
        		</div>
        		<div class="makes" style="display:none;">
        			<div class="mei1" style="width:30%;position:absolute;display:none;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/wechatpay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
        			<div class="mark mei2" style="display:none;">微信支付</div>
        		</div>
        		<div class="make">
        		<!-- 	<div style="width:30%;position:absolute;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/alipay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
        			<button value="alipay" id="marks" style="border-radius:0px 0px 5px 5px;">支付宝</button> -->
        		</div>
        		<div class="makess" style="display:none;">
        			<div class="mei3" style="width:30%;position:absolute;display:none;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/alipay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
        			<div class="marks mei4" style="border-radius:0px 0px 5px 5px;display:none;">支付宝</div>
        		</div>
			</div>
		</div>
	</div>
	
<!-- -日期 -->
<div id="datePlugin">
	<div id="dateshadow" style="display: none;"></div>
	<div id="datePage" class="page" style="display: none; height: 380px; top: 60px;">
		<section>
			<div id="datetitle">
				<h1>请选择日期</h1>
			</div>
			<div id="datemark">
				<a id="markyear"></a>
				<a id="markmonth"></a>
				<a id="markday"></a>
			</div>
			<div id="timemark">
				<a id="markhour"></a>
				<a id="markminut"></a>
				<a id="marksecond"></a>
			</div>
			<div id="datescroll">
				<div id="yearwrapper" style="overflow: hidden; position: absolute; bottom: 200px;">
					<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -640px, 0px) scale(1);">
						<li>&nbsp;</li>
						<li>2000年</li>
						<li>2001年</li>
						<li>2002年</li>
						<li>2003年</li>
						<li>2004年</li>
						<li>2005年</li>
						<li>2006年</li>
						<li>2007年</li>
						<li>2008年</li>
						<li>2009年</li>
						<li>2010年</li>
						<li>2011年</li>
						<li>2012年</li>
						<li>2013年</li>
						<li>2014年</li>
						<li>2015年</li>
						<li>2016年</li>
						<li>2017年</li>
						<li>2018年</li>
						<li>2019年</li>
						<li>2020年</li>
						<li>&nbsp;</li>
					</ul>
				</div>
				<div id="monthwrapper" style="overflow: hidden; position: absolute; bottom: 200px;">
					<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -160px, 0px) scale(1);">
						<li>&nbsp;</li>
						<li>01月</li>
						<li>02月</li>
						<li>03月</li>
						<li>04月</li>
						<li>05月</li>
						<li>06月</li>
						<li>07月</li>
						<li>08月</li>
						<li>09月</li>
						<li>10月</li>
						<li>11月</li>
						<li>12月</li>
						<li>&nbsp;</li>
					</ul>
				</div>
				<div id="daywrapper" style="overflow: hidden; position: absolute; bottom: 200px;">
					<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -80px, 0px) scale(1);"><li>&nbsp;</li>
						<li>1日</li>
						<li>2日</li>
						<li>3日</li>
						<li>4日</li>
						<li>5日</li>
						<li>6日</li>
						<li>7日</li>
						<li>8日</li>
						<li>9日</li>
						<li>10日</li>
						<li>11日</li>
						<li>12日</li>
						<li>13日</li>
						<li>14日</li>
						<li>15日</li>
						<li>16日</li>
						<li>17日</li>
						<li>18日</li>
						<li>19日</li>
						<li>20日</li>
						<li>21日</li>
						<li>22日</li>
						<li>23日</li>
						<li>24日</li>
						<li>25日</li>
						<li>26日</li>
						<li>27日</li>
						<li>28日</li>
						<li>29日</li>
						<li>30日</li>
						<li>31日</li>
						<li>&nbsp;</li>
					</ul>
				</div>
			</div>
			<div id="datescroll_datetime" style="display: block;">
				<div id="Hourwrapper" style="overflow: hidden;">
					<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -443px, 0px) scale(1);">
						<li>&nbsp;</li>
						<li>1时</li>
						<li>2时</li>
						<li>3时</li>
						<li>4时</li>
						<li>5时</li>
						<li>6时</li>
						<li>7时</li>
						<li>8时</li>
						<li>9时</li>
						<li>10时</li>
						<li>11时</li>
						<li>24</li>
						<li>&nbsp;</li>
					</ul>
				</div>
				<div id="Minutewrapper" style="overflow: hidden;">
					<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -2120px, 0px) scale(1);">
						<li>&nbsp;</li>
						<li>00分</li>
						<li>01分</li>
						<li>02分</li>
						<li>03分</li>
						<li>04分</li>
						<li>05分</li>
						<li>06分</li>
						<li>07分</li>
						<li>08分</li>
						<li>09分</li>
						<li>10分</li>
						<li>11分</li>
						<li>12分</li>
						<li>13分</li>
						<li>14分</li>
						<li>15分</li>
						<li>16分</li>
						<li>17分</li>
						<li>18分</li>
						<li>19分</li>
						<li>20分</li>
						<li>21分</li>
						<li>22分</li>
						<li>23分</li>
						<li>24分</li>
						<li>25分</li>
						<li>26分</li>
						<li>27分</li>
						<li>28分</li>
						<li>29分</li>
						<li>30分</li>
						<li>31分</li>
						<li>32分</li>
						<li>33分</li>
						<li>34分</li>
						<li>35分</li>
						<li>36分</li>
						<li>37分</li>
						<li>38分</li>
						<li>39分</li>
						<li>40分</li>
						<li>41分</li>
						<li>42分</li>
						<li>43分</li>
						<li>44分</li>
						<li>45分</li>
						<li>46分</li>
						<li>47分</li>
						<li>48分</li>
						<li>49分</li>
						<li>50分</li>
						<li>51分</li>
						<li>52分</li>
						<li>53分</li>
						<li>54分</li>
						<li>55分</li>
						<li>56分</li>
						<li>57分</li>
						<li>58分</li>
						<li>59分</li>
						<li>&nbsp;</li>
					</ul>
				</div>
				<div id="Secondwrapper" style="overflow: hidden;">
					<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -40px, 0px) scale(1);">
						<li>&nbsp;</li>
						<li>上午</li>
						<li>下午</li>
						<li>&nbsp;</li>
					</ul>
				</div>
			</div>
		</section>
		<footer id="dateFooter">
			<div id="setcancle">
				<ul>
					<li id="dateconfirm">确定</li>
					<li id="datecancle">取消</li>
				</ul>
			</div>
		</footer>
	</div>
</div>
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
	url=url+"?uid="+"<?php echo $uid;?>"
	url=url+"&platform="+platform
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null);
}

function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
 		
	}
}


function GetXmlHttpObject(){
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
	
</script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>	
<script type="text/javascript" src="../media/js/zepto.min.js"></script>
<script type="text/javascript" src="../media/js/cityselect.js"></script>
<script type="text/javascript" src="../media/js/date.js"></script>
<script type="text/javascript" src="../media/js/iscroll.js"></script>
<!-- <script type="text/javascript" src="../media/js/mdatetimer.js"></script> -->
<script type="text/javascript">
	$('.canvasAlert').hide();
	$(function(){
		$('#picktime').date({theme:"datetime"});
	});
// 限制联系人，手机号，详细地址不能为空
	var bol1 = false;
	var bol2 = false;
	var bol3 = false;
	var bol4 = false;
	function dizhic(){
		if($("#road").val()){
			$("#road").css({"border-color":""});
			bol2 = true;
		}else{
			$("#road").css({"border-color":"red"});
			bol2 = false;
			confirm("地址不能为空！");
		}
	}
	function namec(){
		if($("#contact").val()){
			$("#contact").css({"border-color":""});
			bol3 = true;
		}else{
			$("#contact").css({"border-color":"red"});
			bol3 = false;
			confirm("姓名不能为空！");
		}
	}
	function dianhuac(){
		var number = /^1[3|4|5|8|7]\d{9}$/;
		if($("#phone").val() && number.test($("#phone").val())){
		$("#phone").css({"border-color":""});
			bol4 = true;
		}else{
			$("#phone").css({"border-color":"red"});
			bol4 = false;
			confirm("您输入的电话不正确！");
		}
	}
	
	function julic(a,b){
		console.log(a,b);
		if(a==0 || a=="" | a=="0"){
			$(".juli").css({opacity:"0"});
			$(".payButton").css({backgroundColor:"#ee4743"});
			bol1 = true;
		}else{
			if(a >= b){
				$(".juli").css({opacity:"0"});
				$(".payButton").css({backgroundColor:"#ee4743"});
				bol1 = true;
			}else{
				$(".juli").css({opacity:"1"});
				$(".payButton").css({backgroundColor:"#ddd"});
				bol1 = false;
			}
		}
		
 	}
//  默认地址距离判断
	var dft = <?php echo $dis;?>;
	var maxds = <?php echo $maxdistance;?>*1000;
	console.log(dft);
	julic(maxds,dft);
	
// 	收货地址编辑
//	判断是否有默认姓名
	if($('.contact_db').html()){
		$('.bianji_all').show();
		$('.xuanze_all').hide();
	}else{
		$('.bianji_all').hide();
		$('.xuanze_all').show();
	}
//	 增加新的收货地址
	$('.xuanze_all').on('click',function(){
		$('#road').html();
		$('#contact').val();
		$('#phone').val();
		$("#city").citySelect({
	    	prov:'浙江', 
	    	city:'杭州',
			dist:'西湖区',
			nodata:"none"
		});
		$('.dizhi_ad').show();
	})
//	 编辑现有的收货地址
	$('.bianji_all').on('click',function(){
		$('#road').html($('.adr span').eq(3).html());
		$('#contact').val($('.contact_db').html());
		$('#phone').val($('.phone_db').html());
		$("#city").citySelect({
	    	prov:$('.adr span').eq(0).html(), 
	    	city:$('.adr span').eq(1).html(),
			dist:$('.adr span').eq(2).html(),
			nodata:"none"
		});
		$('.dizhi_ad').show();
	});
//	点击取消按钮
	$('.quxiao_ad').on('click',function(){
		$('.dizhi_ad').hide();
	});
//	点击确定按钮
    function tijiaos(datas){
    	dizhic();
    	if(!bol2){
			return false;
        }
    	namec();
    	if(!bol3){
			return false;
        }
    	dianhuac();
    	
		provs=$('.prov').val();
    	citys=$('.city').val();
    	dists=$('.dist').val();
    	roads=$('#road').val();
    	contacts=$('#contact').val();
    	phones=$('#phone').val();

    	datas={
	    	'shopid':'<?php echo $shopid;?>',
			'uid':'<?php echo $uid;?>',
	    	'prov':provs,
	    	'city':citys,
	    	'dist':dists,
	    	'road':roads,
	    	'contact':contacts,
	    	'phone':phones
    	};
    	console.log(datas);
    	if(bol2 && bol3 && bol4){
    		$('.adr span').eq(0).html(provs);
        	$('.adr span').eq(1).html(citys);
        	$('.adr span').eq(2).html(dists);
        	$('.adr span').eq(3).html(roads);
        	$('.contact_db').html(contacts);
        	$('.phone_db').html(phones);   	
            $.ajax({ 
                type: 'POST', 
                url: '<?php echo ROOTURL?>printbill/interface/postbuyerinfo.php', //点击确定按钮后，数据提交的地址
                dataType: 'json', 
                data:datas,
                success:function(datas){
					var maxd = <?php echo $maxdistance;?>*1000;
                	var dis = datas.dis;
            		$('.bianji_all').show();
            		$('.xuanze_all').hide();
//             		select();
            		$(".juli").html("*超出配送范围");
                 	julic(maxd,dis);
            		
                },
    			error:function(){
    				alert("系统繁忙，请稍后！");
    			}
            });
            $('.dizhi_ad').hide();
//             window.location.reload(); //页面重新加载
    	}
    }

/*
	$('#picktime').mdatetimer({
		mode : 3, //时间选择器模式：1：年月日，2：年月日时分（24小时），3：年月日时分（12小时），4：年月日时分秒。默认：1
		format : 2, //时间格式化方式：1：2015年06月10日 17时30分46秒，2：2015-05-10  17:30:46。默认：2
		years : [2000, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017], //年份数组
		nowbtn : true, //是否显示现在按钮
		onOk : function(){
			//alert('OK');
		},  //点击确定时添加额外的执行函数 默认null
		onCancel : function(){
			
		}, //点击取消时添加额外的执行函数 默认null
	});	
*/	
// 	判断是否已经选好地址
	var bol = false;
	function select(){
		if($('.contact_db').html()){
 			bol = true;
// 			$(".juli").html("*超出配送范围");
// 			$(".juli").css({opacity:"0"});
// 			$(".payButton").css({backgroundColor:"#ee4743"});
		}else{
			$(".juli").html("*请选择地址*");
			$(".juli").css({opacity:"1"});
			$(".payButton").css({backgroundColor:"#ddd"});
			bol = false;
		}

	}
	select();
//  点击支付
	$("#select_pay").on("click",function(){
		select();
		if(bol){
			if(bol1){
				$(".wrappBox").css("display","block");
			}
		}
	 });

	$('#closd').on('click',function(){
        $('.wrappBox').hide();
		$('#select_pay').show();
    })
// 	微信支付
	$('#mark').on('click',function(){
    	$(this).parent().hide();
    	$('.makes').show();
    	$('.mei1').show();
    	$('.mei2').show();
		payid=$('#mark').val();
		doPay(payid);
     })
//  支付宝支付
    $('#marks').on('click',function(){
      	$(this).parent().hide();
    	$('.makess').show();
    	$('.mei3').show();
    	$('.mei4').show();
		payid=$('#marks').val();
		doPay(payid);
     })
// 支付函数
	function doPay(payid){
		prov=$('.adr span').eq(0).html();
    	city=$('.adr span').eq(1).html();
    	dist=$('.adr span').eq(2).html();
    	road=$('.adr span').eq(3).html();
    	shopname="";
    	contact=$('.contact_db').html();
    	phone=$('.phone_db').html();
    	picktime="";
    	orderrequest=$('#orderrequest').val();

    	maxdistance=<?php echo $maxdistance;?>;
    	data=
			{
			'openid':'<?php echo $openid;?>',
			'food':'<?php echo $food?>',
			'cook':'<?php echo $cook;?>',
			'uid':'<?php echo $uid;?>',
			'shopid':'<?php echo $shopid;?>',
			'tabid':'<?php echo $tabid;?>',
			'prov':prov,
			'city':city,
			'dist':dist,
			'road':road,
			'shopname':shopname,
			'contact':contact,
			'phone':phone,
			'picktime':picktime,
			'orderrequest':orderrequest,
			'dicountfee':'<?php echo $dicountfee;?>',
			'distributefee':'<?php echo $distributefee;?>'
			};
    	 console.log(data);
    	$('.canvasAlert').show();

       	 var loadingObj = new loading(document.getElementById('canvas'),{radius:8,circleLineWidth:3});

         loadingObj.show(); 

    	 $.ajax({
			url:"<?php echo ROOTURL?>printbill/interface/pretakeoutdownbill.php",
			type:"POST",
			data:data,
			success:function(data){
				var data = JSON.parse(data); 
				//payid=$('input[name="radio"]:checked').val();
	      		PARAMS={
      				'openid':'<?php echo $openid;?>',
      				'orderno':data.orderno,
      				'tabid':data.tabid,
      				'billid':data.billid,
      				'uid':data.uid,
      				'shopid':data.shopid,
      				'paymoney':data.paymoney,
      				'prov':prov,
      				'city':city,
      				'dist':dist,
      				'road':road,
      				'shopname':shopname,
      				'contact':contact,
      				'phone':phone,
      				'picktime':picktime,
      				'orderrequest':data.orderrequest,
      				'dicountfee':'<?php echo $dicountfee;?>',
      				'distributefee':'<?php echo $distributefee;?>'
	      		}
	      		if(payid=="alipay"){
	      			post('<?php echo ROOTURL;?>phwappay/alipayapi.php', PARAMS);
	      		}else if(payid=="wechatpay"){
// 	  		      	console.log(data.jsApiParameters)
  		      		callpay(data.jsApiParameters);
	      		}else if(payid=="directpay"){
	      			if(confirm('确定要下单？')){
	      				post('./directpay.php',PARAMS);
	      			}
	      		}
	  		    $('.canvasAlert').hide();
				//回调完成，支付按钮在2秒之后重新可以被点击
 	  		    setTimeout(function(){
	 		    		$('#mark').parent().show();
	 		        	$('.makes').hide();
	 		        	$('.mei1').hide();
	 		        	$('.mei2').hide();
	 		        	$('#marks').parent().show();
	 		        	$('.makess').hide();
	 		        	$('.mei3').hide();
	 		        	$('.mei4').hide();
	 	 	 	  	},2000);
			},
			error:function(){
				alert("请求过于频繁，请稍后再试~");
			}
		});
	}
	
	function post(URL, PARAMS) {      
	    var temp = document.createElement("form");      
	    temp.action = URL;      
	    temp.method = "get";      
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
	
	function jsApiCall(jsApiParameters){
	    WeixinJSBridge.invoke(
	        'getBrandWCPayRequest',
			jsApiParameters,
			function(res){
			    WeixinJSBridge.log(res.err_msg);
			   // alert(res.err_code+"="+res.err_desc+"="+res.err_msg);
			    if(res.err_msg=="get_brand_wcpay_request:cancel"){
			    }else if(res.err_msg=="get_brand_wcpay_request:ok"){
			    	window.location.href='./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&paystatus=paid';
			    }
			}
		);
	}

	function callpay(jsApiParameters){
	    if (typeof WeixinJSBridge == "undefined"){
	        if( document.addEventListener ){
	            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
	        }else if (document.attachEvent){
	            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
	            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
	        }
	    }else{
	        jsApiCall(jsApiParameters);
	    }
	}
</script>
<script type="text/javascript">  
    function loading(canvas,options){   
      this.canvas = canvas;   
      if(options){   
        this.radius = options.radius||12;   
        this.circleLineWidth = options.circleLineWidth||4;   
        this.circleColor = options.circleColor||'lightgray';   
        this.dotColor = options.dotColor||'gray';   
      }else{
        this.radius = 12;   
        this.circelLineWidth = 4;   
        this.circleColor = 'lightgray';   
        this.dotColor = 'gray';   
      }   
    }   
    loading.prototype = {   
	    show:function (){   
	        var canvas = this.canvas;   
	        if(!canvas.getContext)return;   
	        if(canvas.__loading)return;   
	        canvas.__loading = this;   
	        var ctx = canvas.getContext('2d');   
	        var radius = this.radius;         
	        var rotators = [{angle:0,radius:1.5},{angle:3/radius,radius:2},{angle:7/radius,radius:2.5},{angle:12/radius,radius:3}];         
	        var me = this;   
	        canvas.loadingInterval = setInterval(function(){   
				ctx.clearRect(0,0,canvas.width,canvas.height);            
				var lineWidth = me.circleLineWidth;   
				var center = {x:canvas.width/2 - radius,y:canvas.height/2-radius};        
				ctx.beginPath();   
				ctx.lineWidth = lineWidth;   
				ctx.strokeStyle = me.circleColor;   
				ctx.arc(center.x,center.y,radius,0,Math.PI*2);   
				ctx.closePath();   
				ctx.stroke();   
		      	for(var i=0;i<rotators.length;i++){           
			        var rotatorAngle = rotators[i].currentAngle||rotators[i].angle;           
			            //在圆圈上面画小圆   
			   		var rotatorCenter = {x:center.x-(radius)*Math.cos(rotatorAngle) ,y:center.y-(radius)*Math.sin(rotatorAngle)};               
			        var rotatorRadius = rotators[i].radius;   
			        ctx.beginPath();   
			        ctx.fillStyle = me.dotColor;   
			        ctx.arc(rotatorCenter.x,rotatorCenter.y,rotatorRadius,0,Math.PI*2);   
			        ctx.closePath();   
			        ctx.fill();   
			        rotators[i].currentAngle = rotatorAngle+4/radius;   
			    }   
	        },50);   
	    },   
	    hide:function(){   
	        var canvas = this.canvas;   
	        canvas.__loading = false;   
	        if(canvas.loadingInterval){   
	          window.clearInterval(canvas.loadingInterval);   
	        }   
	        var ctx = canvas.getContext('2d');   
	        if(ctx)ctx.clearRect(0,0,canvas.width,canvas.height);   
	    }   
	};   
 </script> 
</body>
</html>
