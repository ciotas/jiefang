<?php
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ZoneCalc{

}
$zonecac=new ZoneCalc();

?>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							<?php echo $shopname;?>的统计 <small></small>
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid invoice">
					<div class="span12">
					<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-table"></i>选择时间</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./zonecalc.php?shopid=<?php echo $shopid;?>&shopname=<?php echo $shopname;?>&dist=<?php echo $firstclick;?>" method="post">
								<table>
								<tr>
							<td>
							<span class="inline">起始日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" style="width: 100px;" name="startdate" onClick="WdatePicker()" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
								</td>
								<td>
							    <span class="inline">结束日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" style="width: 100px;" name="enddate"  onClick="WdatePicker()" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
								</td>
								</tr>
								
								<tr>
								<td></td>
							<td style="float: right"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
							<tr>
								</table>
								</form>
							</div>
						</div>
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>总量</th><td><?php echo $soldtotalamount;?>份</td>
											<th>总额</th><td>￥<?php echo $foodtotalmoney;?></td>
											<th> 未结款</th><td>￥<?php echo $arr['totalmoney'];?></td>
											<th>应收款</th><td>￥<?php echo $arr['unpaymoney'];?></td>
										</tr>
									</thead>
									
								</table>
								<table class="table table-hover">
									<thead>
										<tr>
											<th >#</th>
											<th >商品名</th>
											<th >数量</th>
											<th>总额</th>
											<th>商家数</th>	
										</tr>
									</thead>
									<tbody>
										<?php foreach ($arr['data'] as $key=>$val){?>
										<tr>
											<td ><?php echo ++$key;?></td>
											<td ><?php echo $val['foodname'];?></td>
											<td><?php echo $val['foodamount'].$val['foodunit'];?></td>
											<td>￥<?php echo $val['foodmoney'];?></td>
											<td><?php echo count($val['shopname']);?></td>
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
require ('footer.php');
?>