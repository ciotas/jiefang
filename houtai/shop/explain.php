<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SelfStockIntro{
	
}
$selfstockintro=new SelfStockIntro();
$title="问题详情";
$shopid=$_SESSION['shopid'];
require_once ('./header.php');
?>
		<h3 class="page-title">
						问题详情<small> </small>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12 blog-page">

						<div class="row-fluid">

							<div class="span12 article-block">

								<h1></h1>
								<p>以下是与飞蛾打印机厂商沟通的聊天记录：</p>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/1.png" alt="">							
								</div>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/2.jpg" alt="">							
								</div>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/3.jpg" alt="">							
								</div>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/4.jpg" alt="">							
								</div>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/5.jpg" alt="">							
								</div>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/6.jpg" alt="">							
								</div>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/explain/7.jpg" alt="">							
								</div>
								
							</div>


						</div>

					</div>

				</div>

		</div>

		<!-- END PAGE -->

	</div>

<?php 
require_once ('./footer.php');
?>
