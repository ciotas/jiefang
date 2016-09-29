<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class EditArticle{
	public function getArticleByshopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getArticleByshopid($shopid);
	}
}
$editarticle=new EditArticle();
$title="写宣传文章";
$menu="profile";
$clicktag="article";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$htmlData=$editarticle->getArticleByshopid($shopid);
?>
<link rel="stylesheet" href="../../kindeditor/themes/default/default.css" />
	<link rel="stylesheet" href="../../kindeditor/plugins/code/prettify.css" />
	<script charset="utf-8" src="../../kindeditor/kindeditor.js"></script>
	<script charset="utf-8" src="../../kindeditor/lang/zh_CN.js"></script>
	<script charset="utf-8" src="../../kindeditor/plugins/code/prettify.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content1"]', {
				cssPath : '../../kindeditor/plugins/code/prettify.css',
				uploadJson : '../../kindeditor/php/upload_json.php',
				fileManagerJson : '../../kindeditor/php/file_manager_json.php',
				allowFileManager : true,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
				}
			});
			prettyPrint();
		});
	</script>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">


						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							写宣传文章<small></small>

						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->
				
				<!--/row-fluid-->   
				<!-- Meer Our Team -->

					<div class="headline">
						<form name="example" method="post" action="./interface/uparticle.php" >
							<textarea name="content1" style="width:700px;height:500px;visibility:hidden;"><?php echo htmlspecialchars($htmlData); ?></textarea>
							<br />
							<button type="button" onclick="window.location.href='./article.php'" class="btn " >返回</button> 
							<button type="submit" class="btn green" >提交</button>
						</form>

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