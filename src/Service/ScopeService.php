<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Entity\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Сервис прав доступа
 */
class ScopeService implements ScopeRepositoryInterface
{
    /**
     * Провайдеры прав доступа
     *
     * @var ScopeProviderInterface[]
     */
    private $providers = [];

    /**
     * Добавляет провайдер прав доступа
     *
     * @param ScopeProviderInterface $provider
     */
    public function addProvider(ScopeProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * Возвращает право доступа
     *
     * @param string $id
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($id)
    {
        return new Scope((string) $id);
    }

    /**
     * Формирует список разрешенных прав доступа
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string                 $grant
     * @param ClientEntityInterface  $client
     * @param string|null            $user
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grant, ClientEntityInterface $client, $user = null)
    {
        $client    = (string) $client->getIdentifier();
        $user      = (string) $user;
        $requested = $this->toIds($scopes);
        $final     = [];

        foreach ($this->providers as $provider) {
            $final = array_merge($final, $provider->getScopes($client, $user, $requested));
        }

        return $this->toScopes(array_unique($final));
    }

    /**
     * Возвращает идентификаторы прав доступа
     *
     * @param ScopeEntityInterface[] $scopes
     *
     * @return string[]
     */
    private function toIds(array $scopes): array
    {
        $ids = [];

        foreach ($scopes as $scope) {
            $ids[] = (string) $scope->getIdentifier();
        }

        return $ids;
    }

    /**
     * Возвращает права доступа
     *
     * @param string[] $ids
     *
     * @return ScopeEntityInterface[]
     */
    private function toScopes(array $ids): array
    {
        $scopes = [];

        foreach ($ids as $id) {
            $scopes[] = $this->getScopeEntityByIdentifier($id);
        }

        return $scopes;
    }
}
