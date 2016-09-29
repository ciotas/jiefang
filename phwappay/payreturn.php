<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
class PayReturn{
	public function getPayRecordData($trade_no,$billid){
		return QuDian_InterfaceFactory::createInstancePayDAL()->getPayRecordData($trade_no, $billid);
	}
	public function getOneBillInfoByBeforeBillid($oldbeforebillid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($oldbeforebillid);
	}
}
$payreturn=new PayReturn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<title>支付账单</title>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="./media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="./media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="./media/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="./media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="./media/css/profile.css" rel="stylesheet" type="text/css" />

</head>
<body>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

			<div class="container-fluid">
				<div class="span12">
						<h3 class="page-title">
							支付账单
							 <small></small>
						</h3>
					</div>
				<div class="row-fluid profile">

					<div class="span12">

						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

							<?php
							//计算得出通知验证结果
							$alipayNotify = new AlipayNotify($alipay_config);
							$verify_result = $alipayNotify->verifyReturn();
							if($verify_result) {//验证成功
								/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								//请在这里加上商户的业务逻辑程序代码
								
								//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
							    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
							
								//商户订单号
							
// 								$out_trade_no = $_GET['out_trade_no'];
							
								//支付宝交易号
							
								$trade_no = $_GET['trade_no'];
							
								//交易状态
								$trade_status = $_GET['trade_status'];
								$body=$_GET['body'];
								$bodyarr=json_decode($body,true);
								$billid=$bodyarr['billid'];
								$beforeinfo=$payreturn->getOneBillInfoByBeforeBillid($billid);
								$tabid=$beforeinfo['tabid'];
								$tabname=$bodyarr['tabname'];
								$uid=$beforeinfo['uid'];
								$shopid=$beforeinfo['shopid'];
								$out_trade_no=$beforeinfo['orderno'];
								$nickname=$beforeinfo['nickname'];
								$total_fee=$_GET['total_fee'];
							
							    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
									//判断该笔订单是否在商户网站中已经做过处理
										//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
										//如果有做过处理，不执行商户的业务程序
							    }
							    else {
							      echo "trade_status=".$_GET['trade_status'];
							    }
// 								echo "验证成功<br />";
								//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
								
								/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							}
							else {
							    //验证失败
							    //如要调试，请看alipay_notify.php页面的verifyReturn函数
// 							    echo "验证失败";
							    echo '<button class="btn red big btn-block">验证失败</button>';
							}
							?>

									<ul class="span10" style="list-style-type: none;">
										<li><h4>订单号：<?php echo $out_trade_no;?></h4></li>
										<li><h4>支付金额：￥<?php echo $total_fee;?></h4></li>
										<li><h4>商家：<?php if(!empty($beforeinfo)){echo $beforeinfo['shopname'];}?></h4> </li> 
										<?php if(!empty($tabname)){?>
										<li><h4>台号：<?php echo $tabname;?></h4> </li>
										<?php }?>
										<li><h4>买单时间：<?php echo date("Y-m-d H:i:s",time())?></h4> </li>
									</ul>
								</div>	
							<a class="btn red big btn-block"  target="_blank" href="<?php echo ROOTURL;?>phwechat/interface/menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>&paystatus=paid"><—返回首页</a>
								<!--end tab-pane-->
						</div>
					
						<!--END TABS-->

					</div>

				</div>

				<!-- END PAGE CONTENT-->

			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014-2016 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>


	</div>

	<!-- END FOOTER -->

	<script src="./media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="./media/js/bootstrap.min.js" type="text/javascript"></script>

	<script src="./media/js/form-components.js"></script>  
	
</body>

<!-- END BODY -->

</html>	