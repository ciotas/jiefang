<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class PrePayPage{
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
}
$prepaypage=new PrePayPage();
$title="扫码支付";
$menu="table";
$clicktag="tabstatus";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$couponarr=$prepaypage->getPayPageData($billid, $shopid);
}

?>
<script type="text/javascript">
<!--
function initpage(){
	document.getElementById('savebtn').disabled=false;
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

function calcpay(totalmoney,fooddisaccountmoney){
	totalmoney=parseFloat(totalmoney);
	fooddisaccountmoney=parseFloat(fooddisaccountmoney);
	othermoney=0;
// 	othermoney=document.getElementById('othermoney').value;
// 	if(othermoney==null || othermoney==""){
// 		othermoney=0;
// 	}
	othermoney=parseFloat(othermoney);
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
	discountval=document.getElementById('discountval').value;
	if(discountval==null || discountval==""){
		discountval=100;
	}
	if(discountval==0 || discountval=="0"){
		alert("您不能打0折！");
	}
	discountval=parseFloat(discountval);
	
// 	clearmoney=document.getElementById('clearmoney').value;
// 	if(clearmoney==null || clearmoney==""){
// 		clearmoney=0;
// 	}
// 	clearmoney=parseFloat(clearmoney);
	clearmoney=0;
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
	sholdpay=totalmoney+othermoney-tdisaccountmoney-ticketval*ticketnum-clearmoney-returndepositmoney;
	sholdpay=Math.round(sholdpay);
	document.getElementById('calctips').value=sholdpay;

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
			<!-- BEGIN PAGE CONTAINER-->
<body onload="initpage()">
			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->   

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							扫码支付
							 <small></small>

						</h3>

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue tabbable">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">扫码支付</span>

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
											<form action="http://shop.meijiemall.com/alipay/qrcode/alipayapi.php" class="form-horizontal" method="post">
												<input type="hidden" name="shopid" value="<?php echo $shopid;?>">
												<input type="hidden" name="billid" value="<?php echo $billid;?>">
												<input type="hidden" name="clearmoney" value="0">
												<input type="hidden" name="othermoney" value="0">

											<div class="control-group">
													<label class="control-label">消费总额：<span style="color: red;font-size:20px;">￥<?php echo sprintf("%.0f",$couponarr['totalmoney']);?></span></label>
													<label class="control-label">可优惠额：<span style="color: green;font-size:20px;">￥<?php echo sprintf("%.0f",$couponarr['fooddisaccountmoney']);?></span></label>
												</div>
												<!-- 
												<div class="control-group">
													<label class="control-label">其他费用</label>
													<div class="controls">
														<input type="number" placeholder="0" name="othermoney" id="othermoney" onblur="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)" class="m-wrap large" />
													</div>
												</div> -->
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
														<input type="number" placeholder="0"   name="ticketval"  id="ticketval" class="m-wrap span2"  onfocus="beticketnum();" onblur="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)"/>
														<label class="help-inline">元</label>
														<select class="small m-wrap"  name="ticketnum"  id="ticketnum" onblur="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)">
														<?php for ($i=0;$i<51;$i++){?>
														<option value="<?php echo $i;?>"><?php echo $i;?></option>
														<?php }?>
														</select>
														<label class="help-inline">张</label>
													</div>
													</div>
													
												<div class="control-group">
													<label class="control-label">折扣</label>
													<div class="controls">
														<input type="number" placeholder="100" name="discountval" id="discountval"  onblur="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)" class="m-wrap span3" />
														<input type="checkbox"  value=""  onchange="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)" >全额折扣
													</div>
												</div>
												<?php if($couponarr['deposit']=="1"){?>
												<div class="control-group">

													<label class="control-label">退押金(￥<?php echo $couponarr['depositmoney'];?>)</label>

													<div class="controls">

														<input type="number" value="<?php echo $couponarr['depositmoney'];?>" name="returndepositmoney" id="returndepositmoney" onblur="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)" class="m-wrap large" />

													</div>

												</div>
												<?php }else{?>
												<input type="hidden" value="0" name="returndepositmoney" id="returndepositmoney" class="m-wrap large" />
												<?php }?>
												
												<!-- 
												<div class="control-group">
													<label class="control-label" style="color:#1d943b;">抹零</label>
													<div class="controls">
														<input type="text" placeholder="0"   name="clearmoney" id="clearmoney" onblur="calcpay(<?php echo $couponarr['totalmoney'];?>,<?php echo $couponarr['fooddisaccountmoney'];?>)" class="m-wrap span4" />
													</div>
												</div>
												 -->
												 <div class="control-group">
													<label class="control-label" style="color:red;">应付款￥</label>
													<div class="controls">
													<input type="text"  class="m-wrap large" style="border: 0" name="shouldpay" id="calctips" value="<?php echo sprintf("%.0f",$couponarr['totalmoney']);?>">
														
														</div>
												</div>
												
											
												<div class="form-actions">
												<button type="button" class="btn black"  onclick="window.location.href='./tabmanage.php'"><i class="m-icon-swapleft m-icon-white"></i> 返回</button>
													<button type="submit" class="btn blue" id="savebtn"><i class="icon-ok"></i> 打印二维码</button>
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
	require_once ('footer.php');
	?>
