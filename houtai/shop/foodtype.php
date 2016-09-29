<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodType{
	public function getPrintersByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getPrintersByShopid($shopid);
	}
	public function getFoodtypesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFoodtypesByShopid($shopid);
	}
}
$foodtype=new FoodType();
$title="美食分类";
$menu="dataset";
$clicktag="foodtype";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$printarr=$foodtype->getPrintersByShopid($shopid);
// print_r($printarr);exit;
$foodtypearr=$foodtype->getFoodtypesByShopid($shopid);
// print_r($foodtypearr);exit;
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("ftid").value="";
 	document.getElementById("ftname").value="";
 	document.getElementById("ftcode").value="";
 	document.getElementById("sortno").value="";
 	putSelectval("0")
}
var xmlHttp
function getOnetype(ftid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonetype.php"
	url=url+"?ftid="+ftid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	oneprinter=xmlHttp.responseText
 	oneprinter1=eval("("+oneprinter+")");
 	document.getElementById("ftid").value=oneprinter1.ftid;
 	document.getElementById("ftname").value=oneprinter1.ftname;
 	document.getElementById("ftcode").value=oneprinter1.ftcode;
 	document.getElementById("sortno").value=oneprinter1.sortno;
 	putSelectval(oneprinter1.printerid);
 }
}
function putSelectval(val){
	  var sel=document.getElementById('sel1');
	  for(var i=0;i<sel.options.length;i++)
	  {
	  	if(sel.options[i].value==val)
	  	{
	  	sel.options[i].selected=true;
	  	break;
	  	}
	  }
	}

function showorhide(ftid){
	checkedval=document.getElementById("showtype_"+ftid).checked;
	if(checkedval){
		status="1";
	}else{
		status="0";
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/showtype.php"
	url=url+"?ftid="+ftid
	url=url+"&status="+status
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=showtypeRes 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function showtypeRes() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	result=xmlHttp.responseText
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
//-->
</script>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						美食分类&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<!-- <a href="./interface/syncfood.php?backurl=foodtype" class="btn red">同步数据</a> -->
						<small> 系统会自动同步数据</small>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>类别</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
<!-- 											<th>图片</th> -->
											<th>类名</th>
											<th>类别编码</th>
											<th>排序序号</th>
											<th class="hidden-480">打印机</th>
											<th>显示</th>
											<th>编辑</th>
<!-- 											<th>图片上传</th> -->
										</tr>
									</thead>
									<tbody>
									<?php foreach ($foodtypearr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<!-- <td class="numeric"><img alt="" src="<?php echo $val['ftpic'];?>" width="60" height="60"></td> -->
											<td><?php echo $val['ftname'];?></td>
											<td><?php echo $val['ftcode'];?></td>
											<td><?php echo $val['sortno'];?></td>
											<td class="hidden-480"><?php echo $val['printername'];?></td>
											<td><div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="showtype_<?php echo $val['ftid'];?>"  name="showtype[]"  <?php if($val['showstatus']=="1"){echo "checked";}elseif ($val['showstatus']=="0"){echo "";}else{echo "checked";}?> onchange="showorhide('<?php echo $val['ftid'];?>')" />
													</div>
												</td>
												
											<td><a href="#static" onclick="getOnetype('<?php echo $val['ftid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonetype.php?ftid=<?php echo base64_encode($val['ftid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										<!-- 	<td>
											<form action="./interface/doupftpeimg.php" method="post" enctype="multipart/form-data">
										<div class="controls">
										<input type="hidden" name="ftid" value="<?php echo  $val['ftid'];?>">
											<div class="fileupload fileupload-new" data-provides="fileupload">
											<input type="hidden" value="" name="">
												<span class="btn btn-file red">
												<span class="fileupload-new">选择图片</span>
												<span class="fileupload-exists">重新上传</span>
												<input type="file" class="default" name="foodtypepic">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
											</div>
										</div>
										</form>
											</td> -->
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

					
					<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">

											<h4></h4>

											<form action="./interface/addoneftype.php" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="ftid"  id="ftid" >
												<div class="control-group">
													<label class="control-label">类别名</label>
													<div class="controls">
														<input type="text" placeholder="必填" id="ftname" name="ftname" class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">编码</label>
													<div class="controls">
														<input type="text" placeholder=""  name="ftcode" id="ftcode"  class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">序号</label>
													<div class="controls">
														<input type="number" placeholder=""  name="sortno" id="sortno"  class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">绑定打印机</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="sel1" name="printerid">
															<option value="0">---未选择---</option>
															<?php foreach ($printarr as $pkey=>$pval){?>
															<option value="<?php echo $pval['printerid'];?>"><?php echo $pval['printername']?></option>
															<?php }?>
														</select>
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
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->

	</div>

	<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014-2015 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>

		<div class="footer-tools">

			<span class="go-top">

			<i class="icon-angle-up"></i>

			</span>

		</div>

	</div>

	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="<?php echo $base_url;?>media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="<?php echo $base_url;?>media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="<?php echo $base_url;?>media/js/excanvas.min.js"></script>

	<script src="<?php echo $base_url;?>media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="<?php echo $base_url;?>media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.js" type="text/javascript"></script>   

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.russia.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.world.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.europe.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.germany.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.usa.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.flot.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.flot.resize.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.pulsate.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/date.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/daterangepicker.js" type="text/javascript"></script>     

	<script src="<?php echo $base_url;?>media/js/jquery.gritter.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/fullcalendar.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.sparkline.min.js" type="text/javascript"></script>  

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="<?php echo $base_url;?>media/js/app.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/index.js" type="text/javascript"></script>    
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/chosen.jquery.min.js"></script>
	<script src="<?php echo $base_url;?>media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="<?php echo $base_url;?>media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.input-ip-address-control-1.0.min.js"></script>
	
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.multi-select.js"></script>  
	<script src="<?php echo $base_url;?>media/js/form-components.js"></script>  
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-colorpicker.js"></script>  
	<script src="<?php echo $base_url;?>media/js/ui-modals.js"></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.toggle.buttons.js"></script>
	
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

		   App.init(); // initlayout and core plugins

		   Index.init();
// 		   Search.init();
		   
		   Index.initJQVMAP(); // init index page's custom scripts

		   Index.initCalendar(); // init index page's custom scripts

		   Index.initCharts(); // init index page's custom scripts

		   Index.initChat();

		   Index.initMiniCharts();

		   Index.initDashboardDaterange();
		   FormComponents.init();
		   UIModals.init();
		   
// 		   Index.initIntro();

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>
<?php if(isset($_GET['res'])){
	$res=$_GET['res'];
	if(!$res){
		echo "<script>alert('无法删除，请将此分类下所有美食移到其他分类或者全部删除后尝试！');window.location.href='./foodtype.php';</script>";
	}
}?>