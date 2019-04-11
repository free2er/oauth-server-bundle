<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Entity\Client;
use Free2er\OAuth\Repository\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface as OAuthClientServiceInterface;

/**
 * Сервис клиентов
 */
class ClientService implements OAuthClientServiceInterface, ClientServiceInterface, ScopeProviderInterface
{
    /**
     * Репозиторий клиентов
     *
     * @var ClientRepositoryInterface
     */
    private $repository;

    /**
     * Конструктор
     *
     * @param ClientRepositoryInterface $repository
     */
    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Возвращает клиента
     *
     * @param string $id
     *
     * @return Client|null
     */
    public function getClient(string $id): ?Client
    {
        return $this->repository->getClient($id);
    }

    /**
     * Возвращает клиента
     *
     * @param string      $id
     * @param string|null $grant
     * @param string|null $secret
     * @param bool        $mustValidateSecret
     *
     * @return ClientEntityInterface|null
     */
    public function getClientEntity($id, $grant = null, $secret = null, $mustValidateSecret = true)
    {
        if (!$client = $this->getClient((string) $id)) {
            return null;
        }

        if ($client->isLocked()) {
            return null;
        }

        if ($grant && !$client->hasGrant((string) $grant)) {
            return null;
        }

        if ($mustValidateSecret && !$client->verifySecret((string) $secret)) {
            return null;
        }

        return $client;
    }

    /**
     * Возвращает права доступа
     *
     * @param string   $client
     * @param string   $user
     * @param string[] $requestedScopes
     *
     * @return string[]
     */
    public function getScopes(string $client, string $user, array $requestedScopes): array
    {
        if (!$client = $this->getClient($client)) {
            return [];
        }

        return $client->getScopes();
    }
}
