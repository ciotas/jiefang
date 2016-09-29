<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/Factory/BLLFactory.php');
class Menu{
	public function getShopinfo($shopid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getShopinfo($shopid);
	}
	public function getWechatUserinfo($uid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getWechatUserinfo($uid);
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
<div class="header"><?php if(!empty($shopinfoarr)){echo $shopinfoarr['shopname'];}?></div>

<div class="main">
	<div class="eat-title">
		<ul></ul>
	</div>
	<div class="eat-count">
		<div class="count-top"></div>
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
    <div class="d-ib v-m view-selected">
        <div class="cart d-ib v-m on">
            <span class="total">0</span>
        </div>
        <span class="totalPrice d-ib v-m">￥0</span>
    </div>
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

<section class="imgzoom_pack">
    <div class="img_table" >
	    <div class="imgzoom_img">
			<div class="imgpadding">
	    		<div class="imgzoom_x">╳</div>
	        	<img  src="../media/images/pic_01.jpg"/>
	        	<p id="foodintro"></p>
	    	</div>
	    </div>
	</div>
</section>
<input type="hidden" id="type" value="<?php echo $type;?>">
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
			$(".totalPrice").text("￥"+shoopMondy.toFixed(2));
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
//   			console.log(PARAMS)
			type=$('#type').val();
			if(type=="outer"){
				post('./confirm.php', PARAMS);
			}else{
				post('./confirm2.php', PARAMS);
			}
			
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
   			$('#foodintro').html(foodintro);
   		});

   		$('.imgzoom_x').on('click',close);
   		$('.imgzoom_pack').on('click',close);//点击背景关闭大图  新增
   		function close(){
   			$('.imgzoom_pack').hide();
   			$(this).next().attr('src',"");
   		}
	})();
</script>