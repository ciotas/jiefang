<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpFoodXls{
	public function genrPreExcel($shopid, $shopname){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->genrPreExcel($shopid, $shopname);
	}
}
$upfoodxls=new UpFoodXls();
$shopid=$_SESSION['shopid'];
// echo $shopid;exit;
$menu="dataset";
$clicktag="upfood";
$title="批量上传";
require_once ('header.php');
$shopname=$_SESSION['shopname'];
$patharr=$upfoodxls->genrPreExcel($shopid, $shopname);
// print_r($patharr);exit;
$error="";
if(isset($_GET['data'])){
	$data=$_GET['data'];
	$statusarr=json_decode(base64_decode($data),true);
	if($statusarr['status']=="ok"){
		$error="<span style='color:green;font-size:18px;'>商品excel文件导入成功，请在顾客端上传美食图片！</span>";
	}else {
		switch ($statusarr['code']){
			case "format_error":$error="<span style='color:red;font-size:18px;'>格式不正确，请上传excel表格。</sapn>";break;
			case "ftid_error":$error="<span style='color:red;font-size:18px;'>类别ID不能为空！</sapn>";break;
			case "foodname_error":$error="<span style='color:red;font-size:18px;'>商品名称不能为空！</sapn>";break;
			case "zid_error":$error="<span style='color:red;font-size:18px;'>档口ID不能为空！</sapn>";break;
			case "foodprice_error":$error="<span style='color:red;font-size:18px;'>价格不能为空，且为数字类型！！</sapn>";break;
			case "foodunit_error":$error="<span style='color:red;font-size:18px;'>计量单位不能为空！</sapn>";break;
			case "orderunit_error":$error="<span style='color:red;font-size:18px;'>点菜单位不能为空！</sapn>";break;
			case "disacount_error":$error="<span style='color:red;font-size:18px;'>优惠不能为空，且只能为1或0！</sapn>";break;
			case "weight_error":$error="<span style='color:red;font-size:18px;'>称重不能为空，且只能为1或0！</sapn>";break;
			case "hot_error":$error="<span style='color:red;font-size:18px;'>hot不能为空，且只能为1或0！</sapn>";break;
			case "package_error":$error="<span style='color:red;font-size:18px;'>套餐标记不能为空，且只能为1或0！</sapn>";break;
		}
	}
	 
}
?>
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				批量上传商品 <small>amazing file upload experience</small>
			</h3>
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">仪表盘</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li>
					<a href="#">设置</a>
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">批量上传</a></li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<blockquote>
							<p style="font-size:16px">
							1.在上传商品excel之前，请确保已在趣店客户端完成了档口和美食分类设置！
							</p>
							<p style="font-size:16px">
							2.请先下载&nbsp;<a href="<?php if(!empty($patharr)){echo $patharr['zonepath'];}?>" style="color: orange" >档口ID表</a>、
							<a href="<?php if(!empty($patharr)){echo $patharr['typepath'];}?>" style="color: orange" >分类ID表</a>
							</p>
							<p style="font-size:16px">
							3.请下载<a href="http://shop.meijiemall.com/upexcel/data/美食模板.xlsx" style="color: orange" >美食模板</a>表格
							</p>
						</blockquote>
						<br>

						<!-- The file upload form used as target for the file upload widget -->

						<form id="fileupload" action="<?php echo ROOTURL;?>upexcel/interface/doupexcel.php" method="POST" enctype="multipart/form-data">

							<!-- Redirect browsers with JavaScript disabled to the origin page -->

							<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
							<input type="hidden" name="shopid"  value="<?php echo $shopid?>">
							<div class="row-fluid fileupload-buttonbar">

								<div class="span7">
									<!-- The fileinput-button span is used to style the file input field as button -->									
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="input-append">
											<div class="uneditable-input">
												<i class="icon-file fileupload-exists"></i> 
												<span class="fileupload-preview"></span>
											</div>
										<span class="btn btn-file">
											<span class="fileupload-new">选择文件</span>
											<span class="fileupload-exists">替换</span>
											<input type="file" class="default" name="foodexcel">
											</span>
											<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">移除</a>
										</div>
									</div>																				

									<button type="submit" class="btn blue start">

									<i class="icon-upload icon-white"></i>

									<span>开始上传</span>

									</button>


								</div>

								<!-- The global progress information -->

								<div class="span5 fileupload-progress fade">

									<!-- The global progress bar -->

									<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">

										<div class="bar" style="width:0%;"></div>

									</div>

									<!-- The extended global progress information -->

									<div class="progress-extended">&nbsp;</div>

								</div>

							</div>

							<!-- The loading indicator is shown during file processing -->

							<div class="fileupload-loading"></div>

							<br>

							<!-- The table listing the files available for upload/download -->

							<table role="presentation" class="table table-striped">

								<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>

							</table>

						</form>

						<br>

						<div class="well">

							<h3>上传状态</h3>

							<ul>

								<li><?php if(!empty($error)){echo $error;}else{echo "等待上传...";}?></li>

							</ul>

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

	<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014 &copy;  <a href="http://www.meijiemall.com/" target="_blank">杭州街坊科技有限公司</a>

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

	<script src="media/js/jquery.fancybox.pack.js"></script>

	<!-- BEGIN:File Upload Plugin JS files-->

	<script src="media/js/jquery.ui.widget.js"></script>

	<!-- The Templates plugin is included to render the upload/download listings -->

	<script src="media/js/tmpl.min.js"></script>

	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->

	<script src="media/js/load-image.min.js"></script>

	<!-- The Canvas to Blob plugin is included for image resizing functionality -->

	<script src="media/js/canvas-to-blob.min.js"></script>

	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->

	<script src="media/js/jquery.iframe-transport.js"></script>

	<!-- The basic File Upload plugin -->

	<script src="media/js/jquery.fileupload.js"></script>

	<!-- The File Upload file processing plugin -->

	<script src="media/js/jquery.fileupload-fp.js"></script>

	<!-- The File Upload user interface plugin -->

	<script src="media/js/jquery.fileupload-ui.js"></script>
	<script type="text/javascript" src="media/js/bootstrap-fileupload.js"></script>

	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->

	<!--[if gte IE 8]><script src="media/js/jquery.xdr-transport.js"></script><![endif]-->

	<!-- END:File Upload Plugin JS files-->

	<!-- END PAGE LEVEL PLUGINS -->

	<script src="media/js/app.js"></script>
	<script>

		jQuery(document).ready(function() {

		// initiate layout and plugins

		App.init();

		FormFileUpload.init();

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>
