<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
$title="商家产品";
$menu="product";
$clicktag="product";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
?>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							产品 <small>时时为最新的产品</small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">主页</a> 

								<i class="icon-angle-right"></i>

							</li>

							<li>

								<a href="#">帮助</a>

								<i class="icon-angle-right"></i>

							</li>

							<li><a href="product.php">产品</a></li>

						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12 blog-page">

						<div class="row-fluid">
							<div class="span9 article-block">
								<h1>趣店</h1>
								<div class="row-fluid">
									<div class="span4 blog-img blog-tag-data">
										<img src="media/image/qudian.png" alt="">
										<ul class="unstyled inline">
											<li><i class="icon-calendar"></i> <a href="#">2015-5-11</a></li>
											<li><i class="icon-tags"></i> <a href="#">2.0版本</a></li>
											<li><i class="icon-tags"></i> <a href="#">下载密码：qudian</a></li>
										</ul>

									</div>

									<div class="span8 blog-article">
										<h2><a href="#">专为管理人员量身定制</a></h2>
									<div class="portlet-body">

									<blockquote>
										<p>桌台状态帮助管理人员一目了然餐厅目前运营状况</p>

									</blockquote>

									<blockquote>

										<p>各种报表随时随地帮助管理人员掌握本店营业收入</p>

									</blockquote>
										<blockquote>
										<p>极简的设置功能，不用教程也能轻松搞定</p>
									</blockquote>
								</div>
										<a class="btn blue" target="_blank"  href="http://www.pgyer.com/HQRR">
										下载IOS版
										<i class="m-icon-swapright m-icon-white"></i>
										</a>
									</div>
								</div>
								<hr>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span9 article-block">
								<h1>小跑堂</h1>
								<div class="row-fluid">
									<div class="span4 blog-img blog-tag-data">
										<img src="media/image/paotang.png" alt="">
										<ul class="unstyled inline">
											<li><i class="icon-calendar"></i> <a href="#">2015-5-11</a></li>
											<li><i class="icon-tags"></i> <a href="#">1.0版本</a></li>
										</ul>
									</div>
									<div class="span8 blog-article">
										<h2><a href="#">专为服务人员量身定制</a></h2>
									<div class="portlet-body">
									<blockquote>
										<p>简介而强大的点菜功能，让你爱不释手</p>

									</blockquote>
									<blockquote>

										<p>查看桌台状态，清台、明细、退菜一键搞定</p>

									</blockquote>
										<blockquote>
										<p>手机就能收银，没有比这更简单的了</p>
									</blockquote>
								</div>
										<a class="btn blue"  target="_blank"  href="http://www.pgyer.com/CgjL">
										下载Android版
										<i class="m-icon-swapright m-icon-white"></i>
										</a>
									</div>
								</div>
								<hr>
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

<?php 
require ('footer.php');
?>