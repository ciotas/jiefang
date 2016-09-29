<?php 
require_once ('startsession.php');
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once ('/var/www/html/boss/global.php');

require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Outgoing{
	public function getOutgoing($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOutgoing($shopid,$theday);
	}
}
require_once ('header.php');
$outgoing = new Outgoing();
$title="出库表";
$menu="stock";
$clicktag="outgoing";
$shopid=$_GET['shopid'];

$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
    $theday=$_GET['theday'];
}
// echo $theday;exit;
$arr=$outgoing->getOutgoing($shopid,$theday);


// print_r($arr);exit;
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./1outgoing.php?shopid=<?php echo $shopid; ?>&theday="+theday;
}
//-->
</script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						月度出库表<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>月度出库表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							<a href="./1outgoingExcel.php?shopid=<?php echo $shopid; if(isset($_GET['theday'])){echo "&theday=".$theday;}?>">生成excel</a>
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>商品名称</th>
											<th>月初库存</th>
											
											<?php for($i=1;$i<=$arr['day'];$i++){ ?>
											<th><?php echo $i; ?></th>
											<?php }?>
											<th>出库合计</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr['food'] as $key=>$val){?>
										<tr>
											<td><?php echo $val['name'];?></td>
											<td><?php echo $val['nownum']?></td>
											
											<?php 
											 foreach ($val['sale'] as $v){
											     echo "<td>".$v."</td>";
											 }
											?>
											<td><?php echo $val['salenum'];?></td>
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