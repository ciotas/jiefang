<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Raw{
	public function getRawsOrderByTime($shopid, $theyear,$themonth) {
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getRawsOrderByTime($shopid, $theyear,$themonth);
	}
	public function getTotalmoneyBymonth($shopid, $theyear, $themonth){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getTotalmoneyBymonth($shopid, $theyear, $themonth);
	}
}
$raw=new Raw();
$title="原料信息";
$menu="stock";
$clicktag="raw";
$shopid=$_SESSION['shopid'];
$typeno="0";
require_once ('../header.php');
if(isset($_GET['typeno'])){
	$typeno=$_GET['typeno'];
}
$theyear=date("Y",time());
$themonth=date("m",time());
if(isset($_REQUEST['theyear'])){
	$theyear=$_REQUEST['theyear'];
	$themonth=$_REQUEST['themonth'];
}
$themonth=str_pad($themonth,2,"0",STR_PAD_LEFT);
$arr=$raw->getRawsOrderByTime($shopid,$theyear,$themonth);
// print_r($arr);exit;
$totalmoney=$raw->getTotalmoneyBymonth($shopid, $theyear, $themonth);
$rawusemoney=0;
$trawmoney=0;
$trawusemoney=0;
$trawleftmoney=0;
$trawpaymoney=0;
$pandiandone=0;
$pandianundone=0;
foreach ($arr as $akey=>$aval){
	foreach ($aval['raw'] as $rkey=>$rval){
		$rawusemoney+=($rval['rawuseamount']*$rval['rawpackrate']+$rval['rawusetinyamount'])*$rval['rawprice'];
		$trawmoney+=$rval['rawmoney'];
		$trawusemoney+=$rval['rawusemoney'];
		$trawleftmoney+=$rval['rawleftmoney'];
		$trawpaymoney+=$rval['rawpaymoney'];
		if(!empty($rval['pandianstatus'])){
			$pandiandone++;
		}else{
			$pandianundone++;
		}
	}
}
// echo $rawusemoney;exit;
if(empty($totalmoney)||empty($rawusemoney)){
	$gross_profit_rate=0;
}else{
	$gross_profit_rate=sprintf("%.2f",100*($totalmoney-$rawusemoney)/$totalmoney)."%";
}
?>
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
function clearbox(){
	
}
var xmlHttp
function getOneRaw(rawid,typeno,theyear,themonth){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="../interface/getonerawbymonth.php"
	url=url+"?rawid="+rawid
	url=url+"&typeno="+typeno
	url=url+"&theyear="+theyear
	url=url+"&themonth="+themonth
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
	 	document.getElementById("rawpackrate").value=oneraw1.rawpackrate;
	 	document.getElementById("rawname").innerHTML=oneraw1.rawname;
	 	document.getElementById("rawunit").innerHTML=oneraw1.rawunit;
	 	document.getElementById("rawtinyunit").innerHTML=oneraw1.rawtinyunit;
	 	if(oneraw1.rawpackrate==1){
	 		document.getElementById("bigunit").style.display="none";
	 	}else{
	 		document.getElementById("bigunit").style.display="block";
	 	}
	 	document.getElementById("rawlefttinyamount").value=oneraw1.rawlefttinyamount;
	 	document.getElementById("rawleftamount").value=oneraw1.rawleftamount;
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
						库存盘点<small> </small>
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
			<div class="portlet box">
							<div class="portlet-body">
								<!-- Button to trigger modal -->
								<button class="btn green" id="pulsate-once">已盘点：<?php echo $pandiandone;?></button>
								<button class="btn yellow" id="pulsate-once">未盘点：<?php echo $pandianundone;?></button>
							</div>
						</div>
			<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid profile">
					<div class="span12">
						<!--BEGIN TABS-->
						<!-- BEGIN INLINE TABS PORTLET-->
						<div class="portlet box purple">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i>原料 <?php if(!empty($gross_profit_rate)){echo "【毛利率：".$gross_profit_rate."】";}?></div>
								<div class="tools">
								<form action="../interface/printrawstock.php" method="post" onsubmit="<?php if(!empty($pandianundone)){echo "alert('还有".$pandianundone."种原料未盘点，请完成盘点后重试！');return false";}?>" style="margin: 0;padding:0">
								<input type="hidden" name="theyear" value="<?php echo $theyear;?>" >
								<input type="hidden" name="themonth" value="<?php echo $themonth;?>" >
								
								<input type="hidden" name="T_rawmoney" value="<?php echo $trawmoney;?>" >
								<input type="hidden" name="T_rawpaymoney" value="<?php echo $trawpaymoney;?>" >
								<input type="hidden" name="T_rawusemoney" value="<?php echo $trawusemoney;?>" >
								<input type="hidden" name="T_rawleftmoney" value="<?php echo $trawleftmoney;?>" >
								<input type="hidden" name="data" value='<?php echo json_encode($arr);?>'>
								<button  type="submit"  class="btn blue middle hidden-print" >小票打印 <i class="icon-print icon-big"></i></button>
								</form>
								</div>
							</div>
							<div class="portlet-body">
								<div class="row-fluid">
									<div class="span12">
									<div class="control-group">
										<form action="./raw.php" method="post">
										<div class="controls">
											<select class="small m-wrap" tabindex="3" id="theyear" name="theyear" >
											<?php for ($year=2015;$year<=2020;$year++){?>
												<option value="<?php echo $year;?>" <?php if($theyear==$year){echo "selected";}?>><?php echo $year;?></option>
												<?php }?>
											</select><label class="help-inline">年</label>
											<select class="small m-wrap" tabindex="3" id="themonth" name="themonth" >
											<?php for($month=1;$month<=12;$month++){?>
												<option value="<?php echo $month;?>" <?php if($themonth==$month){echo "selected";}?>><?php echo $month;?></option>
												<?php }?>
											</select><label class="help-inline">月</label>
											<button class="btn blue">查询 <i class="m-icon-swapright m-icon-white"></i></button>
										</div>
										
										</form>
									</div>
												
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
											<th class="numeric">入库量</th>
											<th class="numeric">入库计算额</th>
											<th class="numeric">入库实付额</th>
											<th class="numeric">消耗量</th>
											<th class="numeric">消耗金额</th>
											<th class="numeric">剩余库存</th>
											<th class="numeric">剩余金额</th>
											<th class="numeric">状态</th>
											<th class="numeric"></th>
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
											<td class="numeric"><?php if(empty($fval['rawamount'])&&empty($fval['rawtinyamount'])){echo "0";}else if($fval['rawpackrate']=="1"){ echo ($fval['rawamount']+$fval['rawtinyamount']/$fval['rawpackrate']).$fval['rawunit'];}else{echo $fval['rawamount'].$fval['rawunit'].$fval['rawtinyamount'].$fval['rawtinyunit'];}?></td>
											<td class="numeric"><?php if($fval['rawmoney']==0){echo "￥0";}else{echo "￥".$fval['rawmoney'];}?></td>
											<td class="numeric"><?php if($fval['rawpaymoney']==0){echo "￥0";}else{echo "￥".$fval['rawpaymoney'];}?></td>
											<td class="numeric"><?php if(empty($fval['rawuseamount'])&&empty($fval['rawusetinyamount'])){echo "0";}else{echo $fval['rawuseamount'].$fval['rawunit'].$fval['rawusetinyamount'].$fval['rawtinyunit'];}?></td>
											<td class="numeric"><?php if($fval['rawusemoney']==0){echo "￥0";}else{echo "￥".$fval['rawusemoney'];}?></td>
											<td class="numeric"><?php if(empty($fval['rawleftamount'])&&empty($fval['rawlefttinyamount'])){echo "0";}else{echo $fval['rawleftamount'].$fval['rawunit'].$fval['rawlefttinyamount'].$fval['rawtinyunit'];}?></td>
											<td class="numeric"><?php if($fval['rawleftmoney']==0){echo "￥0";}else{echo "￥".$fval['rawleftmoney'];}?></td>
											<td class="numeric"><?php if($fval['pandianstatus']=="1"){echo '<span class="label label-success">已盘点</span>';}else{echo '<span class="label label-warning">未盘点</span>';}?></td>
											<td><a href="#static" onclick="getOneRaw('<?php echo $fval['rawid'];?>','<?php echo $ftkey;?>','<?php echo $theyear;?>','<?php echo $themonth;?>')" class="btn mini blue" data-toggle="modal" ><i class="icon-edit"></i> 盘点</a>
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
											<form action="../interface/addleftamount.php" method="post">
												<input type="hidden" name="rawid"  id="rawid">
												<input type="hidden" name="typeno" id="typeno">
												<input type="hidden" name="rawpackrate" id="rawpackrate">
												<input type="hidden" name="theyear"  value="<?php echo $theyear;?>">
												<input type="hidden" name="themonth"  value="<?php echo $themonth;?>">
												<div class="control-group">
													<label class="control-label" style="color: red">名称 ：<span id="rawname"></span></label>
													<div class="controls">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">剩余库存</label>
													<div class="controls" id="bigunit">
														<input type="text" placeholder="数字，如16.8"  name="rawleftamount" id="rawleftamount"  class="m-wrap span3" > <span id="rawunit"></span>
													</div>
													<div class="controls">
														<input type="text" placeholder="数字，如16.8"  name="rawlefttinyamount" id="rawlefttinyamount"  class="m-wrap span3" > <span id="rawtinyunit"></span>
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
						
						<div class="portlet box green">

							<div class="portlet-title">

								<div class="caption"><i class="icon-table"></i>统计</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>
								</div>

							</div>

							<div class="portlet-body">

								<table class="table table-hover">

									<thead>
										<tr>
											<td>入库计算总额</td>
											<td>入库实付总额</td>
											<td>消耗总额</td>
											<td class="hidden-480">剩余总额</td>
										</tr>
									</thead>
									<tbody>
										<tr>
										<td><?php echo "￥".$trawmoney;?></td>
										<td><?php echo "￥".$trawpaymoney;?></td>
										<td><?php echo "￥".$trawusemoney;?></td>
										<td><?php echo "￥".$trawleftmoney;?></td>
										</tr>
									</tbody>

								</table>

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