<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Carbon\Carbon;
use Free2er\OAuth\Entity\RefreshToken;
use Free2er\OAuth\Repository\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface as OAuthRefreshTokenServiceInterface;
use Throwable;

/**
 * Сервис ключей продления доступа
 */
class RefreshTokenService implements OAuthRefreshTokenServiceInterface
{
    /**
     * Репозиторий ключей продления доступа
     *
     * @var RefreshTokenRepositoryInterface
     */
    private $repository;

    /**
     * Конструктор
     *
     * @param RefreshTokenRepositoryInterface $repository
     */
    public function __construct(RefreshTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Создает ключ продления доступа
     *
     * @return RefreshTokenEntityInterface
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * Сохраняет ключ продления доступа
     *
     * @param RefreshTokenEntityInterface $token
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $token)
    {
        if (!$token instanceof RefreshToken) {
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
     * Отзывает ключ продления доступа
     *
     * @param string $id
     */
    public function revokeRefreshToken($id)
    {
        if ($token = $this->repository->getToken((string) $id)) {
            $this->repository->remove($token);
        }
    }

    /**
     * Проверяет отзыв ключа продления доступа
     *
     * @param string $id
     *
     * @return bool
     */
    public function isRefreshTokenRevoked($id)
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
