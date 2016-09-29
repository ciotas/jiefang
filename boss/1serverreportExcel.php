<?php 
require_once ('startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');

require_once '/var/www/html/upexcel/PHPExcel.php';
require_once '/var/www/html/upexcel/PHPExcel/Writer/Excel2007.php';
require_once '/var/www/html/upexcel/PHPExcel/Writer/Excel5.php';
include_once '/var/www/html/upexcel/PHPExcel/IOFactory.php';

class Four{
	public function getServerBill($server,$datestart,$dateover,$uid=NULL){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getServerBill($server, $datestart, $dateover,$uid);
	}

}
$Four = new Four();

$shopid=$_GET['shopid'];

isset($_GET['datestart']) ? $datestart=$_GET['datestart']: $datestart = date('Y-m-d', strtotime($_GET['datestart']. ' -1 month'));
isset($_GET['dateover']) ? $dateover=$_GET['dateover']: $dateover = date('Y-m-d');
isset($_GET['man']) ? $man=$_GET['man'] : $man = NULL;
$arr = $Four->getServerBill($shopid, $datestart, $dateover,$man);
$filename = date('m-d',strtotime($datestart))."到".date('m-d',strtotime($dateover))."月度销售记录";


$objExcel = new PHPExcel();
//设置属性 (这段代码无关紧要，其中的内容可以替换为你需要的)
$objExcel->getProperties()->setCreator("andy");
$objExcel->getProperties()->setLastModifiedBy("andy");
$objExcel->getProperties()->setTitle("Office 2003 XLS Test Document");
$objExcel->getProperties()->setSubject("Office 2003 XLS Test Document");
$objExcel->getProperties()->setDescription("Test document for Office 2003 XLS, generated using PHP classes.");
$objExcel->getProperties()->setKeywords("office 2003 openxml php");
$objExcel->getProperties()->setCategory("Test result file");
$objExcel->setActiveSheetIndex(0);

$i=0;
//表头
$k1="序号";
$k2="区域";
$k3="店铺名称";
$k4="店铺地址";
$k5="已缴款";
$k6="负责人";


$objExcel->getActiveSheet()->setCellValue('a1', "$k1");
$objExcel->getActiveSheet()->setCellValue('b1', "$k2");
$objExcel->getActiveSheet()->setCellValue('c1', "$k3");
$objExcel->getActiveSheet()->setCellValue('d1', "$k4");
$e = "e";
foreach ($arr as $key=>$val){
    
    foreach ($val['food'] as $v){
        $num = $e.'1';
        $objExcel->getActiveSheet()->setCellValue($num , $v['foodname']);
        $e++;
        
    }
    break;

}
$num = $e.'1';
$objExcel->getActiveSheet()->setCellValue($num, "$k5");
$e++;
$num = $e."1";
$objExcel->getActiveSheet()->setCellValue($num, "$k6");
$e++;


foreach($arr as $k=>$v) {
    $u1=$i+2;
    /*----------写入内容-------------*/

    $objExcel->getActiveSheet()->setCellValue('a'.$u1, $i+1);
    $objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["dist"]);
    $objExcel->getActiveSheet()->setCellValue('c'.$u1, $k);
    $objExcel->getActiveSheet()->setCellValue('d'.$u1, $v["road"]);
    $li = "e";
    
        	
        foreach ($v['food'] as $val){
            $objExcel->getActiveSheet()->setCellValue($li.$u1, $val['foodnum']);
            $li++;
        }
    
  
    
    $objExcel->getActiveSheet()->setCellValue($li.$u1, $v["money"]);
    $li++;
    $servers =  implode("/", $v['server']);
    $objExcel->getActiveSheet()->setCellValue($li.$u1, $servers);
    $li++;

    $i++;
}

// 高置列的宽度
$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$objExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BPersonal cash register&RPrinted on &D');
$objExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objExcel->getProperties()->getTitle() . '&RPage &P of &N');

// 设置页方向和规模
$objExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objExcel->setActiveSheetIndex(0);
$timestamp = time();
if($ex == '2007') { //导出excel2007文档
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="links_out'.$timestamp.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
} else {  //导出excel2003文档
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arr['shopname'].$filename.'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

?>