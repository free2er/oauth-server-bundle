<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Entity\Client;
use Free2er\OAuth\Entity\ClientData;
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
     * Возвращает список клиентов
     *
     * @return Client[]
     */
    public function getClients(): array
    {
        return $this->repository->getClients();
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
     * Изменяет сведения о клиенте
     *
     * @param Client     $client
     * @param ClientData $data
     */
    public function update(Client $client, ClientData $data): void
    {
        $data->apply($client);
        $this->repository->save($client);
    }

    /**
     * Блокирует клиента
     *
     * @param Client $client
     */
    public function lock(Client $client): void
    {
        $client->lock();
        $this->repository->save($client);
    }

    /**
     * Разблокирует клиента
     *
     * @param Client $client
     */
    public function unlock(Client $client): void
    {
        $client->unlock();
        $this->repository->save($client);
    }

    /**
     * Удаляет клиента
     *
     * @param Client $client
     */
    public function remove(Client $client): void
    {
        $this->repository->remove($client);
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
