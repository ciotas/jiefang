<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class VipSet{
	public function getVipcardList($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getVipcardList($shopid);
	}

}
$vipset=new VipSet();
$title="会员卡设置";
$menu="vip";
$clicktag="vipset";
$vcid="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$onevcd=array();
$arr=$vipset->getVipcardList($shopid);

?>
<script type="text/javascript">
<!--
function clearbox(){
	document.getElementById("cardnameID").value="";
 	document.getElementById("cardrate").value="";
 	document.getElementById("pointfactorID").value="";
 	document.getElementById("vcidID").value="";
//  	document.getElementById("carddiscount").value="";
 	document.getElementById("cardlimit").value="";
}
var xmlHttp
function getOneVcd(vcid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonevcd.php"
	url=url+"?vcid="+vcid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onevcd=xmlHttp.responseText
 	onevcd1=eval("("+onevcd+")");
 	document.getElementById("cardnameID").value=onevcd1.cardname;
 	document.getElementById("cardrate").value=onevcd1.cardrate;
 	document.getElementById("pointfactorID").value=onevcd1.pointfactor;
 	document.getElementById("vcidID").value=onevcd1.vcid;
//  	document.getElementById("carddiscount").value=onevcd1.carddiscount;
 	document.getElementById("cardlimit").value=onevcd1.cardlimit;
 	
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
							卡设置 <small> 会员卡</small>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>会员卡</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>卡名称</th>
											<th>充值比例</th>
										<!-- 	<th>消费折扣</th> -->
											<th>充值下限</th>
											<th>积分系数</th>
											<?php if($_SESSION['role']=="manager"){?>
											<th></th>
											<?php }?>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){
// 									if($val['storeflag']=="1"){$storeflag="储值卡";}else{$storeflag="非储值卡";}
										?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['cardname'];?></td>
											<td><?php echo $val['cardrate'];?></td>
										<!-- 	<td><?php echo $val['carddiscount'];?></td> -->
											<td><?php echo $val['cardlimit'];?></td>
											<td class="hidden-480"><?php echo $val['pointfactor'];?></td>
											<?php if($_SESSION['role']=="manager"){?>
											<td><a href="#static" onclick="getOneVcd('<?php echo $val['vcid'];?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonevcd.php?vcid=<?php echo base64_encode($val['vcid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
											<?php }?>
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

											<form action="./interface/setvipcard.php" method="post">
												<input type="hidden" name="vcid"  id="vcidID">
												<div class="control-group">
													<label class="control-label">卡名称 </label>
													<div class="controls">
														<input type="text" placeholder="必填，如金卡" id="cardnameID" name="cardname" class="m-wrap span5" value="<?php if(!empty($onevcd)){echo $onevcd['cardname'];}else{echo "";}?>">
													</div>
												</div>
											 	<div class="control-group">
													<label class="control-label">充值比例（注：充值比例=充值金额/赠送金额，若不赠送，请填0） </label>
													<div class="controls">
														<input type="text" placeholder="必填，数字"  name="cardrate" id="cardrate"  class="m-wrap span5" value="<?php if(!empty($onevcd)){echo $onevcd['cardrate'];}else{echo "";}?>">
													</div>
												</div> 
											<!-- <div class="control-group">
													<label class="control-label">消费折扣 （注：消费者用此会员卡支付时享受的折扣，不优惠请填100）</label>
													<div class="controls">
														<input type="text" placeholder="必填，80" id="carddiscount" name="carddiscount" class="m-wrap span5" value="<?php if(!empty($onevcd)){echo $onevcd['carddiscount'];}else{echo "";}?>">
													</div>
												</div> -->	
												<div class="control-group">
													<label class="control-label">充值下限（单位：元） </label>
													<div class="controls">
														<input type="text" placeholder="必填，数字" id="cardlimit" name="cardlimit" class="m-wrap span5" value="<?php if(!empty($onevcd)){echo $onevcd['cardlimit'];}else{echo "";}?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">积分系数 （注：如1.2代表所得积分=1.2×消费额）</label>
													<div class="controls">
														<input type="text" placeholder="选填，如1.2" name="pointfactor" id="pointfactorID"  class="m-wrap span5" value="<?php if(!empty($onevcd)){echo $onevcd['pointfactor'];}else{echo "";}?>">
													</div>
												</div>
											<!-- 	<div class="controls">                                                
														<label class="radio">
														<div class="radio"><input type="radio" name="storeflag" value="1"  id="storeflag1" checked></div>
														储值卡
														</label>
														<label class="radio">
														<div class="radio"><input type="radio" name="storeflag" value="0"  id="storeflag2"></div>
														非储值卡
														</label>  
													</div> -->
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