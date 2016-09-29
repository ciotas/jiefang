<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ShoperAccount{
    public function getOnWechatShop(){
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getOnWechatShop();
    }
}
$shoperaccount=new ShoperAccount();
$title="商家余额";
$menu="data";
$clicktag="shoperaccount"; 
require_once ('header.php');
$arr=$shoperaccount->getOnWechatShop();
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("shopid").value="";
	document.getElementById("shopname").innerHTML="";
 	document.getElementById("money").value="";
}
var xmlHttp
function getMoney(shopid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getmoney.php"
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
 	data=xmlHttp.responseText
 	data1=eval("("+data+")");
 	document.getElementById("shopid").value=data1.shopid;
 	document.getElementById("shopname").innerHTML=data1.shopname;
 	oldmoney=data1.money;
 	document.getElementById("money").value=oldmoney.toFixed(2);
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
<h3 class="page-title">
							商家余额<small> </small>
						</h3>
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>余额</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">商家</th>
											<th class="number">手机号</th>
											<th class="number">余额</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td class="number"><?php echo ++$key;?></td>
											<td class="number"><?php echo $val['shopname'];?></td>
											<td class="number"><?php echo $val['mobilphone'];?></td>
											<td class="number"><?php echo sprintf("%.2f",$val['money']);?></td>
											<td>
											<a href="#static" onclick="getMoney('<?php echo $val['shopid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">

											<h4></h4>

											<form action="./interface/initshopmoney.php" method="post">
												<input type="hidden" id="shopid"  name="shopid" value="">
												<div class="control-group">
													<label class="control-label"></label>
													<div class="controls">
														<label id="shopname"></label>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">余额 </label>
													<div class="controls">
														<input type="text" placeholder="必填"  id="money" name="money" class="m-wrap medium" >
													</div>
												</div>
												
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn green">保存</button>

											</form>

										</div>
						</div>
					</div>
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->

	</div>

<?php 
require_once 'footer.php';
?>