<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ShopList{
	public function getMyShoplistData($bossid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getMyShoplistData($bossid);
	}
}
$shoplist=new ShopList();
$title="我的分店";
$menu="shoplist";
$bossid=$_SESSION['bossid'];
require_once ('header.php');
$arr=$shoplist->getMyShoplistData($bossid);
?>
<script type="text/javascript">
<!--
function clearbox(){

}
var xmlHttp

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
<script src="./media/js/shop.js"></script>
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							我的分店 <small> </small>
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
								<div class="caption"><i class="icon-credit-card"></i>我的分店</div>
								<div class="tools">
									<a href="#static" onclick="clearbox()" data-toggle="modal" class="config"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>店名</th>
											<th>账号</th>
											<th>地址</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($arr as $key=>$val){ ?>
										<tr>
											<td><?php echo ++$key;?></td>
											<td><?php echo $val['shopname'];?></td>
											<td><?php echo $val['mobilphone'];?></td>
											<td><?php echo $val['address'];?></td>
											<td>
											<a href="./interface/deloneshop.php?shopid=<?php echo base64_encode($val['shopid']);?>" onclick="return confirm('确定要删除？');" class="btn mini red"><i class="icon-trash"></i> </a>
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

											<form action="./interface/dobindphone.php" method="post">
												<div class="control-group">
													<label class="control-label">分店账号</label>
													<div class="controls">
														<input type="tel" placeholder="必填，手机号码" name="shopphone"  id="telphone" class="m-wrap large"  onblur="validatemobile()"/>
														<span class="help-inline" id="phonetip"></span>
													</div>
												</div>
												<div class="control-group">
												<label class="control-label">验证码</label>
													<div class="controls">
														<div class="input-icon left">
															<i class=" icon-list-ol"></i>
															<input type="text" placeholder="4位数字验证码" class="m-wrap small"  id="phonecode" name="checkcode">
															<a class="btn purple"  onclick="showHint();">发送验证码 <i class="m-icon-swapright m-icon-white"></i></a>
														</div>
												</div>
												</div>
												<button type="button" data-dismiss="modal" class="btn">取消</button>
												<button type="submit" class="btn blue" id="submitbtn"><i class="icon-ok"></i> 绑定</button>
												
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