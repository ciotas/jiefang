<?php
$jfid="jf10001";
$message="hello ".$jfid;
$connection = new AMQPConnection(array('host' => '114.215.197.166', 'port' => '5672', 'vhost' => '/', 'login' => 'guest', 'password' => 'guest'));
$connection->connect() or die("Cannot connect to the broker!\n");
$channel = new AMQPChannel($connection);
$exchange = new AMQPExchange($channel);
$exchange->setName('jiefang');

$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);//持久化 交换机

$exchange->publish($message, $jfid);//将消息发给特点的队列
$exchange->publish($message."eee",$jfid);
var_dump("[x] Sent $message");

$connection->disconnect();

?>