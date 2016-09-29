<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetPrinters{
	public function getPrintersStatus(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getPrintersStatus();
	}
}
$getprinters=new GetPrinters();
$title="打印机状态";
$menu="data";
$clicktag="getprinters";
$i=0;
require_once ('header.php');
$arr=$getprinters->getPrintersStatus();
// print_r($arr);exit;
?>

<h3 class="page-title">
							商家信息<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>商家信息</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">商家</th>
											<th class="number">编号</th>
											<th class="number">状态</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										if($val['workstatus']=="在线,纸张正常"){										
											$workstatus='<span class="label label-success">'.$val['workstatus'].'</span>';
										}else{
											$workstatus='<span class="label label-error">'.$val['workstatus'].'</span>';
										}
										?>
										<tr>
											<td class="number"><?php echo ++$i;?></td>
											<td class="number"><?php echo $val['shopname'];?></td>
											<td class="number"><?php echo $val['deviceno'];?></td>
											<td class="number"><?php echo $workstatus;?></td>
											
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
	<?php 
	require_once ('footer.php');
	?>