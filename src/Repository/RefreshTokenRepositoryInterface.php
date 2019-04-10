<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Repository;

use DateTimeInterface;
use Free2er\OAuth\Entity\RefreshToken;

/**
 * Интерфейс репозитория ключей продления доступа
 */
interface RefreshTokenRepositoryInterface
{
    /**
     * Возвращает просроченные ключи продления доступа
     *
     * @param DateTimeInterface $time
     *
     * @return RefreshToken[]
     */
    public function getExpired(DateTimeInterface $time): array;

    /**
     * Возвращает ключ продления доступа
     *
     * @param string $id
     *
     * @return RefreshToken|null
     */
    public function getToken(string $id): ?RefreshToken;

    /**
     * Сохраняет ключ продления доступа
     *
     * @param RefreshToken $token
     */
    public function save(RefreshToken $token): void;

    /**
     * Удаляет ключ продления доступа
     *
     * @param RefreshToken $token
     */
    public function remove(RefreshToken $token): void;
}
