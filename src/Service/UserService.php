<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Service;

use Free2er\OAuth\Entity\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface as OAuthUserServiceInterface;

/**
 * Сервис пользователей
 */
class UserService implements OAuthUserServiceInterface
{
    /**
     * Аутентификаторы
     *
     * @var AuthenticatorInterface[]
     */
    private $authenticators = [];

    /**
     * Добавляет аутентификатор
     *
     * @param AuthenticatorInterface $authenticator
     */
    public function addAuthenticator(AuthenticatorInterface $authenticator): void
    {
        $this->authenticators[] = $authenticator;
    }

    /**
     * Возвращает пользователя
     *
     * @param string                $username
     * @param string                $password
     * @param string                $grant
     * @param ClientEntityInterface $client
     *
     * @return UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials($username, $password, $grant, ClientEntityInterface $client)
    {
        $username = (string) $username;
        $password = (string) $password;

        foreach ($this->authenticators as $authenticator) {
            if ($id = $authenticator->authenticateByCredentials($username, $password)) {
                return new User($id);
            }
        }

        return null;
    }
}
