<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Shopinfo{
    public function getAllOnLineShop(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getAllOnLineShop();
	}
}
$shopinfo=new Shopinfo();
$title="转账信息录入";
$menu="manage";
$clicktag="tomoney";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$shopinfo->getAllOnLineShop();
?>
		<h3 class="page-title">
				转账信息录入<small> </small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>转账信息录入</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">商家名</th>
											<th class="number">商户ID</th>
											<th class="number">手机号</th>
											<th class="number">openid</th>
											<th class="number">收款人姓名</th>
											<th class="number">身份证号</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td class="number"><?php echo ++$key;?></td>
											<td class="number"><img alt="" src="<?php echo $val['logo'];?>" width="50"></td>
											<td class="number"><?php echo $val['shopname'];?></td>
											<td class="number"><?php echo $val['shopid'];?></td>
											<td class="number"><?php echo $val['mobilphone'];?></td>
											<td class="number"><?php echo $val['passwd'];?></td>
											<td class="number"><?php echo $val['accountname'];?></td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>
				</div>
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->

	</div>
	<?php 
	require_once ('footer.php');
	?>