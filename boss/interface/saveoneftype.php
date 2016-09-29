<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveOneFtype{
    public function saveBossFtype($inputarr){
        Boss_InterfaceFactory::createInstanceBossOneDAL()->saveBossFtype($inputarr);
    }
    public function updateBossFtype($ftid,$inputarr){
        Boss_InterfaceFactory::createInstanceBossOneDAL()->updateBossFtype($ftid, $inputarr);
    }
}
$saveoneftype=new SaveOneFtype();
if(isset($_POST['ftid'])){
    $bossid=$_SESSION['bossid'];
    $ftid=$_POST['ftid'];
    $ftname=$_POST['ftname'];
    $ftcode=$_POST['ftcode'];
    $sortno=$_POST['sortno'];
    $inputarr=array(
        "bossid"=>$bossid,
        "ftname"=>$ftname,
        "ftcode"=>$ftcode,
        "sortno"=>$sortno,
    );
    if(!empty($ftid)){
        $saveoneftype->updateBossFtype($ftid, $inputarr);
    }else{
        $saveoneftype->saveBossFtype($inputarr);
    }
    header("location: ../goodstype.php");
}
?>