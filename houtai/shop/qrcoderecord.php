<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class QrcodeRecord{
	public function getQrcodeRecordData($shopid,$starttime,$endtime){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getQrcodeRecordData($shopid, $starttime, $endtime);
	}
}
$qrcoderecord=new QrcodeRecord();
$menu="profile";
$clicktag="qrcoderecord";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$startdate=date("Y-m-d",time());
$enddate=date("Y-m-d",time());
$starthour="0";
$endhour="0";
if(isset($_POST['startdate'])){
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
}
$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
// echo $starttime." ".$endtime;exit;
$arr=$qrcoderecord->getQrcodeRecordData($shopid, $starttime, $endtime);
// print_r($arr);exit;
$totalpay=0;
foreach ($arr as $akey=>$aval){
	if($aval['trade_status']=="TRADE_SUCCESS"||$aval['trade_status']=="TRADE_FINISHED"){
		$totalpay+=$aval['total_fee'];
	}
}
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>


				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							支付宝扫码记录 <small></small>

						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">主页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">商家中心</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="qrcoderecord.php">支付宝扫码记录</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid">
					<div class="span12">
					
					<div class="portlet box red">

							<div class="portlet-title">

								<div class="caption"><i class="icon-table"></i>选择时间间隔</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./qrcoderecord.php" method="post">
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
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>详细   [交易总额：￥<?php echo $totalpay;?>]</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">#</th>
											<th class="numeric">商户订单号</th>
											<th class="numeric">商品名称</th>
											<th class="numeric">顾客支付宝账户</th>
											<th class="numeric">交易额</th>
											<th class="numeric">交易状态</th>
										<!-- 	<th class="numeric">交易创建时间</th> -->
											<th class="numeric">交易付款时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										if($val['trade_status']=="TRADE_SUCCESS"||$val['trade_status']=="TRADE_FINISHED"){
											$trade_status="交易成功";
										}else{
											$trade_status="交易失败";
										}
										?>
										<tr>
										    <td class="numeric"><?php echo ++$key;?></td>
											<td class="numeric"><?php echo $val['out_trade_no'];?></td>
											<td class="numeric"><?php echo $val['subject'];?></td>
											<td class="numeric"><?php echo $val['buyer_email'];?></td>
											<td class="numeric"><?php echo $val['total_fee'];?></td>
											<td class="numeric"><?php echo $trade_status;?></td>
										<!-- <td class="numeric"><?php echo date("Y-m-d H:i:s",$val['gmt_create']);?></td>-->
											<td class="numeric"><?php echo date("Y-m-d H:i:s",$val['gmt_payment']);?></td>
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