<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
$title="问答";
$menu="help";
$clicktag="faq";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
?>

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							问答 <small>不懂就要问</small>

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.php">主页</a> 

								<i class="icon-angle-right"></i>

							</li>

							<li>

								<a href="#">帮助</a>

								<i class="icon-angle-right"></i>

							</li>

							<li><a href="faq.php">FAQ</a></li>

						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<div class="span3">

							<ul class="ver-inline-menu tabbable margin-bottom-10">

								<li class="active">

									<a href="#tab_1" data-toggle="tab">

									<i class="icon-briefcase"></i> 小跑堂登录</a> 

									<span class="after"></span>                                    

								</li>

								<li><a href="#tab_2" data-toggle="tab"><i class="icon-group"></i></a></li>

								<li><a href="#tab_3" data-toggle="tab"><i class="icon-leaf"></i> 菜品修改</a></li>

								<li><a href="#tab_4" data-toggle="tab"><i class="icon-info-sign"></i> </a></li>

								<li><a href="#tab_5" data-toggle="tab"><i class="icon-tint"></i> Payment Rules</a></li>

								<li><a href="#tab_6" data-toggle="tab"><i class="icon-plus"></i> Other Questions</a></li>

							</ul>

						</div>

						<div class="span9">

							<div class="tab-content">

								<div class="tab-pane active" id="tab_1">

									<div class="accordion in collapse" id="accordion1" style="height: auto;">

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1">

												Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?

												</a>

											</div>

											<div id="collapse_1" class="accordion-body collapse in">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2">

												Pariatur cliche reprehenderit enim eiusmod highr brunch ?

												</a>

											</div>

											<div id="collapse_2" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_3">

												Food truck quinoa nesciunt laborum eiusmod nim eiusmod high life accusamus  ?

												</a>

											</div>

											<div id="collapse_3" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_4">

												High life accusamus terry richardson ad ?

												</a>

											</div>

											<div id="collapse_4" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_5">

												Reprehenderit enim eiusmod high life accusamus terry quinoa nesciunt laborum eiusmod ?

												</a>

											</div>

											<div id="collapse_5" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_6">

												Wolf moon officia aute non cupidatat skateboard dolor brunch ?

												</a>

											</div>

											<div id="collapse_6" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

									</div>

								</div>

								<div class="tab-pane" id="tab_2">

									<div class="accordion in collapse" id="accordion2" style="height: auto;">

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_1">

												Cliche reprehenderit, enim eiusmod high life accusamus enim eiusmod ?

												</a>

											</div>

											<div id="collapse_2_1" class="accordion-body collapse in">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_2">

												Pariatur cliche reprehenderit enim eiusmod high life non cupidatat skateboard dolor brunch ?

												</a>

											</div>

											<div id="collapse_2_2" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_3">

												Food truck quinoa nesciunt laborum eiusmod ?

												</a>

											</div>

											<div id="collapse_2_3" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_4">

												High life accusamus terry richardson ad squid enim eiusmod high ?

												</a>

											</div>

											<div id="collapse_2_4" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_5">

												Reprehenderit enim eiusmod high life accusamus terry quinoa nesciunt laborum eiusmod ?

												</a>

											</div>

											<div id="collapse_2_5" class="accordion-body collapse">

												<div class="accordion-inner">

													<p>

														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

													</p>

													<p> 

														moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmodBrunch 3 wolf moon tempor

													</p>

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_6">

												Wolf moon officia aute non cupidatat skateboard dolor brunch ?

												</a>

											</div>

											<div id="collapse_2_6" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_7">

												Reprehenderit enim eiusmod high life accusamus terry quinoa nesciunt laborum eiusmod ?

												</a>

											</div>

											<div id="collapse_2_7" class="accordion-body collapse">

												<div class="accordion-inner">

													<p>

														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

													</p>

													<p> 

														moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmodBrunch 3 wolf moon tempor

													</p>

												</div>

											</div>

										</div>

									</div>

								</div>

								<div class="tab-pane" id="tab_3">

									<div class="accordion in collapse" id="accordion3" style="height: auto;">

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1">

												Cliche reprehenderit, enim eiusmod ?

												</a>

											</div>

											<div id="collapse_3_1" class="accordion-body collapse in">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2">

												Pariatur skateboard dolor brunch ?

												</a>

											</div>

											<div id="collapse_3_2" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_3">

												Food truck quinoa nesciunt laborum eiusmod ?

												</a>

											</div>

											<div id="collapse_3_3" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_4">

												High life accusamus terry richardson ad squid enim eiusmod high ?

												</a>

											</div>

											<div id="collapse_3_4" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_5">

												Reprehenderit enim eiusmod high  eiusmod ?

												</a>

											</div>

											<div id="collapse_3_5" class="accordion-body collapse">

												<div class="accordion-inner">

													<p>

														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

													</p>

													<p> 

														moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmodBrunch 3 wolf moon tempor

													</p>

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_6">

												Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?

												</a>

											</div>

											<div id="collapse_3_6" class="accordion-body collapse">

												<div class="accordion-inner">

													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_7">

												Reprehenderit enim eiusmod high life accusamus aborum eiusmod ?

												</a>

											</div>

											<div id="collapse_3_7" class="accordion-body collapse">

												<div class="accordion-inner">

													<p>

														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

													</p>

													<p> 

														moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmodBrunch 3 wolf moon tempor

													</p>

												</div>

											</div>

										</div>

										<div class="accordion-group">

											<div class="accordion-heading">

												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_8">

												Reprehenderit enim eiusmod high life accusamus terry quinoa nesciunt laborum eiusmod ?

												</a>

											</div>

											<div id="collapse_3_8" class="accordion-body collapse">

												<div class="accordion-inner">

													<p>

														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.

													</p>

													<p> 

														moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmodBrunch 3 wolf moon tempor

													</p>

												</div>

											</div>

										</div>

									</div>

								</div>

							</div>

						</div>

						<!--end span9-->                                   

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