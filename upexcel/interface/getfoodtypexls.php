<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/upexcel/global.php');
require_once (EXCEL_DOCUMENT_ROOT.'/Factory/InterfaceFactory.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel2007.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel5.php');//用于输出.xls的
class GetFoodTypeXls{
    public function getFoodTypeList($shopid){
        return EXCEL_InterfaceFactory::createInstanceUpExcelDAL()->getFoodTypeList($shopid);
    }
}

$getfoodtypexls=new GetFoodTypeXls();
if(isset($_POST['shopid'])){
    $shopid=$_POST['shopid'];
    $shopname=$_POST['shopname'];
    $objPHPExcel = new PHPExcel();//创建一个excel
    $foodtypearr=$getfoodtypexls->getFoodTypeList($shopid);
    $objPHPExcel->getActiveSheet()->setCellValue('A'."1","商品类别");
    $objPHPExcel->getActiveSheet()->setCellValue('B'."1","类别ID");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28);
    foreach ($foodtypearr as $key=>$val){
        $pos=strval($key+2);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$pos,$val['foodtypename']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$pos,$val['ftid']);
    }
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);//将excel数据对象实例化为excel文件对象
    $pfilename=EXCEL_DOCUMENT_ROOT."data/type/".$shopname."_商品类别.xls";
//     echo $pfilename;exit;
    $objWriter->save($pfilename);
    echo $dianjiaurl."upexcel/data/type/".$shopname."_商品类别.xls";
}
exit;
$foodtypearr=$getfoodtypexls->getFoodTypeList("547430f016c10932708b4624");
print_r($foodtypearr);exit;
?>