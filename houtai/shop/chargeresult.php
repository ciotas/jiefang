<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ChargeResult{
	public function getChargeResult($recordid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getChargeResult($recordid);
	}
}
$chargeresult=new ChargeResult();
$title="充值结果";
$menu="vip";
$clicktag="recharge";
$shopid=$_SESSION['shopid'];
$arr=array();
require_once ('header.php');
if(isset($_GET['recordid'])){
	$type=$_GET['type'];
	$recordid=$_GET['recordid'];
	if(!empty($recordid)){
		$resultstatus="1";
		$arr=$chargeresult->getChargeResult($recordid);
	}elseif($recordid=="0"){
		$resultstatus="0";
	}
}

?>

			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<div class="span12">
						<h3 class="page-title">
							<?php if($type=="charge"){echo "充值";}else{echo "赠送";}?>结果
							 <small></small>
						</h3>
				</div>
				
				<div class="row-fluid profile">
				
					<div class="span12">

					<div class="portlet-body">
								<?php if($resultstatus=="1"){?>
								<div class="alert alert-success">
									<button class="close" data-dismiss="alert"></button>
									<strong><?php if($type=="charge"){echo "充值";}else{echo "赠送";}?>成功！</strong> 谢谢 O(∩_∩)O~
								</div>
								<?php }elseif($resultstatus=="0"){?>
								
								<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<strong><?php if($type=="charge"){echo "充值";}else{echo "赠送";}?>失败！</strong> 请重试 /(ㄒoㄒ)/~~
								</div>
								<?php }?>
							</div>
						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

									<div class="span2"><img src="<?php if(!empty($arr)){echo $arr['photo'];}?>" alt="" /></div>

									<ul class="span10">

										<li><span>用户:</span> <?php if(!empty($arr)){echo $arr['nickname'];}?></li>
										
										<li><span>手机号:</span> <?php if(!empty($arr)){echo $arr['userphone'];}?></li>
										
										<li><span><?php if($type=="charge"){echo "充值";}else{echo "赠送";}?>卡:</span> <?php if(!empty($arr)){echo $arr['cardname'];}?></li>
										<?php  if($type=="charge"){?>
										<li><span>充:</span>￥<?php if(!empty($arr)){echo $arr['chargemoney'];}?></li>

										<li><span>送:</span>￥<?php if(!empty($arr['cardrate'])){echo sprintf("%.0f",$arr['chargemoney']/$arr['cardrate']);}else{echo "0";}?></li>

										<li style="color:red"><span>账户余额:</span>￥<?php if(!empty($arr)){echo $arr['accountbalance'];}?></li>
									<?php }?>
										<li><span><?php if($type=="charge"){echo "充值";}else{echo "赠送";}?>时间:</span> <?php if(!empty($arr)){echo date("Y-m-d H:i:s",$arr['timestamp']);}?></li>

									</ul>

								</div>	
								<!--end tab-pane-->
						</div>

						<!--END TABS-->

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
	require_once ('./footer.php');
	?>

	