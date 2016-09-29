<?php 
error_reporting(E_ERROR);
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class RunnerWorker{
	public function rollingCurl($urls, $delay){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->rolling_curl($urls, $delay);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function getRePrintContent($nullarr,$urls){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getRePrintContent($nullarr, $urls);
	}
	public function updateBillStatus($billid,$billstatus){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->updateBillStatus($billid, $billstatus);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
}
$runnerworker=new RunnerWorker();
$exchangeName = 'jiefang';
$routeKey=$argv[1];
$connection = new AMQPConnection(array('host' => '115.29.237.189', 'port' => '5672', 'vhost' => '/', 'login' => 'guest', 'password' => 'guest'));
$connection->connect() or die("Cannot connect to the broker!\n");
$channel = new AMQPChannel($connection);
$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->declareExchange();
$queue = new AMQPQueue($channel);
$queue->setFlags(AMQP_DURABLE);
$queue->setName("queue_".$routeKey);
$queue->declareQueue();

$queue->bind($exchangeName, $routeKey);
// var_dump('[*] Waiting for messages. To exit press CTRL+C');
while (TRUE) {
	$queue->consume('callback');
	$channel->qos(0, 1);//公平分发
}
$connection->disconnect();
function callback($envelope, $queue) {
	global $runnerworker;
	$json = $envelope->getbody();
	if(is_numeric($json)){
		echo $json;
		sleep(intval($json));
	}else{
		$billarr=json_decode($json,true);//$temparr
// 		print_r($billarr);exit;
		$billid=$billarr['billid'];
		$urls=$runnerworker->getUrlsArr($json);
// 		$queue->nack($envelope->getDeliveryTag());
// 		print_r($urls);exit;
// 		$nullarr= $runnerworker->rollingCurl($urls, 5000);	
		$nullarr=$runnerworker->sendFreeMessage($urls);
// 		print_r($nullarr);exit;
		$reprinturls=$runnerworker->getRePrintContent($nullarr, $urls);//第二次要打印的内容
// 		print_r($reprinturls);exit;
		$nullarr1=$runnerworker->sendFreeMessage($reprinturls);//第二次打印
		print_r($nullarr1);
		$reprinturls2=$runnerworker->getRePrintContent($nullarr1, $urls);//第三次要打印的内容
		$nullarr2=$runnerworker->sendFreeMessage($reprinturls2);//第三次打印
		print_r($nullarr2);
		$reprinturls3=$runnerworker->getRePrintContent($nullarr2, $urls);//第四次要打印的内容
		$nullarr3=$runnerworker->sendFreeMessage($reprinturls3);//第三次打印
// 		print_r($nullarr3);exit;
		if(!empty($nullarr3)){
			foreach ($nullarr3 as $key=>$status){
				$temparr=explode('|', $key);
				$devicearr[]=array("outputtype"=>$temparr[1],"deviceno"=>$temparr[2],"devicekey"=>$temparr[3],"printstatus"=>$status);
			}
// 			print_r($devicearr);exit;
// 			$billstatus=implode("||", $devicearr);//记录哪个单失败
// 			echo $billstatus;exit;
			//发送信息给商家
			$runnerworker->updateBillStatus($billid, json_encode($devicearr));//表示下单失败,记录
			echo "Send Message\n";
		}else{
			$runnerworker->updateBillStatus($billid, "YES");//修改下单billstatus状态,"YES"表示下单成功
			echo "All Success\n";
		}
	}
	echo "done!\n";
	$queue->nack($envelope->getDeliveryTag());
}
?>