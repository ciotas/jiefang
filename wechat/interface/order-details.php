<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class Order_details{
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function getBillNum($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getBillNum($billid);
	}
}
$order_details=new Order_details();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$shopname=$_GET['shopname'];
	$billarr=$order_details->getOneBillInfoByBillid($billid);
	$billno=$order_details->getBillNum($billid);
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
<title>订单详情</title>
</head>
<body>
<div class="fullele" id="J_fullele">
    <div class="pages" id="J_pages">
        <div class="cm-page" id="J_historyder">
            <div class="page-content historyorder">
                <div class="hist-tl">
                    <div class=" head-top">
                        <span>已支付</span><?php echo $shopname;?>
                    </div>
                    

                </div>
                <div class="hist-ul history-page details-page">
                    <div class="details">
                        <div class="details-top">
                            <div class="details-mondy"><span>总金额：</span><?php echo $billarr['paymoney'];?>元</div>
                         <!--    <p class="watch-number"><span>流水号：<?php echo $billarr['orderno'];?></span><font></font></p> -->
                            <p><span>单号：</span><?php echo $billno;?></p>
                            <p><span>下单人：</span><?php echo $billarr['nickname'];?></p>
							<p><span>备注：</span><?php echo $billarr['orderrequest'];?></p>
                            <p><span>下单时间：</span><?php echo date("Y-m-d H:i:s",$billarr['timestamp']);?></p>

                            <div class="foot-table foot-table-b">
                                <table cellspacing="0" cellpadding="0" width="100%">
                                    <tr>
                                        <th width="30%" align="left">菜名</th><th width="20%">单价</th><th width="15%">数量</th><th width="15%">总价</th>
<!--                                         <th width="20%">状态</th> -->
                                    </tr>
                                    <?php foreach ($billarr['food'] as $key=>$val){?>
                                    <tr>
                                        <td><?php echo $val['foodname'];?></td><td><?php echo $val['foodprice'];?>元</td><td><?php echo $val['foodnum'];?><?php echo $val['foodunit'];?></td><td><?php echo $val['foodprice']*$val['foodamount'];?>元</td>
                                    </tr>
                                    <?php }?>
                                   
                                </table>
                            </div>
                            <div class="gj">共计:<span><?php echo $billarr['paymoney'];?>元</span></div>
                        </div>
                    </div>
                </div>
                <div class="godesh immediately">
                    <a href="./menu.php?shopid=<?php echo $billarr['shopid'];?>&uid=<?php echo $billarr['uid'];?>&tabid=<?php echo $billarr['tabid'];?>"><span><i></i>返回菜单</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
