<?php 
require_once ('startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddAutoStockFoodRecord{
	public function getChangeStockRecord($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getChangeStockRecord($shopid, $theday);
	}
}
$addautostockfoodrecord=new AddAutoStockFoodRecord();
$title="新增库存记录";
$menu="stock";
$clicktag="addautostockfoodrecord";
$shopid=$_GET['shopid'];
require_once ('header.php');
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
// echo $theday;exit;
$arr=$addautostockfoodrecord->getChangeStockRecord($shopid,$theday);
// print_r($arr);exit;
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./addautostockfoodrecord.php?shopid=<?php echo $shopid?>&theday="+theday;
}
//-->
</script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						新增库存记录<small> </small>
						</h3>					
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>新增库存记录</div>
								<div class="tools">
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
											<th>美食名</th>
											<th>新增量</th>
											<th>新增时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><img alt="" width="50" src="<?php echo $val['foodpic'];?>"></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['num'].$val['foodunit'];?></td>
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