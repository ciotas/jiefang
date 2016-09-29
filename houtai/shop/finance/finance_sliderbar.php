<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'/startsession.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SliderBar{
	public function getShopaccounttype($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getShopaccounttype($shopid);
	}
	public function isInDonateticketTable($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->isInDonateticketTable($shopid);
	}
}
$sliderbar=new SliderBar();
$shopid=$_SESSION['shopid'];
$acountarr=$sliderbar->getShopaccounttype($shopid);
// print_r($acountarr);exit;
$indonateticket=$sliderbar->isInDonateticketTable($shopid);
?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        
			<ul class="page-sidebar-menu">
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
			<br>
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
				<li <?php if($menu=="dashboard"){echo 'class="active"';}?>>
					<a href="<?php echo $base_url;?>index.php">
					<i class="icon-home"></i> 
					<span class="title">返回主页</span>
					<span class="selected"></span>
					</a>
				</li>
				<?php if($acountarr['accounttype']!="free"){?>
				<li <?php if($menu=="datasheet"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-sitemap"></i> 
					<span class="title">报表 [外]</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">					
							<li <?php if($clicktag=="day"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/daysheet.php">单日汇总</a></li>
							<li <?php if($clicktag=="days"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/dayssheet.php">按日期</a></li>
							<li <?php if($clicktag=="time"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/timesheet.php">按时段</a></li>
							<!-- <li <?php if($clicktag=="daily"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/dailysheet.php">每日流水</a></li>
							<li <?php if($clicktag=="billrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/billrecord.php">点菜记录</a></li> -->
					<!--	<li <?php if($clicktag=="addbillrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/addbillrecord.php">加菜记录</a></li>--!>
						<li>
							<a href="javascript:;">
							统计
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li <?php if($clicktag=="foodcalc"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/foodcalc.php">美食统计</a></li>
								<li <?php if($clicktag=="typecalc"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>finance/typecalc.php">类别统计</a></li>
							</ul>
						</li>	
					</ul>
				</li>
				<?php }?>
			</ul>

			<!-- END SIDEBAR MENU -->

		</div>
		<!-- END SIDEBAR -->