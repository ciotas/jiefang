<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class Welcome{
	public function isShowScorePage($uid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->isShowScorePage($uid);
	}
	public function getShopinfoByShopid($shopid){
	    return Wechat_BLLFactory::createInstanceWechatBLL()->getShopinfoByShopid($shopid);
	}
	public function getTakeoutSwitch($shopid){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getTakeoutSwitch($shopid);
	}
	public function getOpendTime($shopid){
		return Wechat_BLLFactory::createInstanceWechatBLL()->getOpendTime($shopid);
	}
}
$welcome=new Welcome();
$isshow=false;
$scoreshowarr=array();
$scoreshoparr=array();
$theshoparr=array();
if(isset($_REQUEST['shopid'])){
	$shopid=$_REQUEST['shopid'];
	$uid=$_REQUEST['uid'];
	$tabid=$_REQUEST['tabid'];
	$type=isset($_REQUEST['type'])?$_REQUEST['type']:"room";
	$takeoutswitch=$welcome->getTakeoutSwitch($shopid);
	$scoreshowarr=$welcome->isShowScorePage($uid);
	$isshow=$scoreshowarr['isshow'];
	$isshow=false;//暂时不用
// 	if($isshow){
// 		$scoreshoparr=$welcome->getShopinfoByShopid($scoreshowarr['shopid']);
// 	}
	$theshoparr=$welcome->getShopinfoByShopid($shopid);
	$opentimearr=$welcome->getOpendTime($shopid);
	if($takeoutswitch=="1"){
	    if(!empty($opentimearr)){
	        if($opentimearr['starttime']<=date("H:i",time())&& date("H:i",time())<=$opentimearr['overtime']){
	            $takeoutswitch=1;
	        }else{
	            $takeoutswitch=0;
	        }
	    }
	}
	
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
<link rel="stylesheet" type="text/css" href="../media/css/public.css">
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black-translucent" name="apple-mobile-web-app-status-bar-style"/>
<title>点霸</title>
<style>
.welBanner{height:70%;background: url("<?php if(!empty($theshoparr)){echo $theshoparr['homepic'];}?>") 50% 50% / 90% no-repeat;background-size:100% 100%;}
/* .welBanner{height:70%;background: url("http://jiefang-img.oss-cn-hangzhou.aliyuncs.com/hyp/add.jpg") 50% 50% / 90% no-repeat;background-size:100% 100%;} */
/* .welBanner{height:70%;background: url("../media/images/add.jpg") 50% 50% / 90% no-repeat;background-size:100% 100%;}  */
</style>
</head>
 <div class="fullele welcome">
    <div class="welcomehd">
        <!--活动专题图片-->
        <div class="welBanner J_welBanner"></div>
        <div class="titleer" style="margin-top:0.5rem;">
            <h1><?php if(!empty($theshoparr)){echo $theshoparr['shopname'];}?></h1>
<!--             <h3></h3> -->
        </div>
<!--         <div class="user_name"></div> -->
<!--         <div class="coupon"></div> -->
        <div class="mainer" style="margin:0.5rem auto;">
            <div class="open-wx">
                <div class="order-main" style="width:80%;overflow:hidden;margin:0 auto;">
                        <span class="button none J_Start" style = "width:60%;height:30px;line-height:30px;border-width:1px;margin:0 auto;">开启自助点餐</span>
                </div>
            </div>
        </div>
        <div class="secchd"></div>
<!--         <div class="we-footer none"> -->
<!--             <span>2014-2016 © 杭州街坊科技 Inc. All rights reserved<i>4000-049-517 </i></span> -->
<!--         </div> -->
        <div class="we-footer" style="color:#595857;text-align:center;line-height:20px;">
            <span><small style="color:#727171;opacity:0.8;overflow:hidden;">地址：<?php echo $theshoparr['district'].$theshoparr['road']; ?></small><br/>
            <i><small style="color:#727171;opacity:0.8;">订餐电话：<?php echo $theshoparr['servicephone'];?> </small></i></span>
            <div style="color:#727171;opacity:0.5;font-size: 12px;">&copy;杭州点霸网络科技有限公司</div>
        </div>
    </div>
</div>
<input type="hidden" id="takeoutswitch" value="<?php echo $takeoutswitch;?>">
<?php if($isshow){?>
<div class="wrappBox">
   <div class="wrappAlert">
        <h1><?php if(!empty($scoreshoparr)){echo $scoreshoparr['shopname'];}?><span class="closd" id="closd"></span></h1>
        <div class="alert-main">
            <h2>请为我们做出评分哦~</h2>
            <ul class="clearfix">
               <li><span><input type="radio" name="radio" id="good" value="1"  checked /><label for="good">优秀</label></span></li>
               <li><span><input type="radio" name="radio" id="some" value="0"/><label for="some">一般</label></span></li>
               <li><span><input type="radio" name="radio" id="review" value="-1"/><label for="review">差评</label></span></li>
           </ul>
           <div class="make">
          <a href="javascript:;" id="mark">确定</a></div>
        </div>
    </div> 
</div>
<?php }?>
<script type="text/javascript" src="../media/js/zepto.min.js"></script>
<script type="text/javascript">
	var xmlHttp
    $(function(){
        $('#closd').on('click',function(){
            $('.wrappBox').hide();
        })
        var isTrue = false;
        if($("input[type='hidden']").val() == 1){
            $(".J_Start").html("开启自助点餐").css({border:"1px solid #333",color:"#333"});
            isTrue = true;
            
        }else{
            $(".J_Start").html("商家已经打烊").css({border:"1px solid red",color:"red"});
            isTrue = false;
        }
        
         $('.J_Start').on('click',function(){
             if(isTrue){
            	window.location.href="./menu.php?shopid=<?php echo $shopid;?>&uid=<?php echo $uid;?>&tabid=<?php echo $tabid;?>&type=<?php echo $type;?>";
             }
        })
        $('#mark').on('click',function(){
            //获取选中的值
            var val=$('input[name="radio"]:checked').val();
            
            /*请在这里写ajax*/
            xmlHttp=GetXmlHttpObject()
        	if (xmlHttp==null)
        	  {
        	  alert ("Browser does not support HTTP Request")
        	  return
        	  } 
        	var url="./addscore.php"
        	url=url+"?shopid="+'<?php echo $scoreshowarr['shopid'];?>'
        	url=url+"&uid="+'<?php echo $uid;?>'
        	url=url+"&billid="+'<?php echo $scoreshowarr['billid'];?>'
        	url=url+"&score="+val
        	url=url+"&sid="+Math.random()
        	xmlHttp.onreadystatechange=stateChanged 
        	xmlHttp.open("GET",url,true)
        	xmlHttp.send(null)
            
            $('.wrappBox').hide();
            //成功之后记得关闭弹出框。
        });
       
    });

	function stateChanged() 
	{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
	 	
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
    
</script>
</body>
</html>   