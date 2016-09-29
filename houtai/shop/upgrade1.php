<?php 
$title="账户升级";
$menu="help";
$clicktag="upgrade";
$shopid=$_SESSION['shopid'];
require_once ('header1.php');

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
						 <p style="color:green">免费账户
						 </p>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">

							<li><i class="icon-ok"></i> 免费的店长管理app——趣店</li>

							<li><i class="icon-ok"></i> 免费的服务员点单收银app——小跑堂</li>
						</ul>
					<blockquote class="hero">
					</blockquote>
						<!-- Blockquotes -->
				<br>
					</div>
					<div class="span6">
					<blockquote class="hero">
						 <p style="color:green">试用账户
						 </p>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 免费试用商家web后台15天</li>
							<li><i class="icon-ok"></i> 免费的店长管理app——趣店</li>
							<li><i class="icon-ok"></i> 免费的服务员点单收银app——小跑堂</li>
						</ul>
					<blockquote class="hero">
					</blockquote>
						<!-- Blockquotes -->
				<br>
					</div>
					<div class="span6">
					</div>
				</div>
				
				<div class="row-fluid margin-bottom-30">
					<div class="span6">
					<blockquote class="hero">
						 <p style="color:green">标准账户</p>
						 <small>享受以下服务</small>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 免费的店长管理app——趣店</li>
							<li><i class="icon-ok"></i> 免费的服务员点单收银app——小跑堂</li>
							<li><i class="icon-ok"></i> 给用户发放各种电子会员卡、储值卡</li>
							<li><i class="icon-ok"></i> 更加详细和专业的业务报表</li>
							<li><i class="icon-ok"></i> 掌握每一个顾客的消费详情和统计，筛选优质顾客</li>
							<li><i class="icon-ok"></i> 免费的线上支付(现只支持支付宝，将逐步上线微信支付、扫码支付和银联支付)</li>
							<li><i class="icon-ok"></i> 库存管理，免去您盘点库存的烦恼</li>
							<li><i class="icon-ok"></i> 7×24小时一对一专业免费在线服务，再也不用坐等师傅上门！</li>
							<li><i class="icon-ok"></i> 未来更多更强大的功能，第一时间抢先用...</li>
						</ul>
						<blockquote class="hero">
						<p style="color: green;font-size:16px;">每推荐一家成为我们的客户，赠送一个月！</p>
						<p style="color: green;font-size:16px;">做一次活动推荐顾客使用开饭啦app，赠送一个月！</p>
						</blockquote>
						<blockquote class="hero">
						 <form action="./directpay/alipayapi.php" method="post">
		                     <input type="hidden" name="WIDout_trade_no"  value="<?php echo time().mt_rand(100, 999);?>" >
		                	<input type="hidden" name="WIDsubject" value="购买趣店月账户"><!-- 订单名称 -->
		                	<input type="hidden" name="WIDtotal_fee" value="300" >
		                	<input type="hidden" name="WIDbody" value="续费购买账户一个月"><!-- 订单描述 -->
		                	<input type="hidden" name="WIDshow_url" value=""><!-- 商品展示路劲 -->
		                	<button type="button" class="btn green">300元/月=10元/天</button>
                    	</form>
                    
                     <form action="./directpay/alipayapi.php" method="post">
	                     <input type="hidden" name="WIDout_trade_no"  value="<?php echo time().mt_rand(100, 999);?>" >
	                	<input type="hidden" name="WIDsubject" value="购买趣店半年账户"><!-- 订单名称 -->
	                	<input type="hidden" name="WIDtotal_fee" value="1600" >
	                	<input type="hidden" name="WIDbody" value="续费购买账户一个月"><!-- 订单描述 -->
	                	<input type="hidden" name="WIDshow_url" value=""><!-- 商品展示路劲 -->
	                	<button type="button" class="btn green">1600元/6月(推荐)=8.9元/天</button>
                    </form>
                    
                     <form action="./directpay/alipayapi.php" method="post">
	                     <input type="hidden" name="WIDout_trade_no"  value="<?php echo time().mt_rand(100, 999);?>" >
	                	<input type="hidden" name="WIDsubject" value="购买趣店年账户"><!-- 订单名称 -->
	                	<input type="hidden" name="WIDtotal_fee" value="3000" >
	                	<input type="hidden" name="WIDbody" value="续费购买账户一个月"><!-- 订单描述 -->
	                	<input type="hidden" name="WIDshow_url" value=""><!-- 商品展示路劲 -->
	                	<button type="button" class="btn green">3000元/年(力荐)=8.2元/天</button>
                    </form>					
						 </blockquote>
						<!-- Blockquotes -->
				<br>
					</div>
					<div class="span6">
					<blockquote class="hero">
						 <p style="color:green">高级账户</p>
					</blockquote>
					<blockquote class="hero">
						 <p>成为高级账户免费两年，只需以下条件：</p>
					</blockquote>
						<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 有10家以上的连锁店或在一个月内推荐10及以上家新客户给我们，并使用我们的产品</li>
							<li><i class="icon-ok"></i> 愿意做活动推广顾客端<span style="color: orange">开饭啦</span>点餐app</li>
							<li><i class="icon-ok"></i> 签署三年的使用合同，期间不使用同类竞争产品</li>
							
						</ul>
						<blockquote class="hero">
						<p style="color: green;font-size:16px;">享受标准账户全部服务！</p>
						</blockquote>
						<blockquote class="hero">
						<p style="font-size:16px;">另外还享有以下服务：</p>
						</blockquote>
					<ul class="unstyled margin-top-10 margin-bottom-10">
							<li><i class="icon-ok"></i> 连锁门店或经推荐并使用的商家，赠送一个月的免费服务期！</li>
							<li><i class="icon-ok"></i> 在顾客端app首页免费宣传15天，让更多的用户知道您！</li>
							<li><i class="icon-ok"></i> 更多增值服务打折优惠！</li>
							
						</ul>
						<!-- Blockquotes -->
				<br>
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

										天目山路226号<br>

										网新大厦二楼<br>

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