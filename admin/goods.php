<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Goods{
	public function getGoodsData(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getGoodsData();
	}
}
$goods=new Goods();
$title="商品";
$menu="goods";
$clicktag="goods";
$typeno="0";
require_once ('header.php');
if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$arr=$goods->getGoodsData();
?>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						商品列表
						<small> </small>
						</h3>
					
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

			<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid profile">
					<div class="span12">
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>商品列表</div>
								<div class="tools">
									<a href="./editgoods.php"  class="config"></a>
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
												<li <?php if($key==$typeno){echo "class='active'";}?>><a href="#tab_<?php echo $val['goodstypeid'];?>" data-toggle="tab"><?php echo $val['goodstypename'];?></a></li>
												<?php }?>
											</ul>
											<div class="tab-content">
											<?php foreach ($arr as $ftkey=>$ftval){?>
												<div class="tab-pane <?php if($ftkey==$typeno){echo "active";}?>" id="tab_<?php echo $ftval['goodstypeid']?>">
													<div class="portlet box yellow">
							<div class="portlet-body">
								<table class="table-bordered table-striped table-condensed flip-content" >
									<thead>
										<tr>
											<th class="hidden-480">图片</th>
											<th >商品名称</th>
										<!-- 	<th class="hidden-480">一句话</th> -->
										 	<th class="hidden-480">原价</th> 
											<th >街坊价</th>
											<th>计量单位</th>
											<th>出售单位</th>
										<!-- 	<th class="hidden-480">规格</th> -->
											<th class="hidden-480">上线</th>
											<th>图片上传</th>
											<th ></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($ftval['goods'] as $fkey=>$fval){
									if($fval['online']=="1"){$onlinestr='<span class="label label-success">已上线</span>';}else{$onlinestr='<span class="label label-warning">下线</span>';}
										?>
										<tr>
										<td class="hidden-480"><img alt="" src="<?php echo $fval['goodspic'];?>" width="60" height="60"></td> 
											<td ><?php echo $fval['goodsname'];?></td>
										<!--  	<td class="hidden-480"><?php echo $fval['goodsdesc'];?></td> -->
											<td class="hidden-480"><?php echo $fval['otherprice'];?></td>
											<td><?php echo $fval['ourprice'];?></td>
											<td><?php echo $fval['goodsunit'];?></td>
											<td><?php echo $fval['goodssoldunit'];?></td>
									<!-- 		<td class="hidden-480"><?php echo $fval['goodsformat'];?></td> -->
											<td class="hidden-480"><?php echo $onlinestr;?></td>
											<td>
												<form action="./interface/upgoodsimg.php" method="post" enctype="multipart/form-data">
										<div class="controls">
										<input type="hidden" name="goodsid" value="<?php echo $fval['goodsid'];?>">
										<input type="hidden" name="sortno" value="<?php echo $ftkey;?>">
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<span class="btn btn-file red">
												<span class="fileupload-new">选择图片</span>
												<span class="fileupload-exists">重新上传</span>
												<input type="file" class="default" name="goodspic">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
											</div>
										</div>
										</form>
											</td>
											<td width="70"><a href="./editgoods.php?goodsid=<?php echo $fval['goodsid'];?>&typeno=<?php echo $ftkey;?>" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonegoods.php?goodsid=<?php echo base64_encode($fval['goodsid']);?>&typeno=<?php echo $ftkey;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
require_once ('footer.php');
?>