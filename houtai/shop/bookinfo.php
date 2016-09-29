<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BookInfo{
	public function getCusinfoByuid($uid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getCusinfoByuid($uid);
	}
}
$bookinfo=new BookInfo();
$uid=$_REQUEST['uid'];
$shopid=$_REQUEST['shopid'];
$cusinfo=$bookinfo->getCusinfoByuid($uid);
if(isset($_GET['cusnum'])){
	$cusnum=$_GET['cusnum'];
}
?>
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>预定桌位</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui">
	
	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>
<script>
function checkform(){
	
}
</script>
</head>

<!-- END HEAD -->
<!-- BEGIN BODY -->

    <div class="page-container">
	<!-- BEGIN SIDEBAR -->

		<div class="page-content">

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<div class="portlet-body form">

								<div class="tabbable portlet-tabs">
									<ul class="nav nav-tabs">
										<li>&nbsp;</li>
									</ul>
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/postbook.php" class="form-horizontal" method="post">
												<input type="hidden" name="uid" value="<?php echo $uid;?>">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<div class="control-group">
													<label class="control-label">贵姓（如张先生、李女士）</label>
													<div class="controls">
													<input class="m-wrap span12" type="text" placeholder="" name="cusname"  id="cusname"  value="<?php if(!empty($cusinfo)){echo $cusinfo['cusname'];}?>"  />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">人数</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="cusnum" name="cusnum" >
															<?php for ($i=0;$i<20 ;$i++){?>
															<option value="<?php echo $i;?>" <?php if($cusnum==$i){echo "selected";}?>><?php echo $i;?></option>
															<?php }?>
														</select>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">联系方式</label>
													<div class="controls">
													<input class="m-wrap span12" type="tel" placeholder="手机号" name="cusphone"  id="cusphone"  value="<?php if(!empty($cusinfo)){echo $cusinfo['cusphone'];}?>"/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">到店时间</label>
													<div class="controls">
													<input class="m-wrap large" type="date"  name="bookdate"  id="bookdate"  />
													<input class="m-wrap small" type="time"  name="booktime"  id="booktime"  />
													</div>
												</div>
												<a class="btn red" href="<?php echo $root_url;?>weshop/shopindex.php?uid=<?php echo $uid;?>&shopid=<?php echo $shopid;?>&type=inhouse"><i class="icon-remove"></i> 返回</a>
												<button type="submit" class="btn blue"><i class="icon-ok"></i> 提交</button>
											</form>
											<!-- END FORM-->  
										</div>
									</div>
								</div>

							</div>
					</div>

				</div>

				<!-- END PAGE CONTENT-->         

			</div>

			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->  

	</div>

	<!-- END CONTAINER -->

	<div class="footer">

	</div>

	<!-- END FOOTER -->
	<!-- END JAVASCRIPTS -->
<?php 
if(isset($_GET['status'])){
	$status=$_GET['status'];
	switch ($status){
		case "empty_name":echo '<script>alert("贵姓不能为空！");</script>';break;
		case "empty_num":echo '<script>alert("请选择人数")</script>';break;
		case "empty_phone":echo '<script>alert("请输入联系方式！");</script>';break;
		case "empty_date":echo '<script>alert("请选择到店日期");</script>';break;
		case "empty_time":echo '<script>alert("请选择到店时间");</script>';break;
		case "date_error":echo '<script>alert("您不能选择过去的日期");</script>';break;
		case "ok":echo '<script>
			if(confirm("亲，您提交成功，本店马上跟您确认，您可以先点好菜，到店直接下单~")){
				window.location.href="'.$wechat_url.'index.php?m=Admin&c=Index&a=index&type=inhouse&shopid='.$shopid.'&uid='.$uid.'";
		}</script>';
		break;
	}
}
?>
</body>

<!-- END BODY -->

</html>
