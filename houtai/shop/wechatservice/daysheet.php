<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DaySheet{
	public function getDaySheetData($shopid, $theday,$cashierman){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDaySheetData($shopid, $theday,$cashierman);
	}
	public function getSeversByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getSeversByShopid($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getShopServers($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopServers($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$daysheet=new DaySheet();

$openid=$_REQUEST['openid'];
$shopid=$daysheet->getShopidByOpenid($openid);
$theday=$daysheet->getTheday($shopid);
$cashiermanarr=$daysheet->getShopServers($shopid);
    // var_dump($cashiermanarr);exit;
$cashierman="all";
if(isset($_GET['theday'])){
    $theday=$_GET['theday'];
    $cashierman=$_GET['cashierman'];
}
$dayarr=$daysheet->getDaySheetData($shopid, $theday,$cashierman);

// $shopid="572987a65bc109eb298b470f";
?>
<script>
function getnewdata(){
	openid=document.getElementById("openid").value;
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./daysheet.php?openid="+openid+"&theday="+theday+"&cashierman=all";
}

function getTheCashierman(){
	openid=document.getElementById("openid").value;
	theday=document.getElementById("daydatepicker").value;
	thecashinerman=document.getElementById("cashierman").value;
	window.location.href="./daysheet.php?openid="+openid+"&theday="+theday+"&cashierman="+thecashinerman;
}
</script>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>日汇总</title>

	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="../media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style.css" rel="stylesheet" type="text/css"/>
	
	<link href="../media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>


	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="../media/css/timeline.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->


</head>

<!-- END HEAD -->
<body>
<div class="page-container row-fluid">
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">       
				<div class="row-fluid invoice">

					<div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-reorder"></i>单日汇总表 <?php if($cashierman!="all" && !empty($dayarr['switchtime'])){echo "【交接班时间：".date("Y-m-d H:i:s",$dayarr['switchtime'])."】";}?></div>

								<div class="tools">
							<!-- 	<form action="./interface/printcalc.php" method="post"  style="margin: 0;padding:0">
								<input type="hidden" name="theday" value="<?php echo $theday;?>" >
								<input type="hidden" name="data" value='<?php echo json_encode($dayarr);?>'>
								<input type="hidden" name="cashierman" value='<?php echo $cashierman;?>'>
								<button  type="submit"  class="btn green middle hidden-print" >小票打印 <i class="icon-print icon-big"></i></button>
								</form>		 -->				
								</div>
							</div>
							<input type="hidden" id="openid" value="<?php echo $openid;?>">
							<div class="portlet-body">
							<table style="margin:0">
								<tr>
								<td style="float: left">
									<input type="date"  style="height:35px;" onchange="getnewdata()" id="daydatepicker" value="<?php echo $theday;?>" >
								</td>
								<td style="float: left">
									<label class="help-inline">收银员：</label>
										<select class="medium m-wrap" tabindex="1" id="cashierman" onchange="getTheCashierman();">
											<option value="all">---全部---</option>
											<?php foreach ($cashiermanarr as $onecashier){?>
											<option value="<?php echo $onecashier['serverid'];?>" <?php if($cashierman==$onecashier['serverid']){echo "selected";}?>><?php echo $onecashier['servername'];?></option>
											<?php }?>
											<option value="notpay" style="color:red" <?php if($cashierman=="notpay"){echo "selected";}?>>待收银</option>
											<option value="boss" style="color:red" <?php if($cashierman=="boss"){echo "selected";}?>>老板收银</option>
											<option value="customer" style="color:red" <?php if($cashierman=="customer"){echo "selected";}?>>线上支付</option>
										</select>
								</td>
								</tr>
								</table>
								<table class="table"  style="margin:0">
									<tr>
											<td><?php if(!empty($dayarr)){echo "销售总额："."￥".$dayarr['totalmoney'];}?></td>
											<td>
												<?php if($cashierman=="notpay"){?>
												<?php if(!empty($dayarr['cash_unpay'])){echo " 现金：".$dayarr['cash_unpay'];}?>
													<?php if(!empty($dayarr['mt_unpay'])){echo " 美团：".$dayarr['mt_unpay'];}?>
													<?php if(!empty($dayarr['dz_unpay'])){echo " 大众：".$dayarr['dz_unpay'];}?>
													<?php if(!empty($dayarr['nm_unpay'])){echo " 糯米：".$dayarr['nm_unpay'];}?>
													<?php if(!empty($dayarr['depositmoney'])){echo " 押金：".$dayarr['depositmoney'];}?>
													<?php if(!empty($dayarr['the100minus5'])){echo " 美团立减：".$dayarr['the100minus5'];}?>
												<?php }?>
											</td>
										</tr>
										<tr>
											<td>人数</td>
											<td>
												<?php if(!empty($dayarr)){echo $dayarr['cusnum'];}?>人
											</td>
										</tr>
										<tr>
											<td>下单数</td>
											<td>
												<?php if(!empty($dayarr)){echo $dayarr['billnum'];}?>单
											</td>
										</tr>

										<tr>
											<td>翻台率</td>
											<td>
												<?php if(!empty($dayarr)){echo $dayarr['changerate'];}?>
											</td>
										</tr>
										<tr>
											<td>人均消费</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['avgmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>应收款</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['receivablemoney'];}?>
											</td>
										</tr>
										<tr>
											<td>现金</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['cashmoney'];}?>
											</td>
										</tr>
										
										<tr>
											<td>银联卡</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['unionmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>会员卡</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['vipmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>美团账户</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['meituanpay'];}?>
											</td>
										</tr>
										<tr>
											<td>大众账户</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['dazhongpay'];}?>
											</td>
										</tr>
										<tr>
											<td>糯米账户</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['nuomipay'];}?>
											</td>
										</tr>
											<tr>
											<td>其他</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['otherpay'];}?>
											</td>
										</tr>
										<tr>
											<td>支付宝</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['alipay'];}?>
											</td>
										</tr>
										<tr>
											<td>微信支付</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['wechatpay'];}?>
											</td>
										</tr>
										<!-- <tr>
											<td>其他收入</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['othermoney'];}?>
											</td>
										</tr> -->
										<?php foreach ($dayarr['ticket'] as $ticketway=>$ticketval){?>
										<tr>
											<td><?php echo $ticketval['ticketname'];?></td>
											<td>
												￥<?php echo $ticketval['ticketmoney'];?>
											</td>
										</tr>
										<?php }?>
										<tr>
											<td>折扣额</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['discountmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>抹零</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['clearmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>签单</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['signmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>免单</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['freemoney'];}?>
											</td>
										</tr>
										<tr>
											<td>收押金</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['depositmoney'];}?>
											</td>
										</tr>
										<tr>
											<td>退押金</td>
											<td>
												￥<?php if(!empty($dayarr)){echo $dayarr['returndepositmoney'];}?>
											</td>
										</tr>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->
					</div>

				</div>

				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER--> 
		</div>
		<!-- END PAGE -->
	</div>

	<!-- END CONTAINER -->

	<?php 
	require_once ('../footer.php');
	?>