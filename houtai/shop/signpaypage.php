<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SignPayPage{
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
}
$signpaypage=new SignPayPage();
$title="签单";
$menu="table";
$clicktag="tabstatus";
$shopid=$_SESSION['shopid'];
$billid="";
require_once ('header.php');
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$couponarr=$signpaypage->getPayPageData($billid, $shopid);
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
							签单
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

							<li><a href="./signpaypage.php?billid=<?php echo $billid;?>">签单</a></li>

						</ul>

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box purple tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">签单</span>

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
											<form action="./interface/dosignpay.php" class="form-horizontal" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="billid" value="<?php echo $billid;?>">

											<div class="control-group">
													<label class="control-label">消费总额：<span style="color: red;font-size:20px;">￥<?php echo $couponarr['totalmoney'];?></span></label>
													<label class="control-label">可优惠额：<span style="color: green;font-size:20px;">￥<?php echo $couponarr['fooddisaccountmoney'];?></span></label>
												</div>
												
												<div class="control-group">
													<label class="control-label">签单人</label>
													<div class="controls">
														<input type="text" placeholder="必填"   name="signername" id="signername" class="m-wrap span4" />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">签单单位</label>
													<div class="controls">
														<input type="text" placeholder="必填"   name="signerunit" id="signerunit"  class="m-wrap span4" />
													</div>
												</div>
												<div class="form-actions">
												<button type="button" class="btn black"  onclick="window.location.href='./tabmanage.php'"><i class="m-icon-swapleft m-icon-white"></i> 返回</button>
													<button type="submit" class="btn purple" id="savebtn"><i class="icon-ok"></i> 保存</button>
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