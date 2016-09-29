<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BusinessZone{
	public function getBusinessZoneData(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getBusinessZoneData();
	}
}
$businesszone=new BusinessZone();
$title="提现页面";
$menu="manage";
$clicktag="businesszone";
require_once ('header.php');
$arr=$businesszone->getBusinessZoneData();
?>
	<h3 class="page-title">
							提现页面<small> </small>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span6">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>提现页面</div>
								<div class="tools">
								<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">选择方式</th>
											<th class="number">收入金额</th>
											<th>是否提现</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									switch ($val['city']){
										case "hangzhou":$city="杭州市";break;
										case "shanghai":$city="上海市";break;
										case "nanjing":$city="南京市";break;
										case "zhuji":$city="诸暨市";break;
										case "foshan":$city="佛山市";break;
										case "guangzhou":$city="广州市";break;
									}
										?>
										<tr>
											<td class="number"><?php echo ++$key;?></td>
											<td class="number"><?php echo $city;?></td>
											<td class="number"><?php echo $val['busi_zonename'];?></td>
											<td>
											<a href="#static" onclick="getOneZone('<?php echo $val['busi_zoneid'];?>')" class="btn mini green" data-toggle="modal" >
												<i class="icon-edit"></i>
											</a>
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

								<form action="./interface/saveonebusizone.php" method="post">
									<input type="hidden" id="busi_zoneid"  name="busi_zoneid" value="">
																	
									<div class="control-group">
										<label class="control-label">选择方式</label>
										<div class="controls">
											<select class="medium m-wrap" tabindex="2" id="city" name="city" >
												<option value="hangzhou">中国银行</option>
												<option value="shanghai">中国工商银行</option>
												<option value="nanjing">中国建设银行</option>
												<option value="zhuji">交通银行</option>
												<option value="foshan">招商银行</option>
												<option value="guangzhou">杭州银行</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">提现金额 </label>
										<div class="controls">
											<input type="text" placeholder="例：0.00（必填）" name="busi_zonename"  id="busi_zonename"  class="m-wrap medium" >
										</div>
									</div>
									<hr>
									<button type="button" data-dismiss="modal" class="btn">取消</button>
									<button type="submit"  class="btn green">提现</button>

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
<div class="footer">

		<div class="footer-inner">

			2014-2016 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

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
	<script src="<?php echo $base_url;?>media/js/ui-modals.js"></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.toggle.buttons.js"></script>
	
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

		   App.init(); // initlayout and core plugins

		   Index.init();
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