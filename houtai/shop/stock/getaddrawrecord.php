<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetAddRawRecord{
	public function getRawRecordData($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawRecordData($shopid, $theday);
	}
}
$getaddrawrecord=new GetAddRawRecord();
$title="入库记录";
$menu="stock";
$clicktag="getaddrawrecord";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$getaddrawrecord->getRawRecordData($shopid, $theday);
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./getaddrawrecord.php?theday="+theday;
}
//-->
</script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						入库记录<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>入库记录</div>
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
											<th>名称</th>
											<th>入库价格</th>
											<th>入库数量</th>
											<th>计算金额</th>
											<th>实付金额</th>
											<th>操作人</th>
											<th>入库时间</th>										
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><img alt="" width="50" src="<?php echo $val['rawpic'];?>"></td>
											<td><?php echo $val['rawname'];?></td>
											<td><?php echo $val['rawprice'];?></td>
											<td><?php if($val['rawpackrate']=="1"){ echo ($val['rawamount']+$val['rawtinyamount']/$val['rawpackrate']).$val['rawunit'];}else{echo $val['rawamount'].$val['rawunit'].$val['rawtinyamount'].$val['rawtinyunit'];}?></td>
											<td><?php echo "￥".($val['rawamount']*$val['rawpackrate']+$val['rawtinyamount'])*$val['rawprice'];?></td>
											<td><?php echo "￥".$val['rawpaymoney'];?></td>
											<td><?php echo $val['manager'];?></td>
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
require_once ('../footer.php');
?>