<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/yuntongxun/global.php');
require_once(DOCUMENT_ROOT."Factory/InterfaceFactory.php");
require_once ('/var/www/html/des.php');
require_once(DOCUMENT_ROOT."function.php");
class DownBillNotice{
    
}
if(isset($_POST['phone'])){
    $phone=$_POST['phone'];
    $msg=$_POST['msg'];
    $time=$_POST['timestamp'];
    $signature=$_POST['signature'];
    $datas=json_decode($msg,true);
    $serversign=strtoupper(md5($phone.$msg.$time.$token));
    if($serversign==$signature){
        $status=sendTemplateSMS($phone,$datas,$downbill_takeout_tempid);
        echo $status;
    }else{
        header('Content-type: application/json');
        echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
    }
}
exit;
$phone="13071870889";
$datas=array("20");
$status=sendTemplateSMS($phone,$datas,$downbill_tempid);
?>