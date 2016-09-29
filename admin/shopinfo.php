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
$title="商家信息";
$menu="manage";
$clicktag="shopinfo";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$shopinfo->getAllOnLineShop();
?>

<h3 class="page-title">
							商家信息<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>商家信息</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">头像</th>
											<th class="number">名称</th>
											<th class="number">ID</th>
											<th class="number">账号</th>
											<th class="number">密码</th>
											<th class="number">支付宝账户名</th>
											<th class="number">支付宝账号</th>
											<th class="number">地址</th>
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
											<td class="number"><?php echo $val['shopaccount'];?></td>
											<td class="number"><?php echo $val['address'];?></td>
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