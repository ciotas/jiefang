<?php 
require_once ('/var/www/html/global.php');
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-Cache" />
    <meta http-equiv="Cache-Control" content="max-age=0" />
    <meta name="viewport" content="width=device-width,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="./media/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="./media/js/jquery-1.10.1.min.js" type="text/javascript"></script>
    <title>支付宝支付</title>
</head>

<style>
.scroll-wrapper{   
  position: fixed;    
  right: 0;    
  bottom: 0;    
  left: 0;   
  top: 0;   
  -webkit-overflow-scrolling: touch;   
  overflow-y: scroll;   
}   
   
.scroll-wrapper iframe {   
  height: 100%;   
  width: 100%;   
}   
</style>

<body>
    <div class="scroll-wrapper">
    <?php 
    $payid=$_GET['payid'];
    $path=_ROOT."wappay/paydata/".$payid."/";
    $html=file_get_contents($path.'submit.html');
    echo $html;
    ?>
    </div>
</body>

</html>