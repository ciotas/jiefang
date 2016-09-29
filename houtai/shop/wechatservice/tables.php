<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/phpqrcode/phpqrcode.php');
class Tables{
	public function getTablesData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTablesData($shopid);
	}
	public function getZonesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getZonesByShopid($shopid);
	}
	public function getPrintersByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getPrintersByShopid($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$tables=new Tables();
$openid=$_REQUEST['openid'];
$shopid=$tables->getShopidByOpenid($openid);

$zonearr=$tables->getZonesByShopid($shopid);
$printerarr=$tables->getPrintersByShopid($shopid);
$typeno="0";

if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$arr=$tables->getTablesData($shopid);
// print_r($arr);exit;
//生成二维码图片

$level = 'M';
// 点的大小：1到10,用于手机端4就可以了
$size =4;
// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
$path = "qrimg/";
// 生成的文件名
$path=$path.$shopid."/";
if(!file_exists($path)){
	$cmd="mkdir -p ".$path;
	shell_exec($cmd);
	$cmd="chmod -R 777 ".$path;
	shell_exec($cmd);
}

?>
<script type="text/javascript">
function clearbox(){
	document.getElementById("tabid").value="";
 	document.getElementById("tabname").value="";
 	document.getElementById("seatnum").value="";
 	document.getElementById("sortno").value="";
 	putSelectval("0","printerid");
 	putSelectval("0","zoneid");
}
var xmlHttp
function getOneTable(tabid,typeno){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonetable.php"
	url=url+"?tabid="+tabid
	url=url+"&typeno="+typeno
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function switchbookflag(tabid){
	checkedval=document.getElementById("bookflag_"+tabid).checked;
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
	var url="./interface/bookflag.php"
	url=url+"?tabid="+tabid
	url=url+"&status="+status
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=showBookflag 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function showBookflag() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	xmlHttp.responseText
 }
}

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
 	onetable=xmlHttp.responseText;
 	onetable1=eval("("+onetable+")");
 	
 	document.getElementById("tabid").value=onetable1.tabid;
 	document.getElementById("tabname").value=onetable1.tabname;
 	document.getElementById("seatnum").value=onetable1.seatnum;
 	document.getElementById("sortno").value=onetable1.sortno;
 	document.getElementById("typeno").value=onetable1.typeno;
 	putSelectval(onetable1.printerid,"printerid");
 	putSelectval(onetable1.zoneid,"zoneid");
	}
}
function putSelectval(val,id){
	  var sel=document.getElementById(""+id+"");
	  for(var i=0;i<sel.options.length;i++)
	  {
	  	if(sel.options[i].value==val)
	  	{
	  	sel.options[i].selected=true;
	  	break;
	  	}
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
<!DOCTYPE html>

<!--[if !IE]><!--> <html lang="zh-CN"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>我的订单</title>

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

			<div class="container-fluid">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						桌台
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
								<div class="caption"><i class="icon-reorder"></i>桌台</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
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
												<li <?php if($key==$typeno){echo "class='active'";}?>><a href="#tab_<?php echo $val['zoneid'];?>" data-toggle="tab"><?php echo $val['zonename'];?></a></li>
												<?php }?>
											</ul>
											<div class="tab-content">
											<?php foreach ($arr as $ftkey=>$ftval){?>
												<div class="tab-pane <?php if($ftkey==$typeno){echo "active";}?>" id="tab_<?php echo $ftval['zoneid']?>">
													<div class="portlet box yellow">
							<div class="portlet-body">
								<table class="table table-hover" >
									<thead>
										<tr>
											<th>名称</th>
											<th class="hidden-480">序号</th>
											<th class="hidden-480">座位数</th>
											<th>打印机</th>
											<th>打印二维码</th>
											<th class="hidden-480">二维码预览</th>
											<th>接受预定</th>
											<th></th>
										</tr>
									</thead>

									<tbody>
									<?php foreach ($ftval['tables'] as $fkey=>$fval){
										$fileName = $path.$fval['tabid'].'.png';
										$shopurl=$tabqrcode_url.'?m=Admin&c=Index&a=doorbutton&type=inhouse&deskno='.$fval['tabid'];
// 										$shopurl=$root_url."weshop/shopindex.php?shopid=".$shopid."#deskno=".$fval['tabid'];
										QRcode::png($shopurl, $fileName, $level, $size);
										?>
										<tr>
											<td><?php echo $fval['tabname'];?></td>
											<td class="hidden-480"><?php echo $fval['sortno'];?></td>
											<td class="hidden-480"><?php echo $fval['seatnum'];?></td>
											<td><?php echo $fval['printername'];?></td>
											<td><a href="./interface/gener_tabqrcode.php?tabid=<?php echo $fval['tabid'];?>&typeno=<?php echo $ftkey;?>" class="btn green icn-only"><i class="icon-qrcode icon-white"></i></a></td>
											<td class="hidden-480"><img alt="" width="100" src="<?php echo $base_url.$fileName;?>"></td>
											<td>
											<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="bookflag_<?php echo $fval['tabid'];?>"  name="bookflag[]"  <?php if($fval['bookflag']=="1"){echo "checked";}elseif ($fval['bookflag']=="0"){echo "";}else{echo "";}?> onchange="switchbookflag('<?php echo $fval['tabid'];?>')" />
													</div>
											</td>
											<td><a href="#static" onclick="getOneTable('<?php echo $fval['tabid'];?>','<?php echo $ftkey;?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonetable.php?tabid=<?php echo base64_encode($fval['tabid']);?>&typeno=<?php echo $ftkey;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
						<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" >

											<h4></h4>

											<form action="./interface/editonetab.php" method="post">
												<input type="hidden" name="tabid"  id="tabid" >
												<input type="hidden" name="typeno"  id="typeno" >
												<div class="control-group">
													<label class="control-label">台号</label>
													<div class="controls">
														<input type="text" placeholder="必填" id="tabname" name="tabname" class="m-wrap large" >
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">序号</label>
													<div class="controls">
														<input type="number" placeholder=""  name="sortno" id="sortno"  class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">座位数</label>
													<div class="controls">
														<input type="number" placeholder=""  name="seatnum" id="seatnum"  class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">所属区域</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="zoneid" name="zoneid">
															<option value="0">---未选择---</option>
															<?php foreach ($zonearr as $zkey=>$zval){?>
															<option value="<?php echo $zval['zoneid'];?>"><?php echo $zval['zonename'];?></option>
															<?php }?>
														</select>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">打印机</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="4" id="printerid" name="printerid">
															<option value="0">---未选择---</option>
															<?php foreach ($printerarr as $pkey=>$pval){?>
															<option value="<?php echo $pval['printerid'];?>"><?php echo $pval['printername'];?></option>
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