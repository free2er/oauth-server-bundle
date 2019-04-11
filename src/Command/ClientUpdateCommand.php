<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Command;

use Free2er\OAuth\Entity\Client;
use Free2er\OAuth\Entity\ClientData;
use Free2er\OAuth\Service\ClientServiceInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда изменения клиента
 */
class ClientUpdateCommand extends AbstractCommand
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
        $this->setName('oauth:client:update');
        $this->setDescription('Update client data');
        $this->addArgument('client', InputArgument::REQUIRED, 'Client ID');
        $this->addOption('secret', 'p', InputOption::VALUE_OPTIONAL, 'Secret', '');
        $this->addOption('cost', 'c', InputOption::VALUE_REQUIRED, 'Secret cost');
        $this->addOption('grant', 'g', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Grant type');
        $this->addOption('scope', 's', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Scope');
        $this->addOption('revoke', 'r', InputOption::VALUE_NONE, 'Revoke grant type or scope');
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
        $id   = $input->getArgument('client');
        $data = new ClientData($input->getOptions());

        $client = $this->service->getClient($id) ?: new Client($id);
        $this->service->update($client, $data);

        return $this->ok('Client successfully updated', $input, $output);
    }
}
