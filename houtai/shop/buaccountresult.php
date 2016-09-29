<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BuyAccountResult{
	public function getBuyAccountRecord($buyid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getBuyAccountRecord($buyid);
	}
}
$buyaccountresult=new BuyAccountResult();
$title="购买结果";
$menu="help";
$shopid=$_SESSION['shopid'];
$arr=array();
require_once ('header.php');
if(isset($_GET['paystatus'])){
	$paystatus=$_GET['paystatus'];
	$buyid=$_GET['buyid'];
	if(!empty($buyid)){
		$arr=$buyaccountresult->getBuyAccountRecord($buyid);
	}
}

?>

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">
			
				<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
						购买结果
							 <small></small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">首页</a> 

								<span class="icon-angle-right"></span>

							</li>
							<li>

								<a href="#">产品与合作</a>

								<span class="icon-angle-right"></span>

							</li>

							<li><a href="#">购买结果</a></li>

						</ul>

					</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">

				
					<div class="span12">

					<div class="portlet-body">
								<?php if($paystatus=="ok"){?>
								<div class="alert alert-success">
									<button class="close" data-dismiss="alert"></button>
									<strong>付款成功！</strong> 谢谢 O(∩_∩)O~
								</div>
								<?php }elseif($paystatus=="fail"){?>
								
								<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<strong>付款失败！</strong> 请重试 /(ㄒoㄒ)/~~
								</div>
								<?php }?>
							</div>
						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

									<div class="span2"><img src="<?php if(!empty($arr)){echo $arr['logo'];}?>" alt="" /></div>

									<ul class="span10">

										<li><span>用户:</span> <?php if(!empty($arr)){echo $arr['shopname'];}?></li>
										
										<li><span>交易号:</span> <?php if(!empty($arr)){echo $arr['alipaytradeno'];}?></li>

										<li><span>购买商品:</span> <?php if(!empty($arr)){
											switch ($arr['buytype']){
												case "permonth":echo "300元/月账户";break;
												case "perhalfyear":echo "1600元/6月账户";break;
												case "peryear":echo "3000元/年账户";break;
											}
											
										}?></li>
										
										<li><span>付款金额:</span>￥<?php if(!empty($arr)){echo $arr['paymoney'];}?></li>
										<li><span>购买时间:</span> <?php if(!empty($arr)){echo date("Y-m-d H:i:s",$arr['paytime']);}?></li>
										<li><span>截止日期:</span><?php if(!empty($arr)){echo date("Y-m-d H:i:s",$arr['endtime']);}?></li>
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
	require_once ('./footer.php');
	?>

	