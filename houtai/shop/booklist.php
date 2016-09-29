<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class BookList{
	public function getBooklistSheet($shopid, $theday, $op){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getBooklistSheet($shopid, $theday, $op);
	}
	public function getTodayBookData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getTodayBookData($shopid);
	}
	public function getBookids($shopid, $theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getBookids($shopid, $theday);
	}
	public function getAvilableTabs($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getAvilableTabs($shopid);
	}
}
$booklist=new BookList();
$title="预定";
$menu="table";
$clicktag="booklist";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
	$theday=$_GET['theday'];
}
$readyarr=$booklist->getBooklistSheet($shopid, $theday, "ready");
$acceptarr=$booklist->getBooklistSheet($shopid, $theday, "accept");
$invalidarr=$booklist->getBooklistSheet($shopid, $theday, "invalid");
$todayarr=$booklist->getTodayBookData($shopid);
$bookidarr=$booklist->getBookids($shopid, $theday);
$tabarr=$booklist->getAvilableTabs($shopid);
?>
<script>
function getnewdata(){
	theday=document.getElementById("daydatepicker").value;
	window.location.href="./booklist.php?theday="+theday;
}
</script>
<script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>

<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							预定
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>

				<div class="control-group pull-left margin-right-20">
								<div class="controls">
									<div class="input-append date date-picker" data-date="<?php echo date("Y-m-d",time());?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
										<input class="m-wrap m-ctrl-medium Wdate" id="daydatepicker" onClick="WdatePicker()" onchange="getnewdata()" size="16" type="text" value="<?php echo $theday;?>"><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</div>
							</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<div class="tabbable tabbable-custom boxless">
							<ul class="nav nav-tabs">
							
								<li class="active"><a href="#tab_ready" data-toggle="tab"><span style="font-size:18px; ">未确认</span></a></li>
								<li class=""><a href="#tab_accept" data-toggle="tab"><span style="font-size:18px; ">已确认</span></a></li>
								<li class=""><a href="#tab_invalid" data-toggle="tab"><span style="font-size:18px; ">无效预定</span></a></li>
								<li class=""><a href="#tab_today" data-toggle="tab"><span style="font-size:18px; ">今日预定提醒</span></a></li>
							</ul>
							<div class="tab-content">
							<div class="tab-pane active" id="tab_ready">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>未确认预定</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>预定客户</th>
											<th>人数</th>
											<th>联系方式</th>
											<th>到店时间</th>
											<th>创建时间</th>
											<th width="140">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($readyarr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['cusname'];?></td>
											<td><?php echo $val['cusnum'];?></td>
											<td><?php echo $val['cusphone'];?></td>
											<td><?php echo $val['bookdate']." ".$val['booktime'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>					
											<td><a  href="#static_<?php echo $val['bookid'];?>"  data-toggle="modal"   class="btn mini green" >确认</a>
												<a href="./interface/updatebookstatus.php?bookid=<?php echo $val['bookid'];?>&op=invalid&theday=<?php echo $theday;?>" class="btn mini red"  onclick="return confirm('确定为无效预定？');">无效</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
									</div>
									
									
									
					<div class="tab-pane" id="tab_accept">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>已确认预定</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>预定客户</th>
											<th>人数</th>
											<th>联系方式</th>
											<th>安排桌台</th>
											<th>到店时间</th>
											<th>创建时间</th>
											<th width="140">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($acceptarr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['cusname'];?></td>
											<td><?php echo $val['cusnum'];?></td>
											<td><?php echo $val['cusphone'];?></td>
											<td><?php echo $val['tabname'];?></td>
											<td><?php echo $val['bookdate']." ".$val['booktime'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>										
											<td>
												<a href="./interface/updatebookstatus.php?bookid=<?php echo $val['bookid'];?>&op=invalid&theday=<?php echo $theday;?>" class="btn mini red"  onclick="return confirm('确定为无效预定？');">无效</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
									</div>
									
									<div class="tab-pane" id="tab_invalid">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>无效预定</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>预定客户</th>
											<th>人数</th>
											<th>联系方式</th>
											<th>到店时间</th>
											<th>创建时间</th>
											<th width="140">操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($invalidarr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['cusname'];?></td>
											<td><?php echo $val['cusnum'];?></td>
											<td><?php echo $val['cusphone'];?></td>
											<td><?php echo $val['bookdate']." ".$val['booktime'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>										
											<td>
											<a  href="#static_<?php echo $val['bookid'];?>"  data-toggle="modal"   class="btn mini green" >确认</a>
											</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
									</div>
									
									<div class="tab-pane" id="tab_today">
									<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box purple">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>今日预定提醒</div>
								<div class="tools">
								</div>
							</div>
							<div class="portlet-body">
							
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>预定客户</th>
											<th>人数</th>
											<th>联系方式</th>
											<th>安排桌台</th>
											<th>到店时间</th>
											<th>创建时间</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($todayarr as $key=>$val){?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['cusname'];?></td>
											<td><?php echo $val['cusnum'];?></td>
											<td><?php echo $val['cusphone'];?></td>
											<td><?php echo $val['tabname'];?></td>
											<td><?php echo $val['bookdate']." ".$val['booktime'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['timestamp']);?></td>											
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

						<?php foreach ($bookidarr as $bookid){?>
						<div id="static_<?php echo $bookid;?>" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-body">
							<p></p>
							<div class="tab-pane  active" id="portlet_tab3">

											<h4>确认桌台</h4>

											<form action="./interface/ensuretab.php" method="post">
												<input type="hidden" name="bookid" value="<?php echo $bookid;?>">	
												<input type="hidden" name="theday" value="<?php echo $theday;?>">												
												<div class="control-group">
													<label class="control-label"></label>
													<div class="controls">
														<select class="medium m-wrap" tabindex="3" id="sel1" name="tabid">
															<option value="0">---未选择---</option>
															<?php foreach ($tabarr as $tkey=>$tval){?>
															<option value="<?php echo $tval['tabid'];?>"><?php echo $tval['tabname']."(".$tval['seatnum']."人桌)";?></option>
															<?php }?>
														</select>
													</div>
												</div>
												<hr>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit"  class="btn blue">保存</button>

											</form>

										</div>
						</div>
					</div>		
						<?php }?>
					</div>

				</div>
									</div>
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
if(isset($_GET['status'])){
	if($_GET['status']=="error"){
		echo '<script>alert("短信通知发送失败，请电话通知此顾客~");
		window.location.href="./booklist.php?theday='.$theday.' ";
		</script>';
	}
}
?>
