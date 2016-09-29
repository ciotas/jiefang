<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SendTXErrorMsg{
    public function sendTixianErrorMsg($msg){
        PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->sendTixianErrorMsg($msg);
    }
}
$sendtxerrormsg=new SendTXErrorMsg();
if(isset($_POST['msg'])){
    $msg=$_POST['msg'];
    $sendtxerrormsg->sendTixianErrorMsg($msg);
}
?>