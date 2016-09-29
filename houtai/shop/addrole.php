<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddRole{
	
}
$addrole=new AddRole();
$title="职位";
$menu="dataset";
$clicktag="jobset";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
?>

				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							添加职位
							 <small></small>
						</h3>
						

					</div>

				</div>

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">添加职位</span>

								</div>

							</div>
					
							<div class="portlet-body form">
								<div class="tabbable portlet-tabs">
										<br>
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/addonerole.php" method="post" class="form-horizontal">
												<div class="control-group">

													<label class="control-label" style="color: red">职位名</label>

													<div class="controls">

														<input type="text" placeholder="必填"  name="rolename" class="m-wrap span6" />

														<span class="help-inline"></span>

													</div>

												</div>
												<div class="control-group">
												<label class="control-label">明细</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="detail"  name="detail[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">赠送</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="donate"  name="donate[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">称重</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="weight"  name="weight[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">退菜</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="returnfood"  name="returnfood[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">出单</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="outsheet"  name="outsheet[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">清台</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="empty"  name="empty[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">预定</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="book"  name="book[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">开台</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="start"  name="start[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">占用</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="online"  name="online[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">换台</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="changetab"  name="changetab[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">改价</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="changeprice"  name="changeprice[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">收银</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="pay"  name="pay[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">反结账</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="repay"  name="repay[]"   />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">收押金</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="deposit"  name="deposit[]"   />
													</div>
													</div>
												</div>
												<div class="form-actions">
													<button type="button" class="btn"  onclick="window.location.href='./jobset.php' ">取消</button>
													<button type="submit" class="btn blue"><i class="icon-ok"></i> 保存</button>

												</div>

											</form>

											<!-- END FORM-->  

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

<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014-2015 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>

		<div class="footer-tools">

			<span class="go-top">

			<i class="icon-angle-up"></i>

			</span>

		</div>

	</div>

	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="<?php echo $base_url;?>media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="<?php echo $base_url;?>media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="<?php echo $base_url;?>media/js/excanvas.min.js"></script>

	<script src="<?php echo $base_url;?>media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="<?php echo $base_url;?>media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.js" type="text/javascript"></script>   

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.russia.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.world.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.europe.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.germany.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.usa.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.flot.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.flot.resize.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.pulsate.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/date.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/daterangepicker.js" type="text/javascript"></script>     

	<script src="<?php echo $base_url;?>media/js/jquery.gritter.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/fullcalendar.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.sparkline.min.js" type="text/javascript"></script>  

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="<?php echo $base_url;?>media/js/app.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/index.js" type="text/javascript"></script>    
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/chosen.jquery.min.js"></script>
	<script src="<?php echo $base_url;?>media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="<?php echo $base_url;?>media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.input-ip-address-control-1.0.min.js"></script>
	
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.multi-select.js"></script>  
	<script src="<?php echo $base_url;?>media/js/form-components.js"></script>  
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-colorpicker.js"></script>  
	<script src="<?php echo $base_url;?>media/js/ui-modals.js"></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.toggle.buttons.js"></script>
	
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

		   App.init(); // initlayout and core plugins

		   Index.init();
// 		   Search.init();
		   
		   Index.initJQVMAP(); // init index page's custom scripts

		   Index.initCalendar(); // init index page's custom scripts

		   Index.initCharts(); // init index page's custom scripts

		   Index.initChat();

		   Index.initMiniCharts();

		   Index.initDashboardDaterange();
		   FormComponents.init();
		   UIModals.init();
		   
// 		   Index.initIntro();

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>