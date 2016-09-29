<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/upexcel/global.php');
require_once (EXCEL_DOCUMENT_ROOT.'/Factory/InterfaceFactory.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel2007.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel5.php');//用于输出.xls的
class GetPrinterXls{
    public function getPrinterList($shopid){
        return EXCEL_InterfaceFactory::createInstanceUpExcelDAL()->getPrinterList($shopid);
    }
}
$getprinterxls=new GetPrinterXls();
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $shopname=$_POST['shopname'];
    $objPHPExcel = new PHPExcel();//创建一个excel
    $printerarr=$getprinterxls->getPrinterList($shopid);
    $objPHPExcel->getActiveSheet()->setCellValue('A'."1","打印机");
    $objPHPExcel->getActiveSheet()->setCellValue('B'."1","打印机ID");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    foreach ($printerarr as $key=>$val){
        $pos=strval($key+2);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$pos,$val['remark']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$pos,$val['printerid']);
    }
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);//将excel数据对象实例化为excel文件对象
    $pfilename=EXCEL_DOCUMENT_ROOT."data/printer/".$shopname."_打印机.xls";
//     echo $pfilename;exit;
    $objWriter->save($pfilename);
    echo $dianjiaurl."upexcel/data/printer/".$shopname."_打印机.xls";
}
?>