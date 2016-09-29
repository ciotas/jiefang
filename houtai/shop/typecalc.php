<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (QuDian_DOCUMENT_ROOT.'allowin.php');
class TypeCalc{
	public function getTypeCalcData($shopid, $starttime, $endtime){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTypeCalcData($shopid, $starttime, $endtime);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
}
$typecalc=new TypeCalc();
$menu="datasheet";
$clicktag="typecalc";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$startdate=$typecalc->getTheday($shopid);
$enddate=$typecalc->getTheday($shopid);
$enddate=date("Y-m-d",strtotime($enddate)+86400);
$starthour=$typecalc->getOpenHourByShopid($shopid);
$endhour=$typecalc->getOpenHourByShopid($shopid);
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$starthour=$_REQUEST['starthour'];
	$enddate=$_REQUEST['enddate'];
	$endhour=$_REQUEST['endhour'];
}
$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
// echo $starttime." ".$endtime;exit;
$typearr=$typecalc->getTypeCalcData($shopid, $starttime, $endtime);
// print_r($typearr);exit;
$soldtotalmoney=0;
$soldtotalnum=0;
foreach ($typearr as $akey=>$aval){
	$soldtotalmoney+=$aval['soldmoney'];
	$soldtotalnum+=$aval['soldnum'];
}
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>


				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							类别统计 <small></small>

						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid">
					<div class="span12">
					<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-table"></i>选择时间</div>

								<div class="tools">

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./typecalc.php" method="post">
								<table>
								<tr>
							<td>
							<span class="inline">起始日期</span>
								<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" style="width: 100px;" name="startdate" onClick="WdatePicker()" type="text" value="<?php echo $startdate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
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
										<input class="m-wrap m-ctrl-medium Wdate" style="width: 100px;" name="enddate"  onClick="WdatePicker()" type="text" value="<?php echo $enddate;?>"><span class="add-on"><i class="icon-calendar"></i></span>
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
							<tr>
								</table>
								</form>
							</div>
						</div>
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>查询结果</div>
								<div class="tools">
									<form method="post" action="./interface/printfoodtypecalc.php" style="margin: 0">
									<input type="hidden" name="soldtotalmoney" value="<?php echo $soldtotalmoney;?>">
									<input type="hidden" name="soldtotalnum" value="<?php echo $soldtotalnum;?>">
									<input type="hidden" name="startdate" value="<?php echo $startdate;?>">
									<input type="hidden" name="starthour" value="<?php echo $starthour;?>">
									<input type="hidden" name="enddate" value="<?php echo $enddate;?>">
									<input type="hidden" name="endhour" value="<?php echo $endhour;?>">
									<input type="hidden" name="data" value='<?php echo json_encode($typearr);?>'>
									<button class="btn red middle hidden-print"  type="submit">小票打印 <i class="icon-print icon-big"></i></button>
										<a class="btn purple middle hidden-print" onclick="javascript:window.print();">A4打印 <i class="icon-print icon-big"></i></a>
									</form>	
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
									 <th>排名</th>
									 <th>类名</th>
			                          <th>售出份数</th>
			                          <th>销售额</th>
			                          <th>数量比重</th>
			                          <th>金额比重</th>
									</tr>
									</thead>
									<tbody>
								
									<?php foreach ($typearr as $key=>$val){
										?>
										<tr>
											<td class="numeric"><?php echo ++$key;?></td>
											<td class="numeric"><?php echo $val['ftname'];?></td>
											<td class="numeric"><?php echo $val['soldnum'];?></td>
											<td class="numeric"><?php echo $val['soldmoney']?></td>
											<td class="numeric"><?php if(!empty($soldtotalnum)){echo sprintf("%.1f",100*$val['soldnum']/$soldtotalnum)."%";}else{ echo "/";}?></td>
											<td class="numeric"><?php if(!empty($soldtotalmoney)){echo sprintf("%.1f",100*$val['soldmoney']/$soldtotalmoney)."%";}else{ echo "/";}?></td>
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

