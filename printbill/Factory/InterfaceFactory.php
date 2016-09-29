<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/PrintBillDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/PieceListDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/RunnerWorkerDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/CusListWorkerDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/ConsumeListDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/KitchenWorkerDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/PlaceOrderDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/WaitingDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/ToBePointDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/PayMoneyDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/WorkerDAL.php');
require_once (PRINT_DOCUMENT_ROOT.'DAL/WorkerTwoDAL.php');
class PRINT_InterfaceFactory{
	public static function createInstancePrintBillDAL(){
		return new PrintBillDAL(); 
	}
	public static function createInstancePieceListDAL(){
		return new PieceListDAL();
	}
	public static function createInstanceRunnerWorkerDAL(){
		return new RunnerWorkerDAL();
	}
	public static function createInstanceCusListWorkerDAL(){
		return new CusListWorkerDAL();
	}
	public static function createInstanceConsumeListDAL(){
		return new ConsumeListDAL();
	}
	public static function createInstanceKitchenWorkerDAL(){
		return new KitchenWorkerDAL();
	}
	public static function createInstancePlaceOrderDAL(){
		return new PlaceOrderDAL();
	}
	public static function createInstanceWaitingDAL(){
		return new WaitingDAL();
	}
	public static function createInstanceToBePointDAL(){
	    return new ToBePointDAL();
	}
	public static function createInstancePayMoneyDAL(){
	    return new PayMoneyDAL();
	}
	public static function createInstanceDoWorkerDAL(){
		return new WorkerDAL();
	}
	public static function createInstanceDoWorkerTwoDAL(){
		return new WorkerTwoDAL();
	}
}

?>