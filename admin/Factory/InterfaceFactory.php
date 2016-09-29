<?php 
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'DAL/AdminOneDAL.php');
class Admin_InterfaceFactory{
	public static function createInstanceAdminOneDAL(){
		return new AdminOneDAL();
	}
}
?>