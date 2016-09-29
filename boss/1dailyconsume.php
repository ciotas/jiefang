<?php 
require_once ('startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DailyConsume{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getConsumeListData($shopid, $theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getConsumeListData($shopid, $theday);
	}
}
$dailyconsume=new DailyConsume();
$title="日消耗表";
$menu="stock";
$clicktag="dailyconsume";
$shopid=$_GET['shopid'];
// echo $shopid;exit;
$i=1;
require_once ('header.php');
$theday=$dailyconsume->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$dailyconsume->getConsumeListData($shopid, $theday);
$consumemoney=0;
foreach ($arr as $key=>$val){
	$consumemoney+=$val['foodprice']*$val['foodamount'];
}

?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./dailyconsume.php?shopid=<?php echo $shopid;?>&theday="+theday;
}
//-->
</script>
	<h3 class="page-title">
						日消耗表<small> </small>
						</h3>
						
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
								<div class="caption"><i class="icon-credit-card"></i>日消耗表</div>
								<div class="tools">
								<form method="post" action="../interface/printconsmefood.php" style="margin: 0">
									<input type="hidden" name="theday" value="<?php echo $theday;?>">
									<input type="hidden" name="consumemoney" value="<?php echo $consumemoney;?>">
									<input type="hidden" name="data" value='<?php echo json_encode($arr);?>'>
									<button class="btn green middle hidden-print" type="submit">小票打印 <i class="icon-print icon-big"></i></button>
									</form>								
								</div>
							</div>
							<div class="portlet-body">
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>美食名</th>
											<th>售出份数</th>
											<th>价格</th>
											<th>总额</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $foodid=>$val){?>
										<tr>
											<td><?php echo $i++;?></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['foodamount'].$val['foodunit'];?></td>
											<td><?php echo $val['foodprice'];?></td>
											<td><?php echo "￥".$val['foodamount']*$val['foodprice'] ;?></td>
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