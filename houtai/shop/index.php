<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Profile{
	public function getIndexPageData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getIndexPageData($shopid);
	}
	public function getShopaccounttype($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getShopaccounttype($shopid);
	}
	public function getDecreasenum($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getDecreasenum($shopid);
	}
	public function getAllowinbalanceValue($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getAllowinbalanceValue($shopid);
	}
}
$profile=new Profile();
$title="我的主页";
$menu="dashboard";
$clicktag="index";
$shopid=$_SESSION['shopid'];
// $shopid="554ad9615bc109d8518b45d2";
require_once ('header.php');
// $arr=array();
//问题在这下面这行，先暂时屏蔽
$arr=$profile->getIndexPageData($shopid);
$accountarr=$profile->getShopaccounttype($shopid);
$allowinbalance=$profile->getAllowinbalanceValue($shopid);

// print_r($accountarr);exit;
switch ($accountarr['accounttype']){
	case "standard":$accounttype="标准账户";
		if($accountarr['leftday']<30&&$accountarr['leftday']>=15){
			$alert="<span style='color:orange;'>亲~，还有".$accountarr['leftday']."天账户到期，请及时<a href='upgrade.php'>续费</a>，我们才可以为您服务哦~O(∩_∩)O~<span>";
		}elseif($accountarr['leftday']<15){
			$alert="<span style='color:red;'>亲~，还有".$accountarr['leftday']."天账户到期，请及时<a href='upgrade.php'>续费</a>我们才可以为您服务哦~O(∩_∩)O~<span>";
		}
	break;
	case "senior":$accounttype="高级账户";break;
	case "free":$accounttype="免费账户";
		$alert="<span style='color:red;'>亲~，开通<a href='upgrade.php'>标准账户</a><span>，立即得到优质的服务";
	break;
	case "try":$accounttype="试用账户";
		$alert="<span style='color:red;'>亲~，您的试用账户还有".$accountarr['leftday']."天到期，如得到我们更优质的服务，您可以购买<a href='upgrade.php'>标准账户</a>哦<span>";
	break;
}
$alert="<span style='color:red;'>杭州点霸网络科技有限公司 欢迎您使用点霸微信点餐～</span>";
?>
				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">
							我的主页 <small>商家资料</small>
						</h3>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>

								<a href="index.php">主页</a> 

							</li>
						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">

					<div class="span12">

						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

							<div class="tab-content">

									<ul class="unstyled profile-nav span3">

										<li><img src="<?php if(!empty($arr['shopdata']['logo'])){echo $arr['shopdata']['logo'];}?>" alt=""  width="250"></li>

									</ul>

									<div class="span9">

										<div class="row-fluid">

											<div class="span8 profile-info">
												<h1>您好，<?php if(!empty($arr['shopdata']['shopname'])){echo $arr['shopdata']['shopname'];}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $accounttype;?>]</h1>
												<p><?php echo $alert;?></p>
										<!--  <h4 style="color: #FF9900">"终于等到你，还好我没放弃"，感谢<?php if(!empty($arr['shopdata']['shopname'])){echo $arr['shopdata']['shopname'];}?>和我们的其他客户！在你们的支持下系统已逐渐完善，今天正式推出第一个创新功能——预结单，使收银与扫码支付完美结合。更多新功能陆续到来，敬请期待！点击<a href="./prebillintro.php">了解更多</a></h4> -->	
												<iframe allowtransparency="true" frameborder="0" width="385" height="80" scrolling="no" src="http://tianqi.2345.com/plugin/widget/index.htm?s=2&z=3&t=0&v=0&d=3&bd=0&k=000000&f=&q=1&e=1&a=1&c=58457&w=385&h=80&align=left"></iframe>
												<br><br>
												<ul class="unstyled inline">
													<li><i class="icon-map-marker"></i><?php if(!empty($arr['shopdata'])){echo $arr['shopdata']['city']." ".$arr['shopdata']['district'];}?></li>
													<li><i class="icon-road"></i> <?php if(!empty($arr['shopdata'])){echo $arr['shopdata']['road'];}?></li>
													<li><i class="icon-tag"></i> <?php if(!empty($arr['shopdata'])){echo $arr['shopdata']['shoptypename'];}?></li>

													<li><i class="icon-calendar"></i> <?php echo date("Y-m-d H点",time());?></li>

													<li><i class=" icon-bookmark"></i>街坊认证</li>
													<li><i class="icon-thumbs-up"></i> 街坊推荐</li>
												</ul>

											</div>

											<!--end span8-->
										<?php if($allowinbalance=="2"){?>
											<div class="span4">

												<div class="portlet sale-summary">

													<div class="portlet-title">

														<div class="caption">今日数据</div>

														<div class="tools">

														<!-- 	<a class="reload" href="javascript:;"></a> -->

														</div>

													</div>

													<ul class="unstyled">

														<li>
															<span class="sale-info">销售总额 </span>

															<span class="sale-num"> <?php if(!empty($arr['todaydata'])){echo $arr['todaydata']['totalmoney'];}?></span> 
															
														</li>

														<li>

															<span class="sale-info">顾客数 </span>

														<span class="sale-num"> <?php if(!empty($arr['todaydata'])){echo $arr['todaydata']['cusnum'];}?></span> 
															

														</li>
														<li>

															<span class="sale-info">下单数 </span>
															<span class="sale-num"> <?php if(!empty($arr['todaydata'])){echo $arr['todaydata']['billnum'];}?></span> 														
														</li>
														<li>
															<span class="sale-info">收入</span>
															<span class="sale-num"> <?php if(!empty($arr['todaydata'])){echo $arr['todaydata']['receivablemoney'];}?></span> 
														</li>
													</ul>

												</div>

											</div>
											<!--end span4-->
											<?php }?>
										</div>
										<!--end row-fluid-->

									</div>
							</div>
									<!--end span9-->	
			
					
							<?php if($allowinbalance=="1"){?>		
						<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-user"></i>服务员下单状况</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

									<!-- <a href="javascript:;" class="reload"></a> -->

								</div>

							</div>

							<div class="portlet-body">
								<table class="table table-condensed table-hover" >
									<thead>
										<tr>
											<th><i class="icon-picture"></i> 头像</th>
											<th><i class="icon-female"></i> 服务员</th>
											<th><i class="icon-adjust"></i> 性别</th>
											<th><i class="icon-bell"></i> 工号</th>
											<th><i class="icon-group"></i> 顾客数</th>
											<th><i class="icon-check"></i> 已下单数</th>
											<th ><i class="icon-yen"></i> 点单金额</th>
											<th ><i class="icon-yen"></i> 收银金额</th>
											<th><i class="icon-flag"></i> 工作状态</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr['serverdata'] as $key=>$val){
										switch ($val['workstatus']){
											case "work":$workstatus='<span class="btn mini green">工作中</span>';break;
											case "leave":$workstatus='<span class="btn mini yellow">请假中</span>';break;
											case "holiday":$workstatus='<span class="btn mini blue">正常休息</span>';break;
											case "away":$workstatus='<span class="btn mini red">旷工或离职</span>';break;
										}
										switch ($val['sex']){
											case "male":$sex="男";break;
											case "female":$sex="女";break;
										}
										?>
										<tr>
											<td><img alt="" src="<?php echo $val['photo'];?>" width="50"></td>
											<td><?php echo $val['servername'];?></td>
											
											<td><?php echo $sex;?></td>
											<td><?php echo $val['serverno'];?></td>
											<td><?php echo $val['cusnum'];?></td>
											<td><?php echo $val['billnum'];?></td>
											<td><?php echo $val['totalmoney'];?></td>
											<td><?php echo $val['overtotalmoney'];?></td>
											<td><?php echo $workstatus;?></td>

										</tr>
									<?php }?>
									</tbody>

								</table>

							</div>

						</div>
				<?php }?>
							</div>

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
	require_once ('footer.php');
	?>