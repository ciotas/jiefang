<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AutoStockFood{
	public function getAutoStockFoods($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getAutoStockFoods($shopid,$theday);
	}
	public function getTheday($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
	}
	
}
$autostockfood=new AutoStockFood();
$title="添加库存";
$menu="stock";
$clicktag="autostockfood";
$shopid=$_SESSION['shopid'];
$theday=$autostockfood->getTheday($shopid);
require_once ('../header.php');
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
 	 	document.getElementById("foodid").value="";
 	 	document.getElementById("smallunit").innerHTML="";
 	 	document.getElementById("foodname").innerHTML="";
 	}else{
 		onestock1=eval("("+onestock+")");
 	 	document.getElementById("foodid").value=onestock1.foodid;
 	 	document.getElementById("smallunit").innerHTML=onestock1.foodunit;
 	 	document.getElementById("foodname").innerHTML=onestock1.foodname;
 	 	document.getElementById("num").value="";
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
function checknum(role){
	if(role!="manager"){
		num=document.getElementById("num").value;
		num=parseInt(num);
		if(num<0){
			alert("值不能为负数！")
			document.getElementById("btnsave1").disabled=true;
		}else{
			document.getElementById("btnsave1").disabled=false;
		}
	}
	
}

//-->
</script>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
	<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						添加库存
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->				
				<!-- BEGIN PAGE CONTENT-->    
<!-- 				<div class="alert alert-error">  -->
<!-- 								<button class="close" data-dismiss="green"></button> -->
<!-- 								<strong>自动盘点已简化，已使用过酒水自动库存的客户，请将"库存盘点"界面的原库存数据抄到此界面，此界面的可以添加库存、调整库存、盘点库存，原“库存盘点”页面将在一周后取消。如有疑问请拨打13071870889</strong><br> -->
<!-- 							</div>       -->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>添加库存</div>
								<div class="tools">
									<form action="../interface/printautostock.php" method="post" style="margin: 0">
									<input type="hidden" name="data" value='<?php echo json_encode($arr);?>'>
									<button class="btn green middle hidden-print" type="submit">小票打印 <i class="icon-print icon-big"></i></button>
									</form>
								</div>							
								
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>图片</th>
											<th>品名</th>
											<th>售出</th> 
<!-- 										<th>规格型号</th>
											<th>入库明细</th> -->
											<th>合计</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){?>
										<tr>
											<td><img alt="" width="50" src="<?php echo $val['foodpic'];?>"></td>
											<td><?php echo $val['foodname'];?></td>
											<td><?php echo $val['soldnum'].$val['foodunit'];?></td>
											<td><?php echo $val['num'].$val['foodunit'];?></td>
											<td>
											<a href="#static1"  onclick="getonestock('<?php echo $val['foodid'];?>')"  class="btn green mini" data-toggle="modal" ><i class="icon-plus"></i> 添加库存</a>
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
												<input type="hidden" name="returnurl" value="autostockfood">
												<div class="control-group">
													<label class="control-label">新增 【<span id="foodname" style="color: red"></span>】</label>
													<div class="controls">
													<input type="number" placeholder="0"  id="num" name="num"  oninput="checknum('<?php echo $_SESSION['role'];?>')"  class="m-wrap span2" > <span id="smallunit"></span>
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