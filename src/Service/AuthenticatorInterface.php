<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

/**
 * Интерфейс аутентификатора
 */
interface AuthenticatorInterface
{
    /**
     * Аутентифицирует пользователя по идентификатору
     *
     * @param string $id
     *
     * @return string|null
     */
    public function authenticateById(string $id): ?string;

    /**
     * Аутентифицирует пользователя по логину/паролю
     *
     * @param string $username
     * @param string $password
     *
     * @return string|null
     */
    public function authenticateByCredentials(string $username, string $password): ?string;
}
