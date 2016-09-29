<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ChangeTab{
	public function getZoneTabByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getZoneTabByShopid($shopid);
	}
	public function changeTwoTable($tabid1, $tabid2,$shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->changeTwoTable($tabid1, $tabid2, $shopid);
	}
}
$changetab=new ChangeTab();
$title="换台";
$menu="table";
$clicktag="changetab";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
if(isset($_POST['tab1'])){
	$tabid1=$_POST['tab1'];
	$tabid2=$_POST['tab2'];
	$changetab->changeTwoTable($tabid1, $tabid2, $shopid);
	echo '<script>alert("换台成功！")</script>';
}
$arr=$changetab->getZoneTabByShopid($shopid);

?>


				<div class="row-fluid">

					<div class="span12">
					<h3 class="page-title">
							换台
						</h3>
					

					</div>

				</div>

				<!-- END PAGE HEADER-->
				<div class="alert alert-error">
									<strong>温馨提示：只能开台或占用状态的桌台换到空闲的桌台！</strong>

								</div>
				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-reorder"></i>换台</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>
								</div>

							</div>

							<div class="portlet-body form">

								<!-- BEGIN FORM-->

								<form action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" method="post">

									<div class="control-group">

										<label class="control-label">将台号</label>

										<div class="controls">

											<select data-placeholder="请选择台号" class="chosen span6" tabindex="-1" id="selS0V" name="tab1">

												<option value=""></option>
												<?php foreach ($arr as $key=>$val){?>
												<optgroup label="<?php echo $val['zonename'];?>">
												<?php foreach ($val['table'] as $tkey=>$tval){
													if($tval['tabstatus']=="empty" || $tval['tabstatus']=="book"){continue;}
													switch ($tval['tabstatus']){
														case "empty":$textcolor="style='color:green'";break;
														case "start":$textcolor="style='color:red'";break;
														case "online":$textcolor="style='color:yellow'";break;
														case "book":$textcolor="style='color:blue'";break;
														default:$textcolor="style='color:black'";
													}
													
													?>
												
													<option value="<?php echo $tval['tabid'];?>" <?php echo $textcolor;?>><?php echo $tval['tabname'];?></option>
													<?php }?>
												</optgroup>
												<?php }?>
											</select>
										</div>

									</div>
									
									<div class="control-group">

										<label class="control-label">换到</label>

										<div class="controls">

											<select data-placeholder="请选择台号" class="chosen span6" tabindex="0" id="selS1V" name="tab2">

												<option value=""></option>

												<?php foreach ($arr as $key=>$val){?>
												<optgroup label="<?php echo $val['zonename'];?>">
												<?php foreach ($val['table'] as $tkey=>$tval){
													if($tval['tabstatus']=="start" || $tval['tabstatus']=="online"){continue;}
													switch ($tval['tabstatus']){
														case "empty":$textcolor="style='color:green'";break;
														case "start":$textcolor="style='color:red'";break;
														case "online":$textcolor="style='color:yellow'";break;
														case "book":$textcolor="style='color:blue'";break;
														default:$textcolor="style='color:black'";
													}
													?>
												
													<option value="<?php echo $tval['tabid'];?>" <?php echo $textcolor;?>><?php echo $tval['tabname'];?></option>
													<?php }?>
												</optgroup>
												<?php }?>
											</select>

										</div>

									</div>
																		
									<div class="form-actions">

										<button type="submit" class="btn red">换台</button>


									</div>

								</form>

								<!-- END FORM-->       

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

			2014-2015 © 杭州街坊科技 Inc. All rights reserved

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

	<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="media/js/excanvas.min.js"></script>

	<script src="media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script type="text/javascript" src="media/js/ckeditor.js"></script>  

	<script type="text/javascript" src="media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="media/js/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="media/js/select2.min.js"></script>

	<script type="text/javascript" src="media/js/wysihtml5-0.3.0.js"></script> 

	<script type="text/javascript" src="media/js/bootstrap-wysihtml5.js"></script>

	<script type="text/javascript" src="media/js/jquery.tagsinput.min.js"></script>

	<script type="text/javascript" src="media/js/jquery.toggle.buttons.js"></script>

	<script type="text/javascript" src="media/js/bootstrap-datepicker.js"></script>

	<script type="text/javascript" src="media/js/bootstrap-datetimepicker.js"></script>

	<script type="text/javascript" src="media/js/clockface.js"></script>

	<script type="text/javascript" src="media/js/date.js"></script>

	<script type="text/javascript" src="media/js/daterangepicker.js"></script> 

	<script type="text/javascript" src="media/js/bootstrap-colorpicker.js"></script>  

	<script type="text/javascript" src="media/js/bootstrap-timepicker.js"></script>

	<script type="text/javascript" src="media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="media/js/jquery.input-ip-address-control-1.0.min.js"></script>

	<script type="text/javascript" src="media/js/jquery.multi-select.js"></script>   

	<script src="media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/app.js"></script>

	<script src="media/js/form-components.js"></script>     

	<!-- END PAGE LEVEL SCRIPTS -->

	<script>

		jQuery(document).ready(function() {       

		   // initiate layout and plugins

		   App.init();

		   FormComponents.init();

		});

	</script>

	<!-- END JAVASCRIPTS -->   

</body>

<!-- END BODY -->

</html>