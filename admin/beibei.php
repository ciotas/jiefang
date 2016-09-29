<?php 
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Shopinit{
	public function getAllOnLineShop(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getAllOnLineShop();
	}
}
$shopinit=new Shopinit();
$title="商家配置";
$menu="manage";
$clicktag="shopinit";
require_once ('headertest.php');
$arr=$shopinit->getAllOnLineShop();
   
// print_r($arr);exit;
?>
<script>
var xmlHttp
function changeSwitchStatus(sortno,type,shopid){
	checkedval=document.getElementById(""+type+sortno+"").checked;
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
	var url="./interface/shopswitch.php"
	url=url+"?shopid="+shopid
	url=url+"&type="+type
	url=url+"&status="+status
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
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
function setTopclearmoney(shopid,op,row){
	val=document.getElementById(""+op+row+"").innerHTML;
	val=parseFloat(val);
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/shopswitch.php"
	url=url+"?shopid="+shopid
	url=url+"&type="+op
	url=url+"&status="+val
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
</script>

	<h3 class="page-title">
							商家配置<small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>商家ID</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="number">商家</th>
											<th class="number">抹零上限</th>
											<th class="number">缩小倍数</th>
											<th class="number">收银</th>
											<th class="number">签单</th>
											<th class="number">免单</th>
									<!-- 		<th class="number">反结</th> -->
									<!-- 		<th class="number">结账严格</th> -->
											<th class="number">贝贝</th>
											<th class="number">划菜单金额</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									if($val['shopid']!="5539b36816c109ec748b4640"){continue;}
										?>
										<tr>
											<td class="number"><?php echo $val['shopname'];?></td>
											<td class="number"><p contenteditable="true" onblur="setTopclearmoney('<?php echo $val['shopid'];?>','topclearmoney','<?php echo $key;?>')" id="topclearmoney<?php echo $key;?>"><?php echo $val['topclearmoney'];?></p></td>
											<td class="number"><p contenteditable="true" onblur="setTopclearmoney('<?php echo $val['shopid'];?>','decreasenum','<?php echo $key;?>')" id="decreasenum<?php echo $key;?>"><?php echo $val['decreasenum'];?></p></td>
											<td class="number">
											<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="pay<?php echo $key;?>" <?php if($val['pay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'pay', '<?php echo $val['shopid'];?>')" />
											</div>
											</td>
											<td class="number">
											<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="signpay<?php echo $key;?>" <?php if($val['signpay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'signpay','<?php echo $val['shopid'];?>')" />
											</div>
											</td>
											<td class="number">
											<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="freepay<?php echo $key;?>" <?php if($val['freepay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'freepay','<?php echo $val['shopid'];?>')" />
											</div>
											</td>
										<!-- 	<td class="number">
											<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="repay<?php echo $key;?>" <?php if($val['repay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'repay','<?php echo $val['shopid'];?>')" />
											</div>
											</td>
											 -->
											<td class="number">
											<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="allowinbalance<?php echo $key;?>" <?php if($val['allowinbalance']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'allowinbalance','<?php echo $val['shopid'];?>')" />
											</div>
											</td>
											<td class="number">
											<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="menumoney<?php echo $key;?>" <?php if($val['menumoney']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'menumoney','<?php echo $val['shopid'];?>')" />
											</div>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

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