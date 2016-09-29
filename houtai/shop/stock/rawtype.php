<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class RawType{
	public function getRawtypeData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawtypeData($shopid);
	}
}
$rawtype=new RawType();
$title="原料分类";
$menu="stock";
$clicktag="rawtype";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
$arr=$rawtype->getRawtypeData($shopid);
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("rawtypename").value="";
 	document.getElementById("rtnid").value="";
}
var xmlHttp
function getoneRawtype(rtnid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../interface/getonerawtype.php"
	url=url+"?rtnid="+rtnid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onerawtype=xmlHttp.responseText
 	onerawtype1=eval("("+onerawtype+")");
 	document.getElementById("rawtypename").value=onerawtype1.rawtypename;
 	document.getElementById("rtnid").value=onerawtype1.rtnid;
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
						原料分类&nbsp;
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span8">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>原料分类</div>
								<div class="tools">
								<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>类名</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['rawtypename'];?></td>
											<td><a href="#static" onclick="getoneRawtype('<?php echo $val['rtnid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i></a>
											<a href="../interface/delonerawtype.php?rtnid=<?php echo base64_encode($val['rtnid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
											<form action="../interface/saverawtype.php" method="post">
												<input type="hidden" name="rtnid"  id="rtnid" >
												<div class="control-group">
													<label class="control-label">类名</label>
													<div class="controls">
														<input type="text" placeholder="必填" id="rawtypename" name="rawtypename" class="m-wrap large" >
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
<?php 
require_once ('../footer.php');
?>