<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Servers{
	public function getShopServers($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopServers($shopid);
	}
	public function getRolesData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getRolesData($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$servers=new Servers();
$openid=$_REQUEST['openid'];
$shopid=$servers->getShopidByOpenid($openid);
$arr=$servers->getShopServers($shopid);
$rolearr=$servers->getRolesData($shopid);
// print_r($arr);exit;
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("servername").value="";
	document.getElementById("serverid").value="";
	document.getElementById("serverphone").value="";
	document.getElementById("serverno").value="";
	document.getElementById("serverpwd").value="";
	putSelectval("","roleid");
}
var xmlHttp
function getOneServer(serverid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../interface/getonewechatserver.php"
	url=url+"?serverid="+serverid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	oneserver=xmlHttp.responseText
 	oneserver1=eval("("+oneserver+")");
 	document.getElementById("servername").value=oneserver1.servername;
 	document.getElementById("serverphone").value=oneserver1.serverphone;
 	document.getElementById("serverno").value=oneserver1.serverno;
 	document.getElementById("serverid").value=oneserver1.serverid;
 	document.getElementById("serverpwd").value=oneserver1.serverpwd;
 	putSelectval(oneserver1.roleid,"roleid");
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


function putSelectval(val,id){
	  var sel=document.getElementById(id);
	  for(var i=0;i<sel.options.length;i++)
	  {
	  	if(sel.options[i].value==val)
	  	{
	  	sel.options[i].selected=true;
	  	break;
	  	}
	  }
	}
//-->
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
			<div class="page-content"></div>
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span8">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>员工 </div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>姓名</th>
											<th>小跑堂账号</th>
											<th>密码</th>
											<th>职位</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo $val['servername'];?></td>
											<td><?php echo $val['serverphone'];?></td>
											<td><?php echo $val['serverpwd'];?></td>
											<td><?php echo $val['rolename'];?></td>
											<td><a href="#static" onclick="getOneServer('<?php echo $val['serverid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="../interface/delonewechatserver.php?serverid=<?php echo base64_encode($val['serverid']);?>&openid=<?php echo $openid;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
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
											<form action="../interface/savewechatserver.php" method="post">
												<input type="hidden" name="serverid"  id="serverid">
												<input type="hidden" name="openid" value="<?php echo $openid;?>">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<div class="control-group">
													<label class="control-label">姓名 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="servername" name="servername" class="m-wrap span5"  >
													</div>
												</div>
											<div class="control-group">
													<label class="control-label">小跑堂账号</label>
													<div class="controls">
														<input type="tel" placeholder="必填" id="serverphone" name="serverphone" class="m-wrap span5"  >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">密码 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="serverpwd" name="serverpwd" class="m-wrap span5"  >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">工号 </label>
													<div class="controls">
														<input type="text" placeholder="选填" id="serverno" name="serverno" class="m-wrap span5"  >
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">职位</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="roleid" name="roleid" >
															<?php foreach ($rolearr as $pkey=>$pval){?>
															<option value="<?php echo $pval['roleid'];?>"><?php echo $pval['rolename'];?></option>
															<?php }?>
														</select>
													</div>
												</div>
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn green">保存</button>

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
<?php 
require_once ('../footer.php');
?>