<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class User{
    public function getUserList(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getUserList();
	}
}
$userList=new User();
$title="会员列表";
$menu="manage";
$clicktag="userlist";
require_once ('header.php');
$arr = $userList->getUserList();
//头像、昵称 、openid、uid、注册时间
//headimgurl nickname timestamp
?>
		<h3 class="page-title">
				会员列表<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>会员列表</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">头像</th>
											<th class="number">昵称</th>
											<th class="number">OpenID</th>
											<th class="number">userID</th>
											<th class="number" >使用设备</th>
											<th class="number">注册时间</th>
										</tr>
									</thead>
									<tbody>
									
									<?php
									   $i  = 1;
									foreach ($arr as $key=>$val){?>
										<tr>
											<td class="number"><?php echo $i;?></td>
											<td class="number"><img alt="" src="<?php echo $val['headimgurl'];?>" width="50"></td>
											<td class="number"><?php echo $val['nickname'];?></td>
											<td class="number"><?php echo $val['openid'];?></td>
											<td class="number"><?php echo $val['uid'];?></td>
											<td class="number"><?php echo $val['platform'];?></td>
											<td class="number"><?php echo date('Y-m-d H:i:s',$val['timestamp']);?></td>
										</tr>
										<?php
                                            $i++;
									       }?>
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