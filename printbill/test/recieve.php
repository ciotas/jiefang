<?php 
$exchangeName = 'jiefang';
$routeKey=$argv[1];
$connection = new AMQPConnection(array('host' => '114.215.197.166', 'port' => '5672', 'vhost' => '/', 'login' => 'guest', 'password' => 'guest'));
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
var_dump('[*] Waiting for messages. To exit press CTRL+C');
while (TRUE) {
	$queue->consume('callback');
	$channel->qos(0, 1);//公平分发
}
$connection->disconnect();

function callback($envelope, $queue) {
	$msg = $envelope->getBody();
	var_dump('[x]' . $envelope->getRoutingKey() . ':' . $msg);
	$queue->nack($envelope->getDeliveryTag());
}
?>