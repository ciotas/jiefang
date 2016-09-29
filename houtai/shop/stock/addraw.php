<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddRaw{
	public function getDayRawDetail($shopid, $theday) {
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getDayRawDetail($shopid,$theday);
	}
}
$addraw=new AddRaw();
if(isset($_GET['op'])){
	$op=$_GET['op'];
	if($op=="logout"){
		$_SESSION['manager_id']="";
	}
}
if(empty($_SESSION['manager_id'])){
	header("location: ./checkrole.php?page=addraw");
}
$title="原料入库";
$menu="stock";
$clicktag="addraw";
$shopid=$_SESSION['shopid'];
$typeno="0";
require_once ('../header.php');
if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$arr=$addraw->getDayRawDetail($shopid,$theday);
$totalcalcmoney=0;
$totalrealmoney=0;
foreach ($arr as $akey=>$aval){
	foreach ($aval['raw'] as $rkey=>$rval){
		$totalcalcmoney+=$rval['rawmoney'];
		$totalrealmoney+=$rval['rawpaymoney'];
	}
}
?>
<script type="text/javascript">
<!--
var xmlHttp
function getoneraw(rawid,typeno,theday){	
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../interface/getonerawbyday.php"
	url=url+"?rawid="+rawid
	url=url+"&typeno="+typeno
	url=url+"&theday="+theday
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
	 	oneraw=xmlHttp.responseText
	 	oneraw1=eval("("+oneraw+")");
	 	document.getElementById("rawid").value=oneraw1.rawid;
	 	document.getElementById("typeno").value=oneraw1.typeno;
	 	document.getElementById("rawname").innerHTML=oneraw1.rawname;
	 	document.getElementById("rawunit").innerHTML=oneraw1.rawunit;
	 	document.getElementById("rawtinyunit").innerHTML=oneraw1.rawtinyunit;
	 	document.getElementById("rawunit1").innerHTML=oneraw1.rawunit;
	 	if(oneraw1.rawpackrate==1){
	 		document.getElementById("bigunit").style.display="none";
	 	}else{
	 		document.getElementById("bigunit").style.display="block";
	 	}
	 	document.getElementById("rawamount").value=oneraw1.rawamount;
	 	document.getElementById("rawtinyamount").value=oneraw1.rawtinyamount;
	 	document.getElementById("rawprice").value=oneraw1.rawprice;
	 	document.getElementById("rawpaymoney").value=oneraw1.rawpaymoney;
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

function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./addraw.php?theday="+theday;
}
//-->
</script>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
		<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						原料入库<small> </small>
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

			<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid profile">
					<div class="span12">
					<div class="portlet-body">
						
								<table class="table table-hover">
									<thead>
										<tr>
										<th>操作人员：<?php if(isset($_SESSION['manager_id'])){echo $_SESSION['manager_name'];}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a class="btn red small hidden-print" href="./addraw.php?op=logout">退出</a></th>
										</tr>
									</thead>
								</table>
							</div>
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>原料列表 【计算总额：<?php echo "￥".$totalcalcmoney;?>，实付总额：<?php echo "￥".$totalrealmoney;?>】</div>
								<div class="tools">
								<form action="../interface/printdayrawin.php" method="post"  style="margin: 0;padding:0">
								<input type="hidden" name="theday" value="<?php echo $theday;?>" >
								<input type="hidden" name="data" value='<?php echo json_encode($arr);?>'>
								<input type="hidden" name="manager_name" value='<?php echo $_SESSION['manager_name'];?>'>
								<button  type="submit"  class="btn purple middle hidden-print" >小票打印 <i class="icon-print icon-big"></i></button>
								</form>
								</div>
							</div>
							
							<div class="portlet-body">
							<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
									<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
								</div>
								<div class="row-fluid">
									<div class="span12">
									
										<!--BEGIN TABS-->
										<div class="tabbable tabbable-custom tabs-left">
											<!-- Only required for left/right tabs -->
											<ul class="nav nav-tabs tabs-left">
											<?php foreach ($arr as $key=>$val){?>
											<li <?php if($key==$typeno){echo "class='active'";}?>><a href="#tab_<?php echo $val['rawtypeid'];?>" data-toggle="tab"><?php echo $val['rawtypename'];?></a></li>
											<?php }?>
											</ul>
											<div class="tab-content">
											<?php foreach ($arr as $ftkey=>$ftval){?>
												<div class="tab-pane <?php if($ftkey==$typeno){echo "active";}?>" id="tab_<?php echo $ftval['rawtypeid']?>">
													<div class="portlet box yellow">
							<div class="portlet-body">
							
								<table  class="table table-hover">
									<thead>
										<tr>
											<th class="numeric">图片</th>
											<th class="numeric">名称</th>
											<th class="numeric">编码</th>
											<th class="numeric">规格</th>
											<th class="numeric">库存量</th>
											<th class="numeric">价格</th>
											<th class="numeric">计算额</th>
											<th class="numeric">实际额</th>
											<th class="numeric"></th>
										</tr>
									</thead>

									<tbody>
									<?php 
										foreach ($ftval['raw'] as $fkey=>$fval){
										?>
										<tr>
											<td class="numeric"><img alt="" src="<?php echo $fval['rawpic'];?>" width="60" height="60"></td>
											<td class="numeric"><?php echo $fval['rawname'];?></td>
											<td class="numeric"><?php echo $fval['rawcode'];?></td>
											<td class="numeric"><?php echo $fval['rawformat'];?></td>
											<?php if($fval['rawpackrate']=="1"){?>
											<td class="numeric"><?php echo $fval['rawtinyamount'].$fval['rawtinyunit'];?></td>
											<?php }else{?>
											<td class="numeric"><?php echo $fval['rawamount'].$fval['rawunit'].$fval['rawtinyamount'].$fval['rawtinyunit'];?></td>
											<?php }?>
											<td class="numeric"><?php echo $fval['rawprice'];?></td>
											<td class="numeric"><?php if($fval['rawmoney']==0){echo "￥0";}else{echo "￥".($fval['rawmoney']);}?></td>
											<td class="numeric"><?php if($fval['rawpaymoney']==0){echo "￥0";}else{echo "￥".($fval['rawpaymoney']);}?></td>
											<td><a href="#static" onclick="getoneraw('<?php echo $fval['rawid'];?>','<?php echo $ftkey;?>','<?php echo $theday;?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> 入库 </a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
								</div>
							</div>
				</div>
			<?php }?>
			<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
								<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<form action="../interface/addrawmount.php" method="post">
												<input type="hidden" name="rawid"  id="rawid">
												<input type="hidden" name="typeno"  id="typeno">
												<input type="hidden" name="theday"  value="<?php echo $theday;?>">
												<table>
												<tr>
												<td></td>
												<td></td>
												</tr>
												</table>
												<div class="control-group">
												<label class="control-label" style="color:red">名称 ：<span id="rawname"></span></label>
													<div class="controls">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" style="color:red">入库量</label>
													<div class="controls">
													<div id="bigunit">
														<input type="text" placeholder="数字，如16.8" id="rawamount" name="rawamount" class="m-wrap span2" ><span id="rawunit"></span>
													</div>
													<input type="text" placeholder="数字，如16.8" id="rawtinyamount" name="rawtinyamount" class="m-wrap span2" ><span id="rawtinyunit"></span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label" style="color:red">价格</label>
													<div class="controls">
														<input type="text" placeholder="数字，如2" id="rawprice" name="rawprice" class="m-wrap span2" > 元/<span id="rawunit1"></span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label" style="color:red">实付金额 （必填）</label>
													<div class="controls">
														<input type="text" placeholder="数字，如80.8" id="rawpaymoney" name="rawpaymoney" class="m-wrap span2" > 元
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
										</div>
										<!--END TABS-->
									</div>
									<div class="space10 visible-phone"></div>
								</div>
							</div>
						</div>
						
						<!-- END INLINE TABS PORTLET-->
						
						<!--END TABS-->

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