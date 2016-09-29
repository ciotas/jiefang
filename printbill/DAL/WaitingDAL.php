<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'IDAL/IWaitingDAL.php');
// require_once ('/var/www/html/DALFactory.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/DALFactory.php');
class WaitingDAL implements IWaitingDAL{
	/* (non-PHPdoc)
	 * @see IWaitingDAL::PrintWaitingData()
	 */
	public function PrintWaitingData($type, $json) {
		// TODO Auto-generated method stub
		$waitingkeyarr=json_decode($json,true);
		if(empty($waitingkeyarr)){return array();}
		$waitingarr=$waitingkeyarr[$type];
		$arr=array();
		foreach ($waitingarr as $key=>$val){
			$arr[]=array(
					"printid"=>mt_rand(1000, 9999).mt_rand(1000, 9999),
					"deviceno"=>$val['deviceno'],
					"devicekey"=>$val['devicekey'],
					"outputtype"=>"waiting",
					"msg"=>$this->createContentHtml($val)
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWaitingDAL::createContentHtml()
	 */
	public function createContentHtml($arr) {
		// TODO Auto-generated method stub
	    global $iosqrc;
	    global $selfad;
		$orderInfo='';
		$orderInfo.='<BR>';
		$orderInfo .= '<CB>排队叫号单</CB>';
		$orderInfo .= '<CB>'.$arr['shopname'].'</CB><BR>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo .= '<B>您的排队单号为'.$arr['queuesortno'].'</B><BR>';
		$orderInfo .= '!0a1d@!<BR>';
		$orderInfo .= $arr['waitstr'];
		$orderInfo .='打印时间：'.date('Y-m-d H:i:s',$arr['timestamp']).'<BR>';
		$orderInfo .='---------------------------------------------<BR>';
		$orderInfo .= $arr['jhcontent'];
		$orderInfo .= $selfad.'<BR>';
		$orderInfo .= '<QR>'.$iosqrc.'</QR>';
		$orderInfo .='<BR><BR>';
		$selfMessage = array(
				'sn'=>$arr['deviceno'],
				'printContent'=>$orderInfo,
// 				'apitype'=>'php',
				'key'=>$arr['devicekey'],
				'times'=>1
		);
		return $selfMessage;
	}
	/* (non-PHPdoc)
	 * @see IWaitingDAL::getStableLenStr()
	 */
	public function getStableLenStr($str, $len) {
		// TODO Auto-generated method stub
		$strlength=(strlen($str) + mb_strlen($str,'UTF8'))/2;
		if($strlength<$len){
			return $str.str_repeat(" ",($len-$strlength+1));
		}else{
			return $str;
		}
	}
	
}
?>