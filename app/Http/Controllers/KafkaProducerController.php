<?php

namespace App\Http\Controllers;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaProducerController extends Controller
{
    /**
     * Producer to send a message to topic
     */
    public function ProduceSingleMessage()
    {
        $producer = Kafka::publishOn(env('KAFKA_TOPIC_ID'), env('KAFKA_BROKERS'))

            //setting the configurations required to connect to kafka
            ->withConfigOptions([
                'compression.codec' => env('KAFKA_COMPRESSION_TYPE'),
                'sasl.username' => env('KAFKA_SASL_USERNAME'),
                'sasl.password' => env('KAFKA_SASL_PASSWORD'),
                'security.protocol' => env('KAFKA_SECURITY_PROTOCOL'),
                'sasl.mechanism' => env('KAFKA_SASL_MECHANISM')
            ])

            //a message sample that will be sent to topic
            ->withMessage(
                new Message(
                    headers: ['NewHeader' => 'NewHeaderValue'],
                    body: ['Name' => 'Ravishankar Singh', 'Message' => 'Hi There!'],
                    key: '123123'
                )
            );

        $producer->send();
    }
}
