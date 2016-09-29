<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditShopinfo{
	public function getMyShopinfoData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getMyShopinfoData($shopid);
	}
	public function getFoodidsData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getFoodidsData($shopid);
	}
}
$editshopinfo=new EditShopinfo();
$title="资料编辑";
$menu="profile";
$clicktag="shopinfo";
require_once ('header.php');
$shopid=$_SESSION['shopid'];
$arr=array();
$arr=$editshopinfo->getMyShopinfoData($shopid);
$foods=$editshopinfo->getFoodidsData($shopid);
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
							商家资料
							 <small></small>
						</h3>
					
					</div>

				</div>

				<!-- END PAGE HEADER-->
<!-- 	<div class="alert alert-error"> -->
<!-- 					<button class="close" data-dismiss="alert"></button> -->
<!-- 					<strong>红色字体部分为必填项</strong> -->
<!-- 				</div> -->
				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">美食编辑</span>

								</div>

							</div>
					
							<div class="portlet-body form">
								<div class="tabbable portlet-tabs">
										<br>
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/saveshopinfo.php" method="post" class="form-horizontal">
												<div class="control-group">

													<label class="control-label" >一句话宣传</label>

													<div class="controls">

														<input type="text" placeholder="选填"  name="briefinfo" class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['briefinfo'];}?>" />

														<span class="help-inline"></span>

													</div>

												</div>
												<div class="control-group">
													<label class="control-label">地址</label>
													<div class="controls">
														<input type="text" placeholder="必填"  name="province" id="province"  class="m-wrap span2" value="<?php if(!empty($arr)){echo $arr['province'];}?>">省
														<input type="text" placeholder="必填"  name="city" id="city"  class="m-wrap span2" value="<?php if(!empty($arr)){echo $arr['city'];}?>">市
														<input type="text" placeholder="必填"  name="district" id="district"  class="m-wrap span2" value="<?php if(!empty($arr)){echo $arr['district'];}?>">区（县）
														<input type="text" placeholder="必填"  name="road" id="road"  class="m-wrap span3" value="<?php if(!empty($arr)){echo $arr['road'];}?>">街道
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">经度</label>
													<div class="controls">
														<input type="text" placeholder="范围0-180°"  name="lon" id="lon"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['lon'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">纬度</label>
													<div class="controls">
														<input type="text" placeholder="范围0-90°"  name="lat" id="lat"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['lat'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">人均消费</label>
													<div class="controls">
														<input type="text" placeholder="不填为系统自动计算"  name="avgpay" id="avgpay"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['avgpay'];}?>">元
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" >营业时间</label>
													<div class="controls">
														<input type="text" placeholder="必填"  name="opentime" id="opentime"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['opentime'];}?>">
														<span style=""></span>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" >服务电话</label>
													<div class="controls">
														<input type="text" placeholder="必填"  name="servicephone" id="servicephone"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['servicephone'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="">店长昵称</label>
													<div class="controls">
														<input type="text" placeholder="必填"  name="manager" id="manager"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['manager'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="">商家支付宝账号</label>
													<div class="controls">
														<input type="text" placeholder="必填"  name="alipayaccount" id="alipayaccount"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['alipayaccount'];}?>">
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">开通外卖</label>
												<div class="controls">
													<label class="radio">
													<input type="radio" name="takeoutswitch" value="1"  <?php if(!empty($arr)){if($arr['takeoutswitch']=="1"){echo "checked";}}else{echo "checked";}?>/>
													是
													</label>
													<label class="radio">
													<input type="radio" name="takeoutswitch" value="0"  <?php if($arr['takeoutswitch']=="0"){echo "checked";}?>/>
													否
													</label>  
												</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="">本店标签</label>
													<div class="controls">
														<input type="text" placeholder="多种用顿号、分隔"  name="storetag" id="storetag"  class="m-wrap span6" value="<?php if(!empty($arr)){echo $arr['storetag'];}?>">
													</div>
												</div>
												

												<div class="control-group">

												<label class="control-label">推荐美食</label>

												<div class="controls">
													<select data-placeholder="请选择美食" class="chosen span8" tabindex="-1" multiple id="selS0V" name="favfoodid[]">							
													<option value=""></option>
													<?php foreach ($foods as $ftkey=>$ftval){
														echo '<optgroup label="'.$ftval['ftname'].'">';
														foreach ($ftval['food'] as $fkey=>$fval){
															if(in_array($fval['foodid'], $arr['favfoodid'])){$selected="selected";}else{$selected="";}
															echo '<option value="'.$fval['foodid'].'" '.$selected.'>'.$fval['foodname'].'</option>';
														}
														echo "</optgroup>";
													}?>
													</select>
												</div>
											</div>
											
												<div class="form-actions">

													<button type="submit" class="btn blue"><i class="icon-ok"></i> 保存</button>

													<button type="button" class="btn"  onclick="window.location.href='./shopinfo.php' ">取消</button>

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