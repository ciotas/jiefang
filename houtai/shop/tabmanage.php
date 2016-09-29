<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TabManage{
	public function getShopTablesData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getShopTablesData($shopid);
	}
	public function getFuncRole($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getFuncRole($shopid);
	}
	public function getOneRoleData($roleid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getOneRoleData($roleid);
	}
	public function getAllowinbalanceValue($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getAllowinbalanceValue($shopid);
	}
	public function getTabdataByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTabdataByShopid($shopid);
	}
}
$tabmanage=new TabManage();
$title="桌台状态";
$menu="table";
$clicktag="tabstatus";
$shopid=$_SESSION['shopid'];
// echo $shopid;exit;
$roleid=$_SESSION['roleid'];
require_once ('header.php');
if(isset($_GET['foodid'])){
// 	echo $_GET['foodid'];
}
$allowinbalance=$tabmanage->getAllowinbalanceValue($shopid);
$tabdata=$tabmanage->getTabdataByShopid($shopid);
$tabarr=$tabmanage->getShopTablesData($shopid);
// print_r($tabarr);exit;
$rolearr=$tabmanage->getOneRoleData($roleid);
// print_r($rolearr);exit;
?>
<style>
<!--
 a:hover {text-decoration:none;}
a:visited{text-decoration:none;} 
a:active{text-decoration:none;}
a:link{text-decoration:none;}
-->
</style>
<script type="text/javascript">
<!--
function getbillinfo(billid){
	document.getElementById("paybutton").innerHTML='';
 	document.getElementById("prepaybutton").innerHTML='';
 	document.getElementById("signbutton").innerHTML='';
 	document.getElementById("freebutton").innerHTML='';
 	document.getElementById("repaybutton").innerHTML='';
 	document.getElementById("clearbutton").innerHTML='';
 	document.getElementById("startbutton").innerHTML='';
	
	if(billid==""||billid==null || billid=="0"||billid==0){
		alert("本台无消费！");
		document.getElementById("foods").innerHTML="";
	}else{
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		  {
		  alert ("Browser does not support HTTP Request")
		  return
		  } 
		var url="./interface/getbillByBillid.php"
		url=url+"?billid="+billid
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
}


function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onebill=xmlHttp.responseText
 	onebillobj=eval("("+onebill+")");
 	billid=onebillobj.billid;
 	food=onebillobj.food;
 	tabid=onebillobj.tabid;
 	tabstatus=onebillobj.tabstatus;
 	uid=onebillobj.uid;
 	foodstr='<table class="table table-striped table-hover">'+
	'<thead>'+
		'<tr>'+
			'<th>#</th>'+
			'<th>名称</th>'+
			'<th>价格</th>'+
			'<th>数量</th>'+
			'<th>赠送</th>'+
			'<th>金额</th>'+
			'<th>折扣</th>'+
			<?php if($rolearr['returnfood']=="1"){?>
			'<th>退菜</th>'+
			<?php }?>
		'</tr>'+
	'</thead>'+
	'<tbody>';
 		document.getElementById("prediscountbutton").innerHTML='<a href="prediscount.php?billid='+billid+'" class="btn blue icn-only" >预结单</a>&nbsp;';
		if(tabstatus=="start" || tabstatus=="online"){
		 	// document.getElementById("prepaybutton").innerHTML='<a href="prepaypage.php?billid='+billid+'" class="btn blue icn-only" >扫码支付</a>&nbsp;';
			//document.getElementById("prepaybutton").innerHTML='<a href="./interface/printprepay.php?billid='+billid+'" class="btn blue icn-only" >预结单</a>&nbsp;';
			<?php if($rolearr['pay']=="1"){?>
			document.getElementById("paybutton").innerHTML='<a href="paypage.php?billid='+billid+'&type=pay" class="btn green icn-only" >收 银</a>&nbsp;';
			<?php }?>
			<?php if($rolearr['signpay']=="1"){?>
		 	document.getElementById("signbutton").innerHTML='<a href="signpaypage.php?billid='+billid+'" class="btn purple icn-only" >签单</a>&nbsp;';
			<?php }?>
			<?php if($rolearr['freepay']=="1"){?>
		 	document.getElementById("freebutton").innerHTML='<a href="freepaypage.php?billid='+billid+'" class="btn red icn-only" >免单</a>&nbsp;';
			<?php }?>
			<?php if($rolearr['empty']=="1"){?>
		 	document.getElementById("clearbutton").innerHTML='<a href="./interface/changeonetab.php?tabid='+tabid+'&uid='+uid+'&status=empty"  onclick="return confirm(\'确定要清台？\');" class="btn green icn-only" >清台</a>&nbsp;';
			<?php }?>
		}else{
			<?php if($rolearr['start']=="1"){?>
		 	document.getElementById("startbutton").innerHTML='<a href="./interface/changeonetab.php?tabid='+tabid+'&uid='+uid+'&status=start"  onclick="return confirm(\'确定要开台？\');" class="btn red icn-only" >开台</a>&nbsp;';
			<?php }?>
			<?php if($rolearr['repay']=="1"){?>
			document.getElementById("repaybutton").innerHTML='<a href="paypage.php?billid='+billid+'&type=repay" class="btn red icn-only" >重新收银</a>&nbsp;';
			<?php }?>
		}
	
 	i=1;
 	for(var val in food){
 		//<span class="label label-success">Approved</span>
 		if(food[val].present=="1"){
 			present='<span class="label label-success">已赠送</span>';
 		}else{
 			present='';
 		}
 		foodstr+='<tr>'+
 		'<td>'+(i++)+'</td>'+
 		'<td>'+food[val].foodname+'</td>'+
 		'<td>'+food[val].foodprice+'</td>'+
 		'<td>'+food[val].foodamount+food[val].foodunit+'</td>'+
 		'<td>'+present+'</td>'+
 		'<td>'+(food[val].foodprice*food[val].foodamount).toFixed(2)+'</td>'+
 		'<td><button class="btn purple mini" onclick="discountfood(\''+food[val].foodid+'\',\''+onebillobj.billid+'\')">折扣</button></td>'+
 		<?php if($rolearr['returnfood']=="1"){?>
 		'<td><button onclick="returnNum(\''+billid+'\',\''+food[val].foodid+'\',\''+food[val].foodnum+'\',\''+food[val].cooktype+'\')" class="btn mini red"><i class="icon-trash"></i> 退菜</button></td>'+
 		<?php }?>
		'</tr>';
 	}
 	foodstr+='</tbody></table>';
 	document.getElementById("foods").innerHTML=foodstr;
 }
}

function returnNum(billid,foodid,foodnum,cooktype){
	var returnnum=prompt("请输入退菜量（只能为数字）","");
	if(isInteger(returnnum)){
		  if(returnnum){
			  if(returnnum<=0 || returnnum>foodnum){
				  alert("请输入折扣值在1~"+foodnum+"之间的正整数！");
			  }else{
				  	url="./interface/returnfood.php?billid="+billid+"&foodid="+foodid+"&foodnum="+foodnum+"&cooktype="+cooktype+"&returnnum="+returnnum;
					window.location.href=url;
			  }
		  }
	  }
}
function isInteger(number){
	return number > 0 && String(number).split('.')[1] == undefined
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

function discountfood(foodid,billid){
	var discountval=prompt("请输入折扣值（只能为数字）","");
	if(isInteger(discountval)){
		  if(discountval){
			  if(discountval<0 || discountval>100){
				  alert("请输入折扣值在0~100之间的正整数！");
			  }else{
					window.location.href="./interface/dofooddiscount.php?foodid="+foodid+"&billid="+billid+"&discountval="+discountval;
			  }
		  }
	  }
// 	window.location.href="./interface/dofooddiscount.php?foodid="+foodid+"&billid="+billid+"&discountval="+discountval;
}
function isInteger(number){
	return number > 0 && String(number).split('.')[1] == undefined
}
//-->
</script>

				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							桌台状态 &nbsp;&nbsp;&nbsp;
								<button class="btn red" id="pulsate-once">开台：<?php if(!empty($tabarr)){echo $tabarr['start'];}?></button>
								<button class="btn yellow" id="pulsate-once">占用：<?php if(!empty($tabarr)){echo $tabarr['online'];}?></button>
								<button class="btn green" id="pulsate-once">空闲：<?php if(!empty($tabarr)){echo $tabarr['empty'];}?></button>								
								<button class="btn blue" id="pulsate-once">预定：<?php if(!empty($tabarr)){echo $tabarr['book'];}?></button>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<button class="btn " style="background-color: #EE1289;color:#fff" onclick="window.location.href='./tabmanage.php'">刷新界面</button>
						</h3>
						
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				
				<div class="well" style="margin-button:15px;padding:8px;">
					<h4>精选小票打印纸优惠上线啦(￣▽￣)"</h4>
					80mm打印机适用，一箱超大量100卷80×60，每卷只需1.6元！<a class="btn yellow mini"  href="./onegoods.php?goodsid=56d9a82c7cc109de558b4593" >立即购买 =></a>，购买后的订单在“商家中心”—“我的订单”里查看！
				</div>
				
<!-- 				<div class="portlet box"> -->
<!-- 					<div class="portlet-body"> -->
<!-- 					</div> -->
<!-- 				</div> -->
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<div class="tabbable tabbable-custom boxless">
							<ul class="nav nav-tabs">
							<?php foreach ($tabarr['zone'] as $zkey=>$zval){?>
								<li class="<?php if($zkey==0){echo "active";}?>"><a href="#tab_<?php echo $zval['zoneid'];?>" data-toggle="tab"><span style="font-size:18px; "><?php echo $zval['zonename'];?></span></a></li>
							<?php }?>
							</ul>
							<div class="tab-content">
							<?php foreach ($tabarr['zone'] as $tkey=>$tval){?>
								<div class="tab-pane <?php if($tkey==0){echo "active";}?>" id="tab_<?php echo $tval['zoneid'];?>">
									<div class="portlet box blue">
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
												<div class="row-fluid">
											<?php foreach ($tval['table'] as $key=>$val){
												if(!empty($val['timestamp'])){$timestamp=$val['timestamp'];}else{$timestamp="0";}
												switch ($val['tabstatus']){
													case "empty": 
														if($allowinbalance=="1"){$money=$val['money'];}else{$money="0";}
														if($allowinbalance=="1"){$timestamp=$val['timestamp'];}else{$timestamp="0";}
														if($allowinbalance=="1"){$cusnum=$val['cusnum'];}else{$cusnum="0";}
														if($allowinbalance=="1"){$buytime=$val['buytime'];}else{$buytime="0";}
														if($allowinbalance=="1"){$billid=$val['billid'];}else{$billid="0";}
														if(!empty($val['buytime'])){
															$passtime=sprintf("%.0f",($val['buytime']-$timestamp)/60);
														}else{
															$passtime="0";
														}
														$bgcolor="bg-green";break;
													case "book": 
														if($allowinbalance=="1"){$money=$val['money'];}else{$money="0";}
														if($allowinbalance=="1"){$timestamp=$val['timestamp'];}else{$timestamp="0";}
														if($allowinbalance=="1"){$cusnum=$val['cusnum'];}else{$cusnum="0";}
														if($allowinbalance=="1"){$buytime=$val['buytime'];}else{$buytime="0";}
														if($allowinbalance=="1"){$billid=$val['billid'];}else{$billid="0";}
														if(!empty($val['buytime'])){
															$passtime=sprintf("%.0f",($val['buytime']-$timestamp)/60);
														}else{
															$passtime="0";
														}
														$bgcolor="bg-blue";break;
													case "online": 
														$money=$val['money'];
														$timestamp=$val['timestamp'];
														$cusnum=$val['cusnum'];
														$buytime=$val['buytime'];
														$billid=$val['billid'];
														$passtime=sprintf("%.0f",(time()-$timestamp)/60);
														$bgcolor="bg-yellow";break;
													case "start": 
														$money=$val['money'];
														$timestamp=$val['timestamp'];
														$cusnum=$val['cusnum'];
														$buytime=$val['buytime'];
														$billid=$val['billid'];
														$passtime=sprintf("%.0f",(time()-$timestamp)/60);
														$bgcolor="bg-red";break;
												}
												if($val['tabstatus']=="start" || $val['tabstatus']=="online"){
													$timeon=sprintf("%.0f",(time()-$val['timestamp'])/60);
													if($timeon>=150 &&$val['tabstatus']=="online" ){$bgcolor="bg-purple";}
												}
												if(empty($tabdata) && ($val['tabstatus']=="empty" || $val['tabstatus']=="book") ){
													$money="0";
													$timestamp="0";
													$cusnum="0";
													$buytime="0";
													$billid="0";
													$passtime="0";
												}
											
												?>
					<!-- BEGIN PAGE CONTENT-->
								<div class="tiles"  >
									
									<a class="tile <?php echo $bgcolor;?>" onclick="getbillinfo('<?php echo $billid;?>')" <?php if(!empty($billid)){?> href="#static"  data-toggle="modal" <?php }else{echo ' href="#"  ';}?>>
									
									<div class="tile-body">
										<p style="font-size: 21px;"><?php echo $val['tabname'];?></p><br>
										<p><i class="icon-yen"> </i> <?php echo sprintf("%.0f",$money);?></p>
										<p><i class="icon-calendar"></i> <?php if(!empty($timestamp)){echo date("m-d H:i",$val['timestamp']);}else{echo "0";}?></p>
									</div>
								<div class="tile-object">
									<div class="name" >
									<table>
									<tr>
										<th><i class="icon-user"></i></th><th><?php echo $cusnum;?></th>
										</tr>
										</table>
									</div>
									<div class="number"> <i class="icon-time"></i> <?php echo $passtime;?></div>
								</div>
							</a>
				</div>
				<?php }?>
				</div>
				<br>
				<!-- END PAGE CONTENT-->
												</div>
										<!-- END FORM--> 
										</div>
									</div>
								<?php }?>
					<!-- asdfgh -->
				<div id="static" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<span class="tools" id="prepaybutton"></span>
							<span class="tools" id="paybutton"></span>
							<span class="tools" id="prediscountbutton"></span>
							<span class="tools" id="signbutton"></span>
							<span class="tools" id="freebutton"></span>
							<span class="tools" id="repaybutton"></span>
							&nbsp;&nbsp;&nbsp;
							<span class="tools" id="clearbutton"></span>
							<span class="tools" id="startbutton"></span>
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>
											<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-check"></i>点菜详细</div>
								
								<div class="tools">
								<button type="button" class="btn red icn-only" data-dismiss="modal" class="btn">关闭</button>
								</div>
								&nbsp;&nbsp;
							</div>

							<div class="portlet-body" id="foods">
							</div>
						</div>
					</div>
						</div>
					</div>	
					
				<!-- asdfgh -->
								</div>
							</div>
					</div>
					</div>
				</div>
				
			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->
<?php 
require_once ('footer.php');
?>