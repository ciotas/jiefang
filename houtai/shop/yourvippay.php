<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class YourVipPay{
	public function getVipConsumeRecord($viprcid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getVipConsumeRecord($viprcid);
	}
}
$yourvippay=new YourVipPay();
$title="会员卡消费";
$menu="vip";
$shopid=$_SESSION['shopid'];
$arr=array();
$uphone="";
require_once ('header.php');
if(isset($_GET['viprcid'])){
	$viprcid=$_GET['viprcid'];
	if(!empty($viprcid)){
		$resultstatus="1";
		$arr=$yourvippay->getVipConsumeRecord($viprcid);
	}elseif(viprcid=="0"){
		$resultstatus="0";
	}
}
if(!empty($arr)){
	$phonecrypt = new CookieCrypt($cusphonekey);
	$uphone=$phonecrypt->decrypt($arr['userphone']);
}
?>

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">
			
				<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							会员消费结果
							 <small>账单</small>

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

							<li><a href="#">会员消费结果</a></li>

						</ul>

					</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">

				
					<div class="span12">

					<div class="portlet-body">
								<?php if($resultstatus=="1"){?>
								<div class="alert alert-success">
									<button class="close" data-dismiss="alert"></button>
									<strong>会员卡消费成功！</strong> 谢谢 O(∩_∩)O~
								</div>
								<?php }elseif($resultstatus=="0"){?>
								
								<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<strong>会员卡消费失败！</strong> 请先查询消费记录，如没有此次消费请重试 /(ㄒoㄒ)/~~
								</div>
								<?php }?>
							</div>
						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

									<div class="span2">
									<img src="<?php if(!empty($arr['photo'])){echo $arr['photo'];}?>" alt="" /></div>

									<ul class="span10">

										<li><span>用户:</span> <?php if(!empty($arr)){echo $arr['nickname'];}?></li>
										
										<li><span>开饭啦账号:</span> <?php if(!empty($arr)){echo $uphone;}?></li>

										<li><span>性别:</span> <?php if(!empty($arr)){if($arr['sex']=="male"){echo "男";}elseif($arr['sex']=="female"){echo "女";}else{echo "未知";}}?></li>
										
										<li><span>卡名:</span> <?php if(!empty($arr)){echo $arr['cardname'];}?></li>
										<li><span>本次消费:</span> ￥<?php if(!empty($arr)){echo $arr['vippaymoney'];}?></li>
										<li><span>账户余额:</span>￥<?php if(!empty($arr)){echo $arr['accountbalance'];}?></li>
										<li><span>消费时间:</span> <?php if(!empty($arr)){echo date("Y-m-d H:i:s",$arr['timestamp']);}?></li>

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

	