<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AutoStockFoodInfo{
	public function getAutoStockFoods($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getAutoStockFoods($shopid);
	}
	
}
$autostockfoodinfo=new AutoStockFoodInfo();
$title="库存信息";
$menu="stock";
$clicktag="autostockfoodinfo";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
$arr=$autostockfoodinfo->getAutoStockFoods($shopid);
// print_r($arr);exit;
?>
<script type="text/javascript">
<!--
function clearbox(){
	
}
var xmlHttp
function getonestock(foodid){
	document.getElementById("foodid").value=foodid;
 	document.getElementById("format").value="";
 	document.getElementById("packunit").value="";
 	document.getElementById("packnum").value="";
 	document.getElementById("retailnum").value="";
 	document.getElementById("packrate").value="";
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../interface/getonestock.php"
	url=url+"?foodid="+foodid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onestock=xmlHttp.responseText
 	if(onestock=="[]" || onestock==null){
 	 	document.getElementById("format").value="";
 	 	document.getElementById("packnum").value="";
 	 	document.getElementById("retailnum").value="";
 	 	document.getElementById("packrate").value="";
 	}else{
 		onestock1=eval("("+onestock+")");
 	 	document.getElementById("foodid").value=onestock1.foodid;
 	 	document.getElementById("format").value=onestock1.format;
 	 	document.getElementById("packunit").value=onestock1.packunit;
 	 	document.getElementById("packrate").value=onestock1.packrate;
 	}
 	
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
function getFoodid(foodid){
	document.getElementById("foodid1").value=foodid;
}
function checknum(){
	packnum=document.getElementById("packnum").value;
	packnum=parseInt(packnum);
	retailnum=document.getElementById("retailnum").value;
	retailnum=parseInt(retailnum);
	if(packnum<0 || retailnum<0){
		alert("值不能为负数！")
		document.getElementById("btnsave1").disabled=true;
	}else{
		document.getElementById("btnsave1").disabled=false;
	}
}
//-->
</script>
	<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						添加库存
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
								<div class="caption"><i class="icon-credit-card"></i>添加库存</div>
								<div class="tools">
							<!-- 	<a class="btn purple middle hidden-print" onclick="javascript:window.print();">A4打印 <i class="icon-print icon-big"></i></a> -->
						
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>图片</th>
											<th>品名</th>
											<th>价格</th>
											<th>规格型号</th>
											<th>包装单位</th>
											<th>零售单位</th>
											<th>包装率</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><img alt="" width="50" src="<?php echo $val['foodpic'];?>"></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['foodprice'];?></td>
											<td><?php echo $val['format'];?></td>
											<td><?php echo $val['packunit'];?></td>
											<td><?php echo $val['foodunit'];?></td>
											<td><?php echo $val['packrate'];?></td>
											<td><a href="#static" onclick="getonestock('<?php echo $val['foodid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> 编辑信息 </a>
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
											<form action="../interface/savestock.php" method="post">
												<input type="hidden" name="foodid"  id="foodid" >
												<input type="hidden" name="backurl"  value="<?php echo $clicktag;?>" >
												<div class="control-group">
													<label class="control-label">包装单位</label>
													<div class="controls">
														<input type="text" placeholder="选填" id="packunit" name="packunit" class="m-wrap large" >
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">包装率</label>
													<div class="controls">
														<input type="number" placeholder="1" id="packrate" name="packrate" class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">规格型号</label>
													<div class="controls">
														<input type="text" placeholder="选填" id="format" name="format" class="m-wrap large" >
													</div>
												</div>
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn blue">保存</button>

											</form>

										</div>
						</div>
					</div>									
					<div id="static1" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<form action="../interface/savestocknum.php" method="post">
												<input type="hidden" name="foodid"  id="foodid1" >
												
											<input type="hidden" name="returnurl" value="autostockfood">
												<div class="control-group">
													<label class="control-label">新增包装量</label>
													<div class="controls">
														<input type="number" placeholder="0" id="packnum" name="packnum" oninput="checknum('packnum')" class="m-wrap large" >
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">新增零售量</label>
													<div class="controls">
														<input type="number" placeholder="0"  id="retailnum" name="retailnum"  oninput="checknum('retailnum')"  class="m-wrap large" >
													</div>
												</div>
																						
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn blue" id="btnsave1">保存</button>

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