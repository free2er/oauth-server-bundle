<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Command;

use Free2er\OAuth\Service\ClientServiceInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда разблокировки клиента
 */
class ClientUnlockCommand extends AbstractCommand
{
    /**
     * Сервис клиентов
     *
     * @var ClientServiceInterface
     */
    private $service;

    /**
     * Конструктор
     *
     * @param ClientServiceInterface $service
     */
    public function __construct(ClientServiceInterface $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Устанавливает параметры команды
     */
    protected function configure()
    {
        $this->setName('oauth:client:unlock');
        $this->setDescription('Unlock client');
        $this->addArgument('client', InputArgument::REQUIRED, 'Client ID');
    }

    /**
     * Выполняет команду
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$client = $this->service->getClient($input->getArgument('client'))) {
            return $this->fail('Client not found', $input, $output);
        }

        $this->service->unlock($client);

        return $this->ok('Client successfully unlocked', $input, $output);
    }
}
