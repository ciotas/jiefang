<?php 
require_once ('../../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DonateTicketRule{
	public function getAllDonateticketData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->getAllDonateticketData($shopid);
	}
	
}
$donateticketrule=new DonateTicketRule();
$title="送券活动规则";
$menu="activity";
$clicktag="rule";
$shopid=$_SESSION['shopid'];
require_once ('../../header.php');
$arr=$donateticketrule->getAllDonateticketData($shopid);

?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("fullmoney").value="";
 	document.getElementById("sendmoney").value="";
 	document.getElementById("ruleid").value="";
}
var xmlHttp
function getOneRule(ruleid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../../interface/getonerule.php"
	url=url+"?ruleid="+ruleid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onedonate=xmlHttp.responseText
 	onedonate1=eval("("+onedonate+")");
 	document.getElementById("fullmoney").value=onedonate1.fullmoney;
 	document.getElementById("sendmoney").value=onedonate1.sendmoney;
 	document.getElementById("ruleid").value=onedonate1.ruleid;
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
							赠券规则 <small> </small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="<?php echo $base_url;?>index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">活动</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="<?php echo $base_url;?>activity/donateticket/rule.php">卡充值</a></li>
						</ul>
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
								<div class="caption"><i class="icon-credit-card"></i>赠送规则</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>满</th>
											<th>送</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['fullmoney'];?></td>
											<td><?php echo $val['sendmoney'];?></td>
											<td><a href="#static" onclick="getOneRule('<?php echo $val['ruleid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="../../interface/delonerule.php?ruleid=<?php echo base64_encode($val['ruleid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
											<form action="../../interface/saverule.php" method="post">
												<input type="hidden" name="ruleid"  id="ruleid">
												<div class="control-group">
													<label class="control-label">满 </label>
													<div class="controls">
														<input type="text" placeholder="数字金额" id="fullmoney" name="fullmoney" class="m-wrap span5" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">送</label>
													<div class="controls">
														<input type="number" placeholder="数字金额"  name="sendmoney" id="sendmoney"  class="m-wrap span5" >
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
require_once ('../../footer.php');
?>