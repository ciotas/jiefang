<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DayStock{
	public function getDayStockData($shopid, $theday,$thehour){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getDayStockData($shopid, $theday,$thehour);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
}
$daystock=new DayStock();
$title="库存自动盘点";
$menu="stock";
$clicktag="daystock";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}

$thehour=$daystock->getOpenHourByShopid($shopid);
$arr=$daystock->getDayStockData($shopid, $theday,$thehour);
// print_r($arr);exit;
?>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./daystock.php?theday="+theday;
}
//-->
</script>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
	<h3 class="page-title">
						库存自动盘点
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
					<div class="alert alert-error"> 
								<button class="close" data-dismiss="green"></button>
								<strong>此界面将在12月2日取消，请使用“添加、盘点库存”来做库存盘点</strong><br>
							</div>      
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid profile">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>库存自动盘点</div>
								<div class="tools">
								<form action="../interface/printautostock.php" method="post" style="margin: 0">
								<input type="hidden" name="theday" value='<?php echo $theday;?>'>
								<input type="hidden" name="data" value='<?php echo json_encode($arr);?>'>
								<button class="btn purple middle hidden-print" type="submit">小票打印 <i class="icon-print icon-big"></i></button>
								<a class="btn purple middle hidden-print" onclick="javascript:window.print();">A4打印 <i class="icon-print icon-big"></i></a>
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
											<th>图片</th>
											<th>品名</th>
										<!-- 	<th>包装率</th> -->
										<!-- 	<th>规格</th> -->
											<th>原库存</th>
											<th>今日入库</th>
											<th>今日消耗</th>
											<th>消耗金额</th>
											<!-- <th>现存明细</th> -->
											<th>现库存</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><img alt="" width="50" src="<?php echo $val['foodpic'];?>"></td>
											<td><?php echo $val['foodname'];?></td>
									<!-- 		<td><?php echo $val['packrate'];?></td> -->
									<!-- 		<td><?php echo $val['format'];?></td> -->
									<!-- 		<td><?php echo $val['totalpacknum'].$val['packunit'].$val['totalretailnum'].$val['foodunit'];?></td> -->
											<td><?php echo $val['totalstockamount'].$val['foodunit'];?></td>
											<td><?php echo $val['todayamount'].$val['foodunit']; ?></td>
											<td><?php echo $val['soldamount'].$val['foodunit'];?></td>
											<td><?php echo "￥".$val['soldamount']*$val['foodprice'];?></td>
									<!-- 		<td><?php echo $val['nowpacknum'].$val['packunit'].$val['nowretailnum'].$val['foodunit'];;?></td> -->
											<td><?php echo $val['nowstockamount'].$val['foodunit'];?></td>
											
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
require_once ('../footer.php');
?>