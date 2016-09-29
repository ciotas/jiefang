<?php 
class User{
	private $_ID = -1;
	private $_uName = null;
	private $_passWd = null;
	private $_nickName = null;
	
	public function __construct() {}
	
	public function getID() {return $this->_ID;}
	public function setID($value) {$this->_ID = intval($value);}
	
	public function getuName() {return $this->_uName;}
	public function setuName($value) {$this->_uName =strval($value);}
	
	public function getpassWd() {return $this->_passWd;}
	public function setpassWd($value) {$this->_passWd =strval($value);}
	
	public function getnickName() {return $this->_nickName;}
	public function setnickName($value) {$this->_nickName =strval($value);}
	
}
?>