<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Carbon\Carbon;
use Free2er\OAuth\Entity\AccessToken;
use Free2er\OAuth\Repository\AccessTokenRepositoryInterface;
use Lcobucci\JWT\Signer;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as OAuthAccessTokenServiceInterface;
use Throwable;

/**
 * Сервис ключей доступа
 */
class AccessTokenService implements OAuthAccessTokenServiceInterface
{
    /**
     * Шифратор JWT
     *
     * @var Signer
     */
    private $signer;

    /**
     * Репозиторий ключей доступа
     *
     * @var AccessTokenRepositoryInterface
     */
    private $repository;

    /**
     * Провайдеры параметров JWT
     *
     * @var ClaimProviderInterface[]
     */
    private $providers = [];

    /**
     * Конструктор
     *
     * @param Signer                         $signer
     * @param AccessTokenRepositoryInterface $repository
     */
    public function __construct(Signer $signer, AccessTokenRepositoryInterface $repository)
    {
        $this->signer     = $signer;
        $this->repository = $repository;
    }

    /**
     * Добавляет провайдер параметров JWT
     *
     * @param ClaimProviderInterface $provider
     */
    public function addProvider(ClaimProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * Создает ключ доступа
     *
     * @param ClientEntityInterface  $client
     * @param ScopeEntityInterface[] $scopes
     * @param string|null            $user
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $client, array $scopes, $user = null)
    {
        $client = (string) $client->getIdentifier();
        $user   = (string) $user;
        $claims = [];

        foreach ($this->providers as $provider) {
            $claims = array_merge($claims, $provider->getClaims($client, $user));
        }

        return new AccessToken($this->signer, $claims);
    }

    /**
     * Сохраняет ключ доступа
     *
     * @param AccessTokenEntityInterface $token
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $token)
    {
        if (!$token instanceof AccessToken) {
            return;
        }

        try {
            $this->repository->save($token);
        } catch (Throwable $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->removeExpired();
    }

    /**
     * Отзывает ключ доступа
     *
     * @param string $id
     */
    public function revokeAccessToken($id)
    {
        if ($token = $this->repository->getToken((string) $id)) {
            $this->repository->remove($token);
        }
    }

    /**
     * Проверяет отзыв ключа доступа
     *
     * @param string $id
     *
     * @return bool
     */
    public function isAccessTokenRevoked($id)
    {
        return $this->repository->getToken((string) $id) === null;
    }

    /**
     * Удаляет просроченные ключи доступа
     */
    private function removeExpired(): void
    {
        foreach ($this->repository->getExpired(Carbon::now()) as $token) {
            $this->repository->remove($token);
        }
    }
}
