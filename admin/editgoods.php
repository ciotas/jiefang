<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditGoods{
	public function getGoodsTypeData() {
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getGoodsTypeData();
	}
	public function getOneGoodsData($goodsid){
		return  Admin_InterfaceFactory::createInstanceAdminOneDAL()->getOneGoodsData($goodsid);
	}
}
$editgoods=new EditGoods();
$title="商品编辑";
$menu="goods";
$clicktag="goods";
require_once ('header.php');
$onegoods=array();
$typeno="0";
$goodsid="";
$goodstypearr=$editgoods->getGoodsTypeData();
if(isset($_GET['goodsid'])){
	$goodsid=$_GET['goodsid'];
	$typeno=$_GET['typeno'];
	$onegoods=$editgoods->getOneGoodsData($goodsid);
}
// print_r($onefood);exit;
?>
<script type="text/javascript">
<!--

//-->
</script>
				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							商品编辑
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

									<span class="hidden-480">商品编辑</span>

								</div>

							</div>
					
							<div class="portlet-body form">
								<div class="tabbable portlet-tabs">
										<br>
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/saveonegoods.php" method="post" class="form-horizontal">
												<input type="hidden" name="goodsid" value="<?php echo $goodsid;?>">
												<input type="hidden" name="typeno" value="<?php echo $typeno;?>">
												<div class="control-group">

													<label class="control-label" style="color: red">商品名</label>

													<div class="controls">

														<input type="text" placeholder="必填，商品名"  name="goodsname" class="m-wrap span6" value="<?php if(!empty($onegoods)){echo $onegoods['goodsname'];}?>" />

														<span class="help-inline"></span>

													</div>

												</div>
												
												<div class="control-group">
													<label class="control-label">一句话描述</label>
													<div class="controls">
														<input type="text" placeholder="选填"  name="goodsdesc" id="goodsdesc"  class="m-wrap span6" value="<?php if(!empty($onegoods)){echo $onegoods['goodsdesc'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">原价</label>
													<div class="controls">
														<input type="text" placeholder="选填，原价"  name="otherprice" id="otherprice"  class="m-wrap span6" value="<?php if(!empty($onegoods)){echo $onegoods['otherprice'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">街坊价</label>
													<div class="controls">
														<input type="text" placeholder="必填，数字类型 "  name="ourprice" id="ourprice"  class="m-wrap span6" value="<?php if(!empty($onegoods)){echo $onegoods['ourprice'];}?>">
														<span style="color:red">必填，数字类型，请不要带单位</span>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">计量单位</label>
													<div class="controls">
														<input type="text" placeholder="必填，点菜单位"  name="goodsunit" id="goodsunit"  class="m-wrap span6" value="<?php if(!empty($onegoods)){echo $onegoods['goodsunit'];}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color: red">售卖单位</label>
													<div class="controls">
														<input type="text" placeholder="必填，计量单位"  name="goodssoldunit" id="goodssoldunit"  class="m-wrap span6" value="<?php if(!empty($onegoods)){echo $onegoods['goodssoldunit'];}?>">
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label" style="color: red">类别</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="goodstypeid" name="goodstypeid" >
<!-- 															<option value="0">---请选择档口---</option> -->
															<?php foreach ($goodstypearr as $gtkey=>$gtval){?>
															<option value="<?php echo $gtval['goodstypeid'];?>" <?php if($onegoods['goodstypeid']==$gtval['goodstypeid']){echo "selected";}?>><?php echo $gtval['goodstypename']?></option>
															<?php }?>
														</select>
														<span style="color:red"></span>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">上线</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="online"  name="online[]"  <?php if(!empty($onegoods)){if($onegoods['online']=="1"){echo "checked";}}?>  />
													</div>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">规格</label>
													<div class="controls">
														<textarea  placeholder="选填，多个用“|”分割" rows="3"  name="goodsformat" id="goodsformat"  class="m-wrap span6" ><?php if(!empty($onegoods)){echo $onegoods['goodsformat'];}?></textarea>
													</div>
												</div>
												<div class="form-actions">

													<button type="submit" class="btn blue"><i class="icon-ok"></i> 保存</button>

													<button type="button" class="btn"  onclick="window.location.href='./goods.php?typeno=<?php echo $typeno;?>' ">取消</button>

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