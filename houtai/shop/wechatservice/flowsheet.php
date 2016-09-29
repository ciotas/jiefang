<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FlowSheet{
	public function getDailySheetData($shopid, $starttime, $endtime,$type){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDailySheetData($shopid, $starttime, $endtime,$type);
	}
	public function getFuncRole($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFuncRole($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOneBillShopinfo($tab,$billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getOneBillShopinfo($tab, $billid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$flowsheet=new FlowSheet();
$openid=$_REQUEST['openid'];

$shopid=$flowsheet->getShopidByOpenid($openid);

$theday=$flowsheet->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}

$flowarr=$flowsheet->getDailySheetData($shopid, $theday, "","real");
// print_r($flowarr);exit;
$rolearr=$flowsheet->getFuncRole($shopid);
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./flowsheet.php?theday="+theday+"&cashierman=all";
}

function returnNum(billid,foodid,foodnum,cooktype,theday){
	var returnnum=prompt("请输入退菜量（只能为数字）","");
	if(isInteger(returnnum)){
		  if(returnnum){
			  if(returnnum<=0 || returnnum>foodnum){
				  alert("请输入折扣值在1~"+foodnum+"之间的正整数！");
			  }else{
				  	url="./interface/returnonefood.php?billid="+billid+"&foodid="+foodid+"&foodnum="+foodnum+"&cooktype="+cooktype+"&returnnum="+returnnum+"&theday="+theday+"&op=flowsheet";
					window.location.href=url;
			  }
		  }
	  }
}
function isInteger(number){
	return number > 0 && String(number).split('.')[1] == undefined
}
</script>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>我的订单</title>

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

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">

							流水单<small></small>

						</h3>

					</div>
				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid invoice">

					<div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="tab-pane active" id="tab_3">

									<div class="portlet box blue">

										<div class="portlet-title">

											<div class="caption"><i class="icon-reorder"></i>流水单</div>

									

										</div>

										<div class="portlet-body form">

										<td style="float: left">
										日期：<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
												<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
											</div>
									</td>
											<!-- BEGIN FORM-->

									<?php foreach ($flowarr as $key=>$val){?>
											<div class="form-horizontal form-view">

												<h3 class="form-section"><?php echo $val['nickname'];?></h3>

												<table class="table table-striped table-bordered table-advance table-hover">

									<thead>

										<tr>

											<th><i class="icon-briefcase"></i> 商品</th>

											<th class="hidden-phone"><i class="icon-user"></i> 价格</th>

											<th><i class="icon-shopping-cart"></i> 数量</th>

											<th>金额</th>
											<?php if($_SESSION['role']=="manager"){?>
											<th>退</th>
											<?php }?>
										</tr>

									</thead>

									<tbody>
							<?php 
								$onebillmoney=0;
								foreach ($val['food']  as $fkey=>$fval){
								$onebillmoney+=$fval['foodnum']*$fval['foodprice'];
								?>
										<tr>

											<td class="highlight">

												<div class="success"></div>

												&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fval['foodname'];?>

											</td>

											<td class="hidden-phone">￥<?php echo $fval['foodprice'];?></td>

											<td><?php echo $fval['foodnum']."&nbsp;&nbsp;&nbsp;".$fval['orderunit'];?></td>

											<td>￥<?php echo $fval['foodprice']*$fval['foodnum'];?></td>

											<?php if($_SESSION['role']=="manager"){?>
											<td><button onclick="returnNum('<?php echo $val["billid"];?>','<?php echo $fval["foodid"];?>','<?php echo $fval["foodnum"];?>',''<?php echo $fval["cooktype"];?>,'<?php echo $theday;?>')" class="btn mini red"><i class="icon-trash"></i> 退菜</button></td>
										<?php }?>
										</tr>

										<?php }?>

										<?php 
										$shopinfo=$flowsheet->getOneBillShopinfo("billshopinfo", $val['billid']);
										if(!empty($shopinfo)){
										?>
											<tr>
											<td class="highlight" >
												<div class="warning"></div>&nbsp;&nbsp;&nbsp;
											<span style="color:red;">收货地址：<?php echo $shopinfo['prov'].$shopinfo['city'].$shopinfo['road'].$shopinfo['road'];?></span></td>
											<td><span style="color:red;">店名：<?php echo $shopinfo['shopname'];?></span></td>
											<td><span style="color:red;">联系人：<?php echo $shopinfo['contact'];?></span></td>
											<td><span style="color:red;">联系方式：<?php echo $shopinfo['phone'];?></span></td>
											<td><span style="color:red;">配送时间：<?php echo $shopinfo['picktime'];?></span></td>
											
											</tr>
											<tr>
										<?php }?>
											<td class="highlight" >

												<div class="warning"></div>

												&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;font-size:16px;">总价：￥<?php echo $onebillmoney;?></span>

											</td>

											<td><span style="color:red;">备注：<?php echo $val['orderrequest'];?></span></td>

											<td>时间：<?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>

											<td></td>
										<?php if($_SESSION['role']=="manager"){?>
											<td><a href="./interface/delonebill.php?billid=<?php echo $val['billid'];?>&theday=<?php echo $theday;?>&op=flowsheet" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i>删除此单 </a></td>
										<?php }?>
										</tr>

									</tbody>
								</table>
											</div>
						<?php }?>
											<!-- END FORM-->  

										</div>

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