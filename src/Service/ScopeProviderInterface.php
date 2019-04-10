<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

/**
 * Интерфейс провайдера прав доступа
 */
interface ScopeProviderInterface
{
    /**
     * Возвращает права доступа
     *
     * @param string   $client
     * @param string   $user
     * @param string[] $requestedScopes
     *
     * @return string[]
     */
    public function getScopes(string $client, string $user, array $requestedScopes): array;
}
