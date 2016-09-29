<?php 
require_once ('startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Four{
	public function getServerBill($server,$datestart,$dateover,$uid=NULL){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getServerBill($server, $datestart, $dateover,$uid);
	}
	public function getServers($shopid){
	    return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getServers($shopid);
	}
	
}
$Four = new Four();
$title="销售报表";
$menu="stock";
$clicktag="serverreport";
$shopid=$_GET['shopid'];
//$shopid = "554ad9615bc109d8518b45d2";
isset($_GET['datestart']) ? $datestart=date('Y-m-d',strtotime($_GET['datestart'])): $datestart = date('Y-m-01', strtotime($_GET['datestart']. ' -1 month'));
isset($_GET['dateover']) ? $dateover=date('Y-m-d',strtotime($_GET['dateover'])): $dateover = date('Y-m-d');
isset($_GET['man']) ? $man=$_GET['man'] : $man = NULL;
$arr = $Four->getServerBill($shopid, $datestart, $dateover,$man);
$servers = $Four->getServers($shopid);
require_once ('header.php');


?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker1").value;
	theday1=document.getElementById("daydatepicker").value;

	window.location.href="./serverreport.php?datestart="+theday+"&dateover="+theday1;
}
//-->
</script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						销售记录表<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>销售记录表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							<a href="./serverreportExcel.php?shopid=<?php echo $shopid;?>datestart=<?php echo $datestart?>&dateover=<?php echo $dateover;?>&man=<?php echo $man;?>">生成excel</a>
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<form action="" method="get">
										<input type="hidden" name="shopid" value="<?php echo $shopid; ?>" />
										<span>起始日期</span><input name="datestart" class="m-wrap m-ctrl-medium Wdate" id="daydatepicker1"  onClick="WdatePicker()" size="16" type="text" value="<?php echo $datestart;?>"><span class="add-on"><i class="icon-calendar"></i></span>
										<span>结束日期</span><input name="dateover" class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $dateover;?>"><span class="add-on"><i class="icon-calendar"></i></span>
										<span>业务员</span><select name = "man">
											<option value="" >未选择</option>
											<?php 
											     foreach ($servers as $man=>$name)
											     {
											         if(empty($man)){ continue;}
											         ?>
											 <option <?php if($man==$_GET['man']){echo "selected='selected'"; }?> value="<?php echo $man; ?>"  ><?php echo $name;?></option>        
											         <?php 
											     }
											?>
										</select>
										<input type="submit" value="搜索" />
										</form>
									</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>区域</th>
											<th>店铺名称</th>
											<th>地址</th>
											<?php  foreach ($arr as $key=>$val){
											
											 foreach ($val['food'] as $v){
											     echo "<th>".$v['foodname']."</th>";
											 }
											break;
										
									 } ?>
									 		<th>已缴款</th>
											<th>负责人</th>
										</tr>
									</thead>
									<tbody>
									<?php  foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo $val['dist'] ?></td>
											<td><?php echo $key;?></td>
											<td><?php echo $val['road']; ?></td>
											
											<?php 
											 foreach ($val['food'] as $v){
											     echo "<td>".$v['foodnum']."</td>";
											 }
											?>
											<td><?php echo $val['money'];?></td>
											<td><?php echo implode("/", $val['server']);?></td>
										</tr>
										<?php } ?>
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