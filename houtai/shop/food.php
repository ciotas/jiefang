<?php 
if(isset($_GET['ftid'])){
	$ftid=$_GET['ftid'];
}else{
	$ftid=1;
}
?>
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>美食</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-responsive.css" rel="stylesheet" type="text/css"/>

	
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<link href="media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->

	<link rel="shortcut icon" href="media/image/toptag.png" />
	
	<link href="media/css/search.css" rel="stylesheet" type="text/css"/>
			<link rel="stylesheet" href="media/css/jquery.spinner.css" />

	<!-- END PAGE LEVEL STYLES -->

	<link rel="shortcut icon" href="media/image/favicon.ico" />
<script>
function clearfood(){
	sessionStorage.food="";
	sessionStorage.cusnum=0;
}
</script>
<style type="text/css">

#shopedcart  {
position: fixed; 
bottom: 10px; 
right: 5px; 
text-align: center; 
padding: 2px; 
margin: 2px; 
}
#btnfoodtype{
	position: fixed; 
	bottom: 10px; 
	left: 5px; 
	text-align: center; 
	padding: 2px; 
	margin: 2px; 
}
ul.sidebar-tags a i{color:#FFF}
</style>
</head>

<body onload="initData()">

	<!-- BEGIN CONTAINER -->   

	<div>

		<!-- BEGIN PAGE -->

		<div class="page-content">

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

				<!-- 	<div class="span12">

							<input type="button" onclick="clearfood()"  value="清除"> 

					</div>
 -->
				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="tabbable tabbable-custom tabbable-full-width">

						<div class="tab-content">

								<div class="row-fluid search-images">
									
									<ul class="thumbnails">
										<li class="span3">
								<a class="fancybox-button" data-rel="fancybox-button" >
										<img src="media/image/1.jpg" alt="" width="1024" >
											<span class="foodspan"><em>木瓜西米露&nbsp;&nbsp;5元/份</em>
											
											<em style="text-align:right;float:right"> 
											<div>
												<input type="hidden" id="foodid1" value="foodid1">
												<button class="btn red icn-only"  onclick="addtocart('minus','foodid1','木瓜西米露1','5','微辣、中辣、重辣','0','0')"  style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>
												<input id="text_box_foodid1"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center;color:red"/>
												<?php 
												$foodcooktype="";
												$isweight="0";
												$ispack="0";
												if(empty($foodcooktype)&&$isweight=="0"&&$ispack=="0"){?>
												<button  class="btn red icn-only"   onclick="addtocart('add','foodid1','木瓜西米露1','5','','0','0')" style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>
												<?php }else{?>												
												<em class="btn red icn-only"  href="#static_foodid1"  data-toggle="modal"  style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></em>
																								
												<?php }?>
												</div>
												</em></span>
											</a>
										</li>
										
										<li class="span3">
											<a class="fancybox-button" data-rel="fancybox-button" >
											<img src="media/image/image1.jpg" alt="">
											<span class="foodspan"><em>木瓜西米露&nbsp;&nbsp;6元/份</em>
											
											<em style="text-align:right;float:right"> 
											<div>
												<input type="hidden" id="foodid3" value="foodid3">
												<button class="btn red icn-only"  onclick="addtocart('minus','foodid3','木瓜西米露3','6','微辣、中辣、重辣','1','0')" <?php $isweight="1";if($isweight=="1"){echo "disabled";}?>  style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>
												<input id="text_box_foodid3"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center"/>
												<?php 
												$foodcooktype="";
												$isweight="1";
												$ispack="0";
												if(empty($foodcooktype)&&$isweight=="0"&&$ispack=="0"){
												?>
												<button  class="btn red icn-only"   onclick="addtocart('add','foodid3','木瓜西米露3','6','','1','0')" style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>
												<?php }else{?>												
												<em class="btn red icn-only"  href="#static_foodid3"  data-toggle="modal"  style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></em>
												
																								
												<?php }?>
												</div>
												</em></span>
											</a>
										</li>
										<li class="span3">
											<a class="fancybox-button" data-rel="fancybox-button" >
											<img src="media/image/image1.jpg" alt="">
											<span class="foodspan"><em>木瓜西米露&nbsp;&nbsp;7元/份</em>
											
											<em style="text-align:right;float:right"> 
											<div>
												<input type="hidden" id="foodid4" value="foodid4">
												<button class="btn red icn-only"  onclick="addtocart('minus','foodid4','木瓜西米露5','7','','0','1')"   style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>
												<input id="text_box_foodid4"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center"/>
												<?php 
												$foodcooktype="";
												$isweight="0";
												$ispack="1";
												if(empty($foodcooktype)&&$isweight=="0"&&$ispack=="0"){
												?>
												<button  class="btn red icn-only"   onclick="addtocart('add','foodid4','木瓜西米露5','7','','0','1')" style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>
												<?php }else{?>												
												<em class="btn red icn-only"  href="#static_foodid4"  data-toggle="modal"  style="height:28px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></em>
												<?php }?>
												</div>
												</em></span>
											</a>
										</li>
										
										<li class="span3">
											<a class="fancybox-button" data-rel="fancybox-button" title="390 x 220 - keenthemes.com">
											<img src="media/image/image1.jpg" alt="">
											<span  class="foodspan"><em>木瓜西米露&nbsp;&nbsp;5元/份</em>
											<em style="text-align:right;float:right">
											<div>
												<input type="hidden" id="foodid2" value="foodid2">
												<button class="btn red icn-only"  onclick="addtocart('minus','foodid2','木瓜西米露7','6','','0','0')"  style="height:25px;width:35px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>
												<input id="text_box_foodid2"  disabled type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center"/>
												<button  class="btn red icn-only"   onclick="addtocart('add','foodid2','木瓜西米露7','6','','0','0')" style="height:25px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>
												</div>
												</em></span>
											</a>
										</li>								
									</ul>
								</div>
									
								
							<!--end tab-pane-->		
							<?php 
							$foodarr=array(
									"0"=>array(
											"foodid"=>"foodid1",
											"foodname"=>"油条",
											"foodprice"=>"20",
											"foodunit"=>"份",
											"orderunit"=>"份",
											"fooddisaccount"=>"1",
											"foodcooktype"=>"微辣、中辣、重辣",//微辣、中辣、重辣
											"isweight"=>"0",
											"ishot"=>"1",
											"ispack"=>"0",
											"present"=>"0",
									),
								"1"=>array(
										"foodid"=>"foodid3",
										"foodname"=>"油条",
										"foodprice"=>"20",
										"foodunit"=>"份",
										"orderunit"=>"份",
										"fooddisaccount"=>"1",
										"foodcooktype"=>"微辣、中辣、重辣",//微辣、中辣、重辣
										"isweight"=>"1",
										"ishot"=>"1",
										"ispack"=>"0",
										"present"=>"0",
								),
							"2"=>array(
									"foodid"=>"foodid4",
									"foodname"=>"油条",
									"foodprice"=>"20",
									"foodunit"=>"份",
									"orderunit"=>"份",
									"fooddisaccount"=>"1",
									"foodcooktype"=>"",//微辣、中辣、重辣
									"isweight"=>"0",
									"ishot"=>"1",
									"ispack"=>"1",
									"present"=>"0",
							),
							);
							$package='[[{"foodid":"554b0c6b5bc109d8518b45e5","foodname":"\u82f1\u5f0f\u7ea2\u8336","foodnum":"1","flag":"1"}],[{"foodid":"554b0c6b5bc109d8518b45e4","foodname":"\u661f\u5df4\u514b\u767d\u7261\u4e39\u8336","foodnum":"1","flag":"2"}],[{"foodid":"554b0c6b5bc109d8518b45e3","foodname":"\u8702\u871c\u63d0\u5b50\u53f8\u5eb7","foodnum":"1","flag":"3"}],[{"foodid":"554b0c6b5bc109d8518b45e1","foodname":"\u7279\u6d53\u5de7\u514b\u529b\u5e03\u6717\u5c3c","foodnum":"1","flag":"4"}],[{"foodid":"554b0c6b5bc109d8518b45e0","foodname":"\u7ecf\u5178\u7389\u6842\u5377","foodnum":"1","flag":"5"}],[{"foodid":"554b0c6b5bc109d8518b45de","foodname":"\u51b0\u8c03\u5236\u5496\u5561","foodnum":"1","flag":"6"}],[{"foodid":"554b0c6b5bc109d8518b45dd","foodname":"\u5bc6\u65af\u6735\u5496\u5561","foodnum":"1","flag":"7"}],[{"foodid":"554b0c6b5bc109d8518b45da","foodname":"\u7126\u7cd6\u739b\u5947\u6735","foodnum":"1","flag":"8"},{"foodid":"554b05595bc109dd518b45c3","foodname":"\u9e21\u86cb","foodnum":"1","flag":"8"}],[{"foodid":"554b0c6b5bc109d8518b45db","foodname":"\u9999\u8349\u661f\u51b0\u4e50","foodnum":"1","flag":"9"}],[{"foodid":"554b0c6b5bc109d8518b45e1","foodname":"\u7279\u6d53\u5de7\u514b\u529b\u5e03\u6717\u5c3c","foodnum":"2","flag":"10"},{"foodid":"554b0c6b5bc109d8518b45e3","foodname":"\u8702\u871c\u63d0\u5b50\u53f8\u5eb7","foodnum":"1","flag":"10"}],[{"foodid":"554b0c6b5bc109d8518b45d8","foodname":"\u62ff\u94c1","foodnum":"1","flag":"11"}]]';
							$packagearr=json_decode($package,true);
							foreach ($foodarr as $key=>$val){
								if(!empty($val['foodcooktype'])&&$val['isweight']=="0"&&$val['ispack']=="0"){?>	
							<div id="static_<?php echo $val['foodid'];?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
								<div class="modal-body span12">
								<div class="tab-pane  active" id="portlet_tab3">
												<h4><span style="color: red;"></span></h4>
												<br>
												<div>
												<input type="hidden" id="<?php echo $val['foodid'];?>" value="<?php echo $val['foodid'];?>">
												<button class="btn red icn-only"  onclick="addtocart('minus','<?php echo $val['foodid'];?>','<?php echo $val['foodname'];?>','<?php echo $val['foodprice'];?>','<?php echo $val['foodcooktype'];?>','<?php echo $val['isweight'];?>','<?php echo $val['ispack'];?>')"  style="height:30px;width:50px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>
												<input id="text_box_vice_<?php echo $val['foodid'];?>"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center;color:red"/>
												<button  class="btn red icn-only"   onclick="addtocart('add','<?php echo $val['foodid'];?>','<?php echo $val['foodname'];?>','<?php echo $val['foodprice'];?>','<?php echo $val['foodcooktype'];?>','<?php echo $val['isweight'];?>','<?php echo $val['ispack'];?>')" style="height:30px;width:50px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>
												</div>
											</div><br>
											<?php 
											$cooktypearr=explode("、", $val['foodcooktype']);
											foreach ($cooktypearr as $ckey=>$cval){?>
											<button type="button" class="btn"  id="<?php echo $val['foodid']."_".$cval;?>" onclick="SelectCook('<?php echo $val['foodid'];?>','<?php echo $cval;?>')"><?php echo $cval;?></button>
											<?php }?>
											<br><br><br>
											<button type="button"  class="btn red icn-only"  data-dismiss="modal" class="btn">关闭</button> 
											<button data-dismiss="modal"  onclick="addtocart('cook','<?php echo $val['foodid'];?>','<?php echo $val['foodname'];?>','<?php echo $val['foodprice'];?>','<?php echo $val['foodcooktype'];?>','<?php echo $val['isweight'];?>','<?php echo $val['ispack'];?>')"  class="btn green" style="width: 180px;">完成</button>
									</div>
								</div>
								
						<?php }
						if ($val['isweight']=="1"&&$val['ispack']=="0"){?>
						
						<div id="static_<?php echo $val['foodid'];?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
							<div class="modal-body span12">
								
								<div class="tab-pane  active" id="portlet_tab3">
												<h4><span style="color: red;"></span></h4>
												<div class="tools">
													
												</div>
												<div class="controls">

														重量 <input type="number" placeholder="数字" id="weight_<?php echo $val['foodid'];?>" class="m-wrap mediun" style="color: red">

														<span class="help-inline"><?php echo $val['foodunit'];?></span>
														<br>
											<?php 
											if(!empty($val['foodcooktype'])){
												$cooktypearr=explode("、", $val['foodcooktype']);
												foreach ($cooktypearr as $ckey=>$cval){?>
													<button type="button" class="btn"  id="<?php echo $val['foodid']."_".$cval;?>" onclick="SelectCook('<?php echo $val['foodid'];?>','<?php echo $cval;?>')"><?php echo $cval;?></button>
												<?php }}?>	
													</div>
											</div><br>
											<button type="button" class="btn red icn-only" data-dismiss="modal" class="btn">关闭</button>
											<button data-dismiss="modal"  onclick="addtocart('weight','<?php echo $val['foodid'];?>','<?php echo $val['foodname'];?>','<?php echo $val['foodprice'];?>','<?php echo $val['foodcooktype'];?>','<?php echo $val['isweight'];?>','<?php echo $val['ispack'];?>')"  class="btn green" style="width: 180px;">完成</button>											
									</div>
								</div>
						<?php  }elseif($val['ispack']=="1"){?>
							<div id="static_<?php echo $val['foodid'];?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
							<div class="modal-body span5">
								<div class="tab-pane  active" id="portlet_tab3">
												<h4><span style="color: red;"></span></h4>
												
												<input type="hidden" id="<?php echo $val['foodid'];?>" value="<?php echo $val['foodid'];?>">
												<button class="btn red icn-only"  onclick="addtocart('minus','<?php echo $val['foodid'];?>','<?php echo $val['foodname'];?>','<?php echo $val['foodprice'];?>','<?php echo $val['foodcooktype'];?>','<?php echo $val['isweight'];?>','<?php echo $val['ispack'];?>')"  style="height:30px;width:50px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>
												<input id="text_box_vice_<?php echo $val['foodid'];?>"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center;color:red"/>
												<button  class="btn red icn-only"   onclick="addtocart('add','<?php echo $val['foodid'];?>','<?php echo $val['foodname'];?>','<?php echo $val['foodprice'];?>','<?php echo $val['foodcooktype'];?>','<?php echo $val['isweight'];?>','<?php echo $val['ispack'];?>')" style="height:30px;width:50px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="button" class="btn green icn-only" data-dismiss="modal" class="btn">完成</button>
												</div>
													
												</div>
												<div class="portlet">


							<div class="portlet box green">

							<div class="portlet-title">

								<div class="caption"><i class="icon-picture"></i><?php echo $val['foodname'];?></div>
							</div>

							<div class="portlet-body">

								<table class="table table-condensed table-hover">

									<thead>

										<tr>
											<th>名称</th>
										</tr>
									</thead>
									<tbody>
							<?php foreach ($packagearr as $pkey=>$onepacks){
									foreach ($onepacks as $onepack){
								?>
										<tr>
											<td><?php echo $onepack['foodname']?></td>
										</tr>

										<?php }}?>
									</tbody>
								</table>
							</div>
						</div>
						</div>
											</div><br>
																						
									</div>
								</div>
						<?php 	}}?>	
						</div>

					</div>
					<!--end tabbable-->           
							
				</div>

				<!-- END PAGE CONTENT-->
			<a href="#static_shopcart"  data-toggle="modal"  ><img class="timeline-img pull-right" style="display: none" id="shopedcart" src="media/image/shopedcart.png"  width="50"  alt=""></a>
			<a href="#static_foodtype"  data-toggle="modal" ><img class="timeline-img pull-left"   id="btnfoodtype" src="media/image/foodmenu.png"  width="50"  alt=""></a>
			</div>
			
			
			<div id="static_shopcart" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<div class="portlet box">
							
							<div class="tools">
								
								<button type="button" class="btn red icn-only pull-right"  style="margin: 10px;" data-dismiss="modal" class="btn">关闭</button>
								</div>
						</div>
						
						<div id="mymenu">
							
							</div>
							<form action="#" method="post">
							<input type="hidden" name="cusnum"  id="form_cusnum" value="">
							<input type="hidden" name="food"  id="form_food" value="">
								<button type="submit"  onclick="return downSheet(cusnum)" class="btn red btn-block">下单 <i class="m-icon-swapright m-icon-white"></i></button>
							</form>
					</div>
						</div>
					</div>
					
					<div id="static_foodtype" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<div class="portlet box">
									<div class="portlet-title"  style="margin:5;padding:5">
										<div class="caption" style="color:black">美食分类</div>
								
								<div class="tools">
								
								<button type="button" class="btn red icn-only" data-dismiss="modal" class="btn">关闭</button>
								
								</div>
								&nbsp;&nbsp;
							</div>
						</div>
						<ul class="unstyled inline sidebar-tags">
							<?php
							$foodtypearr=array(
								"0"=>array("ftid"=>1,"ftname"=>"水果"),"1"=>array("ftid"=>2,"ftname"=>"蔬菜")
								);
							foreach ($foodtypearr as $ftkey=>$ftval){?>
									<li><a href="food.php?ftid=<?php echo $ftval['ftid'];?>"  class="btn big <?php if($ftval['ftid']==$ftid){echo 'purple';}else{echo 'black';}?>"  ><i class="icon-tags"></i> <?php echo $ftval['ftname'];?></a></li>
							<?php }?>
								</ul>
					</div>
						</div>
					</div>	
			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->


	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>
	
	<!--[if lt IE 9]>

	<script src="media/js/respond.min.js"></script>  

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/bootstrap-modal.js" type="text/javascript" ></script>
	<script src="media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="media/js/jquery.toggle.buttons.js"></script>
	<script type="text/javascript" src="media/js/jquery.spinner.js"></script>
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

// 		   UIModals.init();
		   
		});

	</script>

	<!-- END JAVASCRIPTS -->
	

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>