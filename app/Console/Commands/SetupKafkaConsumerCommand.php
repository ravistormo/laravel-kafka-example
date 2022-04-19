<?php

namespace App\Console\Commands;

use App\Http\Controllers\KafkaConsumerController;
use Illuminate\Console\Command;

class SetupKafkaConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Start:KafkaConsumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command setups a Kafka Consumer which constantly polls to kafka for some topic';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * registered consumer as a command as this is a one time requirement to start the consumer.
         * And we don't want to start the consumer using API call.
         *
         * in dockerfile we can run the command 'php artisan Start:KafkaConsumer' during project build
         * so that once project is deployed consumer will start automatically.
         */
        (new KafkaConsumerController())->StartConsumer();
    }
}
