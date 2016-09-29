<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');
class GetCashPage{
    public function getShopidByOpenid($openid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
    }
    public function getShopmoneyByShopid($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopmoneyByShopid($shopid);
    }
    public function getTodayMoney($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getTodayMoney($shopid);
    }
    public function getTheday($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
    }
    public function getOpenHourByShopid($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
    }
    public function getTurnfoodTrendData($shopid, $startdate, $enddate,$datearr, $thehour){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getTurnfoodTrendData($shopid, $startdate, $enddate, $datearr, $thehour);
    }
}
$getcashpage=new GetCashPage();
$account="0";
$todaymoney=0;
if(isset($_GET['openid'])){
    $openid = isset($_GET['openid'])?$_GET['openid']:'';
    $shopid=$getcashpage->getShopidByOpenid($openid);
    $account=$getcashpage->getShopmoneyByShopid($shopid);
    $todaymoney=$getcashpage->getTodayMoney($shopid);
    $startdate=date('Y-m-01', strtotime(date("Y-m-d")));//获取本月1号
    $enddate=$getcashpage->getTheday($shopid);
    $thehour=$getcashpage->getOpenHourByShopid($shopid);
    $datearr=array();
    for ($day=strtotime($startdate);$day<=strtotime($enddate);$day=$day+86400){
        $datearr[]=date("Y-m-d",$day);
    }
    $data=$getcashpage->getTurnfoodTrendData($shopid, $startdate, $enddate, $datearr, $thehour);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,user-scalable=no">
<title>我要提现</title>
<link rel="stylesheet" type="text/css" href="../media/css/weicashpage.css" />
<script type="text/javascript" src="../media/js/jquery-2.2.1.js"></script>
<script src="<?php echo $root_url;?>chart.js/Chart.js"></script>
</head>

<body>
<div id="main">
<!-- 账户余额 -->
   <div class="jiangxiang xianjin">
        <div class="jiangxianges">账户余额</div>
        <div class="numh"><?php echo sprintf("%.2f",$account);?></div>
        <div class="zuori">今日收入</div>
        <div class="numy"><?php echo $todaymoney;?></div>
   </div>
<!-- 折线图 -->
    <div class="jiangxiang zhexian">
         <canvas id="lineChart" width="100%" height="72"></canvas>
		<?php 
			$jsondata=json_encode($data);
			echo '<script>window.onload = function(){';
			echo 'var lineChartData='.$jsondata.';';
			echo 'var ctx = document.getElementById("lineChart").getContext("2d");';
			echo 'window.myLine = new Chart(ctx).Line(lineChartData, {responsive: true});';
			echo '}</script>';
		?>
   </div>

<!-- 规则说明 -->
    <div class="jiangxiang shuoming">
      <div class="huoshuo">*规则说明:</div>
      &nbsp&nbsp1.&nbsp单笔单日限额2000元；<br/>
      &nbsp&nbsp2.&nbsp单笔最小金额为1元；<br/>
      &nbsp&nbsp3.&nbsp单日最多提现10次；
   </div> 
<!-- 立即提现 -->
    <div class="anniu">立即提现</div>
<!-- 弹出层 -->
   <div class="boxt">
       <div class="bg"></div>
       <div class="zhuti">
       <!-- 表单提交 -->
               <form action="./getcash.php" method="POST">
               <div class="tixiant">输入金额</div>
               <input class="jinet" type="tel" name="cashvalue" placeholder="点击输入金额" />
               <p class="warming" style="display: none;">实际到账<span>0</span>元(已扣除微信平台提现手续费)</p>
               <input type="hidden" name="openid" value='<?php echo $openid;?>'>
               <p class="jinggao">*您输入的金额有误，请重新输入！*</p>
               <div class="partt">
                    <button type="button" class="quxiaot">取消</button>
                    <button type="submit" class="quedingt" disabled>确定</button>
                    <div class="quedingts">确定</div>
               </div>
            </form>
       </div>
   </div>
</div>
<!-- 移动端适配方法 -->
<script type="text/javascript">
    (function (doc, win) {
        var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function () {
                    window.clientWidth = docEl.clientWidth;
                    if (!window.clientWidth) return;
                    docEl.style.fontSize = 20 * (window.clientWidth / 320) + 'px';
                    window.base = 20 * (window.clientWidth / 320);
                };
        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);
</script>
<!-- 提现页面用到的方法 -->
    <script type="text/javascript">
        $(function(){
            // 可视区域高度
            $('#main').height($(window).height());
/*             $('.boxt').height($('#main').height()); */
            //数字从0涨到目标数值
            var nmm = $('.numh').get(0);
            var naa = $('.numh').html();
            var nmms = $('.numy').get(0);
            var naas = $('.numy').html();
           function addNum(ele,y,time){
               if(y<=1){
            	   ele.innerHTML = y;
            	   return ;
               }else{
                 var n = 0,timer;
                 console.log(parseInt(y));
                 var x = Math.ceil(y/time);
                 console.log(x);
                 timer = setInterval(function(){
                   n += x;
                   if (n-(x-y%x) == y) {
                     clearInterval(timer);
                   };
                   	ele.innerHTML = n-(x-y%x);
                 },time)
               }
           }
           addNum(nmm,naa,30);
           addNum(nmms,naas,20);
            // 点击提现按钮
            $('.anniu').on('click',function(){
                $('.boxt').css({"display":"block"}).animate({"top":"0px"});
            });
            // 提现金额赋值
            emt = $(".numh").html();
            $(".jinet").attr("placeholder","余额："+emt);
            // 点击取消按钮
            $('.quxiaot').on('click',function(){
                $('.boxt').animate({"top":"-125%"}).css({"display":"block"});
            });
            
            //判断2000限额和余额哪一个大
            var at =2000;
            if( emt >= 2000){
              at = 2000;
            }else{
              at = emt;
            }
            var maxMoney = parseInt(at);
            var inputMoney = parseInt(emt);
            // 输入金额
            $(".jinet").keyup(function(){
            	// 输入金额验证
                var number = /^(\d)*$/;
                if(number.test(this.value) && this.value >= 1 && this.value <= maxMoney){
                    $(this).css({"border-color":"gray"});
                    $('.jinggao').hide();
                    $('.quedingt').attr('disabled',false);
                    //输入的金额扣除手续费
                    $(".warming").show();
                    var tip = this.value*0.994;
                    $(".warming span").html(tip.toFixed(2));
                    console.log(tip);
                }else{
                	$(".warming").hide();
                    if(!number.test(this.value)){
                      $('.jinggao').show().html("*请输入整数");
                    }
                    if(this.value <= 0){
                      $('.jinggao').show().html("*最小金额为1元");
                    }
                    if(this.value <= 2000){
                    		if(this.value > inputMoney){
                    			$('.jinggao').show().html("*您的余额不足");
                    		}
                    }
                    if(this.value > 2000){
                    		if(this.value > inputMoney){
                    			$('.jinggao').show().html("*您的余额不足");
                    		}
                    		if(this.value <= inputMoney){
                    			$('.jinggao').show().html("*单笔限额为2000元");
                    		}
                    }
                    $(this).css({"border-color":"red"});
                    $('.quedingt').attr('disabled',true);
                }
            });
			//点击完确定按钮之后失效
			$('.quedingt').on('click',function(){
				$('.quedingt').css({"display":"none"});
				$('.quedingts').css({"display":"block"});

			})
        });

    </script>
</body>
</html>