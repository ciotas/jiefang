<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AutoStockFood{
	public function getAutoStockFoods($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getAutoStockFoods($shopid,$theday);
	}
	
}
$autostockfood=new AutoStockFood();
if(isset($_GET['op'])){
	$op=$_GET['op'];
	if($op=="logout"){
		$_SESSION['manager_id']="";
	}
}
if(empty($_SESSION['manager_id'])){
	header("location: ./checkrole.php?page=updateautostock");
}
$title="添加库存";
$menu="stock";
$clicktag="checkrole";
$shopid=$_SESSION['shopid'];
require_once ('../header.php');
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$autostockfood->getAutoStockFoods($shopid,$theday);
// print_r($arr);exit;
?>
<script type="text/javascript">
<!--
function clearbox(){
	
}
var xmlHttp
function getonestock(foodid){
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
//  	 	document.getElementById("format").value=onestock1.format;
//  	 	document.getElementById("packunit").value=onestock1.packunit;
//  	 	document.getElementById("packrate").value=onestock1.packrate;
 	 	document.getElementById("bigunit").innerHTML=onestock1.packunit;
 	 	document.getElementById("smallunit").innerHTML=onestock1.foodunit;
 	 	document.getElementById("foodname").innerHTML=onestock1.foodname;
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
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./updateautostock.php?theday="+theday;
}
//-->
</script>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
	<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						调整库存
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
							
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
										<th>操作人员：<?php if(isset($_SESSION['manager_id'])){echo $_SESSION['manager_name'];}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a class="btn red small hidden-print" href="./updateautostock.php?op=logout">退出</a></th>
										</tr>
									</thead>
								</table>
							</div>
						
					</div>	
				</div>
				
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">	
					
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>添加库存</div>
								<div class="tools">						
								</div>
							</div>
							<div class="portlet-body">
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
									<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
								</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>图片</th>
											<th>品名</th>
											<th>价格</th>
											<th>规格型号</th>
										<!-- 	<th>入库明细</th> -->
											<th>合计库存</th>
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
										<!-- 	<td><?php echo $val['packnum'].$val['packunit'].$val['retailnum'].$val['foodunit'];?></td> -->
											<td><?php echo $val['stockamount'].$val['foodunit'];?></td>
											<td>
											<a href="#static1"  onclick="getonestock('<?php echo $val['foodid'];?>')"  class="btn red mini" data-toggle="modal" ><i class="icon-plus"></i> 调整库存</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->
					</div>
																
					<div id="static1" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<form action="../interface/savestocknum.php" method="post">
												<input type="hidden" name="foodid"  id="foodid" >
												<input type="hidden" name="returnurl" value="updateautostock">
												<div class="control-group">
													<label class="control-label">新增 【<span id="foodname" style="color: red"></span>】</label>
													<div class="controls">
													 	<input type="number" placeholder="0" id="packnum" name="packnum" oninput="checknum('packnum')" class="m-wrap span2" > <span id="bigunit"></span>，零散 
													<input type="number" placeholder="0"  id="retailnum" name="retailnum"  oninput="checknum('retailnum')"  class="m-wrap span2" > <span id="smallunit"></span>
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