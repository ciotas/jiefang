<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TakeOutSheet{
	public function getTakeoutsheetData($shopid,$theday,$openhour,$op){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getTakeoutsheetData($shopid, $theday,$openhour,$op);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
}
$takeoutsheet=new TakeOutSheet();
$title="提前点餐订单";
$menu="table";
$clicktag="takeoutsheet";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=$takeoutsheet->getTheday($shopid);
$openhour=$takeoutsheet->getOpenHourByShopid($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$unsurearr=$takeoutsheet->getTakeoutsheetData($shopid, $theday,$openhour, "unsure");
$surearr=$takeoutsheet->getTakeoutsheetData($shopid, $theday, $openhour,"sure");
$invalidarr=$takeoutsheet->getTakeoutsheetData($shopid, $theday, $openhour,"invalid");
?>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./takeoutsheet.php?theday="+theday;
}
</script>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							提前点餐订单
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
<!-- 				<div class="alert alert-error"> -->
<!-- 					<button class="close" data-dismiss="green"></button> -->
<!-- 					<strong>系统已升级！如看不见“收银”按钮，请注销后重新登陆，如依然不可见，请及时联系我们~</strong><br> -->
<!-- 				</div> -->

				<div class="control-group pull-left margin-right-20">
								<div class="controls">
									<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</div>
							</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<div class="tabbable tabbable-custom boxless">
							<ul class="nav nav-tabs">
							
								<li class="active"><a href="#tab_unsure" data-toggle="tab"><span style="font-size:18px; ">未确认</span></a></li>
								<li class=""><a href="#tab_sure" data-toggle="tab"><span style="font-size:18px; ">已确认</span></a></li>
								<li class=""><a href="#tab_invalid" data-toggle="tab"><span style="font-size:18px; ">无效订单</span></a></li>
							</ul>
							<div class="tab-content">
							<div class="tab-pane active" id="tab_unsure">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>未确认订单</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>客户</th>
											<th>付款金额</th>
											<th>下单时间</th>
											<th>电话</th>
											<!-- <th>地址</th> -->
											<th>备注</th>
											<th>明细</th>
											<th width="140">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($unsurearr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['nickname'];?></td>
											<td>￥<?php echo $val['alipay'];?></td>
											<td><?php echo date("H:i",$val['timestamp']);?></td>
											<td><?php echo $val['takeoutphone'];?></td>
										<!-- 	<td><?php echo $val['takeoutaddress'];?></td>-->
											<td><?php echo $val['orderrequest'];?></td>
											
											<td>
											<table>
											<?php foreach ($val['food'] as $fkey=>$fval){?>
											<tr>
												<td><?php echo $fval['foodname'];?></td>
												<td><?php echo $fval['foodamount'].$fval['foodunit'];?></td>
												<td>￥<?php echo $fval['foodamount']*$fval['foodprice'];?></td>
											</tr>
											<?php }?>
											</table>
											</td>
											<td><a href="./interface/updatetakeoutstatus.php?billid=<?php echo $val['billid'];?>&op=sure&theday=<?php echo $theday;?>" class="btn mini green"  onclick="return confirm('确认此订单？');">确认</a>
												<a href="./interface/updatetakeoutstatus.php?billid=<?php echo $val['billid'];?>&op=invalid&theday=<?php echo $theday;?>" class="btn mini red"  onclick="return confirm('确定为无效订单？');">无效</a>
												<a href="./interface/printtakeout.php?billid=<?php echo $val['billid'];?>&theday=<?php echo $theday;?>" class="btn mini blue">打印</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
									</div>
									
									
									
					<div class="tab-pane" id="tab_sure">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>已确认订单</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>客户</th>
											<th>付款金额</th>
											<th>下单时间</th>
											<th>电话</th>
										<!-- 	<th>地址</th> -->
											<th>备注</th>
											<th>明细</th>
											<th width="100">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($surearr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['nickname'];?></td>
											<td>￥<?php echo $val['alipay'];?></td>
											<td><?php echo date("H:i",$val['timestamp']);?></td>
											<td><?php echo $val['takeoutphone'];?></td>
										<!-- 	<td><?php echo $val['takeoutaddress'];?></td> -->
											<td><?php echo $val['orderrequest'];?></td>
											
											<td>
											<table>
											<?php foreach ($val['food'] as $fkey=>$fval){?>
											<tr>
												<td><?php echo $fval['foodname'];?></td>
												<td><?php echo $fval['foodamount'].$fval['foodunit'];?></td>
												<td>￥<?php echo $fval['foodamount']*$fval['foodprice'];?></td>
											</tr>
											<?php }?>
											</table>
											</td>
											<td>
											<a href="./interface/updatetakeoutstatus.php?billid=<?php echo $val['billid'];?>&op=invalid&theday=<?php echo $theday;?>" class="btn mini red"  onclick="return confirm('确定为无效订单？');">无效</a>
											<a href="./interface/printtakeout.php?billid=<?php echo $val['billid'];?>&theday=<?php echo $theday;?>"  class="btn mini blue">打印</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
									</div>
									
									<div class="tab-pane" id="tab_invalid">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>无效订单</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>客户</th>
											<th>付款金额</th>
											<th>下单时间</th>
											<th>电话</th>
										<!-- 	<th>地址</th> -->
											<th>备注</th>
											<th>明细</th>
											<th width="100">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($invalidarr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['nickname'];?></td>
											<td>￥<?php echo $val['alipay'];?></td>
											<td><?php echo date("H:i",$val['timestamp']);?></td>
											<td><?php echo $val['takeoutphone'];?></td>
									<!-- 		<td><?php echo $val['takeoutaddress'];?></td> -->
											<td><?php echo $val['orderrequest'];?></td>
											
											<td>
											<table>
											<?php foreach ($val['food'] as $fkey=>$fval){?>
											<tr>
												<td><?php echo $fval['foodname'];?></td>
												<td><?php echo $fval['foodamount'].$fval['foodunit'];?></td>
												<td>￥<?php echo $fval['foodamount']*$fval['foodprice'];?></td>
											</tr>
											<?php }?>
											</table>
											</td>
											<td>
											<a href="./interface/updatetakeoutstatus.php?billid=<?php echo $val['billid'];?>&op=sure&theday=<?php echo $theday;?>" class="btn mini green"  onclick="return confirm('确认此订单？');">确认</a>
											<a href="./interface/printtakeout.php?billid=<?php echo $val['billid'];?>&theday=<?php echo $theday;?>"  class="btn mini blue">打印</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
									</div>
									
								</div>
							</div>
					</div>
					</div>
				</div>
				
			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->
<?php 
require_once ('footer.php');
?>

