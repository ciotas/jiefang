<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DonateAccountRecord{
	public function getDonateRecords($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDonateRecords($shopid);
	}
}
$donateaccountrecord=new DonateAccountRecord();
$title="赠送账户记录";
$menu="profile";
$clicktag="donateaccountrecord";
$vcid="";
$shopid=$_SESSION['shopid'];
// echo $shopid;exit;
require_once ('header.php');
$arr=$donateaccountrecord->getDonateRecords($shopid);
// print_r($arr);exit;
?>

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							赠送账户记录 <small> </small>
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
							<li><a href="donateaccountrecord.php">赠送账户记录</a></li>
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
								<div class="caption"><i class="icon-credit-card"></i>赠送记录</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>赠送方</th>
											<th>赠送时长</th>
											<th>账户到期时间</th>
											<th>赠送时间</th>
											<th>赠送理由</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['donatefrom'];?></td>
											<td><?php echo $val['donatemonth'];?>个月</td>
											<td><?php echo  date("Y-m-d H:i:s", $val['endtime']);;?></td>
											<td><?php echo  date("Y-m-d H:i:s", $val['addtime']);?></td>
											<td><?php echo $val['donatereason'];?></td>
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