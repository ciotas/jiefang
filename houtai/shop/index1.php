<?php
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
$title="仪表盘";
$menu="dashboard";
require_once ('header.php');
date_default_timezone_set('PRC');
$weekarray=array("日","一","二","三","四","五","六");
class DashBoard{
	public function getHomeData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getHomeData($shopid);
	}
}
$dashboard=new DashBoard();
$shopid=$_SESSION['shopid'];
$arr=$dashboard->getHomeData($shopid);
 ?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
<h3 class="page-title">仪表盘 <small>数据统计</small></h3>
<ul class="breadcrumb">
	<li>
		<i class="icon-home"></i>
		<a href="index.php">主页</a> 
		<i class="icon-angle-right"></i>
	</li>
	<li><a href="#"><?php echo $title;?></a></li>
</ul>
<!-- END PAGE TITLE & BREADCRUMB-->
</div></div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-calendar"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo date("m-d H",time());?></div>
									<div class="desc"><?php echo "星期".$weekarray[date("w")];?></div>
								</div>
								<a class="more" href="#">
								&nbsp;
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="icon-group"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['totalcusnum'];}?></div>
									<div class="desc">顾客</div>
								</div>
								<a class="more" href="#">
								查看更多 <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="icon-shopping-cart"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['billnum'];}?></div>
									<div class="desc">单数</div>
								</div>
								<a class="more" href="#">
								查看更多 <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat red">
								<div class="visual">
									<i class="icon-table"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['tabuses'];}?></div>
									<div class="desc">占用台数</div>
								</div>
								<a class="more" href="#">
								查看更多 <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-yen"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php if(!empty($arr)){echo $arr['totalmoney'];}?>
									</div>
									<div class="desc">                           
										流水
									</div>
								</div>
								<a class="more" href="#">
								查看更多 <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="icon-yen"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['totalcashpay'];}?></div>
									<div class="desc">现金</div>
								</div>
								<a class="more" href="#">
								&nbsp; 
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-credit-card"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['totalunionpay'];}?></div>
									<div class="desc">银联卡</div>
								</div>
								<a class="more" href="#">
								&nbsp; 
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="icon-archive"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['totalvippay'];}?></div>
									<div class="desc">会员卡</div>
								</div>
								<a class="more" href="#">
								查看更多  <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
					</div>
					
					<div class="row-fluid">
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-yen"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php if(!empty($arr)){echo $arr['totalalipay'];}?>
									</div>
									<div class="desc">                           
										支付宝
									</div>
								</div>
								<a class="more" href="#">
								查看更多 <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="icon-yen"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['totalwechatpay'];}?></div>
									<div class="desc">微信支付</div>
								</div>
								<a class="more" href="#">
								&nbsp; 
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-credit-card"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['totalmtpay'];}?></div>
									<div class="desc">美团账户</div>
								</div>
								<a class="more" href="#">
								&nbsp; 
								</a>                 
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="icon-archive"></i>
								</div>
								<div class="details">
									<div class="number"><?php if(!empty($arr)){echo $arr['othermoney'];}?></div>
									<div class="desc">其他账户</div>
								</div>
								<a class="more" href="#">
								查看更多  <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
					</div>
					
					<!-- END DASHBOARD STATS -->
					<div class="clearfix"></div>
					<div class="row-fluid">
						<div class="span6">
							<!-- BEGIN PORTLET-->						
							<!-- END PORTLET-->
						</div>					
					</div>
					<div class="clearfix"></div>
					<div class="row-fluid">
					</div>

					<div class="clearfix"></div>

					<div class="row-fluid">

					</div>

					<div class="clearfix"></div>
					<div class="row-fluid">

					</div>
				</div>
			</div>

			<!-- END PAGE CONTAINER-->    

		</div>

		<!-- END PAGE -->

	</div>

	<!-- END CONTAINER -->
<?php 
require_once ('footer.php');
?>