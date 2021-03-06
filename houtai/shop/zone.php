<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Zone{
	public function getZonesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getZonesByShopid($shopid);
	}
	
}
$zone=new Zone();
$title="美食券";
$menu="dataset";
$clicktag="zone";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$zone->getZonesByShopid($shopid);
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("zonename").value="";
	document.getElementById("zoneid").value="";
}
var xmlHttp
function getOneZone(zoneid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonezone.php"
	url=url+"?zoneid="+zoneid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onezone=xmlHttp.responseText
 	onezone1=eval("("+onezone+")");
 	document.getElementById("zonename").value=onezone1.zonename;
 	document.getElementById("zoneid").value=onezone1.zoneid;
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
							区域 <small> </small>
						</h3>
						
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span8">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>区域 </div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>名称</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
									
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['zonename'];?></td>
											<td><a href="#static" onclick="getOneZone('<?php echo $val['zoneid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonezone.php?zoneid=<?php echo base64_encode($val['zoneid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
											<form action="./interface/saveonezone.php" method="post">
												<input type="hidden" name="zoneid"  id="zoneid">
												<div class="control-group">
													<label class="control-label">区域名 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="zonename" name="zonename" class="m-wrap span5"  >
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