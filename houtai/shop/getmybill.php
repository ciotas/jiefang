<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetMyBill{
	public function getOneUnpayBill($uid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getOneUnpayBill($uid);
	}
	public function getPreBillByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getPreBillByBillid($billid);
	}
	public function getShopInfo($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopInfo($shopid);
	}
	public function isShowVipPay($uid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->isShowVipPay($uid, $shopid);
	}
}
$getmybill=new GetMyBill();
$arr=array();
$billid="";
$shouldpay=0;
$totalmoney=0;
$discountmoney=0;
$ticketmoney=0;
$prearr=array();
$shoparr=array();
if(isset($_REQUEST['uid'])){
	$uid=$_REQUEST['uid'];
	$nickname=$_REQUEST['nickname'];
	$photo=$_REQUEST['photo'];
	$arr=$getmybill->getOneUnpayBill($uid);
	$billid=$arr['billid'];
	$prearr=$getmybill->getPreBillByBillid($billid);
	foreach ($arr['food'] as $fley=>$fval){
		if(empty($fval['present'])){
			$totalmoney+=$fval['foodamount']*$fval['foodprice'];
			if($fval['fooddisaccount']=="1"){
				$fooddisaccountmoney+=$fval['foodamount']*$fval['foodprice'];
			}
		}
	}
	$shoparr=$getmybill->getShopInfo($arr['shopid']);
	if($arr['deposit']=="1"){
		$totalmoney+=$shoparr['depositmoney'];
	}
	if(!empty($prearr)){
		$shouldpay=$prearr['shouldpay'];
		if($prearr['allcount']=="1"){
			$tdisaccountmoney=ceil($totalmoney*(1-$prearr['discountval']/100));
		}else{
			$tdisaccountmoney=ceil($fooddisaccountmoney*(1-$prearr['discountval']/100));
		}
		if(!empty($prearr['ticketval'])&&!empty($prearr['ticketnum'])){
			$ticketmoney=$prearr['ticketval']*$prearr['ticketnum'];
		}
		$discountmoney=$tdisaccountmoney+$ticketmoney+$prearr['clearmoney']+$prearr['returndepositmoney'];
		// 	$shouldpay=$totalmoney-$discountmoney;
	}else{
		$shouldpay=$totalmoney;
	}
}
// $showvip=$getmybill->isShowVipPay($uid, $arr['shopid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<title>我的点菜单</title>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/profile.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	var xmlHttp
	var shouldpay1=0;
	var uid1=0;
	var shopid1=0;
	var fooddisaccountmoney1=0;
	var billid1=0;
	var nickname1="";
	function vippay(uid,shouldpay,shopid,billid,fooddisaccountmoney,deposit,nickname){
		
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		  {
		  alert ("Browser does not support HTTP Request")
		  return
		  } 
		shouldpay1=shouldpay;
		uid1=uid;
		shopid1=shopid;
		billid1=billid;
		fooddisaccountmoney1=fooddisaccountmoney;
		deposit1=deposit;
		nickname1=nickname;
		var url="./interface/isbindphone.php"
		url=url+"?uid="+uid
		url=url+"&shopid="+shopid
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}

	function stateChanged() 
	{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
		discounmoney=0;
		url="";
		str='';
	 	data=xmlHttp.responseText
	 	data1=eval("("+data+")");
	 	if(data1['status']=="unbind"){
		 	alert("您尚绑定手机号！请在“我的”菜单里去绑定");
	 	}else if(data1['status']=="unrecharge"){
		 	alert("您未充值，请在吧台充值后再支付，优惠多多");
	 	}else if(data1['status']=="unuse"){
		 	alert("此会员卡已废止，请联系商家核实！");
	 	}else if(data1['status']=="ok"){
		 	if(shouldpay1>data1['accountbalance']){
			 	alert("您的会员卡余额不足，请在吧台充值后再支付，优惠多多！");
		 	}else{
			 	discounmoney=Math.ceil(fooddisaccountmoney1*(1-data1['carddiscount']/100));
			 	if(discounmoney>0){
				 	str="(其中会员卡专享"+data1['carddiscount']+"折优惠"+discounmoney+"元)";
			 	}
			 	shouldpay2=shouldpay1-discounmoney;
			 	if(confirm("消费总额"+shouldpay1+"元，您需支付"+shouldpay2+"元"+str+"，确认买单？")){
// 			 		window.location.href='http://test.meijiemall.com/houtai/shop/interface/docusvippay.php?billid=562354575bc109ad5c8b456b&uid=560ffb637cc10967058b4578&shopid=554ad9615bc109d8518b45d2&shouldpay=708&accountbalance=88198&carddiscount=90&discounmoney=53&deposit=1&nickname=lindy';
				 	url='http://test.meijiemall.com/houtai/shop/interface/docusvippay.php?billid='+billid1+'&uid='+uid1+'&shopid='+shopid1+'&shouldpay='+shouldpay1+'&accountbalance='+data1['accountbalance']+'&carddiscount='+data1['carddiscount']+'&discounmoney='+discounmoney+'&deposit='+deposit1+'&nickname='+nickname1;
				 	window.location.href=url;
			 	}
		 	}
	 	}
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
	</script>
</head>
<body>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

			<div class="container-fluid">
				<div class="span12">
						<h3 class="page-title">
							未付款账单
							 <small></small>
						</h3>
					</div>
				<div class="row-fluid profile">

					<div class="span12">

						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

									<div class="span2"><img src="<?php echo $photo;?>" alt="用户头像" /></div>

									<ul class="span10" style="list-style-type: none;">

										<li><h5>用户：<?php echo $nickname;?></h5></li>
										<li><h5>商家：<?php if(!empty($arr)){echo $arr['shopname'];}?></h5> </li>
										<li><h5>台号：<?php if(!empty($arr)){echo $arr['tabname'];}?></h5> </li>
										<li><h5>人数：<?php if(!empty($arr)){echo $arr['cusnum'];}?></h5> </li>
										<li><h5>时间：<?php if(!empty($arr)){echo date("Y-m-d H:i:s", $arr['timestamp']);}?></h5> </li>
									</ul>

								</div>	
								
								<!--end tab-pane-->
						</div>

						<div class="portlet box ">
							<div class="portlet-body">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>名称</th>
											<th>价格</th>
											<th>数量</th>
											<th>金额</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr['food'] as $key=>$val){
										if(!empty($val['cooktype'])){$foodname=$val['foodname']."(".$val['cooktype'].")";}else{$foodname=$val['foodname'];}
									?>
										<tr>
											<td><?php echo ++$key;?></td>

											<td><?php echo $foodname;?></td>

											<td><?php echo $val['foodprice'];?></td>
											
											<td><?php echo $val['foodamount'];?></td>
											<td><?php echo $val['foodprice']*$val['foodamount'];?></td>
										</tr>
										<?php }?>
										<?php if(!empty($shoparr['depositmoney'])){
											echo '<tr><td>押金</td><td colspan="3"></td><td  style="color:red">￥'.$shoparr['depositmoney'].'</td></tr>';
										}?>
										<tr><td>付款状态</td><td colspan="5" style="color:red"><?php if($arr['paystatus']=="paid"){echo "已付款";}else{echo "未付款";}?></td></tr>
										<tr><td>消费总额</td><td colspan="5" style="color:red"><?php echo "￥".$totalmoney;?></td></tr>
										<tr><td>优惠</td><td colspan="5" style="color:green"><span style="	color:green;"><?php echo "￥".$discountmoney." ";?></span>
										<?php if(!empty($prearr['ticketnum'])&&!empty($prearr['ticketval'])){echo $prearr['ticketname']."：￥".$prearr['ticketnum']*$prearr['ticketval']."，";}?>
										<?php if(!empty($prearr['discountval'])&&$prearr['discountval']<100){echo "折扣￥".$tdisaccountmoney."，";}?>
										<?php if(!empty($prearr['returndepositmoney'])){echo "退押金￥".$prearr['returndepositmoney']."，";}?>
										<?php if(!empty($prearr['clearmoney'])){echo "抹零￥".$prearr['clearmoney']."";}?>
										</td></tr>
										<tr><td>应付金额</td><td colspan="5" style="color:red;"><span style="font-size:24px;color:red;">￥<?php echo $shouldpay;?> </span></td></tr>
										
										<tr><td colspan="6" >
										<form action="./wappay/alipayapi.php" method="post" target="_blank" style="margin:0;padding:0">
										<!-- 商品订单号 -->
											<input type="hidden" name="WIDout_trade_no" value="<?php echo time().mt_rand(1000, 9999);?>" />
											<!-- 商品名称 -->
											<input type="hidden" name="WIDsubject"  value="<?php echo $arr['tabname']."账单"?>"/>
											<!-- 付款金额 -->
										 	<input type="hidden" name="WIDtotal_fee" value="<?php echo $shouldpay;?>"/> 
											<input type="hidden" name="WIDtotal_fee" value="0.01"/>
											<!-- 商品展示地址 -->
											<input type="hidden" name="WIDshow_url"  value="<?php echo $root_url. $_SERVER['PHP_SELF'];?>"/>
											<!-- 订单描述 -->
											<input type="hidden" name="WIDbody"  value="<?php echo $billid."|*|*|*|*|*|*|".$nickname;?>"/>
											<!-- 超时时间 -->
											<input type="hidden" name="WIDit_b_pay"  value="30m"/>
											<!-- 钱包token -->
											<input type="hidden" name="WIDextern_token"  value=""/>
											
											<button type="submit" class="btn blue btn-block">支付宝支付 <i class="m-icon-swapright m-icon-white"></i></button>
										</form>
										</td></tr> 
										<?php if($showvip){?>
										<tr><td colspan="6" ><button class="btn red btn-block" onclick="vippay( '<?php echo $uid;?>','<?php echo $shouldpay;?>','<?php echo $arr['shopid']?>','<?php echo $billid;?>','<?php echo $fooddisaccountmoney;?>','<?php echo $arr['deposit'];?>','<?php echo $nickname;?>')">会员卡支付 <i class="m-icon-swapright m-icon-white"></i></button></td></tr>
										<?php }?>
										<tr><td colspan="6" ><button class="btn green btn-block">微信支付 <i class="m-icon-swapright m-icon-white"></i></button></td></tr>
									</tbody>

								</table>

							</div>

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

			2014-2015 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>


	</div>

	<!-- END FOOTER -->

	<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>

	<script src="media/js/form-components.js"></script>  
	
</body>

<!-- END BODY -->

</html>	