<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class VipTag{
	public function getViptagsData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getViptagsData($shopid);
	}

}
$viptag=new VipTag();
$title="会员标签";
$menu="vip";
$clicktag="viptag";
$tagid="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$onetag=array();
$arr=$viptag->getViptagsData($shopid);

?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("viptaggid").value="";
 	document.getElementById("tagname").value="";
}
var xmlHttp
function getOneViptag(viptagid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonetag.php"
	url=url+"?tagid="+viptagid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onetag=xmlHttp.responseText
 	onetag1=eval("("+onetag+")");
 	document.getElementById("viptaggid").value=onetag1.viptagid;
 	document.getElementById("tagname").value=onetag1.tagname; 	
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
							会员标签 <small> </small>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span6">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>会员标签</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>标签名称</th>
											<td></td>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['tagname'];?></td>
											<td><a href="#static" onclick="getOneViptag('<?php echo $val['tagid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/deloneviptag.php?viptagid=<?php echo base64_encode($val['tagid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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

											<form action="./interface/setviptag.php" method="post">
												<input type="hidden" name="viptaggid"  id="viptaggid">
												<div class="control-group">
													<label class="control-label">标签 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="tagname" name="tagname" class="m-wrap span5" value="<?php if(!empty($oneviptag)){echo $oneviptag['tagname'];}else{echo "";}?>">
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