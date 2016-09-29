<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TabManage{
	public function getShopTablesData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getShopTablesData($shopid);
	}
}
$tabmanage=new TabManage();
$title="桌台状态";
$menu="table";
$clicktag="tabstatus";
$shopid=$_SESSION['shopid'];
// echo $shopid;exit;
require_once ('header.php');
if(isset($_GET['foodid'])){
	echo $_GET['foodid'];
}
$tabarr=$tabmanage->getShopTablesData($shopid);
// print_r($tabarr);exit;
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
 	foodstr='<table class="table table-striped table-hover">'+
	'<thead>'+
		'<tr>'+
			'<th>#</th>'+
			'<th>名称</th>'+
			'<th>价格</th>'+
			'<th>数量</th>'+
			'<th>单位</th>'+
			'<th>赠送</th>'+
			'<th>金额</th>'+
			'<th>单品折扣</th>'+
		'</tr>'+
	'</thead>'+
	'<tbody>';
 	document.getElementById("paybutton").innerHTML='<a href="paypage.php?billid='+billid+'" class="btn green icn-only" >收  银</a>&nbsp;&nbsp;';
 	document.getElementById("prepaybutton").innerHTML='<a href="prepaypage.php?billid='+billid+'" class="btn blue icn-only" >预结单</a>';
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
 		'<td>'+food[val].foodamount+'</td>'+
 		'<td>'+food[val].foodunit+'</td>'+
 		'<td>'+present+'</td>'+
 		'<td>'+(food[val].foodprice*food[val].foodamount)+'</td>'+
 		'<td><button class="btn purple mini" onclick="discountfood(\''+food[val].foodid+'\',\''+onebillobj.billid+'\')">单品折扣</button></td>'+
		'</tr>';
 	}
 	foodstr+='</tbody></table>';
 	document.getElementById("foods").innerHTML=foodstr;
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
	<div class="container-fluid">
			<!-- BEGIN PAGE CONTAINER-->
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							桌台状态
						</h3>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">主页</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">桌台</a>
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="tabmanage.php">桌台状态</a>
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>

				<div class="portlet box red">

							<div class="portlet-title">

								<div class="caption"><i class="icon-table"></i>桌台统计</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<button class="btn green" id="pulsate-once">空闲：<?php if(!empty($tabarr)){echo $tabarr['empty'];}?></button>
								<button class="btn red" id="pulsate-once">开台：<?php if(!empty($tabarr)){echo $tabarr['start'];}?></button>
								<button class="btn yellow" id="pulsate-once">占用：<?php if(!empty($tabarr)){echo $tabarr['online'];}?></button>
								<button class="btn blue" id="pulsate-once">预定：<?php if(!empty($tabarr)){echo $tabarr['book'];}?></button>
								&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<button class="btn purple"  onclick="window.location.href='./tabmanage1.php'">刷新界面</button>
							</div>
						</div>
				
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
												switch ($val['tabstatus']){
													case "empty": $bgcolor="bg-green";break;
													case "book": $bgcolor="bg-blue";break;
													case "online": $bgcolor="bg-yellow";break;
													case "start": $bgcolor="bg-red";break;
												}
												?>
					<!-- BEGIN PAGE CONTENT-->
								<div class="tiles"  >
									
									<a class="tile <?php echo $bgcolor;?>" onclick="getbillinfo('<?php echo $val['billid'];?>')" <?php if(!empty($val['billid'])){?> href="#static"  data-toggle="modal" <?php }else{echo ' href="#"  ';}?>>
									
									<div class="tile-body">
										<p style="font-size: 21px;"><?php echo $val['tabname'];?></p><br>
										<p><i class="icon-yen"> </i> <?php echo $val['money'];?> </p>
										<p><i class="icon-time"></i> <?php echo $val['timestamp'];?></p>
									</div>
								<div class="tile-object">
									<div class="name" >
										<i class="icon-user"></i>
									</div>
									<div class="number"> <?php echo $val['cusnum'];?> </div>
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
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">
											<h4></h4>

											<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-check"></i>点菜详细</div>
								
								<div class="tools">
								<button type="button" class="btn red icn-only" data-dismiss="modal" class="btn">关闭</button>
								</div>
								<div class="tools" id="paybutton"></div>
								<div class="tools" id="prepaybutton"></div>
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