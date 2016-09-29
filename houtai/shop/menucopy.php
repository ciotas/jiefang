<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class MenuCopy{
	public function getBorthershopByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getBorthershopByShopid($shopid);
	}
	public function getFoodtypeByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getFoodtypeByShopid($shopid);
	}
}
$menucopy=new MenuCopy();
$title="复制菜单";
$menu="dataset";
$clicktag="menucopy";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$shopsarr=$menucopy->getBorthershopByShopid($shopid);
$foodtypearr=$menucopy->getFoodtypeByShopid($shopid);
?>
<script>
var xmlHttp;
function getFoodtype(){
	var obj = document.getElementById("toshopid"); //定位id
	var index = obj.selectedIndex; // 选中索引
	var shopid = obj.options[index].value; // 选中值
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getfoodtype.php"
	url=url+"?shopid="+shopid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	data=xmlHttp.responseText;
 	data1=eval("("+data+")");
 	var selstr1='<option value="0">--未选择--</option>';
 	for(var one in data1['foodtype']){
 		ftid=data1['foodtype'][one].ftid;
 		ftname=data1['foodtype'][one].ftname;
 		selstr1+='<option value="'+ftid+'">'+ftname+'</option>';
 	}
 	var selstr2='<option value="0">--未选择--</option>';
 	for(var one in data1['zone']){
 		zoneid=data1['zone'][one].zoneid;
 		zonename=data1['zone'][one].zonename;
 		selstr2+='<option value="'+zoneid+'">'+zonename+'</option>';
 	}
	 document.getElementById("toftid").innerHTML=selstr1;
	 document.getElementById("tozoneid").innerHTML=selstr2;
 }
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}

</script>
			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							复制菜单
							 <small></small>

						</h3>
					</div>

				</div>

				<!-- END PAGE HEADER-->
			<div class="alert alert-error">
					<button class="close" data-dismiss="alert"></button>
					<strong>请确保目标店铺已设置区域和美食分类！</strong>
				</div>
				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">复制菜单</span>

								</div>

							</div>

							<div class="portlet-body form">

								<div class="tabbable portlet-tabs">

									<ul class="nav nav-tabs">
										<li>&nbsp;</li>
									</ul>
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/docopymenu.php" class="form-horizontal" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
											
												<div class="control-group">
													<label class="control-label">目标店</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="toshopid"  id="toshopid" onchange="getFoodtype()">
														<option value="0">--未选择--</option>
														<?php foreach ($shopsarr as $key=>$val){
																if($val['shopid']==$shopid){continue;}
															?>
														<option value="<?php echo $val['shopid'];?>"><?php echo $val['shopname'];?></option>
														<?php }?>
														</select>
														<span class="help-inline" id="">请确保目标店铺已被<a href="<?php echo  $root_url."boss";?>" target="_blank">总店</a>收录</span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">将类别</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="fromftid" >
														<option value="0">--未选择--</option>
														<?php foreach ($foodtypearr as  $ftkey=>$ftval){?>
														<option value="<?php echo $ftval['ftid'];?>"><?php echo $ftval['ftname'];?></option>
														<?php }?>
														</select>
														<span class="help-inline" ></span>
													</div>
												</div>
												
												
												<div class="control-group">
													<label class="control-label">复制到类别</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="toftid"  id="toftid">
														
														</select>
														<span class="help-inline" ></span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">所属区域</label>
													<div class="controls">
														<select class="large m-wrap" tabindex="1" name="tozoneid"  id="tozoneid">
														</select>
														<span class="help-inline" ></span>
													</div>
												</div>
												
												<div class="form-actions">
													<button type="submit" class="btn blue"><i class="icon-ok"></i> 提交</button>
												</div>
											</form>
											<!-- END FORM-->  
										</div>
									</div>
								</div>

							</div>

						</div>

						<!-- END SAMPLE FORM PORTLET-->

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
	?>
