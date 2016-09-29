<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'DAL/BillDAL.php');
class Bill_InterfaceFactory{
	public static function createInstanceBillDAL(){
		return  new BillDAL();
	}
}

?>