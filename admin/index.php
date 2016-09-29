<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Profile{
	public function getStaticsData($starttime){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getStaticsData($starttime);
	}
}
$profile=new Profile();
$title="我的主页";
$menu="dashboard";
$clicktag="index";
$user_id=$_SESSION['user_id'];
require_once ('header.php');
$data=file_get_contents(GetTotalmoneyTime);
$dataarr=json_decode($data,true);
if(empty($dataarr['newtime'])){
	$starttime=1427817600;
}else{
	$starttime=$dataarr['newtime'];
}
$tongjiarr=$profile->getStaticsData($starttime);
$cusnum=$dataarr['cusnum']+$tongjiarr['cusnum'];
$totalmoney=$dataarr['totalmoney']+$tongjiarr['totalmoney'];
$billnum=$tongjiarr['billnum'];
$foodnum=$tongjiarr['foodnum'];
$newtime=$tongjiarr['newtime'];
$arr=array(
		"cusnum"=>$cusnum,
		"totalmoney"=>sprintf("%.0f",$totalmoney),
		"newtime"=>$newtime,
);
file_put_contents(GetTotalmoneyTime, json_encode($arr));
?>

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">
							我的主页 <small></small>
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

											<!--end span8-->

											<div class="span4">

												<div class="portlet sale-summary">

													<div class="portlet-title">

														<div class="caption">统计</div>

														<div class="tools">

														<!-- 	<a class="reload" href="javascript:;"></a> -->

														</div>

													</div>

													<ul class="unstyled">

														<li>
															<span class="sale-info">服务人次 </span>

															<span class="sale-num"> <?php echo $cusnum;?></span> 
															
														</li>

														<li>

															<span class="sale-info">流水 </span>

														<span class="sale-num"> <?php echo $totalmoney;?></span> 
															

														</li>
														<li>

															<span class="sale-info">下单数 </span>
															<span class="sale-num"> <?php echo $billnum;?></span> 														
														</li>
														<li>
															<span class="sale-info">收录菜品</span>
															<span class="sale-num"> <?php echo $foodnum;?></span> 
														</li>
													</ul>

												</div>

											</div>
											<!--end span4-->
										</div>
										<!--end row-fluid-->

									</div>

									<!--end span9-->	
	
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