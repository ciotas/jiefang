<?php
use Admin\Controller\PublicController;
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');

class DayReport{
    public function getDayReport($day=NULL){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getDayReport($day);
	}
	Public function addOneTransferLog($arr)
	{
	    return Admin_InterfaceFactory::createInstanceAdminOneDAL()->addOneTransferLog($arr);
	}
	public function getTransferState($shopid, $day)
	{
	    return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getTransferState($shopid, $day);
	}
}
$dayreport=new DayReport();

$title="商家日账单表";
$menu="manage";
$clicktag="dayreport";
$shopid=$_SESSION['shopid'];

require_once ('header.php');

if(!isset($_GET['day'])){
    $day=date("Y-m-d");
}else{$day = $_GET['day'];}
$arr=$dayreport->getDayReport($day);
?>
		<h3 class="page-title">
				商家日收入信息<small> </small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
					<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											
											<th class="number">日期</th>
											<th class="number">查询</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										<form action="" method="get">
											<td class="number"><input type="date" placeholder="必填"  name="day" value="<?php echo $day; ?>" style="width:80%;"></td>
											<td class="number"><input class="add_h pandm" type="submit" value="查询" style="background-color:#4b8df8;color:#fff;"></td>
										</form>
										</tr>
									</tbody>
								</table>
							</div>
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>商家日收入信息</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">商家名</th>
											<th class="number">支付宝收入</th>
											<th class="number">微信收入</th>
											<th class="number">总额</th>
											<th class="number">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php $all = array();  $i = 1;  foreach ($arr as $val){
									           $res = $dayreport->getTransferState($val['shopid'], $day);
									    ?>
										<tr>
											<td class="number"><?php echo $i;?></td>
											<td class="number"><?php echo $val['shopname'];?></td>
											<td class="number"><?php echo $val['todaydata']['alipay'];?></td>
											<td class="number"><?php echo $val['todaydata']['wechatpay'];?></td>
											<td class="number"><?php echo $val['total'] = $val['todaydata']['alipay']+$val['todaydata']['wechatpay'];?></td>
										
											<td class="number"><?php if($res){?><a href="./interface/addOneTransfer.php?shopid=<?php echo $val[shopid].'&day='.$day.'&money='.$val['total']; ?>" >确认转账</a><?php }else{echo "已经转账";}?></td>
										</tr>
										<?php
										$val['day'] = $day;
                                            $i++;
                                            $all['wechatpay']+=$val['todaydata']['wechatpay'];
                                            $all['alipay']+= $val['todaydata']['alipay'];
                                            $all['total'] += $val['total'];
                                        }?>
                                        <tr>
											<td class="number">总额</td>
											<td class="number"></td>
											<td class="number"><?php echo $all['alipay']; ?></td>
											<td class="number"><?php echo $all['wechatpay']; ?></td>
											<td class="number"><?php echo $all['total']; ?></td>
											<td class="number">别路错账啊</td>
										</tr>
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
	<?php 
	require_once ('footer.php');
?>