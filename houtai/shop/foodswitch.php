<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodSwitch{
	public function getFoodSwitchRecord($shopid, $theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodSwitchRecord($shopid, $theday);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
}
$foodswitch=new FoodSwitch();
$title="转菜表";
$menu="business";
$clicktag="foodswitch";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=$foodswitch->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$foodswitch->getFoodSwitchRecord($shopid, $theday);
?>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
// 	alert(theday)
	window.location.href="./foodswitch.php?theday="+theday;
}
</script>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							转菜表 <small></small>
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
							<li><a href="foodswitch.php">转菜表</a></li>
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
								<div class="caption"><i class="icon-credit-card"></i> 转菜表 </div>
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
											<th>所在原台号</th>
											<th>所在新台号</th>
											<th>转菜量</th>
											<th>下单时间</th>
											<th>转菜时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){ ?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['oldtabname'];?></td>
											<td><?php echo $val['newtabname'];?></td>
											<td><?php echo $val['foodamount'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['addtime']);?></td>
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