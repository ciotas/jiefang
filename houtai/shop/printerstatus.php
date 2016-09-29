<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Printers{
	public function getPrintersByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getPrintersByShopid($shopid);
	}
}
$printers=new Printers();
$title="打印机状态";
$menu="business";
$clicktag="printerstatus";
$printerid="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$oneprinter=array();
$arr=$printers->getPrintersByShopid($shopid);
?>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							打印机 <small>状态</small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">营业表</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="printerstatus.php">打印机状态</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>打印机状态</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>名称</th>
											<th>编码</th>
											<th>卡号</th>
											<th>出单类型</th>
											<th>区域</th>
											<th>状态</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										switch ($val['outputtype']){
											case "menu":$outputtype="划菜单";break;
											case "checkout":$outputtype="结账单";break;
											case "pass":$outputtype="传菜单";break;
											case "single":$outputtype="分单" ;break;
											case "double":$outputtype="二联单";break;
											case "subtotal":$outputtype="分总单";break;
											case "total":$outputtype="总单";break;
										}
										if($val['workstatus']=="在线,纸张正常"){
											$workstatus='<span class="label label-success">'.$val['workstatus'].'</span>';
										}else{
											$workstatus='<span class="label label-error">'.$val['workstatus'].'</span>';
										}
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['printername'];?></td>
											<td><?php echo $val['deviceno'];?></td>
											<td><?php echo $val['workphone'];?></td>
											<td><?php echo $outputtype;?></td>
											<td><?php echo $val['zonename'];?></td>
											<td><?php echo $workstatus;?></td>
											
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