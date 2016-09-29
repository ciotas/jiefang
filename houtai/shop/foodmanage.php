<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
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
}
$foodmanage=new FoodManage();
$title="我的菜单";
$menu="dataset";
$clicktag="foodmanage";
$shopid=$_SESSION['shopid'];
// echo $shopid;exit;
$typeno="0";
require_once ('header.php');
if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$arr=$foodmanage->getFoodOrderByType($shopid);
?>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						我的菜单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<!-- <a href="./interface/syncfood.php?backurl=foodmanage" class="btn red">同步数据</a> -->
						<small> 修改完后系统会自动同步数据</small>
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
						<div class="portlet box yellow">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>美食列表</div>
								<div class="tools">
								<a href="./interface/sortfood.php?op=alpha" class="btn blue">字母排序</a>
								<a href="./interface/sortfood.php?op=price" class="btn purple">价格排序</a>
								<a href="./editfood.php"  class="config"></a>
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
										 	<th class="hidden-480">英文名</th> 
										<!-- 	<th class="hidden-480">编码</th> -->
										<!-- 	<th class="hidden-480">排序</th> -->
											<th >价格</th>
										<!-- 	<th class="hidden-480">点菜单位</th> -->
											<th>计量单位</th>
											<th class="hidden-480">口味</th>
										<!-- 	<th class="hidden-480">档口</th> -->
											<th class="hidden-480">优惠</th>
											<th class="hidden-480">称重</th>
										<!-- 	<th class="hidden-480">Hot</th> -->
											<th class="hidden-480">套餐</th>
										<!-- 	<th class="hidden-480">估清</th> -->
											<th class="hidden-480">酒水标记</th>
											<th class="hidden-480">服务员可见</th>
											<th class="hidden-480">消费者可见</th>
										<!-- 	<th class="hidden-480">必点菜</th>
											<th class="hidden-480">按人数</th>
											<th class="hidden-480">简介</th> -->
											<th ></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($ftval['food'] as $fkey=>$fval){
										if($fval['fooddisaccount']=="1"){
											$fooddisaccount='<span class="label label-success">优惠</span>';
										}else{
											$fooddisaccount='<span class="label label-warning">不优惠</span>';
										}
										if($fval['isweight']=="1"){
											$isweight='<span class="label label-warning">称重</span>';
										}else{
											$isweight='<span class="label label-success">不称重</span>';
										}
										if($fval['ishot']=="1"){
											$ishot='<span class="label label-warning">推荐</span>';
										}else{
											$ishot='<span class="label label-success">不推荐</span>';
										}
										if($fval['ispack']=="1"){
											$ispack='<span class="label label-warning">套餐</span>';
										}else{
											$ispack='<span class="label label-success">非套餐</span>';
										}
										if($fval['foodguqing']=="1"){
											$foodguqing='<span class="label label-warning">估清</span>';
										}else{
											$foodguqing='<span class="label label-success">有货</span>';
										}
										if($fval['autostock']=="1"){
											$autostock='<span class="label label-warning">自动</span>';
										}else{
											$autostock='<span class="label label-success">手动</span>';
										}
										if($fval['showout']=="1"){
											$showout='<span class="label label-success">可见</span>';
										}else{
											$showout='<span class="label label-warning">不可见</span>';
										}
										if($fval['showserver']=="1"){
											$showserver='<span class="label label-success">可见</span>';
										}else{
											$showserver='<span class="label label-warning">不可见</span>';
										}
										if($fval['mustorder']=="1"){
											$mustorder='<span class="label label-success">是</span>';
										}else{
											$mustorder='<span class="label label-warning">否</span>';
										}
										if($fval['orderbynum']=="1"){
											$orderbynum='<span class="label label-success">是</span>';
										}else{
											$orderbynum='<span class="label label-warning">否</span>';
										}
										?>
										<tr>
										<!-- 	<td class="hidden-480"><img alt="" src="<?php echo $fval['foodpic'];?>" width="60" height="60"></td> -->
											<td ><?php echo $fval['foodname'];?></td>
										 	<td class="hidden-480"><?php echo $fval['foodengname'];?></td>
											<!--<td class="hidden-480"><?php echo $fval['foodcode'];?></td>
											<td class="hidden-480"><?php echo $fval['sortno'];?></td> -->
											<td ><?php echo $fval['foodprice'];?></td>
										<!-- 	<td class="hidden-480"><?php echo $fval['orderunit'];?></td> -->
											<td><?php echo $fval['foodunit'];?></td>
											<td class="hidden-480"><?php echo implode("、", $fval['foodcooktype']);?></td>
										<!-- 	<td class="hidden-480"><?php echo $fval['zonename'];?></td>-->
											<td class="hidden-480"><?php echo $fooddisaccount;?></td>
											<td class="hidden-480"><?php echo $isweight;?></td>
										<!-- 	<td class="hidden-480"><?php echo $ishot;?></td> -->
											<td class="hidden-480"><?php echo $ispack;?></td>
										<!-- <td class="hidden-480"><?php echo $foodguqing;?></td> -->	
											<td class="hidden-480"><?php echo $autostock;?></td>
											<td class="hidden-480"><?php echo $showserver;?></td>
											<td class="hidden-480"><?php echo $showout;?></td>
										<!-- 	<td class="hidden-480"><?php echo $mustorder;?></td>
											<td class="hidden-480"><?php echo $orderbynum;?></td> 
											<td class="hidden-480"><?php  if(!empty($fval['foodintro'])){echo mb_substr($fval['foodintro'], 1,4,"UTF-8")."...";}?></td>-->
											<td width="70"><a href="./editfood.php?foodid=<?php echo $fval['foodid'];?>&typeno=<?php echo $ftkey;?>" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonefood.php?foodid=<?php echo base64_encode($fval['foodid']);?>&typeno=<?php echo $ftkey;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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