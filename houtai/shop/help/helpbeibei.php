<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
$title="新功能展示";
$menu="table";
// $clicktag="tabstatus";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
?>

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							 报表转移<small> 介绍</small>

						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12 blog-page">

						<div class="row-fluid">

							<div class="span12 article-block">

								<h1></h1>

								<div class="blog-tag-data">

								</div>

								<!--end news-tag-data-->
							<h3>报表更名为"贝贝",位置移动到右上角，商家名的下拉菜单里，如下图：</h3>
								<div class="blog-tag-data">
									<img src="./img/posbei.png" alt="">
								</div>
								
								<h3>进入"贝贝"的开关由趣店app控制，请将趣店更新到最新版本，点击<a href="http://www.pgyer.com/HQRR" target="_blank">下载</a></h3>
								<h4>位置见<span style="color: red">红色</span>箭头处进入：</h4>
								<div class="blog-tag-data">
									<img src="./img/beipos1.png" alt=""   width=250>
									<img src="./img/beipos2.png" alt=""   width=250>
								</div>
								<br>
						<h3 style="color:red">若贝贝街开关关闭，则无法进入查看报表！</h3>
								
							</div>

							<!--end span9-->

					
							<!--end span3-->

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
require_once ('../footer.php');
?>