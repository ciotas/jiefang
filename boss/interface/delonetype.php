<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneType{
    public function delOneBOssFtype($ftid,$ftcode,$bossid){
        return Boss_InterfaceFactory::createInstanceBossOneDAL()->delOneBOssFtype($ftid,$ftcode,$bossid);
    }
}
$delonetype=new DelOneType();
if(isset($_GET['ftid'])){
    $ftid=$_GET['ftid'];
    $ftcode=$_GET['ftcode'];
    $bossid=$_SESSION['bossid'];
    $delonetype->delOneBOssFtype($ftid, $ftcode, $bossid);
    header("location: ../goodstype.php");
}