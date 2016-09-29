<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class CouponType{
	public function getCoupontypesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getCoupontypesByShopid($shopid);
	}
}
$coupontype=new CouponType();
$title="美食券";
$menu="dataset";
$clicktag="coupontype";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$arr=$coupontype->getCoupontypesByShopid($shopid);
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("couponname").value="";
	document.getElementById("cpid").value="";
}
var xmlHttp
function getOneCoupon(cpid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonecoupon.php"
	url=url+"?cpid="+cpid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onecoupon=xmlHttp.responseText
 	onecoupon1=eval("("+onecoupon+")");
 	document.getElementById("couponname").value=onecoupon1.couponname;
 	document.getElementById("cpid").value=onecoupon1.cpid;
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
							美食券 <small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>美食券 </div>
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
											<td><?php echo $val['couponname'];?></td>
											<td><a href="#static" onclick="getOneCoupon('<?php echo $val['cpid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonecoupon.php?cpid=<?php echo base64_encode($val['cpid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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
											<form action="./interface/saveonecoupon.php" method="post">
												<input type="hidden" name="cpid"  id="cpid">
												<div class="control-group">
													<label class="control-label">美食券 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="couponname" name="couponname" class="m-wrap span5"  >
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