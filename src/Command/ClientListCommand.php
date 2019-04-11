<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Command;

use Carbon\Carbon;
use Free2er\OAuth\Entity\Client;
use Free2er\OAuth\Service\ClientServiceInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда просмотра списка клиентов
 */
class ClientListCommand extends AbstractCommand
{
    /**
     * Сервис клиентов
     *
     * @var ClientServiceInterface
     */
    private $service;

    /**
     * Генератор представлений
     *
     * @var callable
     */
    private $renderer;

    /**
     * Заголовки таблицы
     *
     * @var string[]
     */
    private $headers = [
        'client',
        'secure',
        'redirect uri',
        'grants',
        'scopes',
        'created at',
        'locked at',
    ];

    /**
     * Конструктор
     *
     * @param ClientServiceInterface $service
     * @param callable               $renderer
     * @param string[]|null          $headers
     */
    public function __construct(ClientServiceInterface $service, callable $renderer = null, array $headers = null)
    {
        parent::__construct();

        $this->service  = $service;
        $this->renderer = $renderer ?: function (Client $client) {
            return [
                $client->getIdentifier(),
                $client->hasSecret() ? 'Yes' : 'No',
                $client->getRedirectUri() ?: '-',
                implode(', ', $client->getGrants()) ?: '-',
                implode(', ', $client->getScopes()) ?: '-',
                Carbon::instance($client->getCreatedAt())->toFormattedDateString(),
                $client->isLocked() ? Carbon::instance($client->getLockedAt())->toFormattedDateString() : '-',
            ];
        };

        if ($headers) {
            $this->headers = $headers;
        }
    }

    /**
     * Устанавливает параметры команды
     */
    protected function configure()
    {
        $this->setName('oauth:client:list');
        $this->setDescription('List clients');
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
        return $this->table($this->service->getClients(), $this->headers, $this->renderer, $input, $output);
    }
}
