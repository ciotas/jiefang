<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodManage{
	public function getFoodtypesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodtypesByShopid($shopid);
	}
	public function getFoodOrderByType($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodOrderByType($shopid);
	}
	public function getZonesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getZonesByShopid($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$foodmanage=new FoodManage();
$openid=$_REQUEST['openid'];
$shopid=$foodmanage->getShopidByOpenid($openid);

$typeno="0";

if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$arr=$foodmanage->getFoodOrderByType($shopid);
?>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>我的菜单</title>

	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="../media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="../media/css/style.css" rel="stylesheet" type="text/css"/>
	
	<link href="../media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>


	<!-- BEGIN PAGE LEVEL STYLES -->

	<link href="../media/css/timeline.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->


</head>

<!-- END HEAD -->
<body>
<div class="page-container row-fluid">
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->

				</div>
				<!-- END PAGE HEADER-->

			<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid profile">
					<div class="span12">
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box yellow">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>商品列表</div>
								<div class="tools">
								<a href="../interface/sortwechatfood.php?op=alpha&openid=<?php echo $openid;?>&shopid=<?php echo $shopid;?>" class="btn blue">字母排序</a>
								<a href="../interface/sortwechatfood.php?op=price&openid=<?php echo $openid;?>&shopid=<?php echo $shopid;?>" class="btn purple">价格排序</a>
								<a href="./editfood.php?openid=<?php echo $openid;?>"  class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<div class="row-fluid">
									<div class="span12">
										<!--BEGIN TABS-->
										<div class="tabbable tabbable-custom tabs-left">
											<!-- Only required for left/right tabs -->
											<ul class="nav nav-tabs tabs-left">
											<?php foreach ($arr as $key=>$val){?>
												<li <?php if($key==$typeno){echo "class='active'";}?>><a href="#tab_<?php echo $val['ftid'];?>" data-toggle="tab"><?php echo $val['ftname'];?></a></li>
												<?php }?>
											</ul>
											<div class="tab-content">
											<?php foreach ($arr as $ftkey=>$ftval){?>
												<div class="tab-pane <?php if($ftkey==$typeno){echo "active";}?>" id="tab_<?php echo $ftval['ftid']?>">
													<div class="portlet box yellow">
							<div class="portlet-body">
								<table class="table-bordered table-striped table-condensed flip-content" >
									<thead>
										<tr>
										<!-- 	<th class="hidden-480">图片</th> -->
											<th >名称</th>
									
											<th >价格</th>
											
									
											<th ></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($ftval['food'] as $fkey=>$fval){
										
										?>
										<tr> 
											<td ><?php echo $fval['foodname'];?></td>
									
											<td ><?php echo $fval['foodprice']."/".$fval['foodunit'];?></td>
											 
											<td width="70"><a href="./editfood.php?foodid=<?php echo $fval['foodid'];?>&typeno=<?php echo $ftkey;?>&openid=<?php echo $openid;?>" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="../interface/delwechatfood.php?foodid=<?php echo base64_encode($fval['foodid']);?>&typeno=<?php echo $ftkey;?>&openid=<?php echo $openid;?>&shopid=<?php echo $shopid;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
								</div>
							</div>
				</div>
			<?php }?>
												</div>
										</div>
										<!--END TABS-->
									</div>
									<div class="space10 visible-phone"></div>
								</div>
							</div>
						</div>
						
						<!-- END INLINE TABS PORTLET-->
						
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
<script type="text/javascript" src="media/js/food.js"></script>
<?php 
require_once ('../footer.php');
?>