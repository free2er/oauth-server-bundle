<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Carbon\Carbon;
use Free2er\OAuth\Entity\AuthorizationCode;
use Free2er\OAuth\Repository\AuthorizationCodeRepositoryInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Throwable;

/**
 * Сервис кодов авторизации
 */
class AuthorizationCodeService implements AuthCodeRepositoryInterface
{
    /**
     * Репозиторий кодов авторизации
     *
     * @var AuthorizationCodeRepositoryInterface
     */
    private $repository;

    /**
     * Конструктор
     *
     * @param AuthorizationCodeRepositoryInterface $repository
     */
    public function __construct(AuthorizationCodeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Создает код авторизации
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        return new AuthorizationCode();
    }

    /**
     * Сохраняет код авторизации
     *
     * @param AuthCodeEntityInterface $code
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $code)
    {
        if (!$code instanceof AuthorizationCode) {
            return;
        }

        try {
            $this->repository->save($code);
        } catch (Throwable $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->removeExpired();
    }

    /**
     * Отзывает код авторизации
     *
     * @param string $id
     */
    public function revokeAuthCode($id)
    {
        if ($code = $this->repository->getCode((string) $id)) {
            $this->repository->remove($code);
        }
    }

    /**
     * Проверяет отзыв кода авторизации
     *
     * @param string $id
     *
     * @return bool
     */
    public function isAuthCodeRevoked($id)
    {
        return $this->repository->getCode((string) $id) === null;
    }

    /**
     * Удаляет просроченные коды авторизации
     */
    private function removeExpired(): void
    {
        foreach ($this->repository->getExpired(Carbon::now()) as $token) {
            $this->repository->remove($token);
        }
    }
}
