<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SliderBar{
	public function getMyShoplistData($bossid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getMyShoplistData($bossid);
	}
	public function getPuhongShopZone($bossid){
	    return Boss_InterfaceFactory::createInstanceBossOneDAL()->getPuhongShopZone($bossid);
	}
	
}
$sliderbar=new SliderBar();
$bossid=$_SESSION['bossid'];
$myshops=$sliderbar->getMyShoplistData($bossid);
$puhongshops=$sliderbar->getPuhongShopZone($bossid);

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
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
				<li <?php if($menu=="profile"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class=" icon-heart"></i> 
					<span class="title">个人中心</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="mybills"){echo 'class="active"';}?>><a href="mybills.php">我的订单</a></li>
					</ul>
				</li>
				<?php }?> 
				<li <?php if($menu=="shoplist"){echo 'class="active"';}?>>
					<a href="shoplist.php">
					<i class="icon-plus-sign"></i> 
					<span class="title">添加分店</span>
					<span class="selected"></span>
					</a>
				</li>
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
				<li <?php if($menu=="sheet"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-usd"></i> 
					<span class="title">分店营业额</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
					<?php foreach ($myshops as $key=>$val){?>
					<li <?php if($clicktag=="sheet_".$val['shopid']){echo 'class="active"';}?>><a href="sheet.php?shopid=<?php echo $val['shopid'];?>&shopname=<?php echo $val['shopname'];?>"><?php echo $val['shopname'];?></a></li>
					<?php }?>
					</ul>
				</li> 
				<?php }?>
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
			 	<li <?php if($menu=="shop"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class=" icon-bar-chart"></i> 
					<span class="title">分店统计</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
					<?php foreach ($myshops as $key=>$val){?>
					<li <?php if($clicktag=="shoplist_".$val['shopid']){echo 'class="active"';}?>><a href="shops.php?shopid=<?php echo $val['shopid'];?>&shopname=<?php echo $val['shopname'];?>"><?php echo $val['shopname'];?></a></li>
					<?php }?>
					</ul>
				</li>
				<?php }?> 
				<?php if($_SESSION['bossid']=="5747e7295bc1099a068b45de"){?>
					<li <?php if($menu=="shop"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class=" icon-bar-chart"></i> 
					<span class="title">分仓库统计</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">			
					<?php foreach ($puhongshops as $dist=>$phval){?>		
						<li <?php if($firstclick==$dist){echo 'class="active"';}?>>
							<a href="javascript:;">
							<?php echo $dist;?>
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
						<!-- 	<li <?php if($clicktag=="zonecalc"){echo 'class="active"';}?>><a href="zonecalc.php?zonename=<?php echo $dist;?>">总仓</a></li> -->
							<?php foreach ($phval as $shopval){?>
								<li <?php if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>><a href="shops.php?shopid=<?php echo $shopval['shopid'];?>&shopname=<?php echo $shopval['shopname'];?>&dist=<?php echo $dist;?>"><?php echo $shopval['shopname'];?></a></li>
							<?php }?>
							</ul>
						
						<?php }?>
					</ul>
				</li> 	
				<?php }?>
				
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
				<li <?php if($menu=="goods"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class=" icon-gift"></i> 
					<span class="title">精选商品【新】</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="device"){echo 'class="active"';}?>><a href="device.php">办公用品</a></li>
					</ul>
				</li> 
				<?php }?>
				<li <?php if($menu=="login"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class=" icon-sitemap"></i> 
					<span class="title">分店登陆</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
					<?php foreach ($myshops as $key=>$val){?>
					<li <?php if($clicktag=="shoplist_".$val['shopid']){echo 'class="active"';}?>><a href="./loginshop.php?shopid=<?php echo $val['shopid'];?>"><?php echo $val['shopname'];?></a></li>
					<?php }?>
					</ul>
				</li> 
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
				<li <?php if($menu=="foods"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-heart-empty"></i> 
					<span class="title">商品</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="goodstype"){echo 'class="active"';}?>><a href="goodstype.php">商品分类</a></li>
						<li <?php if($clicktag=="goods"){echo 'class="active"';}?>><a href="goods.php">商品</a></li>
					</ul>
				</li>
				<?php }?>
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
				<li <?php if($menu=="vip"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class="icon-heart-empty"></i> 
					<span class="title">会员</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($clicktag=="vipset"){echo 'class="active"';}?>><a href="vipset.php">卡设置</a></li>
						<li <?php if($clicktag=="viptag"){echo 'class="active"';}?>><a href="viptag.php">会员标签</a></li>
						<li <?php if($clicktag=="vips"){echo 'class="active"';}?>><a href="vips.php">会员列表</a></li>
						<li <?php if($clicktag=="viprecord"){echo 'class="active"';}?>><a href="vippayrecord.php">消费记录</a></li>
						<li <?php if($clicktag=="chargerecord"){echo 'class="active"';}?>><a href="chargerecord.php">充值记录</a></li>
					</ul>
				</li>
				<?php }?>
				<?php if($_SESSION['bossid']=="5747e7295bc1099a068b45de"){?>
				<li <?php if($menu=="shoplist"){echo 'class="active"';}?>>
					<a href="javascript:;">
					<i class=" icon-bar-chart"></i> 
					<span class="title">分仓库数据</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">			
					<?php foreach ($myshops as $key=>$val){?>		
						<li <?php // if(==$dist){echo 'class="active"';}?>>
							<a href="javascript:;">
							<?php echo $val['shopname'];?>
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
							
							
								<li <?php //if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>>
								<a href="1dailyconsume.php?shopid=<?php echo $val['shopid'];?>">
								日消耗表
								</a>
								</li>
								<li <?php //if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>>
								<a href="1stockcalc.php?shopid=<?php echo $val['shopid'];?>">
								入库汇总
								</a>
								</li>
								<li <?php //if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>>
								<a href="1addautostockfoodrecord.php?shopid=<?php echo $val['shopid'];?>">
								入库记录
								</a>
								</li>
								<li <?php //if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>>
								<a href="1getstockin.php?shopid=<?php echo $val['shopid'];?>">
								入库表
								</a>
								</li>
								<li <?php //if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>>
								<a href="1outgoing.php?shopid=<?php echo $val['shopid'];?>">
								出库表
								</a>
								</li>
								<li <?php //if($clicktag=="shoplist_".$shopval['shopid']){echo 'class="active"';}?>>
								<a href="1serverreport.php?shopid=<?php echo $val['shopid'];?>">
								销售记录
								</a>
								</li>
							
							</ul>
						
						<?php }?>
					</ul>
				</li> 
				<?php } ?>	
			</ul>

			<!-- END SIDEBAR MENU -->

		</div>
		<!-- END SIDEBAR -->