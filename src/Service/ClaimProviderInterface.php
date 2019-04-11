<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

/**
 * Интерфейс провайдера параметров JWT
 */
interface ClaimProviderInterface
{
    /**
     * Возвращает параметры JWT
     *
     * @param string $client
     * @param string $user
     *
     * @return string[]
     */
    public function getClaims(string $client, string $user): array;
}
