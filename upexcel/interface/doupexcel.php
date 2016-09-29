<?php 
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/upexcel/global.php');
require_once ('/var/www/html/upexcel/PHPExcel.php');
require_once (EXCEL_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel2007.php');
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Writer/Excel5.php');//用于输出.xls的
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Reader/Excel5.php');//用于输出.xls的
require_once (EXCEL_DOCUMENT_ROOT.'PHPExcel/Reader/Excel2007.php');//用于输出.xls的
class DoUpExcel{
    public function xlsDataToDb($shopid,$foodarr){
        return EXCEL_InterfaceFactory::createInstanceUpExcelDAL()->xlsDataToDb($shopid, $foodarr);
    }
    public function object_to_array($obj){
        return EXCEL_InterfaceFactory::createInstanceUpExcelDAL()->object_to_array($obj);
    }
    public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$doupexcel=new DoUpExcel();
if(isset($_FILES['foodexcel'])){
	$foodexcel=$_FILES['foodexcel'];
	$foodexcel_name=$foodexcel['name'];
	$foodexcel_tmp=$foodexcel['tmp_name'];
	$shopid=$_POST['shopid'];
// 	echo $foodexcel_name;exit;
	$objPHPExcel = new PHPExcel();//创建一个excel

    //自己设置的上传文件存放路径
    $filePath = EXCEL_DOCUMENT_ROOT.'data/food/';
    $str = "";
    //注意设置时区
    $time=date("y-m-d-H-i-s");//去当前上传的时间
    
    //获取上传文件的扩展名
    $extend=strrchr ($foodexcel_name,'.');
    if($extend!=".xls" && $extend!=".xlsx" && $extend!=".et"&& $extend!=".ett"&& $extend!=".xlt"){
        $formaterror=array("status"=>"error","code"=>"format_error") ;
        header("location: ".$dianjiaurl."houtai/shop/upfoodxls.php?data=".base64_encode($formaterror));
        exit;
    }
	//上传后的文件名
    $name=$time.$extend;
    $uploadfile=$filePath.$name;//上传后的文件名地址
    //move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
//     var_dump($uploadfile);exit;
    $result=move_uploaded_file($foodexcel_tmp,$uploadfile);//假如上传到当前目录下
    if($result) //如果上传文件成功，就执行导入excel操作
    {
        $objReader = PHPExcel_IOFactory::createReader('excel2007');//use excel2007 for 2007 format
//     $objReaderarr=$doupexcel->object_to_array($objReader);
//     var_dump($objReader);exit;
        $objPHPExcel = $objReader->load($uploadfile);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();           //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
        $strs=array();
        for ($row = 1;$row <= $highestRow;$row++)
        {
           //注意highestColumnIndex的列数索引从0开始
	        for ($col = 0;$col < $highestColumnIndex;$col++)//列
	        {
	            $cell=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
	            if($cell instanceof PHPExcel_RichText){     //富文本转换字符串
	                $cell = $cell->__toString();
	            }
	           $strs[$row-1][$col]=$cell;
	        }
        }
//    		print_r($strs);exit;
       $resultstaus=$doupexcel->xlsDataToDb($shopid, $strs);
//        print_r($resultstaus);exit;
       @unlink($uploadfile);//删除临时文件
       ob_start();
       $doupexcel->syncData($shopid);
       header("location: ".$dianjiaurl."houtai/shop/upfoodxls.php?data=".base64_encode(json_encode($resultstaus)));
       ob_end_flush();
	}

}
?>