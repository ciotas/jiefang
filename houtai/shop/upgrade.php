<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Upgrade{
	
}
$upgrade=new Upgrade();
$title="账户升级";
$menu="help";
$clicktag="upgrade";
$shopid=$_SESSION['shopid'];
require_once ('header.php');

?>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">


						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							账户升级 <small></small>

						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid margin-bottom-30">
					
					<div class="span6">
					<blockquote class="hero">
					<p style="color:green;font-size:18px;" ><i class="icon-eye-open"></i> 试用账户
						 </p>
						 <small>享受以下功能和服务</small>
					</blockquote>
					
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 标准账户试用15天，注册即可享用！</li>
						</ul>
					<blockquote class="hero">
					</blockquote>
						<!-- Blockquotes -->
				<br>
					<blockquote class="hero">
						 <p style="color:green;font-size:18px;"><i class="icon-glass"></i> 免费账户
						 </p>
						 <small>享受以下功能和服务</small>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 店长管理app，实时查看营业状态，营业额，管理本店人员</li>
							<li><i class="icon-ok"></i> 服务员app，为客人点餐</li>
							<li><i class="icon-ok"></i> 领班经理app，可点餐、退菜、反结、赠送、清台等</li>
							<li><i class="icon-ok"></i> 使用桌台状态</li>
							<li><i class="icon-ok"></i> 便捷的网页收银</li>
							<li><i class="icon-ok"></i> 并台、收银交班</li>
							<li><i class="icon-ok"></i> 设置美食、打印机信息</li>
						</ul>
					<blockquote class="hero">
					</blockquote>
						<!-- Blockquotes -->
				<br>
				
					
					<blockquote class="hero">
						 <p style="color:green;font-size:18px;"><i class="icon-star"></i> 高级账户</p>
						 <small>成为高级账户，只需以下条件</small>
					</blockquote>
					
					<blockquote class="hero">
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 有10家以上的连锁店或在一个月内推荐5及以上家新客户注册并使用</li>
							
						</ul>
						<blockquote class="hero">
						<p style="color: green;font-size:16px;">享受标准账户全部服务，另外还享有以下服务：	</p>
						</blockquote>
					<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 每月服务费8折优惠</li>
							<li><i class="icon-ok"></i> 更多高级服务，如菜品销量走势图，最受欢迎的美食等功能打折优惠！</li>
							<li><i class="icon-ok"></i> 为连锁店提供免费的总店web后台，随时随地查看各店营业状态</li>
							<li><i class="icon-ok"></i> 在顾客端app首页免费广告位15天，让更多的用户知道您！</li>
							<li><i class="icon-ok"></i> 更多资源免费提供！</li>
						</ul>
						<!-- Blockquotes -->
				<br>
					</div>
					
					<div class="span6">
					<blockquote class="hero">
						 <p style="color:green;font-size:18px;"><i class="icon-thumbs-up"></i> 标准账户</p>
						 <small>享受以下功能和服务</small>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 店长管理app，实时查看营业状态，营业额，管理本店人员</li>
							<li><i class="icon-ok"></i> 服务员app，为客人点餐</li>
							<li><i class="icon-ok"></i> 领班经理app，可点餐、退菜、反结、赠送、清台等</li>
							<li><i class="icon-ok"></i> 桌台状态，实时查看每桌消费详情</li>
							<li><i class="icon-ok"></i> 便捷的网页收银，一个网页搞定收银</li>
							<li><i class="icon-ok"></i> 并台、收银交班</li>
							<li><i class="icon-ok"></i> 设置美食、打印机信息</li>
							<li><i class="icon-ok"></i> 查看估清表、退菜表、收银变动表等各种营业表</li>
							<li><i class="icon-ok"></i> 设置划菜单底部广告，为您带来收入</li>
							<li><i class="icon-ok"></i> 全新体验的库存管理，烟酒水自动库存和原料库存，免去您盘点库存的烦恼</li>
							<li><i class="icon-ok"></i> 给用户发放各种电子会员卡、储值卡</li>
							<li><i class="icon-ok"></i> 更加详细和专业的业务报表</li>
							<li><i class="icon-ok"></i> 掌握每一个顾客的消费详情和统计，筛选优质顾客</li>
							<li><i class="icon-ok"></i> 支持线上支付(现只支持支付宝，将逐步上线微信支付、扫码支付和银联支付)</li>
							<li><i class="icon-ok"></i> 7×24小时一对一专业免费在线服务，再也不用坐等师傅上门！</li>
							<li><i class="icon-ok"></i> 未来更多更强大的功能，第一时间抢先用...</li>
						</ul>
						<blockquote class="hero">
						<p style="color: orange;font-size:14px;">每推荐一家餐厅成为我们的客户，赠送两个月价值￥398 的服务期限</p>
					<!-- 	<p style="color: green;font-size:16px;">做一次活动推荐顾客使用开饭啦app，赠送一个月！</p> -->
						</blockquote>
						<blockquote class="hero">
						 <form action="./directpay/alipayapi.php" method="post">
		                     <input type="hidden" name="WIDout_trade_no"  value="<?php echo time().mt_rand(100, 999);?>" >
		                	<input type="hidden" name="WIDsubject" value="购买趣店月账户"><!-- 订单名称 -->
		                	<input type="hidden" name="WIDtotal_fee" value="299" >
		                	<input type="hidden" name="WIDbody" value="续费购买账户一个月"><!-- 订单描述 -->
		                	<input type="hidden" name="WIDshow_url" value=""><!-- 商品展示路劲 -->
		                	<button type="submit" class="btn green" ><i class="icon-gift"></i> 299元/月=9.9元/天</button>
                    	</form>
                    
                     <form action="./directpay/alipayapi.php" method="post">
	                     <input type="hidden" name="WIDout_trade_no"  value="<?php echo time().mt_rand(100, 999);?>" >
	                	<input type="hidden" name="WIDsubject" value="购买趣店半年账户"><!-- 订单名称 -->
	                	<input type="hidden" name="WIDtotal_fee" value="1080" >
	                	<input type="hidden" name="WIDbody" value="续费购买账户一个月"><!-- 订单描述 -->
	                	<input type="hidden" name="WIDshow_url" value=""><!-- 商品展示路劲 -->
	                	<button type="submit" class="btn yellow" ><i class="icon-gift"></i> 1599元/6个月=8.8元/天 (推荐)</button>
                    </form>
                    
                     <form action="./directpay/alipayapi.php" method="post">
	                     <input type="hidden" name="WIDout_trade_no"  value="<?php echo time().mt_rand(100, 999);?>" >
	                	<input type="hidden" name="WIDsubject" value="购买趣店年账户"><!-- 订单名称 -->
	                	<input type="hidden" name="WIDtotal_fee" value="2999" >
	                	<input type="hidden" name="WIDbody" value="续费购买账户一个月"><!-- 订单描述 -->
	                	<input type="hidden" name="WIDshow_url" value=""><!-- 商品展示路劲 -->
	                	<button type="submit" class="btn" style="background-color: #EE1289;color:#fff;"><i class="icon-gift"></i> 2999元/年=8.3元/天 (力荐)</button>
                    </form>					
						 </blockquote>
						<!-- Blockquotes -->
				<br>
					</div>
					
					<div class="span6">
					</div>
				</div>
				
				<div class="row-fluid margin-bottom-30">
					
					<div class="span6">
					
					</div>

				</div>
			
				
				<!--/row-fluid-->   
<div class="row-fluid margin-bottom-20">

							<div class="span6">

								<div class="space20"></div>

								<h3 class="form-section">联系我们</h3>

								<p> </p>

								<div class="well">

									<h4>地址：</h4>

									<address>

										<strong>杭州市 西湖区</strong><br>

										西溪印象城<br>


										<abbr title="Phone">tel:</abbr> 13071870889

									</address>

									<address>

										<strong>Email</strong><br>

										<a href="mailto:#">postmaster@meijiemall.com</a>

									</address>
									<address>

										<strong>QQ：</strong><br>

										<a href="#">1187201645</a>

									</address>
									<address>

										<strong>微信：</strong><br>

										<a href="#">ciotas21gj</a>

									</address>

								</div>

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
	require_once ('footer.php');
	?>