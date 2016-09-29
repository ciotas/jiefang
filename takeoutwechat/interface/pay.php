<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
require_once (_ROOT.'des.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class Pay{
    public function getOneBillInfoByBeforeBillid($billid){
        return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($billid);
    }
}
$pay=new Pay();
$paymoney=0;
if(isset($_REQUEST['billid'])){
    $billid=($_REQUEST['billid']);
    $crypt = new CookieCrypt(ParamKey);
    $billid=$crypt->decrypt($billid);
    $billinfo=$pay->getOneBillInfoByBeforeBillid($billid);
    foreach ($billinfo['food'] as $fkey=>$fval){
        $paymoney+=$fval['foodprice']*$fval['foodnum'];
    }
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
<title>支付</title>
</head>

<body>

	<div class="paywrapp">
		<div class="pay-main">
			<div class="pay-box">
				<div class="title">快捷支付</div>
				<div class="pay-count">
						<p class="pay-inform">支付成功后可以到订单中查看</p>
						<p class="pay-mondy">￥<?php echo $paymoney?></p>
						
						<div class="pay">
							 <button class="pay-button">线下支付</button>
							 <form action="<?php echo ROOTURL;?>wappay/alipayapi.php" method="post">
							     <input type="hidden" name="orderno" value="<?php echo $billinfo['orderno'];?>">
							     <input type="hidden" name="tabid" value="<?php echo $billinfo['tabid'];?>">
							     <input type="hidden" name="billid" value="<?php echo $billid;?>">
							     <input type="hidden" name="uid" value="<?php echo $billinfo['uid'];?>">
							     <input type="hidden" name="shopid" value="<?php echo $billinfo['shopid'];?>">
							     <input type="hidden" name="paymoney" value="<?php echo $paymoney;?>">
							     <button class="pay-button">支付宝支付</button>
							 </form>
						</div>
					<br>
						<!-- <div class="pay">
							 <input type="submit"  class="pay-button" value="支付宝支付">
							 
							 <input type="submit"  class="pay-button" value="支付宝支付">
						</div>
					
						<div class="confirm ">
							<a href="confirm.html">是否需要确认订单?</a>
						</div>
					 -->
				</div>
			</div>
		</div>
	</div>
</body>
</html>