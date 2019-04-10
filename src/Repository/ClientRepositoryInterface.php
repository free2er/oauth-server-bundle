<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use Free2er\OAuth\Entity\Client;

/**
 * Интерфейс репозитория клиентов
 */
interface ClientRepositoryInterface
{
    /**
     * Возвращает клиента
     *
     * @param string $id
     *
     * @return Client|null
     */
    public function getClient(string $id): ?Client;

    /**
     * Сохраняет клиента
     *
     * @param Client $client
     */
    public function save(Client $client): void;

    /**
     * Удаляет клиента
     *
     * @param Client $client
     */
    public function remove(Client $client): void;
}
