<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BillRecord{
	public function getDailySheetData($shopid, $starttime, $endtime,$type){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getDailySheetData($shopid, $starttime, $endtime,$type);
	}
	public function judgePhoneInMyShopid($userphone, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->judgePhoneInMyShopid($userphone, $shopid);
	}
	public function delOneBillByBillid($billid){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->delOneBillByBillid($billid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getDecreasenum($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getDecreasenum($shopid);
	}
}
$billrecord=new BillRecord();
$menu="datasheet";
$clicktag="billrecord";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$startdate=$billrecord->getTheday($shopid);
$enddate=$billrecord->getTheday($shopid);
$enddate=date("Y-m-d",strtotime($enddate)+86400);
$starthour=$billrecord->getOpenHourByShopid($shopid);
$endhour=$billrecord->getOpenHourByShopid($shopid);
$billid="";
$myright="";
$userphone="";
$decreasenum=$billrecord->getDecreasenum($shopid);
if(isset($_POST['startdate'])){
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
// 	$billid=$_POST['billid'];
// 	$userphone=$_POST['userphone'];
	
}

$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
if(!empty($billid)&&!empty($userphone)){
	$myright=$billrecord->judgePhoneInMyShopid($userphone, $shopid);
	if($myright){
// 		$billrecord->delOneBillByBillid($billid);
	}
}

$dailyarr=$billrecord->getDailySheetData($shopid, $starttime, $endtime,"record");
// print_r($dailyarr);exit;
?>

<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>


				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							点菜记录 <small></small>

						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">主页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">报表</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="billrecord.php">点菜记录</a></li>
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
								<form action="./billrecord.php" method="post">
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
								<div class="caption"><i class="icon-table"></i>详细</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">序号</th>
											<th class="numeric">台号</th>
											<th class="numeric">人数</th>
											<th class="numeric">销售额</th>
											<th class="numeric">其他收入</th>
											<th class="numeric">应收款</th>
											<th class="numeric">现金</th>
											<th class="numeric">银联卡</th>
											<th class="numeric">会员卡</th>
											<th class="numeric">美团账户</th>
											<th class="numeric">支付宝</th>
											<th class="numeric">微信支付</th>
											
											<th class="numeric">券</th>
											<th class="numeric">券种</th>
											<th class="numeric">折扣额</th>
											<th class="numeric">折扣值</th>
											<th class="numeric">抹零</th>
											<th class="numeric">签单</th>
											<th class="numeric">免单</th>
											<th class="numeric">押金收</th>
											<th class="numeric">押金返还</th>
											<th class="numeric">下单人</th>
											<th class="numeric">收银员</th>
											<th class="numeric">下单时间</th>
											<th class="numeric">买单时间</th>
											<th class="numeric">下单状态</th>
											<th class="numeric">付款状态</th>
											<th class="numeric">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($dailyarr as $key=>$val){
										switch ($val['paystatus']){
											case "unpay":$paystatus="未付款";break;
											case "paid":$paystatus="已付款";break;
										}
										switch ($val['billstatus']){
											case "done":$billstatus="已下单";break;
											case "undone":$billstatus="未下单";break;
										}
										?>
										<tr>
										    <td class="numeric"><?php echo ++$key;?></td>
										    <td class="numeric"><?php echo $val['tabname'];?></td>
										  <!--    <td class="numeric"><?php echo $val['billid'];?></td> --> 
											<td class="numeric"><?php echo $val['cusnum'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['totalmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['othermoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['receivablemoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['cashmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['unionmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['vipmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['meituanpay'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['alipay'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['wechatpay'];?></td>
											
											<td class="numeric"><?php echo $decreasenum*$val['ticketmoney'];?></td>
											<td class="numeric"><?php echo $val['ticketname'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['discountmoney'];?></td>
											<td class="numeric"><?php echo $val['discountval'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['clearmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['signmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['freemoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['depositmoney'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['returndepositmoney'];?></td>
											<td class="numeric"><?php echo $val['nickname'];?></td>
											<td class="numeric"><?php echo $val['cashierman'];?></td>
											<td class="numeric"><?php echo date("m-d H:i",$val['timestamp']);?></td>
											<td class="numeric"><?php echo $val['buytime'];?></td>
											<td class="numeric"><?php echo $billstatus;?></td>
											<td class="numeric"><?php echo $paystatus;?></td>
										<!-- 	<td class="numeric"><a href="#del_<?php echo $val['billid'];?>" class="btn mini red" data-toggle="modal" ><i class="icon-trash"></i> </a></td> -->
										</tr>
										<?php }?>
									</tbody>

								</table>

							</div>
						<?php foreach ($dailyarr as $key1=>$val1){?>
							<div id="static_<?php echo $val1['billid'];?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
							<div class="modal-body">
								<p></p>
								<div class="tab-pane  active" id="portlet_tab3">
												<h4>点菜详细</h4>
												<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
												<thead class="flip-content">
												<tr>
												<th>菜品</th>
												<th>价格</th>
												<th>数量</th>
												<th>单位</th>
												<th>金额</th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($val1['food'] as $fkey=>$fval){?>
												<tr>
												<td><?php echo $fval['foodname'];?></td>
												<td><?php echo $fval['foodprice'];?></td>
												<td><?php echo $decreasenum*$fval['foodnum'];?></td>
												<td><?php echo $fval['orderunit'];?></td>
												<td><?php echo $decreasenum*$fval['foodmoney'];?></td>
												</tr>
												<?php }?>
												</tbody>
												</table>
											</div><br>
											<button type="button" data-dismiss="modal" class="btn">取消</button>
									</div>
								</div>	
					<?php }?>
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
require ('../footer.php');

?>
