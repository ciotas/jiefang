<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class HistoryOrder{
	public function getHistoryLists($shopid,$uid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getHistoryLists($shopid,$uid);	;
	}
}
$historyorder=new HistoryOrder();
if(isset($_GET['uid'])){
	$uid=$_GET['uid'];
	$shopid=$_GET['shopid'];
	$tabid=$_GET['tabid'];
	$arr=$historyorder->getHistoryLists($shopid,$uid);	
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no"/>
<link rel="stylesheet" type="text/css" href="../media/css/public.css">
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black-translucent" name="apple-mobile-web-app-status-bar-style"/>
<title>历史订单</title>
</head>
<body>



<div class="fullele" id="J_fullele">
    <div class="pages" id="J_pages">
        <div class="cm-page" id="J_historyder">
            <div class="page-content historyorder">
                <div class="hist-tl">
                    <div class="hist-left">
                        <span class="hist-place">历史订单</span>
                    </div>
                   <div class="hist-line"></div>
                </div>
                <div class="hist-ul history-page" id="J_history">
                    <div class="histinfo">
                        <ul>
                             <?php 
                        	foreach ($arr as $key=>$val){
                        ?>
                         <li onclick="window.location.href='./order-details.php?billid=<?php echo $val['billid'];?>&shopname=<?php echo $val['shopname'];?>'">
                           <div class="time"><?php echo date("Y-m-d",$val['time']);?></div>
				            <div class="history-count">
				              <p>商家:<?php echo $val['shopname'];?></p>
				              <p class="mondy-total">消费共计：￥<?php echo $val['paymoney'];?></p>
				           </div>
				           <div class="pay-button">
				                <a href="#" class="wait-pay">已支付</a>
				                <a href="">查看详情&gt;</a>
				            </div></li>
                        <?php }?>
                        </ul>
                    </div>
                </div>
                <div class="godesh immediately">
                        <a href="./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>"><span><i></i>返回</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../media/js/zepto.min.js"></script>

<script type="text/javascript">
    var historyOrders=[
        {
            time:"02-16 15:26",mondyTotal:"57元",payState:"未支付",
        },
         {
            time:"02-17 15:26",mondyTotal:"57元",payState:"未支付",
        },
         {
            time:"02-18 15:26",mondyTotal:"57元",payState:"未支付",
        },
         {
            time:"02-19 15:26",mondyTotal:"57元",payState:"未支付",
        }
    ]

    var historyOrder=""

    
    for(var i=0;i<historyOrders.length;i++){
        historyOrder+='<li><div class="time">'+historyOrders[i].time+'</div>'+
            '<div class="history-count">'+
              '  <p>流水号:</p>'+
              '  <p class="mondy-total">消费共计：'+historyOrders[i].mondyTotal+'</p>'+
           ' </div>'+
           ' <div class="pay-button">'+
              '  <a href="#" class="wait-pay">'+historyOrders[i].payState+'</a>'+
              '  <a href="#">查看详情&gt;</a>'+
            '</div></li>';
    }

  //  $('.histinfo ul').append(historyOrder);
    
    $(function(){
        $(".J_godesh").on("click",function(){
            window.location.href = 'menu.php?uid=<?php echo $uid;?>&shopid=<?php echo $shopid;?>&tabid=<?php echo $tabid;?>';
        });


    });
</script>

</body>
</html>















