<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Myaccount{
	
}
$myaccount=new Myaccount();
$title="我的账户";
$menu="profile";
$clicktag="myaccount";
$vcid="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');

?>

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							我的账户 <small> </small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">商家中心</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="myaccount.php">我的账户</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span6">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>会员折扣卡</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>卡名称</th>
											<th>折扣值</th>
											<th class="hidden-480">积分系数</th>
											<th>储值</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									if($val['storeflag']=="1"){$storeflag="储值卡";}else{$storeflag="非储值卡";}
										?>
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
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

					
					<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">

											<h4></h4>

											<form action="./interface/setvipcard.php" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="vcid"  id="vcidID">
												<div class="control-group">
													<label class="control-label">卡名称 </label>
													<div class="controls">
														<input type="text" placeholder="如金卡" id="cardnameID" name="cardname" class="m-wrap span12" value="<?php if(!empty($onevcd)){echo $onevcd['cardname'];}else{echo "";}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">折扣值 </label>
													<div class="controls">
														<input type="number" placeholder="如80"  name="cardval" id="cardvalID"  class="m-wrap span12" value="<?php if(!empty($onevcd)){echo $onevcd['cardval'];}else{echo "";}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">积分系数 </label>
													<div class="controls">
														<input type="text" placeholder="如1.2" name="pointfactor" id="pointfactorID"  class="m-wrap span12" value="<?php if(!empty($onevcd)){echo $onevcd['pointfactor'];}else{echo "";}?>">
													</div>
												</div>
												<div class="controls">                                                
														<label class="radio">
														<div class="radio"><input type="radio" name="storeflag" value="1"  id="storeflag1" checked></div>
														储值卡
														</label>
														<label class="radio">
														<div class="radio"><input type="radio" name="storeflag" value="0"  id="storeflag2"></div>
														非储值卡
														</label>  
													</div>
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn green">保存</button>

											</form>

										</div>
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