<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorOneDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorTwoDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorThreeDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorFourDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorFiveDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorSixDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/PayDAL.php');
require_once (QuDian_DOCUMENT_ROOT.'DAL/MonitorCusDAL.php');
class QuDian_InterfaceFactory{
	public static function createInstanceMonitorOneDAL(){
		return new MonitorOneDAL();
	}
	public static function createInstanceMonitorTwoDAL(){
		return new MonitorTwoDAL();
	}
	public static function createInstanceMonitorThreeDAL(){
		return new MonitorThreeDAL();
	}
	public static function createInstanceMonitorFourDAL(){
		return new MonitorFourDAL();
	}
	public static function createInstanceMonitorFiveDAL(){
		return new MonitorFiveDAL();
	}
	public static function createInstanceMonitorSixDAL(){
		return new MonitorSixDAL();
	}
	public static function createInstancePayDAL(){
		return new PayDAL();
	}
	public static function createInstanceMonitorCusDAL(){
		return new MonitorCusDAL();
	}
}
?>