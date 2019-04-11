<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Entity\Client;
use Free2er\OAuth\Entity\ClientData;

/**
 * Интерфейс сервиса клиентов
 */
interface ClientServiceInterface
{
    /**
     * Возвращает список клиентов
     *
     * @return Client[]
     */
    public function getClients(): array;

    /**
     * Возвращает клиента
     *
     * @param string $id
     *
     * @return Client|null
     */
    public function getClient(string $id): ?Client;

    /**
     * Изменяет сведения о клиенте
     *
     * @param Client     $client
     * @param ClientData $data
     */
    public function update(Client $client, ClientData $data): void;

    /**
     * Блокирует клиента
     *
     * @param Client $client
     */
    public function lock(Client $client): void;

    /**
     * Разблокирует клиента
     *
     * @param Client $client
     */
    public function unlock(Client $client): void;

    /**
     * Удаляет клиента
     *
     * @param Client $client
     */
    public function remove(Client $client): void;
}
