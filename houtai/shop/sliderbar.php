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
					<span class="title">主页</span>
					<span class="selected"></span>
					</a>
				</li>
				<li <?php if($menu=="profile"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">商家中心</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
					<!-- <li <?php if($clicktag=="myaccount"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>myaccount.php">我的账户</a></li>
						<li <?php if($clicktag=="monthcheck"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>monthcheck.php">本次账单</a></li> -->
						<li <?php if($clicktag=="shopinfo"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>shopinfo.php">商家资料</a></li>
						<li <?php if($clicktag=="shopaccount"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>shopaccount.php">商家账户</a></li>
						<li <?php if($clicktag=="article"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>article.php">宣传文章</a></li>
						<li <?php if($clicktag=="homevisit"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>homevisit.php">访问量</a></li>
						<li <?php if($clicktag=="mypurchasebill"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>mypurchasebill.php">我的订单</a></li>
						<li <?php if($clicktag=="onlinepayrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>onlinepayrecord.php">线上支付账单</a></li>
						<li <?php if($clicktag=="returnmoney"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>returnmoney.php">提现记录</a></li>
						<li <?php if($clicktag=="qrcoderecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>qrcoderecord.php">扫码支付记录</a></li>
						<li <?php if($clicktag=="buyaccountrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>buyaccountrecord.php">购买账户记录</a></li>
						<li <?php if($clicktag=="donateaccountrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>donateaccountrecord.php">赠送账户记录</a></li>
						
					</ul>
				</li> 
				<li <?php if($menu=="table"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-table"></i> 
					<span class="title">桌台</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="tabstatus"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>tabmanage.php">桌台与收银</a></li>
						<li <?php if($clicktag=="takeoutsheet"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>takeoutsheet.php">提前订单</a></li>
						<li <?php if($clicktag=="booklist"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>booklist.php">订桌</a></li>
						<li <?php if($clicktag=="combinetab"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>combinetab.php">并台</a></li>
						<li <?php if($clicktag=="changetab"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>changetab.php">换台</a></li>
						
					</ul>
				</li>
				<?php if($acountarr['accounttype']!="free"){?>
				
				<li <?php if($menu=="goods"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-table"></i> 
					<span class="title">精选商品【新】</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="upgrade"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>upgrade.php">购买账户</a></li>
						<li <?php if($clicktag=="device"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>device.php">办公用品</a></li>
					</ul>
				</li>
				
				<li <?php if($menu=="business"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-bar-chart"></i> 
					<span class="title">营业表</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="guqinglist"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>guqinglist.php">估清表</a></li>
						<li <?php if($clicktag=="returnfood"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>returnfood.php">退菜表</a></li>
						<li <?php if($clicktag=="antibill"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>antibillsheet.php">收银变动表</a></li>
						<li <?php if($clicktag=="tabchanged"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>tabchanged.php">换台表</a></li>
						<li <?php if($clicktag=="addfoodrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>addfoodrecord.php">加菜表</a></li>
						<li <?php if($clicktag=="fooddonate"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>fooddonate.php">赠送表</a></li>
						<li <?php if($clicktag=="foodswitch"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodswitch.php">转菜表</a></li>
						<li <?php if($clicktag=="printerstatus"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>printerstatus.php">打印机状态</a></li>
						<li <?php if($clicktag=="getupdtabstatusrcd"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>getupdtabstatusrcd.php">清台表</a></li>
					</ul>
				</li>
		<?php if($indonateticket){?>
		<li <?php if($menu=="activity"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-sitemap"></i> 
					<span class="title">活动</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">					
							
						<li>
							<a href="javascript:;">
							赠券活动
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li <?php if($clicktag=="selectacttype"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>activity/donateticket/selectacttype.php">活动参与菜品</a></li>
								<li <?php if($clicktag=="rule"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>activity/donateticket/rule.php">赠送规则</a></li>
								<li <?php if($clicktag=="tips"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>activity/donateticket/tips.php">活动说明</a></li>
							</ul>
						</li>	
					</ul>
				</li>
				<?php }?>
				<li <?php if($menu=="dataset"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-cogs"></i> 
					<span class="title">设置</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
					
						<!-- <li <?php if($clicktag=="roles"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>roles.php">角色</a></li> -->
						
						<li <?php if($clicktag=="zone"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>zone.php">区域</a></li>	
						<?php if($_SESSION['role']=="manager"){?>
						<li <?php if($clicktag=="printers"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>printers.php">打印机</a></li>
						<?php }?>
						<li <?php if($clicktag=="tables"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>tables.php">桌台</a></li>	
						<li>
							<a href="javascript:;">
							美食
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								
								<li <?php if($clicktag=="foodtype"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodtype.php">美食分类</a></li>
								<li <?php if($clicktag=="foodmanage"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodmanage.php">我的菜单</a></li>
								<li <?php if($clicktag=="uppic"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>upfoodpic.php">美食图片</a></li>
								
								<?php if($_SESSION['role']=="manager"){?>
								<li <?php if($clicktag=="upfood"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>upfoodxls.php">批量导入</a></li>
								<li <?php if($clicktag=="menucopy"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>menucopy.php">复制菜单</a></li>
								<?php }?>
								<li <?php if($clicktag=="foodpics"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>foodpics.php">商品图片库</a></li>
							</ul>
						</li>
						
						<?php if($_SESSION['role']=="manager"){?>
						<li <?php if($clicktag=="jobset"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>jobset.php">职位</a></li>
						<li <?php if($clicktag=="servers"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>servers.php">员工</a></li>
						<li <?php if($clicktag=="handle"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>handle.php">配置</a></li>
						
						<?php }?>
						<li <?php if($clicktag=="coupontype"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>coupontype.php">美食券</a></li>	
						<li <?php if($clicktag=="cussheetadv"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>cussheetadv.php">黑板报</a></li>
						
					</ul>
				</li>
				
				<li <?php if($menu=="stock"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-truck"></i> 
					<span class="title">库存</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">					
						<!-- 这里单列 -->
					<li>
							<a href="javascript:;">
							酒水库存
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<!-- <li <?php if($clicktag=="autostockfoodinfo"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/autostockfoodinfo.php">库存信息</a></li> -->
								<li <?php if($clicktag=="autostockfood"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/autostockfood.php">增减、盘点库存</a></li>
							<!-- <li <?php if($clicktag=="daystock"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/daystock.php">库存盘点</a></li> --> 
								<li <?php if($clicktag=="dailyconsume"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/dailyconsume.php">日消耗表</a></li>
								<li <?php if($clicktag=="stockcalc"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/stockcalc.php">入库汇总</a></li>
								<li <?php if($clicktag=="addautostockfoodrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/addautostockfoodrecord.php">入库记录</a></li>
								<li <?php if($clicktag=="getstockin"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/getstockin.php">入库表</a></li>
								<li <?php if($clicktag=="outgoing"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/outgoing.php">出库表</a></li>
								<li <?php if($clicktag=="serverreport"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/serverreport.php">销售记录</a></li>
							</ul>
						</li>	
				 <li>
							<a href="javascript:;">
							原料库存
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li <?php if($clicktag=="rawtype"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/rawtype.php">原料分类</a></li>
								<li <?php if($clicktag=="rawinfo"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/rawinfo.php">原料信息</a></li>
								<li <?php if($clicktag=="addraw"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/checkrole.php?page=addraw">原料入库</a></li>
								<li <?php if($clicktag=="raw"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/raw.php">库存盘点</a></li>
								<li <?php if($clicktag=="sumraw"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/sumraw.php">入库汇总</a></li>
								<li <?php if($clicktag=="getaddrawrecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>stock/getaddrawrecord.php">入库记录</a></li>								
							</ul>
							
						</li>	 
					</ul>
				</li>
				
				<li <?php if($menu=="vip"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-heart-empty"></i> 
					<span class="title">会员</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
					<?php if($_SESSION['role']=="manager"){?>
					<!-- 	<li <?php if($clicktag=="sendvip"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>sendvip.php">送会员</a></li> -->
					<?php }?>
					<li <?php if($clicktag=="recharge"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>recharge.php">充值中心</a></li>
					<li <?php if($clicktag=="vipreg"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>vipreg.php">会员注册</a></li>
						<li <?php if($clicktag=="vips"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>vips.php">会员列表</a></li>
						<!-- <li <?php if($clicktag=="vippay"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>vippay.php">卡支付</a></li> -->
						<li <?php if($clicktag=="viprecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>vippayrecord.php">消费记录</a></li>
						<li <?php if($clicktag=="chargerecord"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>chargerecord.php">充值记录</a></li>
					</ul>
				</li>
				
				<!-- 
				<li <?php if($menu=="sign"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-lemon"></i> 
					<span class="title">签单</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag==""){echo 'class="active"';}?>><a href="">签单人</a></li>
						<li <?php if($clicktag==""){echo 'class="active"';}?>><a href="<?php echo $base_url;?>signsheet.php">签单详情</a></li>
						<li <?php if($clicktag==""){echo 'class="active"';}?>><a href="">签单记录</a></li>
					</ul>
				</li> -->
				
				<?php }?>
				<li <?php if($menu=="help"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-bell"></i> 
					<span class="title">产品与合作</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<!-- <li <?php if($clicktag=="faq"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>faq.php">常见问题</a></li> -->
						<li <?php if($clicktag=="product"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>product.php">关于产品</a></li>
						<li <?php if($clicktag=="aboutus"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>aboutus.php">关于我们</a></li>
						</ul>
				</li>
				
				<li <?php if($menu=="intro"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-gift"></i> 
					<span class="title">新功能</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="prebill"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>prebillintro.php">扫码支付</a></li>
						<li <?php if($clicktag=="cusadv"){echo 'class="active"';}?>><a href="<?php echo $base_url;?>cusadv.php">广告平台</a></li>
						</ul>
				</li>
								
			</ul>

			<!-- END SIDEBAR MENU -->

		</div>
		<!-- END SIDEBAR -->