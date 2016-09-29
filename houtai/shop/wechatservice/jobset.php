<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class JobSet{
	public function getRolesData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getRolesData($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$jsobset=new JobSet();
$openid=$_REQUEST['openid'];
$shopid=$jsobset->getShopidByOpenid($openid);
$arr=$jsobset->getRolesData($shopid);
?>
<script>
var xmlHttp
function changeSwitchStatus(sortno,type,roleid){
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
	var url="./interface/changerole.php"
	url=url+"?roleid="+roleid
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
	<h3 class="page-title">
							职位&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./interface/syncfood.php?backurl=jobset" class="btn red">同步数据</a>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box yellow">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>职位</div>
								<div class="tools">
									<a href="./addrole.php" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">职位</th>
											<th class="number">明细</th>
											<th class="number">赠送</th>
											<th class="number">称重</th>
											<th class="number">退菜</th>
											<th class="number">出单</th>
											<th class="number">清台</th>
											<th class="number">预定</th> 
											<th class="number">开台</th>
											<th class="number">占用</th>
											<th class="number">换台</th>
											<th class="number">改价</th>
											<th class="number">收银</th>
											<th class="number">签单</th>
											<th class="number">免单</th>
											<th class="number">反结帐</th>
											<th class="number">收押金</th>
											<th class="number"></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td class="number"><?php echo ++$key;?></td>
											<td class="number"><?php echo $val['rolename'];?></td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="detail<?php echo $key;?>" <?php if($val['detail']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'detail', '<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="donate<?php echo $key;?>" <?php if($val['donate']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'donate','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="weight<?php echo $key;?>" <?php if($val['weight']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'weight','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="returnfood<?php echo $key;?>" <?php if($val['returnfood']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'returnfood','<?php echo $val['roleid'];?>')" />
											</td>
											 
											<td class="number">
												<input type="checkbox" class="toggle"  id="outsheet<?php echo $key;?>" <?php if($val['outsheet']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'outsheet','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="empty<?php echo $key;?>" <?php if($val['empty']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'empty','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="book<?php echo $key;?>" <?php if($val['book']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'book','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="start<?php echo $key;?>" <?php if($val['start']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'start','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="online<?php echo $key;?>" <?php if($val['online']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'online','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="changetab<?php echo $key;?>" <?php if($val['changetab']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'changetab','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="changeprice<?php echo $key;?>" <?php if($val['changeprice']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'changeprice','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="pay<?php echo $key;?>" <?php if($val['pay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'pay','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="signpay<?php echo $key;?>" <?php if($val['signpay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'signpay','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="freepay<?php echo $key;?>" <?php if($val['freepay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'freepay','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="repay<?php echo $key;?>" <?php if($val['repay']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'repay','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
												<input type="checkbox" class="toggle"  id="deposit<?php echo $key;?>" <?php if($val['deposit']=="1"){echo "checked";}?> onchange="changeSwitchStatus(<?php echo $key;?>,'deposit','<?php echo $val['roleid'];?>')" />
											</td>
											<td class="number">
											<a href="./interface/delonerole.php?roleid=<?php echo base64_encode($val['roleid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="<?php echo $base_url;?>media/js/app.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/index.js" type="text/javascript"></script>    
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/chosen.jquery.min.js"></script>
	<script src="<?php echo $base_url;?>media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="<?php echo $base_url;?>media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.input-ip-address-control-1.0.min.js"></script>
	
	<script src="<?php echo $base_url;?>media/js/form-components.js"></script>  
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