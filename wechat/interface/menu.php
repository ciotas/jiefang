<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
class Menu{
	public function getShopinfo($shopid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getShopinfo($shopid);
	}
	public function getWechatUserinfo($uid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getWechatUserinfo($uid);
	}
	public function payHandle($openid = '', $orderno = '', $orderfee = '', $attach = ''){
		return Wechat_BLLFactory::createInstanceWxpayBLL()->jsApiCall($openid, $orderno, $orderfee, $attach);
	}
	public function getByCusnum($shopid){
	    return Wechat_BLLFactory::createInstanceWechatBLL()->getByCusnum($shopid);
	}
}
$menu=new Menu();
$paystatus="unpay";
if(isset($_REQUEST['shopid'])){
	$shopid=$_REQUEST['shopid'];
	$uid=$_REQUEST['uid'];
	$tabid=$_REQUEST['tabid'];
	$paystatus=$_REQUEST['paystatus'];
	$shopinfoarr=$menu->getShopinfo($shopid);
	$bycusnum=$menu->getByCusnum($shopid);
	$wechatinfo=$menu->getWechatUserinfo($uid);
	$openid="";
	if(!empty($wechatinfo)){
		$openid=$wechatinfo['openid'];
	}
}
// $uid="5750151a1a156fa87d8b456e";
// $wechatinfo=$menu->getWechatUserinfo($uid);
// print_r($wechatinfo);
// exit;
// $shopid="554ad9615bc109d8518b45d2";
// $tabid="554addb25bc109dd518b45c1";
?>
<!DOCTYPE html>
<html>
<head>
    
 <meta charset="utf-8"/>
 <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
 <link rel="stylesheet" type="text/css" href="../media/css/public.css">
 <meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black-translucent" name="apple-mobile-web-app-status-bar-style"/>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title><?php if(!empty($shopinfoarr)){echo $shopinfoarr['shopname'];}?></title>

</head>           

<body>
<div class="header"><?php if(!empty($shopinfoarr)){echo $shopinfoarr['shopname'];}?></div>
<canvas id="canvas"  class="canvasAlert" style="margin:60%10%;"></canvas>  
<div class="main">
	<div class="eat-title">
		<ul></ul>
	</div>
	<div class="eat-count">
<!-- 		<div class="count-top"></div> -->
		<div class="items" >
			<div class="wrapper"></div>
		</div>
	</div>	
</div>

<div class="fixnav" id="fixnav">
     <div class="fixnav_add" id="fixnav_add"></div>
</div>

<div class="cartMask none"></div> 
    <div class="J_cart none">
        <h3>购物车<i>◆</i></h3>

        <div class="J_cartinfo">
            <div class="food-info">
				<textarea name="orderrequest" id="orderrequest" placeholder="在此输入备注" rows="2" style="border:1px solid grey;width:100%;font-size:16px;"></textarea>
                <ul></ul>
            </div>
        </div>
    </div>
   

<div class="main-bottom none">
    <div class="d-ib v-m view-selected">
        <div class="cart d-ib v-m on">
            <span class="total">0</span>
        </div>
        <span class="totalPrice d-ib v-m">￥0</span>
    </div>
    <div class="payButton" id="select_ok">选好了</div>
    <div class="payButton" id="select_pay" >支付</div>
</div>

<input type="hidden" id="bycusnum" value="<?php echo $bycusnum;?>">

<div class="wrappBox none">
	<div class="wrappAlert">
		<h1>支付方式<span class="closd" id="closd"></span></h1>
		<div class="alert-main" style="padding:0px;">
    		<div id="renshu" style="padding: 2px 2px;border-top:1px solid #D5D5D5;">
    			<div style="width:50%;font-size:20px;text-align:center;float:left;padding-top:6px;display:inline-block;">人数</div>                		
        		<div style="height:30px;border:1px solid #c6c0ba; border-radius:20px;display:inline-block;margin-top:2px;">
        			<span class="jian" style="width:30px;position: relative;display: inline-block; height: 30px; line-height:25px;float: left;cursor: pointer;font-size:38px;text-align:center;color:#93928f;">-</span>
        			<span class="renshu" id="cusnum" style="display: inline-block; height: 30px; line-height:25px;float: left; width: 40px;border-left:1px solid #c6c0ba; border-right:1px solid #c6c0ba; text-align: center;padding-top:2px;">0</span>
        			<span class="jia" style="width:30px;position: relative;display: inline-block; height: 30px; line-height:32px;float: left;cursor: pointer;font-size:22px;text-align:center;color:#93928f;font-weight:bold;">+</span>
        		</div>
    		</div>
    		<div class="make">
    			<div style="width:30%;position:absolute;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/wechatpay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
    			<button value="wechatpay" id="mark">微信支付</button>
    		</div>
    		<div class="makes" style="display:none;">
    			<div class="mei1" style="width:30%;position:absolute;display:none;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/wechatpay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
    			<div class="mark mei2" style="display:none;">微信支付</div>
    		</div>
    		<div class="make">
    			<div style="width:30%;position:absolute;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/alipay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
    			<button value="alipay" id="marks" style="border-radius:0px 0px 5px 5px;">支付宝</button>
    		</div>
    		<div class="makess" style="display:none;">
    			<div class="mei3" style="width:30%;position:absolute;display:none;"><img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/wechat/alipay.png" width="26px" height="26px" style="float:right;margin-top:8px;"></div>
    			<div class="marks mei4" style="border-radius:0px 0px 5px 5px;display:none;">支付宝</div>
    		</div>
		</div>
	</div>
</div>

<!-- 
	口味选择弹出框 
-->
<div class="dis-alert" id="disAlert">
    <div class="dis-alert-wrapp">
        <div class="dis-top">
            <div class="dis-alert-wrapper" id="disAlertWrapper">
            </div>
        </div>
        <div class="dis-alert-bottom">
            <a href="javascript:;" class="no" id="no">不要了</a> 
            <a href="javascript:;" class="selected" id="selected">选好了</a> 
        </div>
    </div>
</div> 

<section class="imgzoom_pack">
    <div class="img_table">
	    <div class="imgzoom_img">
			<div class="imgpadding ">
	    		<div class="imgzoom_x">╳</div>
	        	<img  src="../media/images/pic_01.jpg"/>
	        	<p id="foodintro"></p>
	    	</div>
	    </div>
	</div>
</section>

</body>
</html>

<script type="text/javascript" src="../media/js/zepto.min.js"></script>
<script type="text/javascript" src="../media/js/common.js"></script>

<!-- <script type="text/javascript" src="../media/js/main.js"></script> -->
<script type="text/javascript" src="../media/foodjs/<?php echo $shopid?>.js"></script>
<script type="text/javascript" src="../media/foodjs/cook_<?php echo $shopid?>.js"></script>
<script type="text/javascript" src="../media/js/assign.js"></script>

<script type="text/javascript">
<?php if($paystatus=="paid"){?>
    sessionStorage.removeItem("key"); 
    sessionStorage.removeItem("number"); 
    sessionStorage.removeItem("dis");
//     sessionStorage.setItem("key",""); 
//     sessionStorage.setItem("number",""); 
//     sessionStorage.setItem("dis","");
    $(".totalPrice").text('');
    $(".cartMask").hide();
    $(".main-bottom").hide();
<?php }?>

$('.canvasAlert').hide();
	(function () {
		$('#select_ok').show();
		$('#select_pay').hide();
		
		var $foodCategory=$(".wrapper .lists");
		var max =$foodCategory.length - 1;

        var $foodCategroyLast = $foodCategory.eq(max);

        var height = $(".eat-count").height();

        var lastHeight = $foodCategroyLast.height();

        var pb = height - lastHeight;
        
        var scrollMenu =Utils.throttle(function () {
        	var sTop = $(".items").scrollTop();
          		for(var i=0;i<$foodCategory.length;i++){
				var $category = $foodCategory.eq(i);
                if (!$category || !$category.position()){
                	break;	
                } 
                var pTop = $category.position().top;
              	var nTop = i < max ? $category.next().position().top : $(".wrapper").height();
              	if (sTop >= pTop && sTop < nTop) {
                	$(".count-top").text($category.find(".items-top").text());
                    var $activeMemuCategory = $(".eat-title li").eq(i).addClass("active");
                    $activeMemuCategory.siblings().removeClass("active");
                	var activeMemuCategory = $activeMemuCategory[0];
                    activeMemuCategory[activeMemuCategory.scrollIntoViewIfNeeded ? "scrollIntoViewIfNeeded" : "scrollIntoView"]();
                    break
                }
            }    
        },50);

        $(".eat-title").on("click",".menu-left-list",function(e){
        	var $me = $(this).addClass("active");
			$me.siblings().removeClass("active");
			$foodCategory.eq($me.index())[0].scrollIntoView(true)
		});
         
        $(".items").on('scroll', scrollMenu);
		var shoopNumber;  //总共数量
		var  foodList;
        if(sessionStorage.getItem("key")){
			foodList=$.parseJSON(sessionStorage.getItem("key"))//;
			shoopNumber=Number(sessionStorage.getItem("number"));
			for(var i=0;i<foodList.length;i++){
				for(key in foodList[i]){
					var foodId=foodList[i][key].foodId;
					$('li[data-type="'+foodId+'"]').find('.totle').hide();
					$('li[data-type="'+foodId+'"]').find('.add').show();
					$('li[data-type="'+foodId+'"]').find(".number").text(foodList[i][key].foodNum);

					var moudeIndex=$('.wrapper li[data-type="'+foodId+'"]').parents(".lists").index();	
// 					console.log(moudeIndex);
					var $mengSpan=$(".eat-title ul li").eq(moudeIndex).find("span");
					$mengSpan.text(parseInt($mengSpan.text())+Number(foodList[i][key].foodNum));
					if($mengSpan.text()>0){
						$mengSpan.show();
					}else{
						$mengSpan.hide();
					}
				}
			}
			totle(shoopNumber,$.parseJSON(sessionStorage.getItem("key")));
		}else{
			foodList=[];
			shoopNumber=0;
		}
        $(".totle").click(function(){
			var obj={};
			var json={};
			var foodId=$(this).parents("li").data("type");  //美食id
			var foodName=$(this).parents("li").find(".name").text();  ///美食名称
			var $value=parseInt($(this).hide().next().show().find(".number").text())+1;  //美食数量
			var foodPrice=$(this).parents("li").find(".pricers em").text();  //获取价格
			var foodUnit=$(this).parents("li").find("i").text();   //美食的单位
			$(this).next().find(".number").text($value);
			json.foodId=foodId;
			json.foodName=foodName;
			json.foodNum=$value;
			json.foodPrice=foodPrice;
			json.foodUnit=foodUnit;
			obj[foodId]=json;
			foodList.push(obj);
			var moudeIndex=$(this).parents(".lists").index();  //获得当前模块的索引值
			var $mengSpan=$(".eat-title ul li").eq(moudeIndex).find("span");
			$mengSpan.text(parseInt($mengSpan.text())+1);
			if($mengSpan.text()>0){
				$mengSpan.show();
			}else{
				$mengSpan.hide();
			}
			
			shoopNumber=shoopNumber+1;  //总数量
			
			totle(shoopNumber,foodList);

		});
        /*加上*/
		$(".plus").live("click",function(e){

			var foodId=$(this).parents("li").data("type");  //美食id
			var foodName=$(this).parents("li").find(".name").text();  //美食名称
			var $value=parseInt($(this).prev().text())+1; //美食数量
			var foodPrice=$(this).parents("li").find(".pricers em").text(); //美食价格
			var foodUnit=$(this).parents("li").find("i").text();   //美食的单位

			var moudeIndex=$('.wrapper li[data-type="'+foodId+'"]').parents(".lists").index();
			
			var $mengSpan=$(".eat-title ul li").eq(moudeIndex).find("span");
			$mengSpan.text(parseInt($mengSpan.text())+1);
			
			for(var i=0;i<foodList.length;i++){
				for(var key in foodList[i]){
					if(key==foodId){
						foodList[i][key].foodNum=$value;
					}
				}
			}
			$('li[data-type="'+foodId+'"]').find(".number").text($value);

			shoopNumber=shoopNumber+1;  //总数量

			totle(shoopNumber,foodList);
			e.stopPropagation();
		});
			/*减去*/
		$(".food-minus").live("click",function(e){
			var foodId=$(this).parents("li").data("type");  //美食id
			var foodName=$(this).parents("li").find(".name").text();  ///美食名称

			var $value=parseInt($(this).next().text())-1;  //美食数量
			
			var foodPrice=$(this).parents("li").find(".pricers em").text();     //美食价格
			var foodUnit=$(this).parents("li").find("i").text();   //美食的单位
			var moudeIndex=$('.wrapper li[data-type="'+foodId+'"]').parents(".lists").index();
// 			console.log(moudeIndex);
			var $mengSpan=$(".eat-title ul li").eq(moudeIndex).find("span")

			

			var moudeValue=parseInt($mengSpan.text())-1;
			
			$mengSpan.text(moudeValue);

			if(!moudeValue){
				$mengSpan.hide();
			}
			if(!$value){
				$('li[data-type="'+foodId+'"]').find(".number").text($value);
				for(var i=0;i<foodList.length;i++){
						for(var key in foodList[i]){
						if(key==foodId){
							foodList.splice(i,1);
							break;
						}
					}
				}
				$('li[data-type="'+foodId+'"]').find(".add").hide().prev().show();
				
			}else{

				for(var i=0;i<foodList.length;i++){
					for(var key in foodList[i]){
						if(key==foodId){
							foodList[i][key].foodNum=$value; //只需要改变美食的数量就行
						}
					}
				}
				$('li[data-type="'+foodId+'"]').find(".number").text($value);	
			}

			shoopNumber=shoopNumber-1;
			totle(shoopNumber,foodList);
			
		});
		function totle(number,foodList){
			var str=JSON.stringify(foodList);//将json对象 转换成string;
			
			sessionStorage.setItem("key",str);  //将string 放到缓存中
			sessionStorage.setItem("number",number);
			
			var shoopMondy=0;//防止总的价钱重叠

			var  foodList=$.parseJSON(sessionStorage.getItem("key"))  //菜的缓存

        	var disArrayListNew=$.parseJSON(sessionStorage.getItem("dis"));  //口味的缓存
			
        	var flavorJson={};
        	if(disArrayListNew){
        		for(var i=0;i<disArrayListNew.length;i++){
	        		for(var key in disArrayListNew[i]){
	        			flavorJson[disArrayListNew[i][key].id]=true;  //用来存id
	        		}
        		}
        	}


			if(number>0){
				$(".main-bottom").show();
				$(".main").css('padding-bottom',"49px")
				$(".total").text(number);
			}else{
				$('.J_cart').hide();
				$(".main").css('padding-bottom',"0")
				$(".main-bottom").hide();	
				$(".total").text(0);

			}
			var html="";
			$(".food-info ul").remove();
			html+="<ul>";
			for(var i=0;i<foodList.length;i++){
				for(var key in foodList[i]){
					var foodPrice=foodList[i][key].foodPrice;
					var foodNum=foodList[i][key].foodNum;
					var foodTotle=foodPrice*foodNum;
					shoopMondy=shoopMondy+foodTotle;
					if(flavorJson[foodList[i][key].foodId]){
						html+='<li data-type='+foodList[i][key].foodId+'><div class="food-name name">'+foodList[i][key].foodName+'<p class="checkfood">(已选口味)</p></div>'+
            		'<div class="food-price pricers">￥<em>'+foodPrice+'</em>/<i>'+foodList[i][key].foodUnit+'</i></div>'+
            	'<div class="adder add"><span class="minus food-minus"></span><span class="number">'+foodNum+'</span><span class="minus plus">'+
            		'</span></div></li>';
					}else{
						html+='<li data-type='+foodList[i][key].foodId+'><div class="food-name name">'+foodList[i][key].foodName+'</div>'+
            		'<div class="food-price pricers">￥<em>'+foodPrice+'</em>/<i>'+foodList[i][key].foodUnit+'</i></div>'+
            	'<div class="adder add"><span class="minus food-minus"></span><span class="number">'+foodNum+'</span><span class="minus plus">'+
            		'</span></div></li>';
					}
					
				}
			}
			html+="</ul>";
			$(".food-info").append(html);
			ok(disArrayListNew,foodList)
			//$(".totalPrice").text("￥"+shoopMondy.toFixed(2));
        }
		$("#select_ok").on("click",function(){
			$(".cartMask").toggle();
			$('.J_cart').toggle();
			$('#select_ok').hide();
			$('#select_pay').show();
// 			console.log(JSON.stringify(disArrayList));
		})
		/*点击背景*/
		$('.cartMask').on("click",function(e){
			$('#select_ok').show();
			$('#select_pay').hide();
			$(this).hide();
			$('.J_cart').hide();
		})
		
		
		/*点击购物车*/
		$(".cart").on("click",function(){
			$(".cartMask").toggle();
			if($(this).find('span').text()==0){
				var html='<li><p class="forget">您忘了点餐</p></li>';
				$('.food-info ul').html(html);
			}else{
				var  foodList=$.parseJSON(sessionStorage.getItem("key"))  //菜的缓存
				var number=$.parseJSON(sessionStorage.getItem("number")); 
        		totle(number,foodList);
			}
			$('.J_cart').toggle();
			
			if($('#select_ok').css("display") == 'none'){
				$('#select_ok').show();
				$('#select_pay').hide();
			}else{
				$('#select_ok').hide();
				$('#select_pay').show();
			}
		});
		/*人数加减*/
		nums = 0;
		$(".jia").live("click",function(){
			nums ++;
			$(".renshu").html(nums);
		});
		$(".jian").live("click",function(){
			nums --;
			if(nums <= 0){
				nums = 0;
			}
			$(".renshu").html(nums);
		});
		/*跳到历史订单页面去*/
		$("#fixnav_add").on("click", function () {
            window.location.href = 'historyorder.php?uid=<?php echo $uid;?>&shopid=<?php echo $shopid;?>&tabid=<?php echo $tabid;?>';
        });

        //点击支付
		$("#select_pay").on("click",function(){
			$(".wrappBox").css("display","block");
			$(".cartMask").toggle();
			$('.J_cart').toggle();

		 });

		$('#closd').on('click',function(){
            $('.wrappBox').hide();
            $(".cartMask").toggle();
			$('.J_cart').toggle();
            $('#select_ok').hide();
			$('#select_pay').show();
        })

       



		//  判断是不是要选择人数
         function panduan(){
        	 var boo = $("#bycusnum").val();
	        if(boo == '1'){//返回数据是不是有该值
		        console.log(1);
            	$("#renshu").css({"display":"block"});
            }else{
            	console.log(0);
           		$("#renshu").css({"display":"none"});
            }
         }
    	panduan();

         //微信支付；
    	$('#mark').on('click',function(){
        	$(this).parent().hide();
        	$('.makes').show();
        	$('.mei1').show();
        	$('.mei2').show();
    		payid=$('#mark').val();
    		doPay(payid);
         })
          //支付宝支付；
         $('#marks').on('click',function(){
          	$(this).parent().hide();
        	$('.makess').show();
        	$('.mei3').show();
        	$('.mei4').show();
    		payid=$('#marks').val();
    		doPay(payid);
         })
                	
        function doPay(payid){
        	if(nums <= 0 && $("#renshu").css("display")=='block'){
                confirm("请选择就餐人数");
            }else{
       	 $('.canvasAlert').show();
         var loadingObj = new loading(document.getElementById('canvas'),{radius:8,circleLineWidth:3});   
           loadingObj.show(); 
           cusnum=$("#cusnum").html();
       	 orderrequest=$('#orderrequest').val();
       	 data={
 					'openid':'<?php echo $openid;?>',
 					'cusnum':cusnum,
 					'food':JSON.stringify(foodList),
 					'cook':JSON.stringify(disArrayList),
 					'orderrequest':orderrequest,
 					'uid':'<?php echo $uid;?>',
 					'shopid':'<?php echo $shopid;?>',
 					'tabid':'<?php echo $tabid;?>',
 					};
       	 $.ajax({
 				url:"<?php echo ROOTURL?>printbill/interface/predownbill.php",
 				type:"POST",
 				data:data,
 				success:function(data){
 					var data = JSON.parse(data);
 		      		PARAMS={
 		      				'openid':'<?php echo $openid;?>',
 		      				'orderno':data.orderno,
 	  		      			'cusnum':cusnum,
 		      				'tabid':data.tabid,
 		      				'billid':data.billid,
 		      				'uid':data.uid,
 		      				'shopid':data.shopid,
 		      				'paymoney':data.paymoney,
 		      				'orderrequest':data.orderrequest,
 		      		}
 	  		      	$('.canvasAlert').show();
			       	 var loadingObj = new loading(document.getElementById('canvas'),{radius:8,circleLineWidth:3});   
			         loadingObj.show();  
			         if(payid=="alipay"){
			        	 post('<?php echo ROOTURL;?>wappay/alipayapi.php', PARAMS);
			         }else if(payid=="wechatpay"){
 	  		      		callpay(data.jsApiParameters);
 		      		}else if(payid=="directpay"){
 		      			if(confirm('确定要下单？')){
 		      				post('./directpay.php',PARAMS);
 		      			}
 		      		}
 	  		      	$('.canvasAlert').hide();
 	 	  		    setTimeout(function(){
 	 	 	  		    console.log(123);
 	 		    		$('#mark').parent().show();
 	 		        	$('.makes').hide();
 	 		        	$('.mei1').hide();
 	 		        	$('.mei2').hide();
 	 		        	$('#marks').parent().show();
 	 		        	$('.makess').hide();
 	 		        	$('.mei3').hide();
 	 		        	$('.mei4').hide();
 	 	 	 	  	},3000);
 				},
 				error:function(){
 					alert("服务正在维护中，请稍后再试~");
 					}
 				});
            }
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

		function jsApiCall(jsApiParameters)
		{
		    WeixinJSBridge.invoke(
		        'getBrandWCPayRequest',
				jsApiParameters,
				function(res){
				    WeixinJSBridge.log(res.err_msg);
				   // alert(res.err_code+"="+res.err_desc+"="+res.err_msg);
				    if(res.err_msg=="get_brand_wcpay_request:cancel"){
//				       window.location.href='./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>'
				    }else if(res.err_msg=="get_brand_wcpay_request:ok"){
				    	window.location.href='./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>&paystatus=paid';
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

		/*点击小图 放大*/
   		$('.lists').on('click','.shoop-pic img',function(){
   			$('.imgzoom_pack').show();
   			var src=$(this).attr('src');
   			$('.imgzoom_img img').attr('src',src);
   			var imgHeight=$('.imgzoom_img .imgpadding').height();
   			$('.imgpadding').css('marginTop',-imgHeight/10); // 修改marginTop的值
   			foodid=$(this).parents('li').attr('data-type');
   			foodintro=$('#foodintro_'+foodid).val();
   			$('#foodintro').html(foodintro);
   		});

   		$('.imgzoom_x').on('click',close);
//    		$('.imgzoom_pack').on('click',close);//点击背景关闭大图  新增
   		function close(){
   			$('.imgzoom_pack').hide();
   			$(this).next().attr('src',"");
   		}
		 
	})();
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
      
