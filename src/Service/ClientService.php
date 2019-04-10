<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Repository\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface as OAuthClientServiceInterface;

/**
 * Сервис клиентов
 */
class ClientService implements OAuthClientServiceInterface
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
     * @param string      $id
     * @param string|null $grant
     * @param string|null $secret
     * @param bool        $mustValidateSecret
     *
     * @return ClientEntityInterface
     */
    public function getClientEntity($id, $grant = null, $secret = null, $mustValidateSecret = true)
    {
        // TODO: check grant and secret
        return $this->repository->getClient($id);
    }
}
