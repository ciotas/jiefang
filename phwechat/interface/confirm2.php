<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/Factory/BLLFactory.php');
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
	
	$oneshopinfo=$confirm->getOneShop_infoData($shopid,$uid);
// 	print_r($oneshopinfo);
	if(!empty($oneshopinfo['prov'])){$prov=$oneshopinfo['prov'];}else{$prov="浙江";}
	if(!empty($oneshopinfo['city'])){$city=$oneshopinfo['city'];}else{$city="杭州";}
	if(!empty($oneshopinfo['dist'])){$dist=$oneshopinfo['dist'];}else{$dist="西湖区";}
	
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
// 	print_r($newcookarr);exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
<link rel="stylesheet" type="text/css" href="../media/css/public1.css">
<link rel="stylesheet" type="text/css" href="../media/css/public.css">
<link rel="stylesheet" type="text/css" href="../media/css/style.css">

<link href="../media/css/common.css" rel="stylesheet" type="text/css">

<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black-translucent" name="apple-mobile-web-app-status-bar-style"/>
<title>订单确认-员工自助</title>
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
	
</script>

</head>
<body style="background:#fff;">
	
	<canvas id="canvas"  class="canvasAlert" style="margin:60%10%;"></canvas>  
	
	<div class="prompt">员工自助</div>
	<div class="businesses  flex_a ">
		<div class="conf_list flex_box">
			<span>配&nbsp;送&nbsp;方&nbsp;式：</span>
			<div class="conf_main flex_a">
				<em><input type="radio" name="play" value="1" checked/><label for="play_k">出库单</label></em>
				<em><input type="radio" name="play" value="0" /><label for="play_s">送货单</label></em>
			</div>
		</div>	

		<div class="conf_list flex_box">
			
			<span>配&nbsp;送&nbsp;方&nbsp;式：</span>
			<div class="conf_main flex_a">
				<em><input type="radio" name="distribution" id="distribution_k" value="1" checked/><label for="distribution_k">配送</label></em>
				<em><input type="radio" name="distribution" id="distribution_z"  value="0" /><label for="distribution_z">自提</label></em>
			</div>
		</div>	
		

		<div class="conf_list flex_box">
			<span>下&nbsp;&nbsp;&nbsp;单&nbsp;&nbsp;&nbsp;人：</span>
			<div class="conf_main flex_a">
				<input type="text"  id="author" value="<?php if(!empty($oneshopinfo)){echo $oneshopinfo['author'];}?>"/>
			</div>
		</div>	

		<div class="conf_list flex_box">
			<span>车&nbsp;&nbsp;&nbsp;牌&nbsp;&nbsp;&nbsp;号：</span>
			<div class="conf_main flex_a">
				<input type="text" id="carno" value="<?php if(!empty($oneshopinfo)){echo $oneshopinfo['carno'];}?>"/>
			</div>
		</div>			

		<div class="conf_list flex_box">
			<span>提&nbsp;送&nbsp;时&nbsp;间：</span>
			<div class="conf_main flex_a">
				<input type="text" id="tipicktime" value="<?php echo date("Y-m-d",time()+2*3600);?>" readonly />
			</div>
		</div>	


		<div class="conf_list flex_box">
			<span>商&nbsp;户&nbsp;名&nbsp;称：</span>
			<div class="conf_main flex_a">
				<input type="text"  id="shopname" value="<?php if(!empty($oneshopinfo['shopname'])){echo $oneshopinfo['shopname'];}?>"/>
			</div>
		</div>	

		

		<div class="conf_list flex_box">
			<span>联&nbsp;&nbsp;&nbsp;系&nbsp;&nbsp;&nbsp;人：</span>
			<div class="conf_main flex_a">
				<input type="text" id="contact" value="<?php if(!empty($oneshopinfo['contact'])){echo $oneshopinfo['contact'];}?>"/>
			</div>
		</div>

		<div class="conf_list flex_box">
			<span>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话：</span>
			<div class="conf_main flex_a">
				<input type="tel" id="phone" value="<?php if(!empty($oneshopinfo)){echo $oneshopinfo['phone'];}?>"/>
			</div>
		</div>	
		
		<div class="conf_list flex_box">
			<span>地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址：</span>
			<div class="conf_main flex_a"  id="city">
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
				<textarea placeholder="详细地址"  id="road"><?php if(!empty($oneshopinfo)){echo $oneshopinfo['road'];}?></textarea>
			</div>
		</div>

		<div class="conf_list flex_box">
			<span>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</span>
			<div class="conf_main flex_a">
				<textarea id="orderrequest"><?php if(!empty($oneshopinfo)){echo $oneshopinfo['orderrequest'];}?></textarea>
			</div>
		</div>	

		
	</div>
	<div class="foot-table">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<th width="50%" align="left">商品</th><th width="20%">单价</th><th width="15%">数量</th><th width="15%">总价</th>
			</tr>
			
			 <?php 
			 foreach ($foodarr as $fkey=>$fval){
			 	foreach ($fval as $foodid=>$vval){
					if(!empty($newcookarr[$foodid])){$cooktype="(".$newcookarr[$foodid].")";}else{$cooktype="";}
			 		?>
			 		<tr>
	                <td><?php echo $vval['foodName'].$cooktype;?></td><td>￥<?php echo $vval['foodPrice'];?></td><td><?php echo $vval['foodNum'];?><?php echo $vval['foodUnit'];?></td><td>￥<?php echo $vval['foodPrice']*$vval['foodNum'];?></td>
	               </tr>
			 		<?php
			 	}
			 }
			 ?>
		</table>
	</div>
<br><br><br>
	<div class="main-bottom">
    	<div class="d-ib v-m view-selected">
        <span class="totalPrice d-ib v-m">￥<?php echo $paymoney?></span>
	    </div>
	     <div class="payButton" id="select_pay" >支付</div>
	</div>
	
	
	<div class="wrappBox none">
	<div class="wrappAlert">
	<h1>支付方式<span class="closd" id="closd"></span></h1>
	<div class="alert-main">
	<ul class="clearfix">
	<li><span><input type="radio" name="radio" id="wechatpay" value="wechatpay" checked /><label for="wechatpay">微信</label></span></li>
	<li><span>
	<!-- <input type="radio" name="radio" id="directpay" value="directpay" /><label for="alipay">到付</label> -->
	</span></li>
	<li><span><input type="radio" name="radio" id="alipay" value="alipay" /><label for="alipay">支付宝</label></span></li>
	</ul>
	<div class="make">
	<a href="javascript:;" id="mark">确定</a></div>
	</div></div></div></div>
<!-- -日期 -->
<div id="datePlugin"><div id="dateshadow" style="display: none;"></div>
<div id="datePage" class="page" style="display: none; height: 380px; top: 60px;">
<section><div id="datetitle">
<h1>请选择日期</h1></div><div id="datemark"><a id="markyear"></a>
<a id="markmonth"></a><a id="markday"></a></div><div id="timemark">
<a id="markhour"></a><a id="markminut"></a><a id="marksecond"></a>
</div><div id="datescroll">
<div id="yearwrapper" style="overflow: hidden; position: absolute; bottom: 200px;">
<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -640px, 0px) scale(1);">
<li>&nbsp;</li><li>2000年</li><li>2001年</li><li>2002年</li><li>2003年</li><li>2004年</li><li>2005年</li><li>2006年</li><li>2007年</li><li>2008年</li><li>2009年</li><li>2010年</li><li>2011年</li><li>2012年</li><li>2013年</li><li>2014年</li><li>2015年</li><li>2016年</li><li>2017年</li><li>2018年</li><li>2019年</li><li>2020年</li><li>&nbsp;</li></ul></div>
<div id="monthwrapper" style="overflow: hidden; position: absolute; bottom: 200px;"><ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -160px, 0px) scale(1);"><li>&nbsp;</li><li>01月</li><li>02月</li><li>03月</li><li>04月</li><li>05月</li><li>06月</li><li>07月</li><li>08月</li><li>09月</li><li>10月</li><li>11月</li><li>12月</li><li>&nbsp;</li></ul></div>
<div id="daywrapper" style="overflow: hidden; position: absolute; bottom: 200px;">
<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -80px, 0px) scale(1);"><li>&nbsp;</li>
<li>1日</li><li>2日</li><li>3日</li><li>4日</li><li>5日</li><li>6日</li><li>7日</li><li>8日</li><li>9日</li><li>10日</li><li>11日</li><li>12日</li><li>13日</li><li>14日</li><li>15日</li><li>16日</li><li>17日</li><li>18日</li><li>19日</li><li>20日</li><li>21日</li><li>22日</li><li>23日</li><li>24日</li><li>25日</li><li>26日</li><li>27日</li><li>28日</li><li>29日</li><li>30日</li><li>31日</li>
<li>&nbsp;</li></ul></div></div><div id="datescroll_datetime" style="display: block;"><div id="Hourwrapper" style="overflow: hidden;"><ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -443px, 0px) scale(1);"><li>&nbsp;</li><li>1时</li><li>2时</li><li>3时</li><li>4时</li><li>5时</li><li>6时</li><li>7时</li><li>8时</li><li>9时</li><li>10时</li><li>11时</li><li>24</li><li>&nbsp;</li></ul></div>
<div id="Minutewrapper" style="overflow: hidden;"><ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -2120px, 0px) scale(1);"><li>&nbsp;</li><li>00分</li><li>01分</li><li>02分</li><li>03分</li><li>04分</li><li>05分</li><li>06分</li><li>07分</li><li>08分</li><li>09分</li><li>10分</li><li>11分</li><li>12分</li><li>13分</li><li>14分</li><li>15分</li><li>16分</li><li>17分</li><li>18分</li><li>19分</li><li>20分</li><li>21分</li><li>22分</li><li>23分</li><li>24分</li><li>25分</li><li>26分</li><li>27分</li><li>28分</li><li>29分</li><li>30分</li><li>31分</li><li>32分</li><li>33分</li><li>34分</li><li>35分</li><li>36分</li><li>37分</li><li>38分</li><li>39分</li><li>40分</li><li>41分</li><li>42分</li><li>43分</li><li>44分</li><li>45分</li><li>46分</li><li>47分</li><li>48分</li><li>49分</li><li>50分</li><li>51分</li><li>52分</li><li>53分</li><li>54分</li><li>55分</li><li>56分</li><li>57分</li><li>58分</li><li>59分</li><li>&nbsp;</li></ul></div><div id="Secondwrapper" style="overflow: hidden;">
<ul style="transition-property: -webkit-transform; transform-origin: 0px 0px 0px; transform: translate3d(0px, -40px, 0px) scale(1);"><li>&nbsp;</li><li>上午</li><li>下午</li><li>&nbsp;</li></ul></div></div></section>
<footer id="dateFooter"><div id="setcancle"><ul><li id="dateconfirm">确定</li><li id="datecancle">取消</li></ul></div></footer>
</div></div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>	
<script type="text/javascript" src="../media/js/zepto.min.js"></script>
<script type="text/javascript" src="../media/js/cityselect.js"></script>
<script type="text/javascript" src="../media/js/date.js"></script>
<script type="text/javascript" src="../media/js/iscroll.js"></script>
<!-- <script type="text/javascript" src="../media/js/mdatetimer.js"></script> -->

<script type="text/javascript">
	$('.canvasAlert').hide();
	$(function(){
		$('#tipicktime').date({theme:"datetime"});
	});

	$(function(){
		
		$("#city").citySelect({
	    	prov:'<?php echo $prov;?>', 
	    	city:'<?php echo $city;?>',
			dist:'<?php echo $dist;?>',
			nodata:"none"

		}); 
	});
	
	 //点击支付
	$("#select_pay").on("click",function(){
		$(".wrappBox").css("display","block");
	 });

	$('#closd').on('click',function(){
        $('.wrappBox').hide();
		$('#select_pay').show();
    })

	$('#mark').on('click',function(){
		var porttype = $('input[name="play"]:checked ').val();
		var distribution = $('input[name="distribution"]:checked ').val();
		prov=$('.prov').val();
    	city=$('.city').val();
    	dist=$('.dist').val();
    	road=$('#road').val();
    	author=$('#author').val();
		carno=$('#carno').val();
		picktime=$('#tipicktime').val();
		shopname=$('#shopname').val();
		contact=$('#contact').val();
		phone=$('#phone').val();
    	orderrequest=$('#orderrequest').val();

    	data=
			{
			'openid':'<?php echo $openid;?>',
			'food':'<?php echo $food?>',
			'cook':'<?php echo $cook;?>',
			'uid':'<?php echo $uid;?>',
			'shopid':'<?php echo $shopid;?>',
			'tabid':'<?php echo $tabid;?>',
			'distribution':distribution,
			'porttype':porttype,
			'prov':prov,
			'city':city,
			'dist':dist,
			'road':road,
			'carno':carno,
			'author':author,
			'shopname':shopname,
			'contact':contact,
			'phone':phone,
			'picktime':picktime,
			'orderrequest':orderrequest,
			};
//     	 console.log(data);
    	$('.canvasAlert').show();
       	 var loadingObj = new loading(document.getElementById('canvas'),{radius:8,circleLineWidth:3});   
         loadingObj.show();   
    	 $.ajax({
				url:"<?php echo ROOTURL?>printbill/interface/preph_inner_downbill.php",
				type:"POST",
				data:data,
				success:function(data){
					var data = JSON.parse(data); 
// 					console.log(data);
					payid=$('input[name="radio"]:checked').val();

		      		PARAMS={
		      				'openid':'<?php echo $openid;?>',
		      				'orderno':data.orderno,
		      				'tabid':data.tabid,
		      				'billid':data.billid,
		      				'uid':data.uid,
		      				'shopid':data.shopid,
		      				'paymoney':data.paymoney,
		      				'distribution':distribution,
		      				'porttype':porttype,
		      				'prov':prov,
		      				'city':city,
		      				'dist':dist,
		      				'road':road,
		      				'carno':carno,
		      				'author':author,
		      				'shopname':shopname,
		      				'contact':contact,
		      				'phone':phone,
		      				'picktime':picktime,
		      				'orderrequest':orderrequest,
		      		}
//	  		    console.log(PARAMS);
		      		if(payid=="alipay"){
		      			post('<?php echo ROOTURL;?>phinnerwappay/alipayapi.php', PARAMS);
		      		}else if(payid=="wechatpay"){
// 	  		      		console.log(data.jsApiParameters)
	  		      		callpay(data.jsApiParameters);
		      		}else if(payid=="directpay"){
		      			if(confirm('确定要下单？')){
		      				post('./directpay.php',PARAMS);
		      			}
		      		}
	  		      	$('.canvasAlert').hide();
				},
				error:function(){
					alert("服务正在维护中，请稍后再试~");
				}
			});
     })
    
	
	
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
	
		function jsApiCall(jsApiParameters)
		{
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

		function callpay(jsApiParameters)
		{
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
