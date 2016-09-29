<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Servers{
	public function getShopServers($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopServers($shopid);
	}
	public function getRolesData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getRolesData($shopid);
	}
}
$servers=new Servers();
$title="员工";
$menu="dataset";
$clicktag="servers";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
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
	document.getElementById("openid").value="";
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
	var url="./interface/getoneserver.php"
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
 	document.getElementById("openid").value=oneserver1.openid;
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
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							员工 <small> </small>
						</h3>
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="alert alert-error">
					<button class="close" data-dismiss="green"></button>
					<strong>提示：直接添加员工账号和密码，就可以登陆小跑堂了，无须在app上注册！</strong><br>
				</div>
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
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
											<th>#</th>
											<th>姓名</th>
											<th>小跑堂账号</th>
											<th>密码</th>
											<th>工号</th>
											<th>openid</th>
											<th>职位</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['servername'];?></td>
											<td><?php echo $val['serverphone'];?></td>
											<td><?php echo $val['serverpwd'];?></td>
											<td><?php echo $val['serverno'];?></td>
											<td><?php echo $val['openid'];?></td>
											<td><?php echo $val['rolename'];?></td>
											<td><a href="#static" onclick="getOneServer('<?php echo $val['serverid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/deloneserver.php?serverid=<?php echo base64_encode($val['serverid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
											<form action="./interface/saveoneserver.php" method="post">
												<input type="hidden" name="serverid"  id="serverid">
												<div class="control-group">
													<label class="control-label">姓名 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="servername" name="servername" class="m-wrap span5"  >
													</div>
												</div>
											<div class="control-group">
													<label class="control-label">小跑堂账号</label>
													<div class="controls">
														<input type="text" placeholder="必填" id="serverphone" name="serverphone" class="m-wrap span5"  >
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
													<label class="control-label">openid </label>
													<div class="controls">
														<input type="text" placeholder="选填" id="openid" name="openid" class="m-wrap span5"  >
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
require_once ('footer.php');
?>