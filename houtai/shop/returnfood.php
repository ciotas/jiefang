<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ReturnFood{
	public function getReturnFoodData($shopid, $theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getReturnFoodData($shopid, $theday);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
}
$returnfood=new ReturnFood();
$title="退菜表";
$menu="business";
$clicktag="returnfood";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=$returnfood->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$returnfood->getReturnFoodData($shopid, $theday);
?>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
// 	alert(theday)
	window.location.href="./returnfood.php?theday="+theday;
}
</script>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							退菜表 <small></small>
						</h3>
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
								<div class="caption"><i class="icon-credit-card"></i>退菜表</div>
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
											<th>退菜量</th>
											<th>单位</th>
											<th>退菜人</th>
											<th>下单时间</th>
											<th>退菜时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['tabname'];?></td>
											<td><?php echo $val['returnnum'];?></td>
											<td><?php echo $val['orderunit'];?></td>
											<td><?php echo $val['nickname'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['returntime']);?></td>
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