<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class CusSheetAdv{
	public function getCusSheetAdvData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getCusSheetAdvData($shopid);
	}
}
$cussheetadv=new CusSheetAdv();
$title="划菜单底部内容设置";
$menu="dataset";
$clicktag="cussheetadv";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$cussheetadv->getCusSheetAdvData($shopid);
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("bottomadv").value="";
	document.getElementById("advurl").value="";
}
//-->
</script>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							划菜单底部内容设置 <small> </small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">设置</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="cussheetadv.php">划菜单底部内容</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="alert alert-info">
						<button class="close" data-dismiss="alert"></button>
						<strong>用途：设置本店说明或者做广告用！</strong>
				</div>
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i></div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>底部内容</th>
											<th>链接地址(可生成二维码)</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['content'];?></td>
											<td><?php echo substr($val['advurl'], 0,80) ;?></td>
											<td>
											<a href="./interface/deloneadv.php?advid=<?php echo base64_encode($val['advid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->
					</div>
					
					<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<form action="./interface/postadv.php" method="post">
												<div class="control-group">
													<label class="control-label">底部内容 </label>
													<div class="controls">
														<textarea placeholder="文字" id="bottomadv" placeholder="可选，文字说明" name="bottomadv" class="m-wrap span5"></textarea>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">链接地址 </label>
													<div class="controls">
														<input placeholder="可选，http://" id="advurl" name="advurl"  class="m-wrap span5">
													</div>
												</div>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn green">保存</button>
											</form>

										</div>
						</div>
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