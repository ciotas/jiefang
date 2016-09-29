<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class TransferNote{
    public function getAllOnLineShop(){
		return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getAllOnLineShop();
	}
	public function getTransferLog($where = NULL){
	    return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getTransferLog($where);
	}
	public function getShopInfoById($shopid)
	{
	    return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getShopInfoById($shopid);
	}
}
$shopinfo=new TransferNote();
$title="商家信息";
$menu="manage";
$clicktag="transfernote";
$shopid=$_SESSION['shopid'];
require_once ('header.php');
$where = NULL;
if(isset($_GET['day']) || isset($_GET['phone']))
{
    $where['phone'] = $_GET['phone'];
    $where['day'] = $_GET['day'];
}
$arr=$shopinfo->getTransferLog($where);
?>
		<h3 class="page-title">
				转账记录<small> </small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
<!-- 		增加记录 start -->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>增加记录</div>
								<div class="tools">
								</div>
							</div>
							
							<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											<th class="number">手机号</th>
											
											<th class="number">转账日期</th>
											
											<th class="number">查询</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										<form action="" method="get">
											<td class="number"><input type="text" placeholder="必填" value = "<?php echo $_GET['phone']; ?>"  name="phone" class="mobilphone" style="width:80%;"></td>
											<td class="number"><input size="16" type="date" value="<?php echo $_GET['day']; ?>" name="day" class="form_datetime" style="width:80%;"></td>
											<td class="number"><input class="add_h pandm" type="submit" value="查询" style="background-color:#4b8df8;color:#fff;"></td>
										</form>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- END SAMPLE TABLE PORTLET-->

					</div>
				</div>
<!-- 		增加记录 end -->
		
		
		
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->          
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="icon-credit-card"></i>转账记录</div>
								<div class="tools">
								</div>
							</div>
							
							<div class="portlet-body">
								<table class="table table-hover table-bordered" >
									<thead>
										<tr>
											<th class="number">#</th>
											<th class="number">商户名</th>
											<th class="number">日期</th>
											<th class="number">转账时间</th>
											<th class="number">金额</th>
											<th class="number">手机号码</th>
										</tr>
									</thead>
									<tbody class="menuh">
									
									<?php 
									       $info = array('shopname'=>'无法获取','mobilphone' =>'无法获取');
									       $i=1; foreach ($arr as $key=>$val){ 
									       $info = $shopinfo->getShopInfoById($val['shopid']);
									    ?>
									
										<tr class="listh">
											<td class="number"><?php echo $i;?></td>
											<td class="number"><?php echo $info['shopname']; ?></td>
											<td class="number"><?php echo $val['day'];?></td>
											<td class="number"><?php echo date('Y-m-d H:i:s',$val['time'])?>
											<td class="number">￥<?php echo $val['money']?></td>
											<td class="number"><?php echo $info['mobilphone'];?></td>
											<td class="number"><a class="minus pandm" href="/admin/interface/addOneTransfer.php?id=<?php echo $val['_id']; ?>" style="background-color:#4b8df8;color:#fff;">删除</a></td>
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
				</div>
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->
	</div>
	
	<script type="text/javascript">
$(function(){ 
	var bol1 = false;
	var bol2 = false;
	$(".num_h").blur(function(){
		var number = /^1[3|4|5|8|7]\d{9}$/;
		if(number && number.test(this.value)){
			$(this).css({"border-color":""});
			bol1 = true;
		}else{
			$(this).css({"border-color":"red"});
			bol1 = false;
		}
	});
	$(".money_h").blur(function(){

		var money=$(".money_h").val();
		if(!isNaN(money) && money){
			$(this).css({"border-color":""});
			bol2 = true;
		}else{
			$(this).css({"border-color":"red"});
			bol2 = false;
		}
	});
	$(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
    $(".pandm").click(function(){
        
        var act = "";
        if($(this).val()=="增加" && bol1 && bol2){
            act = "plus";
            plush();
            }
        else if($(this).val()=="删除"){ 
            act ="minus";
            minush(this);
            }
        console.log(act);
//     	addh(); 
   });
//     $(".abc").click(function(){console.log(123);});
});

function minush(a){console.log(a);
	$(a).parent().parent().remove();
	
}
function addh(){
    $.ajax({ 
        type: 'POST', 
        url: 'data.php', 
        dataType: 'json', 
        cache: false,
        data:{

            n:$(".num_h").val(),
            m:$("money_h").val(),
            t:$(".form_datetime").val()
        },
        error: function(){ 
            alert('出错了！'); 
            return false; 
        }, 
        success:function(json){ 
 
        } 
    }); 
}
</script>

	<?php 
	require_once ('footer.php');
	?>
