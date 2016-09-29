<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GuqingList{
	public function getGuqingFoodData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getGuqingFoodData($shopid);
	}
}
$guqinglist=new GuqingList();
$title="估清表";
$menu="business";
$clicktag="guqinglist";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$guqinglist->getGuqingFoodData($shopid);
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							估清表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./interface/syncfood.php?backurl=guqinglist" class="btn red">同步数据</a>
						<small> 修改完后请同步数据</small>
						</h3>

						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>估清表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>图片</th>
											<th>名称</th>
											<th>类别</th>
											<th class="hidden-480">价格</th>
											<th>单位</th>
											<th>估清</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									if($val['foodguqing']=="1"){$foodguqing='<span class="label label-warning">已估清</span>';}else{$foodguqing='<span class="label label-success">有货</span>';}
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><img alt="" src="<?php echo $val['foodpic'];?>" width="60"></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['ftname'];?></td>
											<td><?php echo $val['foodprice'];?></td>
											<td class="hidden-480"><?php echo $val['foodunit'];?></td>
											<td class="hidden-480"><?php echo $foodguqing;?></td>
											<td><a href="./interface/updateguqing.php?foodid=<?php echo $val['foodid'];?>" class="btn mini green"><i class="icon-share"></i> 已有货</a>
											
											</td>
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