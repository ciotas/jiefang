<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class Menu{
	public function getShopinfo($shopid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getShopinfo($shopid);
	}
	public function getWechatUserinfo($uid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getWechatUserinfo($uid);
	}
	public function getStartmoney($shopid){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getStartmoney($shopid);
	}
}
$menu=new Menu();
$paystatus="unpay";
if(isset($_REQUEST['shopid'])){
	$shopid=$_REQUEST['shopid'];
	$uid=$_REQUEST['uid'];
	$tabid=$_REQUEST['tabid'];
	$type=$_REQUEST['type'];
	$paystatus=$_REQUEST['paystatus'];
	$shopinfoarr=$menu->getShopinfo($shopid);
	
	$wechatinfo=$menu->getWechatUserinfo($uid);
	$openid="";
	if(!empty($wechatinfo)){
		$openid=$wechatinfo['openid'];
	}
	//起送费
	$startmoney=$menu->getStartmoney($shopid);
}
// $uid="560fcf6b7cc1096c058b4575";
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
<title><?php if(!empty($shopinfoarr)){echo $shopinfoarr['shopname'];}?></title>
</head>           
	
<body>
<!-- 新增滚动条 -->
    <!-- start -->
    <?php if(!empty($shopinfoarr['notice'])){?>
        <div class="adm">
        	<marquee scrollamount="3" scrolldelay="10" direction="left"><?php echo $shopinfoarr['notice'];?></marquee>
        </div>
        <img alt="" src="http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/hyp/labas.png" width="20px" height="16px" style="position:fixed;top:8px;left:10px;">
    <?php }?>
    <!-- end -->
    <input type="hidden" id="notice" value="<?php echo $shopinfoarr['notice'];?>">
<div class="header"><?php if(!empty($shopinfoarr)){echo $shopinfoarr['shopname'];}?></div>

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
                <ul></ul>
            </div>
        </div>
    </div>
   

<div class="main-bottom none">
    <div class="d-ib v-m view-selected" style="float:left;">
        <div class="cart d-ib v-m on">
            <span class="total">0</span>
        </div>
        <span class="totalPrice d-ib v-m">￥0</span>
    </div>
    <div  class="warming" style="float:left;color:red;font-size:14px;">*<?php echo $startmoney;?>元起送</div>
    <div class="payButton">选好了</div>
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
<!-- 大图展示 -->
<section class="imgzoom_pack">
    <div class="img_table">
    <div class="imgzoom_x">╳</div>
	    <div class="imgzoom_img">
			<div class="imgpadding" style="height: 66%;">
	    		<div style="height: 98%;overflow: scroll;">
	        		<img  src="../media/images/pic_01.jpg"/>
	        		<p id="foodintro" style="text-indent:1.5rem;">非常好吃</p>
	        	</div>
	    	</div>
	    </div>
	</div>
</section>
<input type="hidden" id="type" value="<?php echo $type;?>">

<!-- 外卖起送金额 -->
<input type="hidden" id="startmoney" value="<?php echo $startmoney;?>">

</body>
</html>

<script type="text/javascript" src="../media/js/zepto.min.js"></script>
<script type="text/javascript" src="../media/js/common.js"></script>

<!-- <script type="text/javascript" src="../media/js/main.js"></script> -->
<script type="text/javascript" src="../media/foodjs/<?php echo $shopid?>.js"></script>
<script type="text/javascript" src="../media/foodjs/cook_<?php echo $shopid?>.js"></script>
<script type="text/javascript" src="../media/js/assign.js"></script>

<script type="text/javascript">
<?php 
if($paystatus=="paid"){
?>
sessionStorage.setItem("key","");  //将string 放到缓存中
sessionStorage.setItem("number","");
sessionStorage.setItem("dis","");
<?php }?>

// sessionStorage.setItem("dis","");	
// localStorage.setItem("dis","");
	(function () {

		var $foodCategory=$(".wrapper .lists");
		var max =$foodCategory.length - 1;

        var $foodCategroyLast = $foodCategory.eq(max);

        var height = $(".eat-count").height();

        var lastHeight = $foodCategroyLast.height();

        var pb = height - lastHeight;
//         给出价格不满足配送的起始样式；
// 		start
		isTrue = false;//无价格默认不能进行点击；
		function stmoney(isTrue){
    		if(isTrue){
    			$(".payButton").css({background:"#ee4743"});
    			$(".warming").hide();
    		}else{
    			$(".payButton").css({background:"#999"});
    			$(".warming").show();
            }
		}
		stmoney(isTrue);
// 		end
        var scrollMenu =Utils.throttle(function () {
        	event.cancelBubble = true;
    	    event.preventDefault();
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
			
			if(!foodList.length){
				$(".cartMask").hide();
				$(".main-bottom").hide();
			}
			
			var shoopMondy=0;//防止总的价钱重叠
			
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
			html+="<ul>"
			for(var i=0;i<foodList.length;i++){
				for(var key in foodList[i]){
					var foodPrice=foodList[i][key].foodPrice;
					var foodNum=foodList[i][key].foodNum;
					var foodTotle=foodPrice*foodNum;
					shoopMondy=shoopMondy+foodTotle;
					html+='<li data-type='+foodList[i][key].foodId+'><div class="food-name name">'+foodList[i][key].foodName+'</div>'+
            		'<div class="food-price pricers">￥<em>'+foodPrice+'</em>/<i>'+foodList[i][key].foodUnit+'</i></div>'+
            	'<div class="adder add"><span class="minus food-minus"></span><span class="number">'+foodNum+'</span><span class="minus plus">'+
            		'</span></div></li>';
				}
			}
			html+="</ul>"
			$(".food-info").append(html);
			// 新增价格判断方法
			// 	start
			var mon = shoopMondy.toFixed(2);
			var smoney = $("#startmoney").val();
			var m = parseFloat(mon);
			var s = parseFloat(smoney);
// 			console.log(m,s);
			if(m>=s){
				isTrue = true;
			}else{
				isTrue = false;
			}
			
			stmoney(isTrue);
			// 	end
			$(".totalPrice").text("￥"+mon);
		}
		$(".payButton").on("click",function(){
			
			PARAMS={
  					'openid':'<?php echo $openid;?>',
  					'food':JSON.stringify(foodList),
  					'cook':JSON.stringify(disArrayList),
  					'uid':'<?php echo $uid;?>',
  					'shopid':'<?php echo $shopid;?>',
  					'tabid':'<?php echo $tabid;?>',
  			};
			console.log(isTrue);
			if(isTrue){
				post('./confirm.php', PARAMS);
			}

		});
		
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
		
		/*点击背景*/
		$('.cartMask').on("click",function(e){
			
				$(this).hide();
				$('.J_cart').hide();
		})
		
		/*点击购物车*/
		$(".cart").on("click",function(){
			$(".cartMask").toggle();
			$('.J_cart').toggle();

		});
		/*跳到历史订单页面去*/
		$("#fixnav_add").on("click", function () {
            window.location.href = 'historyorder.php?uid=<?php echo $uid;?>&shopid=<?php echo $shopid;?>&tabid=<?php echo $tabid;?>';
        });

		/*点击小图 放大*/
   		$('.lists').on('click','.shoop-pic img',function(){
   			$('.imgzoom_pack').show();
   			var src=$(this).attr('src');
   			$('.imgzoom_img img').attr('src',src);
   			var imgHeight=$('.imgzoom_img .imgpadding').height();
   			$('.imgpadding').css('marginTop',-imgHeight/10); // 修改marginTop的值
   			foodid=$(this).parents('li').attr('data-type');
   			foodintro=$('#foodintro_'+foodid).val();
//    			$('#foodintro').html(foodintro);
   			$('#foodintro').html(foodintro);
   		});

   		$('.imgzoom_x').on('click',close);
   		function close(){
   			$('.imgzoom_pack').hide();
   			$(this).next().attr('src',"");
   		}
	})();
</script>
<!-- 新增：当不存在通知时，CSS样式的改变 -->
<script type="text/javascript">
    $(function(){
        var tongzhi = $("#notice").val();
    	if(tongzhi){
    		console.log(1);
    		$(".main").css({paddingTop:"80px"});
    		$(".header").css({top:"32px"});
    		$(".fixnav_add").css({top:"42px"});
        }else{
        	console.log(0);
        	$(".main").css({paddingTop:"45px"});
        	$(".header").css({top:"0px"});
        	$(".fixnav_add").css({top:"10px"});
        }
    
    })
</script>











