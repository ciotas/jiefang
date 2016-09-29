<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditShopinfo{
	public function getShopAcountData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopAcountData($shopid);
	}
	
}
$editshopinfo=new EditShopinfo();
$title="账户编辑";
$menu="profile";
$clicktag="shopaccount";
require_once ('header.php');
$shopid=$_SESSION['shopid'];
$arr=array();
$arr=$editshopinfo->getShopAcountData($shopid);
?>
<script type="text/javascript">
<!--
function shutup(){
	
}
//-->
</script>
				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							账户信息
							 <small></small>
						</h3>
					
					</div>

				</div>

				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">账户编辑</span>

								</div>

							</div>
					
							<div class="portlet-body form">
								<div class="tabbable portlet-tabs">
										<br>
										<div class="tab-pane active" id="portlet_tab1">
										
										<div class="control-group">
                                    <span style="margin-left: 80px;">身份证正面照 &nbsp&nbsp&nbsp&nbsp</span>
									<img src="<?php if(!empty($arr)){echo $arr['IDCardface'];}?>" alt=""  width="200"/>
    								<span class="help-inline">
    									<form action="./interface/doupaccountimg.php" method="post" enctype="multipart/form-data">
    										<div class="controls">
    											<div class="fileupload fileupload-new" data-provides="fileupload">
    											<input type="hidden" value="IDCardface" name="op">
    												<span class="btn btn-file red">
    												<span class="fileupload-new"  style="color:#FFF">选择图片</span>
    												<span class="fileupload-exists" style="color:#FFF">重新上传</span>
    												<input type="file" class="default" name="IDCardface">
    												</span>
    												<span class="fileupload-preview"></span>
    												
    												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
    											</div>
    										</div>
    									</form>
    								</span>
    								<br/>
    								<br/>
                                    </div>
                                    <div class="control-group">
    								<span style="margin-left: 80px;">身份证反面照 &nbsp&nbsp&nbsp&nbsp</span>
									<img src="<?php if(!empty($arr)){echo $arr['IDCardback'];}?>" alt=""  width="200"/>
									<span class="help-inline">
    									<form action="./interface/doupaccountimg.php" method="post" enctype="multipart/form-data">
    										<div class="controls">
    											<div class="fileupload fileupload-new" data-provides="fileupload">
    											<input type="hidden" value="IDCardback" name="op">
    												<span class="btn btn-file red">
    												<span class="fileupload-new"  style="color:#FFF">选择图片</span>
    												<span class="fileupload-exists" style="color:#FFF">重新上传</span>
    												<input type="file" class="default" name="IDCardback">
    												</span>
    												<span class="fileupload-preview"></span>
    												
    												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
    											</div>
    										</div>
    									</form>
									</span>
    								<br/>
    								<br/>
                                   </div>
                                    <div class="control-group">
                                    <span style="margin-left: 80px;">银行卡正面照 &nbsp&nbsp&nbsp&nbsp</span>
									<img src="<?php if(!empty($arr)){echo $arr['banckcardface'];}?>" alt=""  width="200"/>
									<span class="help-inline">
    									<form action="./interface/doupaccountimg.php" method="post" enctype="multipart/form-data">
    										<div class="controls">
    											<div class="fileupload fileupload-new" data-provides="fileupload">
    											<input type="hidden" value="banckcardface" name="op">
    												<span class="btn btn-file red">
    												<span class="fileupload-new"  style="color:#FFF">选择图片</span>
    												<span class="fileupload-exists" style="color:#FFF">重新上传</span>
    												<input type="file" class="default" name="banckcardface">
    												</span>
    												<span class="fileupload-preview"></span>
    												
    												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
    											</div>
    										</div>
    									</form>
									</span>
    								<br/>
    								<br/>
                                    </div>
										
											<!-- BEGIN FORM-->
											<form action="./interface/saveshopaccount.php" method="post" class="form-horizontal">
												<input type="hidden" name="shopaccountid" value="<?php if(!empty($arr)){echo $arr['_id'];}?>">
												<div class="control-group">

													<label class="control-label" >店长姓名</label>

													<div class="controls">

														<input type="text" placeholder="必填"  name="shopkeeper" class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['shopkeeper'];}?>" />

														<span class="help-inline"></span>

													</div>

												</div>
										

												<div class="control-group">
													<label class="control-label" >银行卡号</label>
													<div class="controls">
														<input type="number" placeholder="必填"  name="bankno" id="bankno"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['bankno'];}?>">
														<span style=""></span>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" >开户行</label>
													<div class="controls">
														<input type="text" placeholder="必填"  name="bankbranch" id="bankbranch"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['bankbranch'];}?>">
													</div>
												</div>

											
												<div class="form-actions">

													<button type="submit" class="btn blue" ><i class="icon-ok"></i> 保存</button>

													<button type="button" class="btn"  onclick="window.location.href='./shopaccount.php' ">取消</button>

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
	
	<script src="<?php echo $base_url;?>media/js/form-components.js"></script>     
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

			 App.init();

			   FormComponents.init();
			  

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>