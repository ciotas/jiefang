<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SelfStockIntro{
	
}
$selfstockintro=new SelfStockIntro();
$title="自动库存盘点使用说明";
$menu="stock";
$clicktag="selfstockintro";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
?>
		<h3 class="page-title">
						自动库存盘点使用说明<small> </small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="<?php echo $base_url;?>index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">库存</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="<?php echo $base_url;?>/stock/selfstockintro.php">自动库存盘点使用说明</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12 blog-page">

						<div class="row-fluid">

							<div class="span12 article-block">

								<h1></h1>
								<strong>1. 在美食管理里，将需要自动的盘点“自动库存”的开关打开，如下图：</strong>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/autostock/autostockintro1.png" alt="">							
								</div>
								
								<!--end news-tag-data-->

								<strong>2. 然后在左边菜单栏依次选择“库存”——“自动库存”——“添加库存”</strong>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/autostock/autostockintro2.png" alt="">							
								</div>
								<strong>打开如下：</strong><br>
								<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/autostock/autostockintro3.png" alt="">							
								</div>
									<p>1. 请先编辑信息，再新增库存！</p>
									<p>2. 包装量和包装单位：如今日进了10箱酒，包装量就为10，包装单位就为箱</p>
									<p>3. 零售量和零售单位：如今日进了8瓶酒，零售量就为8，零售单位就为瓶（零售单位这里不需要设置，系统会自动获取）</p>
									<p>4. 包装率：如一箱12瓶酒，包装率就为12</p>
									<p>5. 合计库存：系统会自动计算，计算公式为 合计库存=包装量×包装率+零售量</p>
									<strong style="color: red">若每日都有进货，请及时新增库存，系统会自动累加！</strong><br>
							</div>

							<h3>如何盘点库存呢？</h3>
							<strong>点击库存菜单内的"盘点库存"</strong>
							<div class="blog-tag-data">
									<img src="<?php echo $base_url;?>media/image/autostock/autostockintro4.png" alt="">							
								</div>
							<strong>可以查看每种品项每天消耗数，剩余库存数。</strong>
							<strong>可以在"库存"内的"日消耗表"中，查询烟酒水的消耗情况，无需再到报表统计里面查询!</strong>
						</div>

					</div>

				</div>

		</div>

		<!-- END PAGE -->

	</div>

<?php 
require_once ('../footer.php');
?>
