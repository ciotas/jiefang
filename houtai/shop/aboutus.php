<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AboutUs{
	
}
$aboutus=new AboutUs();
$title="关于我们";
$menu="help";
$clicktag="aboutus";
$shopid=$_SESSION['shopid'];
require_once ('header.php');

?>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">


						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							关于我们 <small></small>

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
							<li><a href="aboutus.php">关于我们</a></li>
						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid margin-bottom-30">

					<div class="span6">
					<blockquote class="hero">
						 <p>开饭啦~ 我们来啦~ ，为何要选择我们呢？
						 </p>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">

							<li><i class="icon-ok"></i> 青春活泼的80后、90后团队，年轻就是未来！</li>

							<li><i class="icon-ok"></i> 10余年专业的餐饮系统人士作为产品顾问，市场经验为我们保驾护航！</li>

							<li><i class="icon-ok"></i> 网上开实体店，网上运营，您的商店运营不止在室内！</li>
							
							<li><i class="icon-ok"></i> 与众不同的产品设计，您未曾所见！</li>
							
							<li><i class="icon-ok"></i> 极简的产品设计，将熟悉产品的过程难度降低了80%！</li>

							<li><i class="icon-ok"></i> 至少2次/月以上的产品更新，时时都是最新的体验！</li>
							
							<li><i class="icon-ok"></i> 让您掌握每一个顾客的消费数据，再也不怕客户丢失！</li>
							
							<li><i class="icon-ok"></i> 7×24小时一对一专业免费在线服务，再也不用坐等师傅上门！</li>

							<li><i class="icon-ok"></i> 不断添加更实用、好玩、新奇的功能，让您开店成为乐趣！</li>
							
						</ul>

						<!-- Blockquotes -->
<br>
						<blockquote class="hero">

							<p> 客户开心，我们才开心！</p>

							 <small>街坊科技</small>

						</blockquote>

					</div>

					<div class="span6">

					<img src="http://image.tianjimedia.com/uploadImages/2014/295/05/2GFJ7A1WS7ND_1000x500.jpg"  style="width:100%; height:327px;border:0">

					</div>

				</div>
				
				<!--/row-fluid-->   
				<!-- Meer Our Team -->

				<div class="headline">

					<h3>我们的团队</h3>

				</div>

				<ul class="thumbnails">

					<li class="span3">

						<div class="meet-our-team">

							<h3>张林梓  <small>创始人 / CEO</small></h3>

							<img src="media/image/2.jpg" alt="" />

							<div class="team-info">

								<p></p>

								<ul class="social-icons pull-right">

								</ul>

							</div>

						</div>

					</li>

					<li class="span3">

						<div class="meet-our-team">

							<h3>徐燕芝 <small>合伙人、COO</small></h3>

							<img src="media/image/3.jpg" alt="" />

							<div class="team-info">

								<p></p>

								<ul class="social-icons pull-right">


								</ul>

							</div>

						</div>

					</li>

					<li class="span3">

						<div class="meet-our-team">

							<h3>刘庆  <small>客户发展团队负责人</small></h3>

							<img src="media/image/2.jpg" alt="" />

							<div class="team-info">

								<p></p>

								<ul class="social-icons pull-right">

								

								</ul>

							</div>

						</div>

					</li>

					<li class="span3">

						<div class="meet-our-team">

							<h3>周恒帆  <small>Android开发工程师</small></h3>

							<img src="media/image/3.jpg" alt="" />

							<div class="team-info">

								<p></p>

								<ul class="social-icons pull-right">

								

								</ul>

							</div>

						</div>

					</li>
					<li class="span3">

						<div class="meet-our-team">

							<h3>权媛琳  <small>IOS 开发攻城狮</small></h3>

							<img src="media/image/3.jpg" alt="" />

							<div class="team-info">

								<p></p>

								<ul class="social-icons pull-right">

								

								</ul>

							</div>

						</div>

					</li>
					<li class="span3">

						<div class="meet-our-team">

							<h3>王孟辉  <small> 设计总监</small></h3>

							<img src="media/image/3.jpg" alt="" />

							<div class="team-info">

								<p></p>

								<ul class="social-icons pull-right">

								

								</ul>

							</div>

						</div>

					</li>
					

				</ul>

				<!--/thumbnails-->

				<!-- //End Meer Our Team -->        
			<div class="row-fluid margin-bottom-20">

							<div class="span6">

								<div class="space20"></div>

								<h3 class="form-section">联系我们</h3>

								<p> </p>

								<div class="well">
<!-- 
									<h4>地址：</h4>

									<address>

										<strong>杭州市 西湖区</strong><br>

										天目山路226号<br>

										网新大厦二楼<br>

										<abbr title="Phone">tel:</abbr> 13071870889

									</address>
 -->
 										<address>

										<strong>联系方式</strong><br>

										<a href="mailto:#">13071870889(张林梓)、13738087144(徐燕芝)</a>

									</address>
									<address>

										<strong>Email</strong><br>

										<a href="mailto:#">postmaster@meijiemall.com</a>

									</address>
									<address>

										<strong>QQ：</strong><br>

										<a href="#">1187201645</a>

									</address>
									<address>

										<strong>微信：</strong><br>

										<a href="#">ciotas21gj</a>

									</address>

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
	require_once ('footer.php');
	?>