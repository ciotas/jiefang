<?php 
// 手动生存模板案例
date_default_timezone_set("PRC");
require_once ('/var/www/html/upexcel/global.php');
require_once (EXCEL_DOCUMENT_ROOT.'/Factory/InterfaceFactory.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/IOFactory.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel2007.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel5.php');//用于输出.xls的
class GetFoodXls{
    public function getFoodInfo($shopid){
        return EXCEL_InterfaceFactory::createInstanceUpExcelDAL()->getFoodInfo($shopid);
    }
}

$exceltext=new GetFoodXls();
$objPHPExcel = new PHPExcel();//创建一个excel

$shopid="547430f016c10932708b4624";//案例shopid
$foodarr=$exceltext->getFoodInfo($shopid);
// print_r($foodarr);exit;
$objPHPExcel->getActiveSheet()->setCellValue('A'."1","类别ID");
$objPHPExcel->getActiveSheet()->setCellValue('B'."1","商品名称");
$objPHPExcel->getActiveSheet()->setCellValue('C'."1","档口ID");
$objPHPExcel->getActiveSheet()->setCellValue('D'."1","商品编码");
$objPHPExcel->getActiveSheet()->setCellValue('E'."1","单价");
$objPHPExcel->getActiveSheet()->setCellValue('F'."1","计量单位");
$objPHPExcel->getActiveSheet()->setCellValue('G'."1","点菜单位");
$objPHPExcel->getActiveSheet()->setCellValue('H'."1","规格与口味");
$objPHPExcel->getActiveSheet()->setCellValue('I'."1","优惠");
$objPHPExcel->getActiveSheet()->setCellValue('J'."1","称重");
$objPHPExcel->getActiveSheet()->setCellValue('K'."1","hot");
$objPHPExcel->getActiveSheet()->setCellValue('L'."1","套餐");
$objPHPExcel->getActiveSheet()->setCellValue('M'."1","简介");

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(28);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
//水平居中
// $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

foreach ($foodarr as $key=>$val){
    $pos=strval($key+2);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$pos,$val['foodtypeid']);//excel的第A列第i行写入$list[0]
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$pos,$val['foodname']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$pos,$val['zoneid']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$pos,$val['foodcode']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$pos,$val['foodprice']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$pos,$val['foodunit']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$pos,$val['orderunit']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$pos,$val['foodcooktype']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$pos,$val['fooddisaccount']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$pos,$val['isweight']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$pos,$val['ishot']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$pos,$val['ispack']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$pos,$val['foodintro']);
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);//将excel数据对象实例化为excel文件对象
$pfilename=EXCEL_DOCUMENT_ROOT."data/美食模板.xlsx";
$objWriter->save($pfilename);//导出并写入当前目录，按照$excel_name命名
echo "OK!已导出";
?>