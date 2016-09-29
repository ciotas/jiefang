<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Printers{
	public function getPrintersByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getPrintersByShopid($shopid);
	}
	public function getZonesByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getZonesByShopid($shopid);
	}
}
$printers=new Printers();
$title="打印机";
$menu="dataset";
$clicktag="printers";
$printerid="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$oneprinter=array();
$arr=$printers->getPrintersByShopid($shopid);
// print_r($arr);exit;
$zonearr=$printers->getZonesByShopid($shopid);
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("deviceno").value="";
 	document.getElementById("devicekey").value="";
 	document.getElementById("workphone").value="";
 	document.getElementById("printername").value="";
 	document.getElementById("printerid").value="";
 	putSelectval("","outputtype");
 	putSelectval("","zoneid");
 	
}
var xmlHttp
function getOneprinter(printerid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getoneprinter.php"
	url=url+"?printerid="+printerid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	oneprinter=xmlHttp.responseText
 	oneprinter1=eval("("+oneprinter+")");
 	document.getElementById("deviceno").value=oneprinter1.device_no;
 	document.getElementById("devicekey").value=oneprinter1.device_key;
 	document.getElementById("workphone").value=oneprinter1.workphone;
 	document.getElementById("printername").value=oneprinter1.printername;
 	document.getElementById("printerid").value=oneprinter1.pid;
 	putSelectval(oneprinter1.outputtype,"outputtype");
 	putSelectval(oneprinter1.zoneid,"zoneid");
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
							打印机 <small> 列表</small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">首页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">设置</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="printers.php">打印机</a></li>
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
								<div class="caption"><i class="icon-credit-card"></i>打印机</div>
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
											<th>编码</th>
											<th>密钥</th>
											<th>卡号</th>
											<th>出单类型</th>
											<th>区域</th>
											<th>状态</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
										switch ($val['outputtype']){
											case "menu":$outputtype="划菜单";break;
											case "checkout":$outputtype="结账单";break;
											case "pass":$outputtype="传菜单";break;
											case "single":$outputtype="分单" ;break;
											case "double":$outputtype="二联单";break;
											case "subtotal":$outputtype="分总单";break;
											case "total":$outputtype="总单";break;
										}
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['printername'];?></td>
											<td><?php echo $val['deviceno'];?></td>
											<td><?php echo $val['devicekey'];?></td>
											<td><?php echo $val['workphone'];?></td>
											<td><?php echo $outputtype;?></td>
											<td><?php echo $val['zonename'];?></td>
											<td><?php echo $val['workstatus'];?></td>
											<td><a href="#static" onclick="getOneprinter('<?php echo $val['printerid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/deloneprinter.php?printerid=<?php echo base64_encode($val['printerid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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

											<form action="./interface/saveoneprinter.php" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="printerid"  id="printerid">
												<div class="control-group">
													<label class="control-label">编码 </label>
													<div class="controls">
														<input type="text" placeholder="必填，编码" id="deviceno" name="deviceno" class="m-wrap span5"  >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">密钥 </label>
													<div class="controls">
														<input type="text" placeholder="必填，密钥 （请注意大小写）"  name="devicekey"  id="devicekey"  class="m-wrap span5" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">SIM卡号 </label>
													<div class="controls">
														<input type="text" placeholder="可选" name="workphone" id="workphone"  class="m-wrap span5" >
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">出单类型</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="outputtype" name="outputtype" >
															<option value="menu">划菜单</option>
															<option value="checkout">结账单</option>
															<option value="pass">传菜单</option>
															<option value="single">分单</option>
															<option value="double">二联单</option>
															<option value="subtotal">分总单</option>
															<option value="total">总单</option>
														</select>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">区域</label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="zoneid" name="zoneid" >
															<?php foreach ($zonearr as $pkey=>$pval){?>
															<option value="<?php echo $pval['zoneid'];?>"><?php echo $pval['zonename'];?></option>
															<?php }?>
														</select>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">备注名 </label>
													<div class="controls">
														<input type="text" placeholder="必填" name="printername" id="printername"  class="m-wrap span5" value="<?php if(!empty($onevcd)){echo $onevcd['pointfactor'];}else{echo "";}?>">
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