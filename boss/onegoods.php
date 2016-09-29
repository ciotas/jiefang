<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (_ROOT.'admin/Factory/InterfaceFactory.php');
class OneGoods{
	public function getOneGoodsData($goodsid){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getOneGoodsData($goodsid);
	}
}
$onegoods=new OneGoods();
$title="打印机";
$menu="goods";
$clicktag="device";
require_once ('header.php');
$bossid=$_SESSION['bossid'];
$arr=array();
if(isset($_GET['goodsid'])){
	$goodsid=$_GET['goodsid'];
	$arr=$onegoods->getOneGoodsData($goodsid);
}
?>
						<h3 class="page-title">
						<?php echo $arr['goodsname'];?>
							 <small></small>

						</h3>
					</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">

					<div class="span12">

						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<div class="tab-pane profile-classic row-fluid" >

									<div class="span4"><img src="<?php echo $arr['goodspic'];?>" alt="" /></div>

									<div class=" span8">
										<div class="alert alert-success">
										<?php if(!empty($arr['otherprice'])){?>
										 <span style="text-decoration:line-through">原价：<?php echo $arr['otherprice'];?>元/<?php echo $arr['goodssoldunit'];?></span>
										 <?php }?>
											<br>街坊价：<span style="color: red;font-size:22px;"><?php echo $arr['ourprice'];?>元/<?php echo $arr['goodssoldunit'];?></span>
											<form action="<?php echo $root_url;?>houtai/shop/wappay/alibuygoods.php" method="post">
											<input type="hidden" name="goodsid" value="<?php echo $arr['goodsid'];?>">
											<input type="hidden" name="goodsname" value="<?php echo $arr['goodsname'];?>">
											<input type="hidden" name="goodsprice" value="<?php echo $arr['ourprice'];?>">
											<input type="hidden" name="soldunit" value="<?php echo $arr['goodssoldunit'];?>">
											<input type="hidden" name="from" value="boss">
											<input type="hidden" name="via" value="bossid">
											<input type="hidden" name="value" value="<?php echo $bossid;?>">
											<br>数量：
													<select class="small m-wrap" tabindex="3" id="goodsnum" name="goodsnum" >
															<?php for ($i=1;$i<=50;$i++){?>
															<option value="<?php echo $i;?>"><?php echo $i;?></option>
															<?php }?>
														</select> <?php echo $arr['goodssoldunit'];?>
											<br><br>
											<a href="./device.php" class="btn red">返回</a>&nbsp;&nbsp;&nbsp;
											<button type="submit"  class="btn blue">立即购买</button>
<!-- 											<button type="button" id="pay" class="btn blue">立即购买</button> -->
											</form>
										</div>
										
									</div>
									<ul class="span8" style="list-style-type: none">
									<?php if(!empty($arr)){
										$goodsformatstr=$arr['goodsformat'];
										$goodsformatarr=explode("|", $goodsformatstr);
										foreach ($goodsformatarr as $fkey=>$fval){
									?>
										<li><span><?php echo $fval;?></span></li>
										<?php }}?>
									</ul>

								</div>	
								<!--end tab-pane-->
						</div>

						<!--END TABS-->

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
require_once 'footer.php';
?>