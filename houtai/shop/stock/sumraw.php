<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SumRaw{
	public function getSumrawData($shopid, $startdate, $endate){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getSumrawData($shopid, $startdate, $endate);
	}
}
$sumraw=new SumRaw();
$title="入库汇总";
$menu="stock";
$clicktag="sumraw";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
$startdate=date("Y-m-d",time());
$enddate=$startdate;
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$arr=$sumraw->getSumrawData($shopid, $startdate, $enddate);
// print_r($arr);exit;
$trawcalcmoney=0;
$trawpaymoney=0;
foreach ($arr as $key=>$val){
	$trawcalcmoney+=sprintf("%.0f",($val['rawamount']+$val['rawtinyamount']/$val['rawpackrate'])*$val['rawprice']);
	$trawpaymoney+=$val['rawpaymoney'];
}
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
	<h3 class="page-title">
						入库汇总<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>入库汇总 <?php if(!empty($trawpaymoney)){echo '【计算金额：￥'.$trawcalcmoney.' ， 实付金额：￥'.$trawpaymoney.'】';}?></div>
								<div class="tools">
								<form action="../interface/printcalcraw.php" method="post"  style="margin: 0;padding:0">
								<input type="hidden" name="startdate" value="<?php echo $startdate;?>" >
								<input type="hidden" name="enddate" value="<?php echo $enddate;?>" >
								<input type="hidden" name="data" value='<?php echo json_encode($arr);?>'>
								<button  type="submit"  class="btn green middle hidden-print" >小票打印 <i class="icon-print icon-big"></i></button>
								<a class="btn purple middle hidden-print" onclick="javascript:window.print();">A4打印 <i class="icon-print icon-big"></i></a>
								</form>
								</div>
							</div>
							<div class="portlet-body">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
							<table style="margin: 0">
							<tr>
							<td>
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" size="16" type="text"  name="startdate" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
							</td>
							<td>
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" size="16"  name="enddate" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div></td>
							<td><button type="submit" class="btn purple">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
							</tr>
							
							</table>
							</form>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>图片</th>
											<th>原料名称</th>
											<th>规格</th>
											<th>进货量</th>
											<th>计算金额</th>
											<th>实付金额</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><img alt="" width="50" src="<?php echo $val['rawpic'];?>"></td>
											<td><?php echo $val['rawname'];?></td>
											<td><?php echo $val['rawformat'];?></td>
											<td><?php if($val['rawpackrate']=="1"){ echo ($val['rawamount']+$val['rawtinyamount']/$val['rawpackrate']).$val['rawunit'];}else{echo $val['rawamount'].$val['rawunit'].$val['rawtinyamount'].$val['rawtinyunit'];}?></td>
											<td><?php echo sprintf("%.1f",($val['rawamount']+$val['rawtinyamount']/$val['rawpackrate'])*$val['rawprice']);?></td>
											<td><?php echo $val['rawpaymoney']?></td>
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