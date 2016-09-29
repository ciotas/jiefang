<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class MonthCheck{
	public function getChargeResult($recordid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getChargeResult($recordid);
	}
}
$monthcheck=new MonthCheck();
$title="本月账单";
$menu="profile";
$clicktag="monthcheck";
$shopid=$_SESSION['shopid'];
$arr=array();
require_once ('header.php');


?>

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">
			
				<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							本月账单
							 <small>上次累计账单</small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">首页</a> 

								<span class="icon-angle-right"></span>

							</li>
							<li>

								<a href="#">我的会员</a>

								<span class="icon-angle-right"></span>

							</li>

							<li><a href="monthcheck.php">本月账单</a></li>

						</ul>

					</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">

				
					<div class="span12">

					<div class="portlet-body">
						
							
							</div>
						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

									<div class="span2"><img src="<?php if(!empty($arr)){echo $arr['logo'];}?>" alt="" /></div>

									<ul class="span10">

										<li><span>订单号:</span> <?php if(!empty($arr)){echo $arr['nickname'];}?></li>
										
										<li><span>起始时间:</span> <?php if(!empty($arr)){echo $arr['userphone'];}?></li>

										<li><span>结束时间:</span> <?php if(!empty($arr)){if($arr['sex']=="male"){echo "男";}elseif($arr['sex']=="female"){echo "女";}else{echo "未知";}}?></li>
										
										<li><span>上次支付时间:</span>￥<?php if(!empty($arr)){echo $arr['chargemoney'];}?></li>
										<li><span>本期限销售额:</span> <?php if(!empty($arr)){echo $arr['cardname'];}?></li>
										
										<li><span>支付状态:</span>￥<?php if(!empty($arr)){echo $arr['sendmoney'];}?></li>

										<li><span>应付:</span> <?php if(!empty($arr)){echo date("Y-m-d H:i:s",$arr['timestamp']);}?></li>

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

	