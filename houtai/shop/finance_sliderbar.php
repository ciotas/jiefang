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
					<span class="title">报表 [内部使用]</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">					
							<li <?php if($clicktag=="day"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>daysheet.php">单日汇总</a></li>
							<li <?php if($clicktag=="days"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>dayssheet.php">按日期</a></li>
							<li <?php if($clicktag=="time"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>timesheet.php">按时段</a></li>
							<li <?php if($clicktag=="daily"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>dailysheet.php">每日流水</a></li>
							<li <?php if($clicktag=="flowsheet"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>flowsheet.php">流水单</a></li>
							<li <?php if($clicktag=="flowinnersheet"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>flowinnersheet.php">出库送货单(供应商)</a></li>
							<li <?php if($clicktag=="unpaycheck"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>unpaycheck.php">未结款单(供应商)</a></li>
							<li <?php if($clicktag=="unpaybill"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>unpaybill.php">未结账单</a></li>
							<li <?php if($clicktag=="billrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>billrecord.php">点菜记录</a></li>
							<li <?php if($clicktag=="foodcalc"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodcalc.php">美食统计</a></li>
							
							<li <?php if($clicktag=="typecalc"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>typecalc.php">类别统计</a></li>
					<!--	<li <?php if($clicktag=="addbillrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>addbillrecord.php">加菜记录</a></li>--!>
				
						<li>
							<a href="javascript:;">
							走势图
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li <?php if($clicktag=="foodtrend"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodtrend.php">销量</a></li>
								<li <?php if($clicktag=="turnovertrend"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>turnovertrend.php">营业额</a></li>
								<li <?php if($clicktag=="cusnumtrend"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>cusnumtrend.php">客流量</a></li>
								<li <?php if($clicktag=="cusnumrealtrend"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>cusnumrealtrend.php">每日客流</a></li>
								<li <?php if($clicktag=="foodsoldbytype"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodsoldbytype.php">类别下的美食</a></li>
							</ul>
						</li>	
						<li>
							<a href="javascript:;">
							消费者
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li <?php if($clicktag=="cusconsume"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>cusconsume.php">明细单</a></li>
							</ul>
						</li>	
					</ul>
				</li>
			
			
				
				<?php }?>
				
			
			
			</ul>

			<!-- END SIDEBAR MENU -->

		</div>
		<!-- END SIDEBAR -->