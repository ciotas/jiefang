<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class Directpay{
	public function getPreBillByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getPreBillByBillid($billid);
	}
	public function updateCommonPayData($inputarr){
		return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateCommonPayData($inputarr);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function getOneBillInfoByBeforeBillid($oldbeforebillid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($oldbeforebillid);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printConsumeListData($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function delPrebillByBillid($billid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delPrebillByBillid($billid);
	}
	public function addPayRecord($inputarr){
		QuDian_InterfaceFactory::createInstancePayDAL()->addPayRecord($inputarr);
	}
	public function getFoodInfoByFoodid($foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getFoodInfoByFoodid($foodid);
	}
	public function intoConsumeRecord($inputdarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoConsumeRecord($inputdarr);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function PrintKitchenData($json){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json);
	}
	public function emoji2str($str){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->emoji2str($str);
	}
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
	public function intoShop_infoData($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->intoShop_infoData($inputarr);
	}
	public function intoBillShopinfo($inputarr,$tab){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->intoBillShopinfo($inputarr, $tab);
	}
}

$directpay=new Directpay();
if($_POST['billid']) {
	//商户订单号
	$out_trade_no = $_POST['orderno'];
	//支付宝交易号
	$trade_no = "";
	$billid=$_POST['billid'];
	$orderrequest=$_POST['orderrequest'];
	$beforeinfo=$directpay->getOneBillInfoByBeforeBillid($billid);
	$tabid=$_POST['tabid'];
	$tabname=$directpay->getTablenameByTabid($tabid);
	$uid=$_POST['uid'];
	$paytype="directpay";
	$shopid=$_POST['shopid'];
	$shopname=$beforeinfo['shopname'];
	$foodarr=$beforeinfo['food'];
	$nickname=$beforeinfo['nickname'];//处理emoji表情
	$nickname=$directpay->emoji2str($nickname);
	$cuspay=$_POST['paymoney'];
	$cusnum=isset($beforeinfo['cusnum'])?$beforeinfo['cusnum']:2;

	$prov=$_REQUEST['prov'];
	$city=$_REQUEST['city'];
	$dist=$_REQUEST['dist'];
	$road=$_REQUEST['road'];
	$shop_name=$_REQUEST['shopname'];
	$contact=$_REQUEST['contact'];
	$phone=$_REQUEST['phone'];
	$picktime=$_REQUEST['picktime'];
	
    	$clearmoney="0";
    	$ticketval="0";
    	$ticketnum="0";
    	$ticketway="";
    	$discountval="100";
    	$discountmode="part";
    	$returndepositmoney="0";
    	$paymethod=$paytype;
    	$paymoney=$cuspay;
    	$inputarr=array(
    			"tradeno"=>$trade_no,
    			"orderno"=>$out_trade_no,
    			"uid"=>$uid,
    			"shopid"=>$shopid,
    			"nickname"=>$nickname,
    			"shopname"=>$shopname,
    			"wait"=>$beforeinfo['wait'],
    			"tabid"=>$tabid,
    			"takeout"=>"1",
    			"invoice"=>$beforeinfo['invoice'],
    			"deposit"=>$beforeinfo['desposit'],
    			"takeoutaddress"=>$beforeinfo['takeoutaddress'],
    			"discountype"=>$beforeinfo['discountype'],
    			"paystatus"=>"paid",
    			"payrole"=>$beforeinfo['payrole'],
    			"paystate"=>$beforeinfo['paystate'],
    			"tabname"=>$tabname,
    			"cusnum"=>$cusnum,
    			"timestamp"=>time(),
    			"billstatus"=>"done",
    			"paymoney"=>$cuspay,
    			"clearmoney"=>$clearmoney,
    			"othermoney"=>"0",
    			"discountval"=>$discountval,
    			"cashmoney"=>"0",
    			"unionmoney"=>"0",
    			"vipmoney"=>"0",
    			"discountmode"=>$discountmode,
    			"ticketval"=>$ticketval,
    			"ticketnum"	=>$ticketnum,
    			"ticketway"=>$ticketway,
    			"meituanpay"=>"0",
    			"dazhongpay"=>"0",
    			"nuomipay"=>"0",
    			"alipay"=>"0",
    			"wechatpay"=>"0",
    			"paytype"=>$paytype,
    			"returndepositmoney"=>$returndepositmoney,
    			"paymethod"=>$paymethod,
    			"cashierman"=>$nickname,
    			"orderrequest"=>$orderrequest,
    			"food"=>$foodarr,
    	);
//     		print_r($inputarr);exit;
//     	file_put_contents("/var/www/html/log.txt", json_encode($inputarr));
    	//录入商家信息
    	$barr=array(
    			"prov"=>$prov,
    			"city"=>$city,
    			"dist"=>$dist,
    			"road"=>$road,
    			"shopname"=>$shop_name,
    			"contact"=>$contact,
    			"phone"=>$phone,
    			"orderrequest"=>$orderrequest,
    			"picktime"=>$picktime,
    	);
    	$barr['shopid']=$shopid;
    	$barr['uid']=$uid;
    	$directpay->intoShop_infoData($barr);
    	
    	$normalbillid=$directpay->intoConsumeRecord($inputarr);//插入正式数据库
    	
    	unset($barr['shopid']);
    	unset($barr['uid']);
    	$barr['billid']=$normalbillid;
    	 
    	$directpay->intoBillShopinfo($barr, "billshopinfo");
    	
    	$billarr=$directpay->getOneBillInfoByBillid($normalbillid);//新数据
    	$consumeListArr=$directpay->tobeConsumeList($billarr,$paymethod,$paymoney);
    	
    	// 	print_r($consumeListArr);exit;//消费清单
    	$consumearr=$directpay->printConsumeListData(json_encode($consumeListArr));
    	if(!empty($consumearr)){$temparr[]=$consumearr;}
    	
    	//厨房单
    	$billarr['printerid']="";//代表按照规则打印
    	$orderfoodarr=$directpay->orderByprinterid($billarr);
    	// print_r($orderfoodarr);exit;
    	$piecelistArr=$directpay->tobePieceList($orderfoodarr);
    	// print_r($piecelistArr);exit;
    	$kitchenarr=$directpay->PrintKitchenData(json_encode($piecelistArr));
    	if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
    	
    	$urls=$directpay->getUrlsArr(json_encode($temparr));
    	// 	print_r($urls);exit;
    	
    	$directpay->sendFreeMessage($urls);//打印
    	if(!empty($tabid)){
    		$directpay->updateOneTabStatus($tabid, "empty");//买单之后自动清台
    	}
//     	$notifyurl->delPrebillByBillid($billid);
    	
}
// header("location: ./menu.php?shopid=$shopid&uid=$uid&paystatus=paid");
header("location: ./order-details.php?billid=".$normalbillid."&shopname=".$billarr['shopname']."&paystatus=paid")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<title>支付账单</title>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="../webmedia/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="../webmedia/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="../webmedia/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="../webmedia/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="../webmedia/css/profile.css" rel="stylesheet" type="text/css" />

</head>
<body>
				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

			<div class="container-fluid">
				<div class="span12">
						<h3 class="page-title">
							账单
							 <small></small>
						</h3>
					</div>
				<div class="row-fluid profile">

					<div class="span12">

						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >
									<ul class="span10" style="list-style-type: none;">
										<li><h4>订单号：<?php echo $out_trade_no;?></h4></li>
										<li><h4>金额：￥<?php echo $cuspay;?></h4></li>
										<li><h4>商家：<?php if(!empty($beforeinfo)){echo $beforeinfo['shopname'];}?></h4> </li> 
										<?php if(!empty($tabname)){?>
										<li><h4>台号：<?php echo $tabname;?></h4> </li>
										<?php }?>
										<li><h4>买单时间：<?php echo date("Y-m-d H:i:s",time())?></h4> </li>
									</ul>
								</div>	
							<a class="btn red big btn-block"  target="_blank" href="<?php echo ROOTURL;?>phwechat/interface/menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&paystatus=paid"><—返回首页</a>
								<!--end tab-pane-->
						</div>
					
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

	<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

			2014-2016 &copy;  <a href="http://www.meijiemall.com/" title="街坊" target="_blank">杭州街坊科技 Inc.</a> All rights reserved

		</div>


	</div>

	<!-- END FOOTER -->

	<script src="../webmedia/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="../webmedia/js/bootstrap.min.js" type="text/javascript"></script>

	<script src="../webmedia/js/form-components.js"></script>  
	
</body>

<!-- END BODY -->

</html>	