<?php
declare(strict_types=1);

/**
 * File:PublishCommand.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2019 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\RabbitMQ\Topic;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PublishCommand
 * @package LizardMedia\RabbitMQ\Topic
 */
class PublishCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('topic:publish')
            ->addArgument('message', InputArgument::REQUIRED)
            ->addArgument('bindingKey', InputArgument::REQUIRED);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exchangeName = 'topic_messaging';
        $message = $input->getArgument('message');
        $bindingKey = $input->getArgument('bindingKey');

        $connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_MAIN_PORT'),
            getenv('RABBITMQ_USER'),
            getenv('RABBITMQ_PASS')
        );
        $channel = $connection->channel();

        $channel->exchange_declare($exchangeName, 'topic', false, true, true);

        $msg = new AMQPMessage($message);

        $channel->basic_publish($msg, $exchangeName, $bindingKey);

        $channel->close();
        $connection->close();

        $output->writeln("Message ({$message}) sent using binding key: {$bindingKey}");
    }
}
