<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodPics{
	public function getFoodpicsData($searchfoodname,$p,$pagenum){
		return QuDian_InterfaceFactory::createInstanceMonitorCusDAL()->getFoodpicsData($searchfoodname,$p, $pagenum);
	}
}
$foodpics=new FoodPics();
$title="商品图片库";
$menu="dataset";
$clicktag="foodpics";
$shopid=$_SESSION['shopid'];
$roleid=$_SESSION['roleid'];
require_once ('header.php');
$p=1;
if(isset($_GET['p'])){
	$p=$_GET['p'];
}
if($p<=1){$p=1;}
$searchfoodname="";
if(isset($_REQUEST['searchfoodname'])){
	$searchfoodname=$_REQUEST['searchfoodname'];
}
$pagenum=12;
$arr=$foodpics->getFoodpicsData($searchfoodname,$p, $pagenum);
// print_r($arr);
?>
<style>
<!--
 a:hover {text-decoration:none;cursor:pointer;}
a:visited{text-decoration:none;} 
a:active{text-decoration:none;}
a:link{text-decoration:none;}
-->
</style>
				<div class="row-fluid">
				
				</div>
			<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<div class="tabbable tabbable-custom boxless">
							
							<div class="tab-content">
							<div id="tab_2_5" class="tab-pane active">

								<div class="row-fluid search-forms search-default">

									<form class="form-search" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

										<div class="chat-form" style="white-space:nowrap; ">

											<div class="input-cont span10"  style="white-space:nowrap; display:inline-block">   

												<input type="text" placeholder="输入商品名"  value="<?php echo $searchfoodname;?>" class="m-wrap"  name="searchfoodname"/>

											</div>

											<button type="submit" class="btn green">

											搜索 &nbsp; 

											<i class="m-icon-swapright m-icon-white"></i>

											</button>

										</div>

									</form>

								</div>

								<div class="row-fluid search-images">
									<?php
										$i=0;
									 foreach ($arr as $key=>$val){
										if($i%4==0){
											echo '<ul class="thumbnails">';
										}
										?>
										<li class="span3">

											<span class="fancybox-button" data-rel="fancybox-button" title="<?php echo $val['foodname'];?>" >

											<img src="<?php echo $val['foodpic'];?>" alt="" >

											<h4><?php echo $val['foodname'];?></h4>

											</span>

										</li>
										<?php 
										if($i%4==3){
											echo '</ul>';
										}
										?>						
									
										<?php $i++; }?>
								</div>

								<div class="spac40"></div>

								<div class="pagination pagination-right">

									<ul>

										<li><a href="./foodpics.php?p=<?php if($p<=1){echo 1;}else{echo ($p-1);}?>&searchfoodname=<?php echo $searchfoodname;?>">Prev</a></li>
										 <?php if($p<=1){echo '<li></li>'; }else{echo '<li><a href="./checkshop.php?p='.($p-1).'&searchfoodname='.$searchfoodname.' ">'.($p-1).'</a></li>';}?>             
					                          <li class="active"><a href="./foodpics.php?p=<?php echo $p;?>&searchfoodname=<?php echo $searchfoodname;?>"><?php echo $p;?></a></li>
					                          <li><a href="./foodpics.php?p=<?php echo ($p+1);?>&searchfoodname=<?php echo $searchfoodname;?>"><?php echo ($p+1);?></a></li>
					                          <li><a href="./foodpics.php?p=<?php echo ($p+1);?>&searchfoodname=<?php echo $searchfoodname;?>">Next</a></li>
									</ul>

								</div>
							
							</div>
								</div>
							</div>
					</div>
					</div>
				</div>
				
			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->
<?php 
require_once 'footer.php';
?>