<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class OnLine{
	public function getOnlineData(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getOnlineData();
	}
}
$online=new OnLine();
$title="营业情况";
$menu="data";
$clicktag="online";
require_once ('header.php');
$arr=$online->getOnlineData();
// print_r($arr);exit;
?>

	<h3 class="page-title">
							营业情况<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>营业</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="number"  rowspan="2">#</th>
											<th class="number" rowspan="2">商家</th>
											<th class="number" rowspan="1" colspan="2">桌台数</th>
											<th class="number" rowspan="1" colspan="3">昨日营业</th>
											<th class="number" rowspan="1" colspan="3">今日营业</th>
										</tr>
										<tr>
										<td class="number"  rowspan="1">开台</td>
										<td class="number"  rowspan="1">占用</td>
										<td class="number"  rowspan="1">营业额 <i class="icon-money"></i></td>
										<td class="number"  rowspan="1"> 人数 <i class="icon-group"></i></td>
										<td class="number"  rowspan="1">单数 <i class=" icon-copy"></i></td>
										<td class="number"  rowspan="1"> 营业额 <i class="icon-money"></i></td>
										<td class="number"  rowspan="1">人数 <i class="icon-group"></i></td>
										<td class="number"  rowspan="1">单数 <i class=" icon-copy"></i></td>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td class="number"><?php echo ++$key;?></td>
											<td class="number"><?php echo $val['shopname'];?></td>
											<td class="number"><span class="label label-success"><?php echo $val['startnum'];?></span></td>
											<td class="number"><span class="label label-warning"><?php echo $val['onlinenum'];?></span></td>
											<td class="number">￥<?php echo $val['yestodaydata']['totalmoney'];?></td>
											<td class="number"><?php echo $val['yestodaydata']['cusnum'];?></td>
											<td class="number"><?php echo $val['yestodaydata']['billnum'];?></td>
											<td class="number">￥<?php echo $val['todaydata']['totalmoney'];?></td>
											<td class="number"><?php echo $val['todaydata']['cusnum'];?></td>
											<td class="number"><?php echo $val['todaydata']['billnum'];?></td>
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