<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class UpArticle{
	public function upArticleData($shopid, $htmlData){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->upArticleData($shopid, $htmlData);
	}
}
$uparticle=new UpArticle();
$htmlData = '';
if (!empty($_POST['content1'])) {
	$shopid=$_SESSION['shopid'];
	if (get_magic_quotes_gpc()) {
		$htmlData = stripslashes($_POST['content1']);
	} else {
		$htmlData = $_POST['content1'];
	}
	$uparticle->upArticleData($shopid, $htmlData);
	header("location: ../article.php");
}
?>