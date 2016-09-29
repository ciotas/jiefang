<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpFoodPic{
	public function getFoodOrderByType($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodOrderByType($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$upfoodpic=new UpFoodPic();
$openid=$_REQUEST['openid'];
$shopid=$upfoodpic->getShopidByOpenid($openid);
$arr=$upfoodpic->getFoodOrderByType($shopid);
// print_r($arr);exit;
$sortno="0";
$status="";
if(isset($_GET['status'])){
	$status=$_GET['status'];
	$sortno=$_GET['sortno'];
}
?>
<script type="text/javascript">
<!--
function getOnefood(foodid,foodname,key){
	document.getElementById("foodid").value=foodid;
	document.getElementById("foodname").value=foodname;
	document.getElementById("sortno").value=key;
}

//-->
</script>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>商品图片</title>

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
	<link href="../media/css/jquery.fileupload-ui.css" rel="stylesheet" />
	<link href="../media/css/bootstrap-fileupload.css" rel="stylesheet" type="text/css" />


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

			<div class="container-fluid">
				<div class="row-fluid">
				<div class="alert alert-error">

									<button class="close" data-dismiss="alert"></button>

									<strong>若图片过大，上传会较慢，请耐心等待！</strong>

								</div>
					
				</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid profile">
					<div class="span12">
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box yellow">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>图片列表</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
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
												<li <?php if($key==$sortno){echo "class='active'";}?>><a href="#tab_<?php echo $val['ftid'];?>" data-toggle="tab" ><?php echo $val['ftname'];?></a></li>
												<?php }?>
											</ul>
											<div class="tab-content">
											<?php foreach ($arr as $ftkey=>$ftval){?>
												<div class="tab-pane <?php if($ftkey==$sortno){echo "active";}?>" id="tab_<?php echo $ftval['ftid']?>">
													<div class="portlet box yellow">

							<div class="portlet-body">

								<table class="table table-bordered table-hover">

									<thead>

										<tr>

											<th>图片</th>

											<th>名称</th>



											<th>操作</th>

										</tr>

									</thead>

									<tbody>
									<?php foreach ($ftval['food'] as $fkey=>$fval){?>
										<tr>
											<td><img alt="" src="<?php echo $fval['foodpic'];?>" width="60" height="60"></td>
											<td><?php echo $fval['foodname']?></td>
											<td>
											<form action="../interface/dowechatupimg.php" method="post" enctype="multipart/form-data">
										<div class="controls">
										<input type="hidden" name="openid" value="<?php echo $openid;?>">
										<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
										<input type="hidden" name="foodid" value="<?php echo $fval['foodid'];?>">
										<input type="hidden" name="sortno" value="<?php echo $ftkey;?>">
											<div class="fileupload fileupload-new" data-provides="fileupload">
											<input type="hidden" value="" name="">
												<span class="btn btn-file red">
												<span class="fileupload-new">选图</span>
												<span class="fileupload-exists">重新上传</span>
												<input type="file" class="default" name="foodpic">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i>上传</button>
											</div>
										</div>
										</form>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
						<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">
								<h4></h4>
								<form action="./interface/addpicaddr.php" method="post">
									<input type="hidden" name="foodid"  id="foodid" >
									<input type="hidden" name="sortno"  id="sortno">
									<div class="control-group">
										<label class="control-label">图片地址：<span id="foodname"></span></label>
										<div class="controls">
											<input type="text" placeholder="必填" id="foodpic" name="foodpic" class="m-wrap large" >
										</div>
									</div>
									<hr>
									<button type="button" data-dismiss="modal" class="btn">取消</button>
									<button type="submit"  class="btn blue">保存</button>
								</form>
							</div>
						</div>
					</div>
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

<?php 
require_once ('../footer.php');
switch ($status){
	case "fail": echo "<script>alert('图片上传失败！')</script>";break;
	case "ok":break;
	case "formaterror":echo "<script>alert('图片格式错误！')</script>";break;
	case "imgerror":echo "<script>alert('图片上传错误！')</script>";break;
}
?>