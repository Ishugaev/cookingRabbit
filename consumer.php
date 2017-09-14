<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('RabbitQueue', false, false, false, false);

echo " [x] Waiting for Greetings!'\n";

$callback = function (AMQPMessage $msg) {
    echo " [x] Received ", $msg->body, "\n";
};

$channel->basic_consume('RabbitQueue', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}


