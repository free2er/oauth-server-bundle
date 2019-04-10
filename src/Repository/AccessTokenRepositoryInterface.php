<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use DateTimeInterface;
use Free2er\OAuth\Entity\AccessToken;

/**
 * Интерфейс репозитория ключей доступа
 */
interface AccessTokenRepositoryInterface
{
    /**
     * Возвращает просроченные ключи доступа
     *
     * @param DateTimeInterface $time
     *
     * @return AccessToken[]
     */
    public function getExpired(DateTimeInterface $time): array;

    /**
     * Возвращает ключ доступа
     *
     * @param string $id
     *
     * @return AccessToken|null
     */
    public function getToken(string $id): ?AccessToken;

    /**
     * Сохраняет ключ доступа
     *
     * @param AccessToken $token
     */
    public function save(AccessToken $token): void;

    /**
     * Удаляет ключ доступа
     *
     * @param AccessToken $token
     */
    public function remove(AccessToken $token): void;
}
