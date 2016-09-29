<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class VipPayRecord{
	public function getVipPayrecord($shopid, $starttime, $endtime){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getVipPayrecord($shopid, $starttime, $endtime);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
}
$vippayrecord=new VipPayRecord();
$menu="vip";
$clicktag="viprecord";
$shopid=$_SESSION['shopid'];

require_once ('header.php');
$startdate=$vippayrecord->getTheday($shopid);
$enddate=date("Y-m-d",strtotime($startdate)+86400);
$starthour=$vippayrecord->getOpenHourByShopid($shopid);
$endhour=$vippayrecord->getOpenHourByShopid($shopid);
if(isset($_POST['startdate'])){
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
}
$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
$arr=$vippayrecord->getVipPayrecord($shopid, $starttime, $endtime);
// print_r($arr);exit;
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							消费记录 <small></small>
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

								<div class="caption"><i class="icon-table"></i>选择日期</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./vippayrecord.php" method="post">
								<table>
								<tr>
								<td>
								<span class="inline">起始日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
								<input class="m-wrap m-ctrl-medium Wdate"  style="width: 100px;" name="startdate" onClick="WdatePicker()" size="8" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
							</td>
							<td>
							<span class="inline">起始时间点</span>
								<select class="small m-wrap" tabindex="1" name="starthour">
								<?php for ($i=0;$i<24;$i++){?>
									<option value="<?php echo $i;?>" <?php if($starthour==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>
								</td>
								
								</tr>
								<tr>
								<td>
								<span class="inline">结束日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" name="enddate" style="width: 100px;" onClick="WdatePicker()" size="8" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
							</div>
							</td>
							<td>
							<span class="inline">结束时间点</span>
								<select class="small m-wrap" tabindex="1" name="endhour">
								<?php for ($i=0;$i<24;$i++){?>
									<option value="<?php echo $i;?>" <?php if($endhour==$i){echo "selected";}?>><?php echo $i;?></option>
									<?php }?>
								</select>
								</td>
								
								</tr>
								<tr>
								<td></td>
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
											<?php if($_SESSION['role']=="manager"){?>
											<th class="numeric">用户手机</th>
											<?php }?>
											<th class="numeric">卡名</th>
											<th class="numeric">台号</th>
											<th class="numeric">消费金额</th>
											<th class="numeric">卡内余额</th>
											<th class="numeric">时间</th>
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
										    <?php if($_SESSION['role']=="manager"){?>
										    <td class="numeric"><?php echo $uphone;?></td>
										    <?php }?>
											<td class="numeric"><?php echo $val['cardname'];?></td>
											<td class="numeric"><?php echo $val['tabname'];?></td>
											<td class="numeric">￥<?php echo $val['consumemoney'];?></td>
											<td class="numeric">￥<?php echo $val['accountbalance'];?></td>
											<td class="numeric"><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>
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