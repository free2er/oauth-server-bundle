<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Command;

use Free2er\OAuth\Service\ClientServiceInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда удаления клиента
 */
class ClientRemoveCommand extends AbstractCommand
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
        $this->setName('oauth:client:remove');
        $this->setDescription('Remove client');
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
        if ($client = $this->service->getClient($input->getArgument('client'))) {
            $this->service->remove($client);
        }

        return $this->ok('Client successfully removed', $input, $output);
    }
}
