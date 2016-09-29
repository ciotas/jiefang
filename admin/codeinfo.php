<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class getCodeLog{
    public function getCode($mobile=null){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getCodeLog($mobile);
	}
}

$title="后台验证码";
$menu="manage";
$clicktag="codeinfo";
$shopid=$_SESSION['shopid'];
$getCode = new getCodeLog();


require_once ('header.php');
 
if($_GET['m'])
{
    $mobile = $_GET['m'];
    $data = $getCode->getCode($mobile);
}
else 
{
    $data = $getCode->getCode();
}

?>

        
			<h3 class="page-title">
				验证码后台<small> </small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
		</div>
		<div class="row-fluid search-forms search-default">
        
        	<form class="form-search" action="http://test.meijiemall.com/admin/codeinfo.php" method="get">
        
        		<div class="chat-form">
        
        			<div class="input-cont">   
        
        				<input type="text" placeholder="请输入手机号码" name="m" class="m-wrap">
        
        			</div>
        
        			<button type="submit" class="btn green">搜索 &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
        
        		</div>
        
        	</form>
        
        </div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>验证码后台</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">手机号</th>
											<th class="number">验证码</th>
											<th class="number">发送时间段</th>

										</tr>
									</thead>
									<tbody>
									<?php 
									foreach ($data as $key=>$val){?>
										<tr>
											<td class="number"><?php echo $key;?></td>
											<td class="number"><?php echo $val['mobilphone'];?></td>
											<td class="number"><?php echo $val['code'];?></td>
											<td class="number"><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>
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