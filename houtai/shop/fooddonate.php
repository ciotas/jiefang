<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodDonate{
	public function getFoodDonateData($shopid, $theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodDonateData($shopid, $theday);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
}
$fooddonate=new FoodDonate();
$title="赠送表";
$menu="business";
$clicktag="fooddonate";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=$fooddonate->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$fooddonate->getFoodDonateData($shopid, $theday);
$donatetotal=0;
foreach ($arr as $key=>$val){
	$donatetotal+=$val['foodamount']*$val['foodprice'];
}
?>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
// 	alert(theday)
	window.location.href="./fooddonate.php?theday="+theday;
}
</script>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							赠送表 <small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">营业表</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="returnfood.php">赠送表</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i> 赠送表 【赠送总额：￥<?php echo $donatetotal;?>】</div>
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
											<th>名称</th>
											<th>所在台号</th>
											<th>赠送量</th>
											<th>价格</th>
											<th>单位</th>
											<th>金额</th>
											<th>下单时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){ ?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['tabname'];?></td>
											<td><?php echo $val['foodamount'];?></td>
											<td><?php echo $val['foodprice'];?></td>
											<td><?php echo $val['foodunit'];?></td>
											<td><?php echo $val['foodamount']*$val['foodprice'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>
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