<?php 
require_once ('/var/www/html/boss/global.php');
require_once ('./startsession.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Index{
	public function getOpenHourByShopid($shopid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getOpenHourByShopid($shopid);
	}
	public function getMyShoplistData($bossid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getMyShoplistData($bossid);
	}
	public function getOneShopRundata($shopid, $startdate, $enddate, $datearr, $thehour){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getOneShopRundata($shopid, $startdate, $enddate, $datearr, $thehour);
	}
	public function getShopPercentData($shoparr, $op){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getShopPercentData($shoparr, $op);
	}
	public function getTheday($shopid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getTheday($shopid);
	}
	public function getTableRunStatus($shoparr){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getTableRunStatus($shoparr);
	}
}
$index=new Index();
$title="我的主页";
$menu="dashboard";
$clicktag="index";
$bossid=$_SESSION['bossid'];
// echo $bossid;exit;
$menu="dashboard";
$clicktag="turnovertrend";
require_once ('header.php');
$enddate=date("Y-m-d",time());
$startdate=date("Y-m-d",time()-86400*15);
$shops=$index->getMyShoplistData($bossid);
if(isset($_REQUEST['startdate'])){
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
}
$datearr=array();
for ($day=strtotime($startdate);$day<=strtotime($enddate);$day=$day+86400){
	$datearr[]=date("Y-m-d",$day);
}
$dataarr=array();
$shoparr=array();
foreach ($shops as $key=>$val){
	$thehour=$index->getOpenHourByShopid($val['shopid']);
	$dataarr[]=$index->getOneShopRundata($val['shopid'], $startdate, $enddate, $datearr, $thehour);
	$theday=$index->getTheday($val['shopid']);
	$shoparr[]=array("shopid"=>$val['shopid'],"shopname"=>$val['shopname'],"today"=>$theday, "openhour"=>$thehour);
}
$yestodaypercent=$index->getShopPercentData($shoparr, "yestoday");
// var_dump($todaypercent);exit;
// $todaypercent=$index->getShopPercentData($shoparr, "today");
$tabarr=$index->getTableRunStatus($shoparr);
?>

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							分店应收款<small>最新15天</small>

						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<div class="well" style="margin-button:15px;padding:8px;">
<!-- 					<h4>精选小票打印纸优惠上线啦(￣▽￣)"</h4> -->
<!-- 					80mm打印机适用，一箱超大量100卷80×60，每卷只需1.6元！<a class="btn yellow mini"  href="./onegoods.php?goodsid=56d9a82c7cc109de558b4593" >立即购买 =></a>，购买后的订单在“商家中心”—“我的订单”里查看！ -->
<!-- 				</div> -->
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT--> 
				<?php if($_SESSION['bossid']!="5747e7295bc1099a068b45de"){?>
				<div class="portlet box green">
				<div class="portlet-title">
						<div class="caption"> 桌台状态</div>
					</div>
				<div class="portlet-body">  
				<table class="table table-hover">
				  <tr>
				    <th>店名</th>
				    <th>开台数</th>
				    <th>占用数</th>
				    <th>预定数</th>
				     <th>空闲数</th>
				  </tr>
				  <?php foreach ($tabarr as $key=>$val){?>
				  <tr>
				  <td><?php echo $val['shopname'];?></td>
				    <td><?php echo $val['startnum'];?></td>
				    <td><?php echo $val['onlinenum'];?></td>
				    <td><?php echo $val['booknum'];?></td>
				    <td><?php echo $val['emptynum'];?></td>
				  </tr>
				  <?php }?>
				</table>
 			</div></div>
 			<?php }?>
				<div class="span6">
				<h3>应收款对比</h3>
				<canvas id="chart-area_yes" width="220" height="220" style="text-align:center"/></canvas>
				<?php 
					$jsondata=json_encode($yestodaypercent);
					echo '<script>';
					echo 'var pieData='.$jsondata.';';
					echo 'var ctx = document.getElementById("chart-area_yes").getContext("2d");';
					echo 'window.myPie = new Chart(ctx).Pie(pieData);';
					echo '</script>';
				?>		
				</div>		    
			<?php foreach ($dataarr as $key=>$val){?>
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box">
							<div class="portlet-title">
								<div class="caption" style="color:black"><h3><?php echo $val['shopname'];?></h3></div>
								
							</div>
							<div class="portlet-body flip-scroll">
							
								<canvas id="lineChart_<?php echo $key;?>" ></canvas>
								<?php 
									$jsondata=json_encode($val);
									echo '<script>';
									echo 'var lineChartData='.$jsondata.';';
									echo 'var ctx = document.getElementById("lineChart_'.$key.'").getContext("2d");';
									echo 'window.myLine = new Chart(ctx).Line(lineChartData, {responsive: true});';
									echo '</script>';
								?>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->
					</div>

				</div>
		<?php }?>
				<!-- END PAGE CONTENT-->

			</div>

			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->

	</div>

	<!-- END CONTAINER -->
	
<!-- BEGIN FOOTER -->
	<!-- BEGIN FOOTER -->

	<!-- BEGIN FOOTER -->

	<?php 
	require_once 'footer.php';
	?>