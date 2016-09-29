<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpFoodPic{
	public function getFoodOrderByType($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodOrderByType($shopid);
	}
}
$upfoodpic=new UpFoodPic();
$title="美食图片";
$menu="dataset";
$clicktag="uppic";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
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
				<div class="row-fluid">

					<div class="span12">
						<!-- BEGIN STYLE CUSTOMIZER -->
						<h3 class="page-title">
							美食图片 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<!-- <a href="./interface/syncfood.php?backurl=upfoodpic" class="btn red">同步数据</a> -->
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="alert alert-success">
					<button class="close" data-dismiss="alert"></button>
					<strong>温馨提示：建议图片的大小不超过5M，正方形最好，若图片过大，请点击<a href="http://xiuxiu.web.meitu.com/main.html" target="_blank"> 这里 </a>压缩</strong><br>
					<strong>压缩步骤：1. 先点击屏幕中间的黄色按钮“打开一张图片”，打开需要压缩的图片</strong><br>
					<strong>压缩步骤：2. 点击顶部菜单栏最右边的“保存与分享”，系统会自动压缩，然后点解蓝色按钮“保存图片”，保存到本地，所得图片即为压缩后的图片</strong>
				</div>
				<div class="row-fluid profile">
					<div class="span12">
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box yellow">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>美食图片列表</div>
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

											<th class="hidden-480">价格</th>

											<th>图片地址</th>

											<th>操作</th>

										</tr>

									</thead>

									<tbody>
									<?php foreach ($ftval['food'] as $fkey=>$fval){?>
										<tr>
											<td><img alt="" src="<?php echo $fval['foodpic'];?>" width="60" height="60"></td>
											<td><?php echo $fval['foodname'];?></td>
											<td class="hidden-480"><?php echo $fval['foodprice']."元/".$fval['orderunit'];?></td>
											<td>
											<a href="#static" onclick="getOnefood('<?php echo $fval['foodid'];?>','<?php echo $fval['foodname'];?>','<?php echo $ftkey;?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											</td>
											<td>
											<form action="./interface/doupimg.php" method="post" enctype="multipart/form-data">
										<div class="controls">
										<input type="hidden" name="foodid" value="<?php echo $fval['foodid'];?>">
										<input type="hidden" name="sortno" value="<?php echo $ftkey;?>">
											<div class="fileupload fileupload-new" data-provides="fileupload">
											<input type="hidden" value="" name="">
												<span class="btn btn-file red">
												<span class="fileupload-new">选择图片</span>
												<span class="fileupload-exists">重新上传</span>
												<input type="file" class="default" name="foodpic">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
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
require_once ('footer.php');
switch ($status){
	case "fail": echo "<script>alert('图片上传失败！')</script>";break;
	case "ok":break;
	case "formaterror":echo "<script>alert('图片格式错误！')</script>";break;
	case "imgerror":echo "<script>alert('图片上传错误！')</script>";break;
}
?>