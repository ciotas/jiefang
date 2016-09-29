<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FreePayPage{
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
}
$freepaypage=new FreePayPage();
$title="免单";
$menu="table";
$clicktag="tabstatus";
$shopid=$_SESSION['shopid'];
$billid="";
require_once ('header.php');
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$couponarr=$freepaypage->getPayPageData($billid, $shopid);
}

?>
<body>
			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							免单
							 <small></small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">主页</a> 

								<span class="icon-angle-right"></span>

							</li>

							<li>

								<a href="#">桌台</a>

								<span class="icon-angle-right"></span>

							</li>

							<li><a href="./freepaypage.php?billid=<?php echo $billid;?>">免单</a></li>

						</ul>

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box red tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">免单</span>

								</div>

							</div>

							<div class="portlet-body form">

								<div class="tabbable portlet-tabs">

									<ul class="nav nav-tabs">
										<li>&nbsp;</li>
									</ul>
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/dofreepay.php" class="form-horizontal" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="billid" value="<?php echo $billid;?>">

											<div class="control-group">
													<label class="control-label">消费总额：<span style="color: red;font-size:20px;">￥<?php echo $couponarr['totalmoney'];?></span></label>
													<label class="control-label">可优惠额：<span style="color: green;font-size:20px;">￥<?php echo $couponarr['fooddisaccountmoney'];?></span></label>
												</div>
												
												<div class="control-group">
													<label class="control-label">免单人</label>
													<div class="controls">
														<input type="text" placeholder="必填"   name="freename" id="freename" class="m-wrap span4" />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">免单理由</label>
													<div class="controls">
														<input type="text" placeholder="可选"   name="freereason" id="freereason"  class="m-wrap span4" />
													</div>
												</div>
												<div class="form-actions">
												<button type="button" class="btn black"  onclick="window.location.href='./tabmanage.php'"><i class="m-icon-swapleft m-icon-white"></i> 返回</button>
													<button type="submit" class="btn red" id="savebtn"><i class="icon-ok"></i> 保存</button>
												</div>
											</form>
											<!-- END FORM-->  
										</div>
									</div>
								</div>

							</div>

						</div>

						<!-- END SAMPLE FORM PORTLET-->

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