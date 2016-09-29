<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class Vips{
	public function getChargeVipCard($bossid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getChargeVipCard($bossid);
	}
	public function getMyVips($bossid,$userphone,$cardid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getMyVips($bossid,$userphone,$cardid);
	}
	public function getUidByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getUidByphone($phone);
	}
	public function getBossidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getBossidByShopid($shopid);
	}
}
$vips=new Vips();
$title="会员名单";
$menu="vip";
$clicktag="vips";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$cardid="";
$userphone="";
$bossid=$vips->getBossidByShopid($shopid);
$viparr=$vips->getChargeVipCard($bossid);
if(isset($_POST['cardid'])){
	$cardid=$_POST['cardid'];
	$userphone=$_POST['userphone'];
}

$arr=$vips->getMyVips($bossid, $userphone, $cardid);

?>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							会员列表 <small></small>

						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
					<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>搜索</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./vips.php" method="post">
								<table>
								<tr>
								<td>
								<label class="help-inline">绑定手机号：</label>
									<input type="tel" placeholder="手机号" name="userphone" class="m-wrap small" value="<?php if(!empty($userphone)){echo $userphone;}?>">
							</td>
							<td>
							<label class="help-inline">卡类型：</label>
								<select class="small m-wrap" name="cardid">
								<option value="0">---全部---</option>
								<?php foreach ($viparr as $vkey=>$vval){?>
								<option value="<?php echo $vval['cardid'];?>" <?php if($cardid==$vval['cardid']){echo "selected";}?>><?php echo $vval['cardname']?></option>
								<?php }?>
								</select>
							</td>
								
								</tr>
								<tr>
								<td>
								
							</td>
						
							<td style="float: right"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>								
								</tr>
							
								</table>
								</form>
							</div>
						</div>
				
						
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>详细</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">头像</th>
											<th class="numeric">用户名</th>
											<th class="numeric">手机号</th>
											
											<th class="numeric">卡类型</th>
											<th class="numeric">余额</th>
<!-- 											<th class="numeric">加入时间</th> -->
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										$phonecrypt = new CookieCrypt($cusphonekey);
										$uphone=$phonecrypt->decrypt($val['userphone']);
										?>
										<tr>
										    <td class="numeric"><img width="50" height="50" src="<?php echo $val['photo'];?>"></td>
										    <td class="numeric"><?php echo $val['nickname'];?></td>
										    <td class="numeric"><?php echo $uphone;?></td>
											<td class="numeric"><?php echo $val['cardname'];?></td>
											<td class="numeric"><?php echo $val['accountbalance'];?></td>
									<!-- 		<td class="numeric"><?php echo date("Y-m-d H:i:s",$val['addtime']);?></td> -->
									
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

	<!-- END CONTAINER -->

<?php 
require ('footer.php');
?>