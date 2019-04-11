<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Entity\Client;

/**
 * Интерфейс сервиса клиентов
 */
interface ClientServiceInterface
{
    /**
     * Возвращает клиента
     *
     * @param string $id
     *
     * @return Client|null
     */
    public function getClient(string $id): ?Client;
}
