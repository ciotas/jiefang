<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class FoodType{
    public function getBossFtypes($bossid){
        return Boss_InterfaceFactory::createInstanceBossOneDAL()->getBossFtypes($bossid);
    }
}
$foodtype=new FoodType();
$title="商品分类";
$menu="foods";
$clicktag="goodstype";
$bossid=$_SESSION['bossid'];
require_once ('header.php');
$arr=$foodtype->getBossFtypes($bossid);
?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("ftid").value="";
 	document.getElementById("ftname").value="";
 	document.getElementById("ftcode").value="";
 	document.getElementById("sortno").value="";
 	putSelectval("0")
}
var xmlHttp
function getOnetype(ftid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonetype.php"
	url=url+"?ftid="+ftid
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
 	document.getElementById("ftid").value=oneprinter1.ftid;
 	document.getElementById("ftname").value=oneprinter1.ftname;
 	document.getElementById("ftcode").value=oneprinter1.ftcode;
 	document.getElementById("sortno").value=oneprinter1.sortno;
 	putSelectval(oneprinter1.printerid);
 }
}
function putSelectval(val){
	  var sel=document.getElementById('sel1');
	  for(var i=0;i<sel.options.length;i++)
	  {
	  	if(sel.options[i].value==val)
	  	{
	  	sel.options[i].selected=true;
	  	break;
	  	}
	  }
	}

function showorhide(ftid){
	checkedval=document.getElementById("showtype_"+ftid).checked;
	if(checkedval){
		status="1";
	}else{
		status="0";
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/showtype.php"
	url=url+"?ftid="+ftid
	url=url+"&status="+status
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=showtypeRes 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function showtypeRes() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	result=xmlHttp.responseText
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
						商品分类&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<!-- <a href="./interface/syncfood.php?backurl=foodtype" class="btn red">同步数据</a><small> 修改完后请同步数据</small> -->
						</h3>
						
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
								<div class="caption"><i class="icon-credit-card"></i>类别</div>
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
											<th>类别编码</th>
											<th>排序序号</th>
											<th>编辑</th>
										</tr>
									</thead>
									<tbody>
									<?php 
									$i=0;
									foreach ($arr as $key=>$val){?>
										<tr>
											<td><?php echo $i++;?></td>
											<td><?php echo $val['ftname'];?></td>
											<td><?php echo $val['ftcode'];?></td>
											<td><?php echo $val['sortno'];?></td>												
											<td><a href="#static" onclick="getOnetype('<?php echo $val['_id'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonetype.php?ftid=<?php echo ($val['_id']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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

											<form action="./interface/saveoneftype.php" method="post">
												<input type="hidden" name="bossid" value="<?php echo $bossid;?>">
												<input type="hidden" name="ftid"  id="ftid" >
												<div class="control-group">
													<label class="control-label">类别名</label>
													<div class="controls">
														<input type="text" placeholder="必填" id="ftname" name="ftname" class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">编码</label>
													<div class="controls">
														<input type="text" placeholder="必填，添加后不可修改"  name="ftcode" id="ftcode"  class="m-wrap large" >
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">序号</label>
													<div class="controls">
														<input type="number" placeholder=""  name="sortno" id="sortno"  class="m-wrap large" >
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
require_once 'footer.php';
?>
<?php if(isset($_GET['res'])){
	$res=$_GET['res'];
	if(!$res){
		echo "<script>alert('无法删除，请将此分类下所有美食移到其他分类或者全部删除后尝试！');window.location.href='./foodtype.php';</script>";
	}
}?>