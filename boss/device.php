<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (_ROOT.'admin/Factory/InterfaceFactory.php');
class Device{
	public function getGoodsData(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getGoodsData();
	}
}
$device=new Device();
$title="办公用品";
$menu="goods";
$clicktag="device";
require_once ('header.php');
$shopid=$_SESSION['shopid'];
$arr=$device->getGoodsData();
?>
<style>
<!--
a{text-decoration:none}
a:hover{text-decoration:none}
-->
</style>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							办公用品 <small>打印机、打印纸、天线、平板电脑等</small>

						</h3>
					
					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->
			<?php foreach ($arr as $key=>$val){?>
				<div class="row-fluid">

					<div class="span12 news-page">

						<h2><?php echo $val['goodstypename'];?></h2>

						<div class="row-fluid" >
						<?php foreach ($val['goods'] as $gkey=>$gval){
							if(empty($gval['online'])){continue;}
						?>
							<div class="span3"  style="margin:8px 10px 0 0 ; padding:10px;-moz-box-shadow:2px 0px 5px #333333; -webkit-box-shadow:2px 0px 5px #333333; box-shadow:2px 0px 5px #333333;">
							<a href="./onegoods.php?goodsid=<?php echo $gval['goodsid'];?>">
									<img class="news-block-img pull-right" src="<?php echo $gval['goodspic']?>" alt="" >
								<p><span style="color:#000;font-size:18px;"><?php echo $gval['goodsname'];?></span></p>
								<p><span style="color:#000;font-size:16px;"><?php echo $gval['goodsdesc'];?></span></p>
								<p><span style="color:red;font-size:16px">街坊价：￥<?php echo $gval['ourprice'];?></span></p>
								</a>
							</div>
						<?php }?>
							<!--end span6-->

						</div>
						

					</div>

				</div>
				<?php }?>

				<!-- END PAGE CONTENT-->

			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->

	<?php 
	require 'footer.php';
	?>