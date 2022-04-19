<?php

namespace App\Http\Controllers;

use Exception;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class KafkaConsumerController extends Controller
{
    /**
     * @var Logger
     */
    private Logger $logger;

    public function __construct()
    {
        //creating a log file to log the consumed messaged
        $fileName = "KafkaConsumer-" . date('Y-m-d') . ".log";
        $this->logger = new Logger($fileName);
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/' . $fileName)));
    }

    /**
     * Consumer to listen to a topic
     */
    public function StartConsumer()
    {
        $this->logger->info("StartConsumer | Entered!");

        ini_set('max_execution_time', 0);
        $consumer = Kafka::createConsumer([env('KAFKA_TOPIC_ID')], env('KAFKA_CONSUMER_GROUP_ID'), env('KAFKA_BROKERS'))

            //setting the configurations required to connect to kafka
            ->withOptions([
                'compression.codec' => env('KAFKA_COMPRESSION_TYPE'),
                'sasl.username' => env('KAFKA_SASL_USERNAME'),
                'sasl.password' => env('KAFKA_SASL_PASSWORD'),
                'security.protocol' => env('KAFKA_SECURITY_PROTOCOL'),
                'sasl.mechanism' => env('KAFKA_SASL_MECHANISM')
            ])

            //assigning handler to process a message when consumer gets one
            ->withHandler(function (KafkaConsumerMessage $message) {
                call_user_func([$this, 'ConsumerHandler'], $message);
            })

            //building the consumer which will consume messages
            ->build();

        try {
            $this->logger->info("StartConsumer | Entered Try!");

            //this is an infinite loop where consumer is constantly asking kafka whether a message is there to consume
            $consumer->consume();

            $this->logger->info("StartConsumer | Exiting Try!");
        } catch (\Carbon\Exceptions\Exception | Exception $e) {
            $this->logger->critical("StartConsumer | Exception: " . $e->getMessage());
        }
    }

    /**
     * @param KafkaConsumerMessage $message
     * Consumer will act on message using a Handler. Here we are simply logging the message content in /storage/logs folder
     */
    public function ConsumerHandler(KafkaConsumerMessage $message)
    {
        $this->logger->info("ConsumerHandler | Entered!------------------------");
        $messageData = [
            "getBody" => $message->getBody(),
            "getHeaders" => $message->getHeaders(),
            "getKey" => $message->getKey(),
            "getOffset" => $message->getOffset(),
            "getPartition" => $message->getPartition(),
            "getTimestamp" => $message->getTimestamp(),
            "getTopicName" => $message->getTopicName()
        ];
        $this->logger->info("ConsumerHandler | New Message Data:", $messageData);
        $this->logger->info("ConsumerHandler | Exiting!--------------------------");
    }
}
