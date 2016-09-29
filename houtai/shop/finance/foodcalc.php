<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodCalc{
	public function getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime);
	}
	public function getFoodTypes($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodTypes($shopid);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getOpenHourByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getFoodtypenameByFtidarr($ftidarr){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodtypenameByFtidarr($ftidarr);
	}
	public function getDecreasenum($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getDecreasenum($shopid);
	}
}
$foodcalc=new FoodCalc();
$menu="datasheet";
$clicktag="foodcalc";
$shopid=$_SESSION['shopid'];
require_once ('./finance_header.php');
$ftid="0";
$startdate=$foodcalc->getTheday($shopid);
$enddate=$foodcalc->getTheday($shopid);
$enddate=date("Y-m-d",strtotime($enddate)+86400);
$starthour=$foodcalc->getOpenHourByShopid($shopid);
$endhour=$foodcalc->getOpenHourByShopid($shopid);
if(isset($_POST['startdate'])){
	$ftidarr=$_POST['ftid'];
	$startdate=$_POST['startdate'];
	$starthour=$_POST['starthour'];
	$enddate=$_POST['enddate'];
	$endhour=$_POST['endhour'];
}
$starttime=$startdate." ".$starthour.":0:0";
$endtime=$enddate." ".$endhour.":0:0";
$ftarr=$foodcalc->getFoodTypes($shopid);
$ftnamearr=array();
if(!empty($ftidarr)){
	$ftnamearr=$foodcalc->getFoodtypenameByFtidarr($ftidarr);
}
$arr=$foodcalc->getFoodDataByFtid($shopid, $ftidarr, $starttime, $endtime);
// print_r($arr);exit;
$foodtotalmoney=0;
if(!empty($arr['data'])){
	foreach ($arr['data'] as $fkey=>$fval){
		$foodtotalmoney+=$fval['foodmoney'];
	}
}
$decreasenum=$foodcalc->getDecreasenum($shopid);
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							美食统计 <small>单品</small>

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
							<li><a href="foodcalc.php">美食统计</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid invoice">
					<div class="span12">
					<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption"><i class="icon-table"></i>选择时间</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<form action="./foodcalc.php" method="post">
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
								<td>
								类别 <select class="middle m-wrap" multiple="multiple" size=5  tabindex="1" name="ftid[]">
								<option value="0">---全部---</option>
								<?php foreach ($ftarr as $ftkey=>$ftval){?>
									<option value="<?php echo $ftval['ftid'];?>" <?php if(in_array($ftval['ftid'], $ftidarr)){echo "selected";}?>><?php echo $ftval['ftname'];?></option>
									<?php }?>
								</select>
							</td>
							<td style="float: right"><button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button></td>
							<tr>
								</table>
								</form>
							</div>
						</div>
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>查询结果 <?php if(!empty($ftnamearr)){echo '【查询商品类别：'. implode("、", $ftnamearr).'】';}else{echo '【查询商品类别：所有】';}?></div>
								<div class="tools">
								<form method="post" action="../interface/printfoodcalc.php" style="margin: 0">
								<input type="hidden" name="tabnum" value="<?php echo $arr['tabnum'];?>">
								<input type="hidden" name="foodtotalmoney" value="<?php echo $foodtotalmoney;?>">
								<input type="hidden" name="ftname" value='<?php echo json_encode($ftnamearr);?>' >
								<input type="hidden" name="startdate" value="<?php echo $startdate;?>">
								<input type="hidden" name="starthour" value="<?php echo $starthour;?>">
								<input type="hidden" name="enddate" value="<?php echo $enddate;?>">
								<input type="hidden" name="endhour" value="<?php echo $endhour;?>">
								<input type="hidden" name="data" value='<?php echo json_encode($arr['data']);?>'>
<!-- 								<button class="btn purple middle hidden-print"  type="submit">小票打印 <i class="icon-print icon-big"></i></button> -->
									<a class="btn purple middle hidden-print" onclick="javascript:window.print();">A4打印 <i class="icon-print icon-big"></i></a>
								</form>
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center; margin:0" >
							<tr>
							<th>总台数</th>
							<th>总金额</th>
							</tr>
							<tr>
							<td><?php echo $arr['tabnum'];?></td>
							<td><?php echo $foodtotalmoney;?></td>
							</tr>
							</table>
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
									<thead class="flip-content">
									<tr>
									<th class="numeric">排名</th>
									<th class="numeric">商品名</th>
									<th class="numeric">数量</th>
									<th class="numeric">单位</th>
									<th class="numeric">总额</th>
									<th class="numeric">周期(天)</th>
									<th class="numeric">占比</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($arr['data'] as $key=>$val){?>
										<tr>
											<td class="numeric"><?php echo ++$key;?></td>
											<td class="numeric"><?php echo $val['foodname'];?></td>
											<td class="numeric"><?php echo $decreasenum*$val['foodamount'];?></td>
											<td class="numeric"><?php echo $val['foodunit']?></td>
											<td class="numeric"><?php echo $decreasenum*$val['foodmoney'];?></td>
											<td class="numeric"><?php echo $val['foodcycle'];?></td>
											<td class="numeric"><?php if(!empty($foodtotalmoney)){echo sprintf("%.1f",100*$val['foodmoney']/$foodtotalmoney)."%";}else{echo "/";}?></h4></td>
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
require ('../footer.php');
?>
