<?php 
require_once ('/var/www/html/upexcel/global.php');
require_once (EXCEL_DOCUMENT_ROOT.'DAL/UpExcelDAL.php');
class EXCEL_InterfaceFactory{
	public static function createInstanceUpExcelDAL(){
		return new UpExcelDAL();
	}
}
?>