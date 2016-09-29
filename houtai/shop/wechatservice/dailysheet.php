<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');

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
$title="流水单";
$menu="datasheet";
$clicktag="flowsheet";
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
<script>
function getnewdata(){
	openid=document.getElementById("openid").value;
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./dailysheet.php?openid="+openid+"&theday="+theday;
}

function returnNum(billid,foodid,foodnum,cooktype,theday){
	var returnnum=prompt("请输入退菜量（只能为数字）","");
	openid=document.getElementById("openid").value;
	if(isInteger(returnnum)){
		  if(returnnum){
			  if(returnnum<=0 || returnnum>foodnum){
				  alert("请输入折扣值在1~"+foodnum+"之间的正整数！");
			  }else{
				  	url="../interface/returnonefood.php?billid="+billid+"&openid="+openid+"&from=wechatservice&foodid="+foodid+"&foodnum="+foodnum+"&cooktype="+cooktype+"&returnnum="+returnnum+"&theday="+theday+"&op=dailysheet";
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

	<title>流水单</title>

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
						<div class="tab-pane active" id="tab_3">
									<div class="portlet box blue">
										<div class="portlet-title">
											<div class="caption"><i class="icon-reorder"></i>流水单</div>
										</div>
										<div class="portlet-body form">
											<input type="hidden" id="openid" value="<?php echo $openid;?>">
										<td style="float: left">
										<input type="date"  style="height:35px;" onchange="getnewdata()" id="daydatepicker" value="<?php echo $theday;?>" >
									</td>

									<?php foreach ($flowarr as $key=>$val){?>
											<div class="form-horizontal form-view">

												<h3 class="form-section"><?php echo $val['nickname'];?></h3>

												<table class="table table-striped table-bordered table-advance table-hover">

									<thead>

										<tr>

											<th>商品</th>

											<th> 价格</th>

											<th> 数量</th>

											<th>金额</th>
											<th>退</th>
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

												&nbsp;<?php echo $fval['foodname'];?>

											</td>

											<td>￥<?php echo $fval['foodprice'];?></td>

											<td><?php echo $fval['foodnum']."&nbsp;&nbsp;".$fval['orderunit'];?></td>

											<td>￥<?php echo $fval['foodprice']*$fval['foodnum'];?></td>

											<td><button onclick="returnNum('<?php echo $val['billid'];?>','<?php echo $fval['foodid'];?>','<?php echo $fval['foodnum'];?>',''<?php echo $fval['cooktype'];?>,'<?php echo $theday;?>')" class="btn mini red"><i class="icon-trash"></i> 退</button></td>
										</tr>

										<?php }?>

										<?php 
										$shopinfo=$flowsheet->getOneBillShopinfo("billshopinfo", $val['billid']);
										if(!empty($shopinfo)){
										?>
											<tr>
										<?php }?>
											<td class="highlight" >
												<div class="warning"></div>
												&nbsp;<span style="color:red;font-size:16px;">总价：</span></td>
											<td style="color:red;font-size:16px;">￥<?php echo $onebillmoney;?></td>

											<td>时间：</td>

											<td><?php echo date("m-d H:i",$val['timestamp']);?></td>
											<!--<td><a href="../interface/delonebill.php?billid=<?php echo $val['billid'];?>&theday=<?php echo $theday;?>&op=dailysheet&openid=<?php echo $openid;?>&from=wechatservice" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i>删</a></td>-->
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
	require_once ('footer.php');
	?>