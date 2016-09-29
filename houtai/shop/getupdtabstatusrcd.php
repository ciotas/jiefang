<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetUpdtabStatusRcd{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getUpdatestatusRecord($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getUpdatestatusRecord($shopid, $theday);
	}
}
$getupdtabstatusrcd=new GetUpdtabStatusRcd();
$title="清台表";
$menu="business";
$shopid=$_SESSION['shopid'];
$theday=$getupdtabstatusrcd->getTheday($shopid);
$clicktag="getupdtabstatusrcd";
require_once ('header.php');
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$getupdtabstatusrcd->getUpdatestatusRecord($shopid, $theday);
?>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./getupdtabstatusrcd.php?theday="+theday;
}
</script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							清台表 <small> </small>
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
							<li><a href="getupdtabstatusrcd.php">清台表</a></li>
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
								<div class="caption"><i class="icon-credit-card"></i>清台表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
									<thead>
										<tr>
											<th>#</th>
											<th>桌台</th>
											<th>状态</th>
											<th>时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										switch ($val['tabstatus']){
											case "empty":$tabstatus='<span class="label label-success">清台</span>'; break;
											case "start": $tabstatus='<span class="label label-error">开台</span>';  break;
											case "book": $tabstatus='<span class="label label-info">预定</span>'; break;
											case "online": $tabstatus='<span class="label label-warning">占用</span>'; break;
										}
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['tabname'];?></td>
											<td><?php echo $tabstatus ;?></td>
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
