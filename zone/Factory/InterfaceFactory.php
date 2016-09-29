<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'DAL/ZoneDAL.php');
require_once (Zone_DOCUMENT_ROOT.'DAL/TabDAL.php');
class Zone_InterfaceFactory{
	public static function createInstanceZoneDAL(){
		return new ZoneDAL();
	}
	public static function createInstanceTabDAL(){
		return new TabDAL();
	}
}
?>