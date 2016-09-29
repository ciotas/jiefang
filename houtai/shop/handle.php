<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Handle{
	public function getShopSetData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getShopSetData($shopid);
	}
	public function shopTimeSlot($shopid){
	    return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->shopTimeSlot($shopid);
	}
	public function getDiscount($shopid){
	    return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getDiscount($shopid);
	}
    public function getFare($shopid){
	    return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getFare($shopid);
	}
}
$handle=new Handle();
$title="配置";
$menu="dataset";
$clicktag="handle";
require_once ('header.php');
$arr=$handle->getShopSetData($shopid);
$timearr = $handle->shopTimeSlot($shopid);
$discountarr = $handle->getDiscount($shopid);
$farearr = $handle->getFare($shopid);
//获取该店铺的营业时间设置
?>
<script>

var xmlHttp
function changeSwitchStatus(op){
	checkedval=document.getElementById(""+op+"").checked;
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
	var url="./interface/shopswitch.php"
	url=url+"?op="+op
	url=url+"&status="+status
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
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
function setTopclearmoney(op){
	val=document.getElementById(""+op+"").innerHTML;
	//val=parseFloat(val);
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/shopswitch.php"
	url=url+"?op="+op
	url=url+"&status="+val
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function clearbox(){
	document.getElementById("name").value="";
	document.getElementById("starttime").value="";
	document.getElementById("overtime").value="";
}
function getOneTime(id){

	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonetimeslot.php"
	url=url+"?id="+id

	xmlHttp.onreadystatechange=stateChanged2 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}


function stateChanged2() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	res=xmlHttp.responseText
 	result=eval("("+res+")");
 	document.getElementById("id").value=result.id;
 	document.getElementById("name").value=result.name;
 	document.getElementById("starttime").value=result.starttime;
 	document.getElementById("overtime").value=result.overtime;

 }
}
</script>

				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							配置
							 <small></small>
						</h3>
						

					</div>

				</div>

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN SAMPLE FORM PORTLET-->   

						<div class="portlet box blue">

							<div class="portlet-title">

								<div class="caption">

									<i class="icon-reorder"></i>

									<span class="hidden-480">配置</span>

								</div>

							</div>
					
							<div class="portlet-body">
								<div class="tabbable portlet-tabs">
										<br>
										<div class="tab-pane active" id="portlet_tab">
											<!-- BEGIN FORM-->
											<div class="control-group">
											<label class="control-label">收银员抹零上限</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('topclearmoney')" id="topclearmoney"><?php echo $arr['topclearmoney'];?></p>
												</div>
											</div>
											<div class="control-group">
											<label class="control-label">每桌押金【适用于火锅店】</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('depositmoney')" id="depositmoney"><?php echo $arr['depositmoney'];?></p>
												</div>
											</div>
																						
											<div class="control-group">
											<label class="control-label">营业时间点</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('openhour')" id="openhour"><?php echo $arr['openhour'];?></p>
												</div>
											</div>
											<div class="control-group">
											<label class="control-label">会员折扣%（部分店适用）</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('vipdiscount')" id="vipdiscount"><?php echo $arr['vipdiscount'];?></p>
												</div>
											</div>
												<div class="control-group">
												<label class="control-label">小票双份</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="doublesheet"   <?php if($arr['doublesheet']=="1"){echo "checked";}?>  onchange="changeSwitchStatus('doublesheet')" />
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">桌面数据</label>
												<div class="controls">
													<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="tabdata" <?php if($arr['tabdata']=="1"){echo "checked";}?> onchange="changeSwitchStatus('tabdata')" />
											</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">计算人数</label>
												<div class="controls">
													<div class="info-toggle-button">
												<input type="checkbox" class="toggle"  id="bycusnum" <?php if($arr['bycusnum']=="1"){echo "checked";}?> onchange="changeSwitchStatus('bycusnum')" />
											</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">划菜单金额显示</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="menumoney"  <?php if($arr['menumoney']=="1"){echo "checked";}?> onchange="changeSwitchStatus('menumoney')" />	
													</div>
													</div>
												</div>
												
												<div class="control-group">
												<label class="control-label">支付宝支付开关(微信点餐下)</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="alipay_switch"  <?php if($arr['alipay_switch']=="1"){echo "checked";}?> onchange="changeSwitchStatus('alipay_switch')" />	
													</div>
													</div>
												</div>
												
												<div class="control-group">
												<label class="control-label">微信支付开关(微信点餐下)</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="wechatpay_switch"  <?php if($arr['wechatpay_switch']=="1"){echo "checked";}?> onchange="changeSwitchStatus('wechatpay_switch')" />	
													</div>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">直接下单开关(微信点餐下)</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="directpay_switch"  <?php if($arr['directpay_switch']=="1"){echo "checked";}?> onchange="changeSwitchStatus('directpay_switch')" />	
													</div>
													</div>
												</div>
											<!-- END FORM-->  
											<div class="control-group">
												<label class="control-label">外卖开关</label>
												<div class="controls">
													<div class="info-toggle-button">
													<input type="checkbox" class="toggle"  id="takeoutswitch"  <?php if($arr['takeoutswitch']=="1"){echo "checked";}?> onchange="changeSwitchStatus('takeoutswitch')" />	
													</div>
												</div>
											</div>
											<div class="control-group">
											<label class="control-label">配送距离（公里）</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('distance')" id="distance"><?php echo $arr['distance'];?></p>
												</div>
											</div>
											<div class="control-group">
											<label class="control-label">后台通知配置</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('notice')" id="notice"><?php echo $arr['notice'];?></p>
												</div>
											</div>
											<div class="control-group">
											<label class="control-label">外卖起送费</label>
												<div class="controls">
													<p contenteditable="true" onblur="setTopclearmoney('startmoney')" id="startmoney"><?php echo $arr['startmoney'];?></p>
												</div>
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
			<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span8">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>时间段设置 </div>
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
											<th>开始时间</th>
											<th>结束时间</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php $i = 1; foreach ($timearr as $key=>$val){
									
										?>
										<tr>
											<td><?php echo $i;?></td>
											<td class="shijianduan1"><?php echo $val['name'];?></td>
											<td><?php echo $val['starttime'];?></td>
											<td><?php echo $val['overtime'];?></td>
											<td><a href="#static" class="btn mini blue bianji" onclick="getOneTime('<?php echo strval($val['_id']); ?>');"data-toggle="modal" ><i class="icon-edit"></i> </a>
											<a href="./interface/delonetimeslot.php?id=<?php echo strval($val['_id']);?>&shopid=<?php echo $shopid;?>&op=notwechat" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										</tr>
										<?php 
										$i++;
									   }?>
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
											<form action="./interface/timeslot.php" method="post">
												<input type="hidden" name="id" id="id" value="">
												<input type="hidden" name="op" id="op" value="notwechat">
												<input type="hidden" name="shopid" id="shopid" value="<?php echo $shopid;?>">
												<div class="control-group">
													<label class="control-label">时间段名 </label>
													<div class="controls">
														<input type="text" placeholder="必填" id="name" name="name" class="m-wrap span5 shijianduan2"  >
													</div>
													<label class="control-label">开始时间 </label>
													<div class="controls">
														<input type="time" placeholder="必填" id="starttime" name="starttime" class="m-wrap span5"  >
													</div>
													<label class="control-label">结束时间 </label>
													<div class="controls">
														<input type="time" id="overtime" name="overtime" class="m-wrap span5"  >
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
				
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span4">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>满减优惠 </div>
								<div class="tools">
									<a href="#static1" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>消费金额</th>
											<th>减免金额</th>
											
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php $i = 1; foreach ($discountarr as $key=>$val){
									
										?>
										<tr>
											<td><?php echo $i;?></td>
											<td class="shijianduan"><?php echo $val['money'];?></td>
											<td><?php echo $val['discount'];?></td>
											
											<td><!--  a href="#static1" class="btn mini blue bianji" onclick="" data-toggle="modal" ><i class="icon-edit"></i> </a-->
											<a href="./interface/delDiscount.php?id=<?php echo strval($val['_id']);?>&op=notwechat" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										</tr>
										<?php 
										$i++;
									   }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>
					<div class="span5">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>配送费设置 </div>
								<div class="tools">
									<a href="#static2" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>范围（公里）</th>
											<th>配送费</th>
											
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php $i = 1; foreach ($farearr as $key=>$val){
									
										?>
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $val['area'];?></td>
											<td><?php echo $val['fare'];?></td>
											<td><!--  a href="#static2" class="btn mini blue bianji" onclick="" data-toggle="modal" ><i class="icon-edit"></i> </a -->
											<a href="./interface/delFare.php?id=<?php echo strval($val['_id']);?>&shopid=<?php echo $shopid;?>&op=notwechat" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
											</td>
										</tr>
										<?php 
										$i++;
									   }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>
					
					<div id="static2" class="modal hide fade" tabindex="-1" data-backdrop="static1" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab">

											<h4></h4>
											<form action="./interface/editFare.php" method="post">
											    <input type="hidden" name="op" id="op" value="notwechat">
												<input type="hidden" name="shopid" id="shopid" value="<?php echo $shopid;?>">
												<div class="control-group">
													<label class="control-label">范围（公里内）</label>
													<div class="controls">
														<input type="number" placeholder="填写数字" id="area" name="area" class="m-wrap span5 shijianduan2"  >
													</div>
													<label class="control-label">价格 </label>
													<div class="controls">
														<input type="number" placeholder="配送费价格" id="fare" name="fare" class="m-wrap span5"  >
													</div>
													
												</div>
										
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn green">保存</button>

											</form>

										</div>
						</div>
					</div>									
					
					<div id="static1" class="modal hide fade" tabindex="-1" data-backdrop="static2" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab">

											<h4></h4>
											<form action="./interface/editDiscount.php?act=add" method="post">
												<input type="hidden" name="op" id="op" value="notwechat">
												<input type="hidden" name="shopid" id="shopid" value="<?php echo $shopid;?>">
												<div class="control-group">
													<label class="control-label">消费金额</label>
													<div class="controls">
														<input type="number" placeholder="如：满30减5此处应填30" id="money" name="money" class="m-wrap span5 shijianduan2"  >
													</div>
													<label class="control-label">减免金额 </label>
													<div class="controls">
														<input type="number" placeholder="如：满30减5此处应填5" id="discount" name="discount" class="m-wrap span5"  >
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
			<!-- END PAGE CONTAINER-->



		<!-- END PAGE -->  


	<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014-2015 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>

		<div class="footer-tools">

			<span class="go-top">

			<i class="icon-angle-up"></i>

			</span>

		</div>

	</div>

	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="<?php echo $base_url;?>media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="<?php echo $base_url;?>media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="<?php echo $base_url;?>media/js/excanvas.min.js"></script>

	<script src="<?php echo $base_url;?>media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="<?php echo $base_url;?>media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.js" type="text/javascript"></script>   

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.russia.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.world.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.europe.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.germany.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.usa.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>  

	<script src="<?php echo $base_url;?>media/js/jquery.flot.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.flot.resize.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.pulsate.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/date.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/daterangepicker.js" type="text/javascript"></script>     

	<script src="<?php echo $base_url;?>media/js/jquery.gritter.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/fullcalendar.min.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/jquery.sparkline.min.js" type="text/javascript"></script>  

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="<?php echo $base_url;?>media/js/app.js" type="text/javascript"></script>

	<script src="<?php echo $base_url;?>media/js/index.js" type="text/javascript"></script>    
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/chosen.jquery.min.js"></script>
	<script src="<?php echo $base_url;?>media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="<?php echo $base_url;?>media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.input-ip-address-control-1.0.min.js"></script>
	
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.multi-select.js"></script>  
	<script src="<?php echo $base_url;?>media/js/form-components.js"></script>  
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/bootstrap-colorpicker.js"></script>  
	<script src="<?php echo $base_url;?>media/js/ui-modals.js"></script> 
	<script type="text/javascript" src="<?php echo $base_url;?>media/js/jquery.toggle.buttons.js"></script>
	
	
	<!-- END PAGE LEVEL SCRIPTS -->  

	<script>

		jQuery(document).ready(function() {    

		   App.init(); // initlayout and core plugins

		   Index.init();
// 		   Search.init();
		   
		   Index.initJQVMAP(); // init index page's custom scripts

		   Index.initCalendar(); // init index page's custom scripts

		   Index.initCharts(); // init index page's custom scripts

		   Index.initChat();

		   Index.initMiniCharts();

		   Index.initDashboardDaterange();
		   FormComponents.init();
		   UIModals.init();
		   
// 		   Index.initIntro();

		});

	</script>

	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>