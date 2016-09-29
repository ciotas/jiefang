<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PayPage{
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
}
$paypage=new PayPage();
$title="收银";
$menu="table";
$clicktag="tabstatus";
$type="";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$type=$_GET['type'];
	$couponarr=$paypage->getPayPageData($billid, $shopid);
}
$headtitle="";
if(!empty($type)){
	if($type=="pay"){
		$headtitle="收银";
	}elseif($type=="repay"){
		$headtitle="反结";
	}
}
?>
<script type="text/javascript">
<!--
function initpage(){
	document.getElementById('savebtn').disabled=true;
	document.getElementById('cash').focus();
	showVipBox();
}

function changecolor(key,n,ctypeid){
	color=document.getElementById('coupon'+key+'').style.background;
// 	alert(document.getElementById('coupon'+key+'').value)
	i=0;
	if(color==null || color==""){
		for(i=0;i<n;i++){
			if(key==i){
				document.getElementById('coupon'+i+'').style.background="#FF7F00";
				document.getElementById('coupon'+i+'').style.color="#FFF";
				document.getElementById('coupon'+i+'').value=ctypeid;
			}else{
				document.getElementById('coupon'+i+'').style.background="";
				document.getElementById('coupon'+i+'').style.color="black";
				document.getElementById('coupon'+i+'').value="";
			}
		}
	}else{
		document.getElementById('coupon'+key+'').style.background="";
		document.getElementById('coupon'+key+'').style.color="black";
		document.getElementById('coupon'+key+'').value="";
	}
}

function showVipBox(){
	  var obj=document.getElementById("paytype1");
	  var index = obj.selectedIndex; // 选中索引
	  var value = obj.options[index].value; // 选中值
	 if(value=="vipmoney"){
		document.getElementById("vipphonediv").style.display="block";
	 }else{
		 document.getElementById("vipphonediv").style.display="none";
	 }
	 
}
var xmlHttp
checkcode=0;
function checkCodeR(val){
	checkcode=val;
	userphone=document.getElementById("userphone").value;
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getcheckcode.php"
	url=url+"?userphone="+userphone
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=ckstateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}


function ckstateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	recieveckode=xmlHttp.responseText
 	if(recieveckode==checkcode){
 		document.getElementById("checkcodetip").innerHTML="√ 验证码正确！";
 		document.getElementById("checkcodetip").style.color="green";
 	}else{
 		document.getElementById("checkcodetip").innerHTML=" × 验证码错误！";
 		document.getElementById("checkcodetip").style.color="red";
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

function calcpay(totalmoney,fooddisaccountmoney,topclearmoney,role){
	if(role==null){role="server";}
	totalmoney=parseFloat(totalmoney);
	fooddisaccountmoney=parseFloat(fooddisaccountmoney);
	topclearmoney=parseFloat(topclearmoney);
	othermoney=0;
// 	othermoney=document.getElementById('othermoney').value;
// 	if(othermoney==null || othermoney==""){
// 		othermoney=0;
// 	}
	//othermoney=parseFloat(othermoney);
	ticketval=0;
	ticketnum=0;
	ticketway="";
	if(document.getElementById('ticketval') && document.getElementById('ticketway')){
		ticketval=document.getElementById('ticketval').value;
		if(ticketval==null || ticketval==""){
			ticketval=0;
		}
		ticketval=parseFloat(ticketval);
		if(ticketval=="0"){
			document.getElementById('ticketnum').value="0";
		}
		ticketnum=document.getElementById('ticketnum').value;
		if(ticketnum==null || ticketnum==""){
			ticketnum=0;
		}
		ticketnum=parseFloat(ticketnum);
		ticketway=document.getElementById('ticketway').value;
	}
	discountval=document.getElementById('discountval').value;
	if(discountval==null || discountval==""){
		discountval=100;
	}
	if(discountval==0 || discountval=="0"){
		alert("您不能打0折！");
	}
	discountval=parseFloat(discountval);
	serverfee=0;
	if(document.getElementById('serverfee')){
		serverfee=document.getElementById('serverfee').value;
		if(serverfee=="" || serverfee==null){
			serverfee=0;
		}
	}
	servermoney=fooddisaccountmoney*(serverfee/100);
	clearmoney=document.getElementById('clearmoney').value;
	if(clearmoney==null || clearmoney==""){
		clearmoney=0;
	}
	clearmoney=parseFloat(clearmoney);
	if(role=="server"){
		if(clearmoney>=topclearmoney){
			alert("抹零金额不能超过 "+topclearmoney+"元");
		}
	}
	returndepositmoney=document.getElementById('returndepositmoney').value;
	if(returndepositmoney==null || returndepositmoney==""){
		returndepositmoney=0;
	}
	returndepositmoney=parseFloat(returndepositmoney);
	checkval=getCheckboxVal();
	if(checkval=="1"){
		tdisaccountmoney=Math.ceil(totalmoney*(1-discountval/100));
	}else{
		tdisaccountmoney=Math.ceil(fooddisaccountmoney*(1-discountval/100));
	}
	sholdpay=totalmoney+othermoney+servermoney-tdisaccountmoney-ticketval*ticketnum-clearmoney-returndepositmoney;
	sholdpay=Math.round(sholdpay);
	
	cash=document.getElementById('cash').value;
	cash1=document.getElementById('cash').value;
	if(cash==null || cash==""){
		cash=0;
	}
	cash=parseFloat(cash);
	anothermoney=document.getElementById('anothermoney').value;
	anothermoney1=document.getElementById('anothermoney').value;
	if(anothermoney==null || anothermoney==""){
		anothermoney=0;
	}
	anothermoney=parseFloat(anothermoney);
	cuspay=cash+anothermoney;
	if( (anothermoney1==null || anothermoney1=="") && (cash1==null || cash1=="")){
		document.getElementById('calctips').innerHTML="应付：￥"+sholdpay;
		document.getElementById('savebtn').disabled=true;
	}else{
		cuspay=parseFloat(cuspay);
		if(cuspay-sholdpay<0){
			document.getElementById('calctips').innerHTML="少￥"+(sholdpay-cuspay)+" 【应收￥"+sholdpay+"】，金额不足无法提交！";
			document.getElementById('savebtn').disabled=true;
		}else if(cuspay-sholdpay>=0){
			zhaoling=parseFloat(cuspay-sholdpay);
			zhaoling=zhaoling.toFixed(2);
			document.getElementById('calctips').innerHTML="找零：￥"+zhaoling;
			if(role=="manager"){
				document.getElementById('savebtn').disabled=false;
			}else{
				if(clearmoney>topclearmoney){
					document.getElementById('savebtn').disabled=true;
				}else{
					document.getElementById('savebtn').disabled=false;
				}
			}
			
		}
	}
}

function getCheckboxVal(){
	 var s="0";
	 var obj=document.getElementsByTagName('input');
	 for(var i=0;i<obj.length;i++){
		 if(obj[i].checked){s="1";break;}
	 }
	 return s;
}
function beticketnum(){
	document.getElementById('ticketnum').value="1";
}
//-->
</script>
<script src="./media/js/vip.js"></script>
			<!-- BEGIN PAGE CONTAINER-->
<body onload="initpage()">
			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							<?php echo $headtitle;?>
							 <small></small>

						</h3>

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box green tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480"><?php echo $headtitle;?></span>

								</div>

							</div>

							<div class="portlet-body form">

								<div class="tabbable portlet-tabs">

									<ul class="nav nav-tabs">
										<li>&nbsp;</li>
									</ul>
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="portlet_tab1">
											<!-- BEGIN FORM-->
											<form action="./interface/cashier.php" class="form-horizontal" method="post"  onsubmit ="getElementById('savebtn').disabled=true;return true;">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="billid" value="<?php echo $billid;?>">
												<input type="hidden" name="type" value="<?php echo $type;?>">
											<div class="control-group">
													<label class="control-label">消费总额：<span style="color: red;font-size:20px;">￥<?php echo sprintf("%.0f",$couponarr['totalmoney']);?></span></label>
													<label class="control-label">可优惠额：<span style="color: green;font-size:20px;">￥<?php echo sprintf("%.0f",$couponarr['fooddisaccountmoney']);?></span></label>
												</div>
												<!-- 
												<div class="control-group">
													<label class="control-label">其他费用</label>
													<div class="controls">
														<input type="number" placeholder="0" name="othermoney" id="othermoney" oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap large" />
													</div>
												</div> -->
												
												<?php if(count($couponarr['ctype'])>0){?>
												<div class="control-group" >
													<label class="control-label">券种</label>
													<div class="controls">
													<select class="medium m-wrap" name="ticketway"  id="ticketway" >
													<?php foreach ($couponarr['ctype'] as $key=>$val){?>
														<option value="<?php echo $val['ctypeid'];?>"><?php echo $val['coupontype'];?></option>
													<?php }?>
													</select>
													</div>
													</div>
												<div class="control-group" >
													<label class="control-label">券</label>
													<div class="controls">
														<input type="text" placeholder="0"   name="ticketval"  id="ticketval" class="m-wrap small"  onfocus="beticketnum();" oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')"/>
														<label class="help-inline">元</label>
														<select class="small m-wrap"  name="ticketnum"  id="ticketnum" onchange="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')">
														<?php for ($i=0;$i<51;$i++){?>
														<option value="<?php echo $i;?>"><?php echo $i;?></option>
														<?php }?>
														</select>
														<label class="help-inline">张</label>
													</div>
													</div>
													<?php }?>
												<div class="control-group">
													<label class="control-label">折扣</label>
													<div class="controls">
														<input type="text" placeholder="100" name="discountval" id="discountval"  tabindex="1"  onkeydown="if(event.keyCode==13)document.getElementById('cashmoney').focus()"  oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap small" />
													<input type="checkbox"  name="allcount[]"   onchange="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" >全额折扣
													</div>
												</div>
												<?php if($shopid=="574a6e9b1a156fdd138b4b1f"){?>
												<div class="control-group">
													<label class="control-label">服务费率</label>
													<div class="controls">
														<input type="text" placeholder="10：代表多收10%的费用" name="serverfee" id="serverfee"  tabindex="1"   oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap large" />
													</div>
												</div>
												<?php }?>
												<?php if($couponarr['deposit']=="1"){?>
												<div class="control-group">
													<label class="control-label">退押金(￥<?php echo $couponarr['depositmoney'];?>)</label>
													<div class="controls">
														<input type="number" value="<?php echo $couponarr['depositmoney'];?>" name="returndepositmoney" id="returndepositmoney" oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap medium" />
													</div>
												</div>
												<?php }else{?>
												<input type="hidden" value="0" name="returndepositmoney" id="returndepositmoney" class="m-wrap large" />
												<?php }?>
												<div class="control-group">
														<label class="control-label " style="color:red;">金额</label>
														<div class="controls">
															<input type="number"  placeholder="0"   name="anothermoney1"  id="cash"  tabindex="2"  onkeydown="if(event.keyCode==13){document.getElementById('anothermoney').focus();}"  oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap small" />
															<label class="help-inline " style="color:red;">方式</label>
															<select class="small m-wrap"  name="paytype1" id="paytype1" onchange="showVipBox()" onkeydown="if(event.keyCode==13){document.getElementById('savebtn').focus();}" >
															<option  value="cashmoney">现金</option>
															<option value="alipay">支付宝</option>		
															<option value="unionmoney">银联卡</option>
															<option value="wechatpay">微信支付</option>
															<option value="vipmoney">会员卡</option>
														</select><span style="color: red " id="calctips"></span>
														</div>
													</div>
													<div id="vipphonediv" style="display:none">
													<div class="control-group" >
														<label class="control-label" >会员卡号NO.</label>
														<div class="controls">
															<input type="number" placeholder="必填，数字"   name="cardno" id="cardno" onblur="checkCardno(this.value)"  class="m-wrap medium" />
															<span class="help-inline" id="cardnotip" style="color: red"></span>
														</div>
													</div>
												
													</div>
													<div class="control-group">
														<label class="control-label " style="color:orange;">其他</label>
														<div class="controls">
															<input type="number"  placeholder="0"   name="anothermoney2"  id="anothermoney"  tabindex="2"  onkeydown="if(event.keyCode==13){document.getElementById('paytype').focus();}"  oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap small" />
															<label class="help-inline " style="color:orange;">方式</label>
															<select class="small m-wrap"  name="paytype2" id="paytype2" onchange="showVipBox()" onkeydown="if(event.keyCode==13){document.getElementById('savebtn').focus();}" >
															<option value="meituanpay">美团账户</option>
															<option value="unionmoney">银联卡</option>
															<option value="alipay">支付宝</option>		
															<option value="wechatpay">微信支付</option>
															<option value="dazhongpay">大众账户</option>
															<option value="nuomipay">糯米账户</option>
															<option value="otherpay">其他</option>
														</select><span class="help-inline" style="color:red">与上面付款方式一起可单一使用，也可组合使用！</span>
														</div>
													</div>
												
												<div class="control-group">
													<label class="control-label" style="color:#1d943b;">抹零</label>
													<div class="controls">
														<input type="text" placeholder="0"   name="clearmoney" id="clearmoney"  oninput="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>,<?php echo $couponarr['topclearmoney'];?>,'<?php echo $_SESSION['role']?>')" class="m-wrap medium" />
													</div>
												</div>
												
												<div class="form-actions">
												<button type="button" class="btn black"  onclick="window.location.href='./tabmanage.php'"><i class="m-icon-swapleft m-icon-white"></i> 返回</button>
													<button type="submit" class="btn green"  id="savebtn" ><i class="icon-ok"></i> 保存</button>
												</div>
											</form>
											<!-- END FORM-->  
										</div>
									</div>
								</div>

							</div>

						</div>

						<!-- END SAMPLE FORM PORTLET-->

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
	if(isset($_GET['status'])){
		$status=$_GET['status'];
		if($status=="vip_notenough"){
			echo '<script>alert("会员卡余额不足，买单失败！");</script>';	
		}
	}
	require_once ('footer.php');
	?>
