<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('task_queue', false, true, false, false);

echo " [x] Waiting for Rabbits!'\n";

$callback = function (AMQPMessage $msg) {
    echo " [x] Received ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.') * 10);
    echo " [x] Done \n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}


