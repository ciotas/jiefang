<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ShopAccount{
	public function getShopAcountData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopAcountData($shopid);
	}
}
$shopaccount=new ShopAccount();
$title="商家账户";
$menu="profile";
$clicktag="shopaccount";
$shopid=$_SESSION['shopid'];
$arr=array();
require_once ('header.php');
$arr=$shopaccount->getShopAcountData($shopid);
?>


			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">
			
				<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							商家账户
							 <small></small>

						</h3>
					</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">
				
					<div class="span12">

					<div class="portlet-body">
								
							</div>
						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >
									<ul class="span10" style="list-style-type: none">
									<li><span>姓名:</span><em><?php echo $arr['shopkeeper'];?></em></li>
										<li><span>身份证正面照:</span>
									<img src="<?php if(!empty($arr)){echo $arr['IDCardface'];}?>" alt=""  width="200"/>
									</li>
									<li><span>身份证反面照:</span>
									<img src="<?php if(!empty($arr)){echo $arr['IDCardback'];}?>" alt=""  width="200"/>
									</li>
										<li><span>银行卡号:</span><em><?php echo $arr['bankno'];?></em></li>
										<li><span>银行卡正面照:</span>
									<img src="<?php if(!empty($arr)){echo $arr['banckcardface'];}?>" alt=""  width="200"/>
									</li>
										<li><span>开户行:</span><em><?php echo $arr['bankbranch'];?></em> </li>
									</ul>

								</div>	
								<!--end tab-pane-->
								<div class="form-actions">
										<button onclick="window.location.href='./personinfo.php'" class="btn blue" style="float:right">编辑</button>

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
	require_once ('./footer.php');
	?>
