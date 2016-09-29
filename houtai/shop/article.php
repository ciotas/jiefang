<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Article{
	public function getArticleByshopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getArticleByshopid($shopid);
	}
}
$article=new Article();
$title="宣传文章";
$menu="profile";
$clicktag="article";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$htmldata=$article->getArticleByshopid($shopid);
?>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">


						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							宣传文章<small></small>&nbsp;&nbsp;&nbsp;
							<button onclick="window.location.href='./editarticle.php'" type="button" class="btn blue">编辑文章</button>
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->
				
				<!--/row-fluid-->   
				<!-- Meer Our Team -->

					<div class="headline">
					<span class="span8">
					<?php echo $htmldata;?>
					</span>
				</div>

				<!--/thumbnails-->

				<!-- //End Meer Our Team -->        
			
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