<?php 
require_once ('startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');

require_once '/var/www/html/upexcel/PHPExcel.php';
require_once '/var/www/html/upexcel/PHPExcel/Writer/Excel2007.php';
require_once '/var/www/html/upexcel/PHPExcel/Writer/Excel5.php';
include_once '/var/www/html/upexcel/PHPExcel/IOFactory.php';

class Outgoing{
	public function getOutgoing($shopid,$theday){
		return QuDian_InterfaceFactory::createInstanceMonitorFourDAL()->getOutgoing($shopid,$theday);
	}
}
$outgoing = new Outgoing();
$title="出库表";
$menu="stock";
$clicktag="outgoing";
$shopid=$_GET['shopid'];
$theday=date("Y-m-d",time());
if(isset($_GET['theday'])){
    $theday=$_GET['theday'];
}
$filename = date("Y-m")."月度出库报告";

$arr=$outgoing->getOutgoing($shopid,$theday);

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
		$k2="名称";
		$k3="仓库总数";
	    $k4="出库合计";
        
		$objExcel->getActiveSheet()->setCellValue('a1', "$k1");
		$objExcel->getActiveSheet()->setCellValue('b1', "$k2");
		$objExcel->getActiveSheet()->setCellValue('c1', "$k3");
		
		$e = "d";
	    for($j=1;$j<=$arr['day'];$j++){ 
	      
	        $num = $e.'1';
		    $objExcel->getActiveSheet()->setCellValue($num,"{$j}号");
		   
		    $e++;
	    }
	    $num = $e.'1';
	    $objExcel->getActiveSheet()->setCellValue($num, "$k4");
		foreach($arr['food'] as $k=>$v) {
			$u1=$i+2;
			/*----------写入内容-------------*/
		
			$objExcel->getActiveSheet()->setCellValue('a'.$u1, $i);
			$objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["name"]);
			$objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["nownum"]);
			
			$li = "d";
			foreach ($v['sale'] as $val){
			    $objExcel->getActiveSheet()->setCellValue($li.$u1, $val);
			    $li++;
				}
			$objExcel->getActiveSheet()->setCellValue($li.$u1, $v["salenum"]);
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