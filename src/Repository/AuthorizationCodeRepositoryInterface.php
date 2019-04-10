<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use DateTimeInterface;
use Free2er\OAuth\Entity\AuthorizationCode;

/**
 * Интерфейс репозитория кодов авторизации
 */
interface AuthorizationCodeRepositoryInterface
{
    /**
     * Возвращает просроченные коды авторизации
     *
     * @param DateTimeInterface $time
     *
     * @return AuthorizationCode[]
     */
    public function getExpired(DateTimeInterface $time): array;

    /**
     * Возвращает код авторизации
     *
     * @param string $id
     *
     * @return AuthorizationCode|null
     */
    public function getCode(string $id): ?AuthorizationCode;

    /**
     * Сохраняет код авторизации
     *
     * @param AuthorizationCode $code
     */
    public function save(AuthorizationCode $code): void;

    /**
     * Удаляет код авторизации
     *
     * @param AuthorizationCode $code
     */
    public function remove(AuthorizationCode $code): void;
}
