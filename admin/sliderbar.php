<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SliderBar{
	
}
$sliderbar=new SliderBar();
$shopid=$_SESSION['shopid'];

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
					<a href="index.php">
					<i class="icon-home"></i> 
					<span class="title">主页</span>
					<span class="selected"></span>
					</a>
				</li>
				<li <?php if($menu=="manage"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">商家管理</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="shopinit"){echo 'class="active"';}?>><a href="shopinit.php">商家配置</a></li>
						
					<li <?php if($clicktag=="shopinitvice"){echo 'class="active"';}?>><a href="shopinitvice.php">商家配置二</a></li> 
						<li <?php if($clicktag=="shopinfo"){echo 'class="active"';}?>><a href="shopinfo.php">商家信息</a></li>
						<li <?php if($clicktag=="codeinfo"){echo 'class="active"';}?>><a href="codeinfo.php">验证码信息</a></li>
						<li <?php if($clicktag=="businesszone"){echo 'class="active"';}?>><a href="businesszone.php">商圈</a></li>
						<li <?php if($clicktag=="donateacccount"){echo 'class="active"';}?>><a href="donateacccount.php">赠送账户</a></li>
						<li <?php if($clicktag=="transfernote"){echo 'class="active"';}?>><a href="transfernote.php">转账记录</a></li>
						<li <?php if($clicktag=="dayreport"){echo 'class="active"';}?>><a href="dayreport.php">商家日结单</a></li>
					</ul>
				</li> 
				<li <?php if($menu=="goods"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">商品设置</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="goodstype"){echo 'class="active"';}?>><a href="goodstype.php">商品分类</a></li>
						<li <?php if($clicktag=="goods"){echo 'class="active"';}?>><a href="goods.php">商品列表</a></li>
						</ul>
				</li> 
				<li <?php if($menu=="data"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">数据服务</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="online"){echo 'class="active"';}?>><a href="online.php">营业情况</a></li>
						<li <?php if($clicktag=="shoperaccount"){echo 'class="active"';}?>><a href="shoperaccount.php">商家账户余额</a></li>
						<li <?php if($clicktag=="getprinters"){echo 'class="active"';}?>><a href="getprinters.php">打印机状态</a></li>
						<li <?php if($clicktag=="cusflow"){echo 'class="active"';}?>><a href="cusflow.php">客流</a></li>
						<li <?php if($clicktag=="billnumflow"){echo 'class="active"';}?>><a href="billnumflow.php">下单数</a></li>
						<li <?php if($clicktag=="moneyflow"){echo 'class="active"';}?>><a href="moneyflow.php">流水</a></li>
					</ul>
				</li> 
					<li <?php if($menu=="user"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">用户管理</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="userlist"){echo 'class="active"';}?>><a href="userList.php">用户列表</a></li>
					</ul>
				</li> 
			 	
	
			</ul>

			<!-- END SIDEBAR MENU -->

		</div>
		<!-- END SIDEBAR -->