<?php
declare(strict_types=1);

/**
 * File:ConsumeCommand.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2019 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\RabbitMQ\Direct;

use ErrorException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class ConsumeCommand
 * @package LizardMedia\RabbitMQ\Direct
 */
class ConsumeCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('direct:consume')
            ->addArgument('bindingKey', InputArgument::REQUIRED);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws ErrorException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exchangeName = 'direct_messaging';
        $bindingKey = $input->getArgument('bindingKey');

        $output->writeln('Waiting for messages...');

        $connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_MAIN_PORT'),
            getenv('RABBITMQ_USER'),
            getenv('RABBITMQ_PASS')
        );
        $channel = $connection->channel();

        $channel->exchange_declare($exchangeName, 'direct', false, true, true);
        list($queueName, ,) = $channel->queue_declare('', false, true, false, true);

        $channel->queue_bind($queueName, $exchangeName, $bindingKey);

        $callback = function ($msg) use ($output) {
            $output->writeln("I got: {$msg->body}. Waiting for new messages...");
        };

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
