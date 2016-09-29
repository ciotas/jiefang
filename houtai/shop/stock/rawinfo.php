<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class RawInfo{
	public function getRawsOrderByRawtype($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawsOrderByRawtype($shopid);
	}
	public function getRawtypeData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawtypeData($shopid);
	}
}
$rawinfo=new RawInfo();
$title="原料信息";
$menu="stock";
$clicktag="rawinfo";
$shopid=$_SESSION['shopid'];
$typeno="0";
require_once ('../header.php');
if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$rawtypearr=$rawinfo->getRawtypeData($shopid);
$arr=$rawinfo->getRawsOrderByRawtype($shopid);
// print_r($arr);exit;
$status="";
if(isset($_GET['status'])){
	$status=$_GET['status'];
	$typeno=$_GET['typeno'];
}
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("rawid").value="";
 	document.getElementById("rawname").value="";
 	document.getElementById("rawcode").value="";
 	document.getElementById("rawformat").value="";
 	document.getElementById("rawunit").value="";
 	document.getElementById("rawtinyunit").value="";
 	document.getElementById("rawpackrate").value="";
 	document.getElementById("typeno").value="";
 	putSelectval("","rawtypeid");
}
var xmlHttp
function getOneRaw(rawid,typeno){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../interface/getoneraw.php"
	url=url+"?rawid="+rawid
	url=url+"&typeno="+typeno
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
	 	document.getElementById("typeno1").value=oneraw1.typeno;
	 	
	 	document.getElementById("rawname").value=oneraw1.rawname;
	 	document.getElementById("rawcode").value=oneraw1.rawcode;
	 	document.getElementById("rawformat").value=oneraw1.rawformat;
	 	document.getElementById("rawtinyunit").value=oneraw1.rawtinyunit;
	 	document.getElementById("rawpackrate").value=oneraw1.rawpackrate;
	 	document.getElementById("rawunit").value=oneraw1.rawunit;
	 	putSelectval(oneraw1.rawtypeid,"rawtypeid");	 	
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
  for(var i=0;i<sel.options.length;i++){
  	if(sel.options[i].value==val){sel.options[i].selected=true;break;}
  }
}
//-->
</script>
		<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						原料信息<small> </small>
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="alert alert-success">
					<button class="close" data-dismiss="alert"></button>
					<strong>温馨提示：建议图片的大小不超过300k，若图片的超过300k可能会上传缓慢，请耐心等待。若图片过大，则可能出现上传失败的情况~</strong>
				</div>
			<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid profile">
					<div class="span12">
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>原料信息列表</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
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
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="numeric">图片</th>
											<th class="numeric">名称</th>
											<th class="numeric">编码</th>
											<th class="numeric">规格</th>
											<th class="numeric">配送单位</th>
											<th class="numeric">出库单位</th>
											<th class="numeric">包装率</th>
											<th class="numeric">操作</th>
											<th class="numeric">上传图片</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($ftval['raw'] as $fkey=>$fval){
										?>
										<tr>
											<td class="numeric"><img alt="" src="<?php echo $fval['rawpic'];?>" width="60" height="60"></td>
											<td class="numeric"><?php echo $fval['rawname'];?></td>
											<td class="numeric"><?php echo $fval['rawcode'];?></td>
											<td class="numeric"><?php echo $fval['rawformat'];?></td>
											<td class="numeric"><?php echo $fval['rawunit'];?></td>
											<td class="numeric"><?php echo $fval['rawtinyunit'];?></td>
											<td class="numeric"><?php echo $fval['rawpackrate'];?></td>
											<td><a href="#static" onclick="getOneRaw('<?php echo $fval['rawid'];?>','<?php echo $ftkey;?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i></a>
											<a href="../interface/deloneraw.php?rawid=<?php echo base64_encode($fval['rawid']);?>&typeno=<?php echo $ftkey;?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
											<td>
											<form action="../interface/douprawimg.php" method="post" enctype="multipart/form-data">
										<div class="controls">
											<input type="hidden" name="rawid" value="<?php echo $fval['rawid'];?>">
											<input type="hidden" name="typeno"  value="<?php echo $ftkey;?>">
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<span class="btn btn-file red">
												<span class="fileupload-new">选择图片</span>
												<span class="fileupload-exists">重新上传</span>
												<input type="file" class="default" name="rawpic">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
											</div>
										</div>
										</form>
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
											<form action="../interface/editrawinfo.php" method="post">
												<input type="hidden" name="rawid"  id="rawid">
												<input type="hidden" name="typeno" id="typeno1">
												<table>
													<tr>
													<td  style="color: red">名称 </td>
													<td><input type="text" placeholder="必填" id="rawname" name="rawname" class="m-wrap span2" ></td>
													<td style="color: red">类别 </td>
													<td>
													<select class="medium m-wrap" tabindex="3" id="rawtypeid" name="rawtypeid" >
															<?php foreach ($rawtypearr as $pkey=>$pval){?>
															<option value="<?php echo $pval['rtnid'];?>"><?php echo $pval['rawtypename']?></option>
															<?php }?>
														</select>
													</td>
													</tr>
													<tr>
													<td>编码 </td>
													<td> <input type="text" placeholder="可选"  name="rawcode" id="rawcode"  class="m-wrap span2" ></td>
													<td>规格 </td>
													<td><input type="text" placeholder="可选"  name="rawformat" id="rawformat"  class="m-wrap span2" ></td>
													</tr>
													<tr>
													<td style="color: red">大单位 </td>
													<td><input type="text" placeholder="必填"  name="rawunit"  id="rawunit"  class="m-wrap span2" ></td>
													<td style="color: red">小单位 </td>
													<td><input type="text" placeholder="必填"  name="rawtinyunit"  id="rawtinyunit"  class="m-wrap span2" ></td>
													</tr>
													<tr>
													<td style="color: red">包装率 </td>
													<td><input type="text" placeholder="必填，数字，默认为1"  name="rawpackrate"  id="rawpackrate"  class="m-wrap span2" ></td>
													<td></td>
													<td></td>
													</tr>
												</table>
												
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
switch ($status){
	case "fail": echo "<script>alert('图片上传失败！')</script>";break;
	case "ok":break;
}
?>