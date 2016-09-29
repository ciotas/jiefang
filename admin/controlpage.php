<?php 
$json = file_get_contents('data/data.json');
$data = json_decode($json,true);



$title="监控台";
$menu="manage";
$clicktag="transfernote";

$printer = $data['printer'];
$food = $data['food'];
$foodtype = $data['foodtype'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>监控台</title>
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=2">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="media/css/controlpage.css" rel="stylesheet" type="text/css"/>
	
</head>

<!-- END HEAD -->
<!-- BEGIN BODY -->

<body >

<!-- 				商家状态展示 -->
    			<div class="jihe_hyp">
    				<div class="box1 box">
<!--     					标题 -->
						<div class="lixian">离线打印机</div>
<!-- 						商户信息 -->
						<div class="biao libiao">
    						<div class="slider_j lislider">
        						<?php foreach ($printer as $v){ ?>
        						<div class="liebiao">
            						<div class="tou">
            							<div class="touxiang" style="background-image: url('<?php echo $v['logo'];?>');"><?php echo $v['shopname']; ?></div>
            						</div>
            						<div class="wenzi">
        								<div class="title_j"><?php echo $v['shopname']; ?></div>
        								<div class="dianhua_j"><?php echo $v['mobilphone']; ?></div>
        								<div class="line_j"></div>
        								<div class="bianma_j">编码：<span><?php echo $v['deviceno']; ?></span></div>
        								状态：<?php echo $v['res']; ?>
            						</div>
        						</div>
        						<?php } ?>
    						</div>
						</div>
    				</div>
    				<div class="box2 box">
<!--     					标题 -->
						<div class="lixian caidan">菜单设置问题商家</div>
<!-- 						商户信息 -->
						<div class="biao caibiao">
    						<div class="slider_j caislider">
        						<?php foreach ($food as $v){ ?>
        						<div class="liebiao">
            						<div class="tou">
            							<div class="touxiang caidan_touxiang" style="background-image: url('<?php echo $v['logo'];?>');"><?php echo $v['shopname']; ?></div>
            						</div>
            						<div class="wenzi">
        								<div class="title_j caidan_title"><?php echo $v['shopname']; ?></div>
        								<div class="dianhua_j"><?php echo $v['mobilphone']; ?></div>
        								<div class="line_j"></div>
        								<div class="bianma_j">菜单名：<span><?php echo $v['foodname']; ?></span></div>
        								问题：<?php echo implode('、', $v['res']); ?>
            						</div>
        						</div>
        						<?php } ?>
        					</div>
						</div>   					
    				</div>
    				<div class="box3 box">
<!--     					标题 -->
						<div class="lixian leibie">类别设置问题商家</div>
<!-- 						商户信息 -->
						<div class="biao leibiao">
    						<div class="slider_j leislider">
        						<?php foreach ($foodtype as $v){ ?>
        						<div class="liebiao">
            						<div class="tou">
            							<div class="touxiang leibie_touxiang" style="background-image: url('<?php echo $v['logo'];?>');"><?php echo $v['shopname']; ?></div>
            						</div>
            						<div class="wenzi">
        								<div class="title_j leibie_title"><?php echo $v['shopname']; ?></div>
        								<div class="dianhua_j"><?php echo $v['mobilphone']; ?></div>
        								<div class="line_j"></div>
        								问题：<?php echo implode('、', $v['res']); ?>
            						</div>
        						</div>
        						<?php } ?>
    						</div>
						</div>
    				</div>
    			</div>

<script type="text/javascript">
function moveTop (ele,box,type) {
 var speed = 9;
 var timer;
 if (type == 0) {
     ele.innerHTML = ele.innerHTML + ele.innerHTML;
     ele.style.height = ele.children[0].offsetHeight*ele.children.length + 'px';
     box.onmouseover = function(){
         clearInterval(timer);
     }
     box.onmouseout = function(){
         timer = setInterval(move,50);
     }
     timer = setInterval(move,50);
     function move(){
         if (ele.offsetTop <= -ele.offsetHeight/2) {
             ele.style.top = 0;
         }
         if (ele.offsetTop > 0) {
             ele.style.top = '-' + ele.offsetHeight/2 + 'px';
         }
         ele.style.top =ele.offsetTop - speed + 'px';
     }
 }
}

 var lislider = document.querySelector(".lislider");
 var libiao = document.querySelector(".libiao");
 moveTop(lislider,libiao,0);
    
 var caislider = document.querySelector(".caislider");
 var caibiao = document.querySelector(".caibiao");
 moveTop(caislider,caibiao,0);
    
 var leislider = document.querySelector(".leislider");
 var leibiao = document.querySelector(".leibiao");
 moveTop(leislider,leibiao,0);
    
</script> 

</body>
</html>
