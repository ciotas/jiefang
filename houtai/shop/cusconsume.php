<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class CusConsume{
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	public function getCusConsumeInfo($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorCusDAL()->getCusConsumeInfo($shopid, $theday);
	}
	public function getSexPercentRate($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorCusDAL()->getSexPercentRate($inputarr);
	}
	public function getPlatformPercent($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorCusDAL()->getPlatformPercent($inputarr);
	}
	public function getCityPercet($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorCusDAL()->getCityPercet($inputarr);
	}
}
$cusconsume=new CusConsume();
$title="消费者明细";
$menu="datasheet";
$clicktag="cusconsume";
$shopid=$_SESSION['shopid'];
require_once ('finance_header.php');
$theday=$cusconsume->getTheday($shopid);
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$cusconsume->getCusConsumeInfo($shopid, $theday);
$sexarr=$cusconsume->getSexPercentRate($arr);
$paltformarr=$cusconsume->getPlatformPercent($arr);
$cityarr=$cusconsume->getCityPercet($arr);
?>

<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./cusconsume.php?theday="+theday;
}

</script>

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid invoice">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid invoice">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-table"></i>每日流水</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body flip-scroll">
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
									<br>
						
						<div class="responsive span6" data-tablet="span6" data-desktop="span3">
								<div class="details">
								<h3>男女比例</h3>
								<canvas id="chart-area_yes" style="text-align:center"/></canvas>
								<?php 
									$jsondata=json_encode($sexarr);
									echo '<script>';
									echo 'var pieData='.$jsondata.';';
									echo 'var ctx = document.getElementById("chart-area_yes").getContext("2d");';
									echo 'window.myPie = new Chart(ctx).Pie(pieData);';
									echo '</script>';
								?>
							</div>
						</div>	
					
					<div class="responsive span6" data-tablet="span6" data-desktop="span3">
								<div class="details">
								<h3>手机平台比例</h3>
								<canvas id="chart-platform"  style="text-align:center"/></canvas>
								<?php 
									$jsondata=json_encode($paltformarr);
									echo '<script>';
									echo 'var pieData='.$jsondata.';';
									echo 'var ctx = document.getElementById("chart-platform").getContext("2d");';
									echo 'window.myPie = new Chart(ctx).Pie(pieData);';
									echo '</script>';
								?>			
							</div>
						</div>	
						
						<div class="responsive span6" data-tablet="span6" data-desktop="span3">
								<div class="details">
								<h3>城市比例</h3>
								<canvas id="chart-city"  style="text-align:center"/></canvas>
								<?php 
									$jsondata=json_encode($cityarr);
									echo '<script>';
									echo 'var pieData='.$jsondata.';';
									echo 'var ctx = document.getElementById("chart-city").getContext("2d");';
									echo 'window.myPie = new Chart(ctx).Pie(pieData);';
									echo '</script>';
								?>	
							</div>
						</div>	
						
					
						
								<table class="table-bordered table-striped table-condensed flip-content" style="text-align:center;">
										<thead>
										<tr>
											<th class="numeric">#</th>
											<th class="numeric">头像</th>
											<th class="numeric">昵称</th>
											<th class="numeric">性别</th>
											<th class="numeric">手机类型</th>
											<th class="numeric">省</th>
											<th class="numeric">市</th>
											<th class="numeric">下单数</th>
											<th class="numeric">消费总额</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$i=0;
									 foreach ($arr as $key=>$val){
										?>
										<tr>
										  
											<td class="numeric"><?php echo ++$i;?></td>
											<td class="numeric"><img src="<?php echo $val['headimgurl'];?>" width="50" ></td>
											<td class="numeric"><?php echo $val['nickname'];?></td>
											<td class="numeric"><?php echo $val['sexname'];?></td>
											<td class="numeric"><?php echo $val['platform'];?></td>
											<td class="numeric"><?php echo $val['province'];?></td>
											<td class="numeric"><?php echo $val['city'];?></td>
											<td class="numeric"><?php echo $val['num'];?></td>
											<td class="numeric">￥<?php echo $val['money'];?></td>
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
