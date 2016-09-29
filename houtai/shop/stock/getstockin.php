<?php 

require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Outgoing{
	public function getOutgoing($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getStockin($shopid,$theday);
	}
}
$outgoing = new Outgoing();
$title="入库表";
$menu="stock";
$clicktag="getstockin";
$shopid=$_SESSION['shopid'];
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
    $theday=$_GET['theday'];
}
// echo $theday;exit;
$arr=$outgoing->getOutgoing($shopid,$theday);
// var_dump($arr);
// die;
require_once ('../header.php');

// print_r($arr);exit;
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./getstockin.php?theday="+theday;
}
//-->
</script>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						月度入库表<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>月度入库表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							<a href="./getstockinExcel.php<?php if(isset($_GET['theday'])){echo "?theday=".$theday;}?>">生成excel</a>
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>商品名称</th>
											<th>月初库存</th>
											<th>入库量</th>
											<?php for($i=1;$i<=$arr['day'];$i++){ ?>
											<th><?php echo $i.'号'; ?></th>
											<?php }?>
											<th>入库总量</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr['food'] as $key=>$val){?>
										<tr>
											<td><?php echo $val['name'];?></td>
											<td><?php echo $val['lastnum']; ?></td>
											<td><?php echo $val['stocknum'];?></td>
											<?php 
											 foreach ($val['stock'] as $v){
											     echo "<td>".$v."</td>";
											 }
											?>
											<td><?php echo $val['final']; ?></td>
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