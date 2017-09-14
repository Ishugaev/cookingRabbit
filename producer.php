<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('RabbitQueue', false, false, false, false);

$message = new AMQPMessage('Hello Rabbit!');
$channel->basic_publish($message, '', 'rabbit_greeting');

echo " [x] Sent 'Hello Rabbit!'\n";

$channel->close();
$connection->close();