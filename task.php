<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$conn = new  AMQPStreamConnection('localhost', 5672,  'guest', 'guest');
$channel = $conn->channel();
$channel->queue_declare('task_queue', false, true, false, false);

$data = implode(' ', array_slice($argv, 1));
if (empty($data)) {
    $data = 'Cooking a rabbit';
}

$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($msg, '', 'task_queue');

echo " [x] Sent ", $data, "\n";