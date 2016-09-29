<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SignSheet{
	
}
$signsheet=new SignSheet();
$title="签单详情";
$menu="sign";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							卡设置 <small> 会员卡</small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">我的会员</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="vipset.php">卡充值</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>会员折扣卡</div>
								<div class="tools">
									<a href="#static" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>签单人</th>
											<th>所属单位</th>
											<th>所属金额</th>
											<th>收银员</th>
											<th>签单时间</th>
											<th>距今</th>
										</tr>
									</thead>
									<tbody>
								
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['cardname'];?></td>
											<td><?php echo $val['cardval'];?></td>
											<td class="hidden-480"><?php echo $val['pointfactor'];?></td>
											<td class="hidden-480"><?php echo $storeflag;?></td>
											<td><a href="#static" onclick="getOneVcd('<?php echo $val['vcid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonevcd.php?vcid=<?php echo base64_encode($val['vcid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										</tr>
										
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

	<!-- END CONTAINER -->
<?php 
require_once ('footer.php');
?>