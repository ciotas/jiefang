<?php 
/**
 * Created by PhpStorm.
 * User: wangjj
 * Date: 6/1/16
 * Time: 20:32
 */

require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/Factory/BLLFactory.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');

class GetBalanceClass{
    public function getTixianRecord($shopid){
        return Wechat_BLLFactory::createInstanceWecashBLL()->getTixianRecord($shopid);
    }
    public function getShopidByOpenid($openid){
        return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
    }
    public function write_logs($content = '') {
        Wechat_BLLFactory::createInstanceWechatBLL()->write_logs($content);
    }
}

$var = new GetBalanceClass();
if(isset($_GET['openid'])){
    $openid = isset($_GET['openid'])?$_GET['openid']:'';
    $shopid=$var->getShopidByOpenid($openid);
    $var->write_logs('[openid] = '.$openid);
    $result= $var->getTixianRecord($shopid);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>提现记录</title>
	<link rel="stylesheet" type="text/css" href="../media/css/historypage.css"/>
	<script type="text/javascript" src="../media/js/jquery-2.2.1.js"></script>
</head>
<body>
	<div class="bgs">
	<!-- 	头部样式 -->
		<div class="jilus headh">
			<div class="riqis ri">提现时间</div>
			<div class="jutis ju">金额(元)</div>
		</div>
		<div class="jilus" style="border-bottom:none;">
			<div class="riqis"></div>
			<div class="jutis"></div>
		</div>
		<!-- 主体部分 -->
			<?php foreach ($result as $key=>$val){?>
		<div class="jilus">
			<div class="riqis"><?php echo date("Y-m-d H:i",$val['getcash_time']);?></div>
			<div class="jutis"> ¥<?php echo $val['amount'];?></div>
		</div>
		<?php }?>
	</div>
<!-- 移动端适配方法 -->
<script type="text/javascript">
    (function (doc, win) {
        var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function () {
                    window.clientWidth = docEl.clientWidth;
                    if (!window.clientWidth) return;
                    docEl.style.fontSize = 20 * (window.clientWidth / 320) + 'px';
                    window.base = 20 * (window.clientWidth / 320);
                };
        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);
</script>
	<script type="text/javascript">
		$('.bgs').height($(window).height());
	</script>
</body>
</html>