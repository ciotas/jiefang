<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddFoodRecord{
	public function getAddFoodRecordData($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getAddFoodRecordData($shopid, $theday);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
}
$addfoodrecord=new AddFoodRecord();
$title="加菜表";
$menu="business";
$clicktag="addfoodrecord";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=$addfoodrecord->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$addfoodrecord->getAddFoodRecordData($shopid, $theday);
// print_r($arr);exit;
?>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
// 	alert(theday)
	window.location.href="./addfoodrecord.php?theday="+theday;
}
</script>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							加菜表 <small> </small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">设置</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="addfoodrecord.php">加菜表</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>加菜表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							<div class="control-group pull-left margin-right-20">
								<div class="controls">
									<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</div>
							</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>台号</th>
											<th>下单人</th>
											<th>加菜时间</th>
											<th>加菜详细</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										?>
										<tr>
											<td><?php ++$key;?></td>
											<td><?php echo $val['tabname'];?></td>
											<td><?php echo $val['nickname'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>
											<td>
											<table style="border:0;padding:0;">
											<tr>
												<th>名称</th>
												<th>数量</th>
												<th>价格</th>
												<th>单位</th>
												<th>金额</th>
											</tr>
											<?php foreach ($val['food'] as $fkey=>$fval){?>
											<tr>
												<td><?php echo $fval['foodname'];?></td>
												<td><?php echo $fval['foodamount'];?></td>
												<td><?php echo $fval['foodprice'];?></td>
												<td><?php echo $fval['foodunit'];?></td>
												<td><?php echo $fval['foodprice']*$fval['foodamount'];?></td>
											</tr>
											<?php }?>
											</table>
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