<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

use Carbon\Carbon;
use DateTime;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Код авторизации
 */
class AuthorizationCode implements AuthCodeEntityInterface
{
    /**
     * Идентификатор
     *
     * @var string
     */
    private $id = '';

    /**
     * Пользователь
     *
     * @var string
     */
    private $user = '';

    /**
     * URI перенаправления
     *
     * @var string
     */
    private $uri = '';

    /**
     * Права доступа
     *
     * @var string[]
     */
    private $scopes = [];

    /**
     * Дата создания
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * Дата истечения срока действия
     *
     * @var DateTime
     */
    private $expiredAt;

    /**
     * Клиент
     *
     * @var ClientEntityInterface|null
     */
    private $client;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->createdAt = Carbon::now()->toMutable();
        $this->expiredAt = $this->createdAt;
    }

    /**
     * Возвращает идентификатор
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Устанавливает идентификатор
     *
     * @param string $id
     */
    public function setIdentifier($id)
    {
        $this->id = (string) $id;
    }

    /**
     * Возвращает пользователя
     *
     * @return string
     */
    public function getUserIdentifier()
    {
        return $this->user;
    }

    /**
     * Устанавливает пользователя
     *
     * @param string $user
     */
    public function setUserIdentifier($user)
    {
        $this->user = (string) $user;
    }

    /**
     * Возвращает URI перенаправления
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->uri;
    }

    /**
     * Устанавливает URI перенаправления
     *
     * @param string $uri
     */
    public function setRedirectUri($uri)
    {
        $this->uri = (string) $uri;
    }

    /**
     * Возвращает дату истечения срока действия
     *
     * @return DateTime
     */
    public function getExpiryDateTime()
    {
        return clone $this->expiredAt;
    }

    /**
     * Устанавливает дату истечения срока действия
     *
     * @param DateTime $time
     */
    public function setExpiryDateTime(DateTime $time)
    {
        $this->expiredAt = clone $time;
    }

    /**
     * Возвращает клиента
     *
     * @return ClientEntityInterface|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Устанавливает клиента
     *
     * @param ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Return an array of scopes associated with the token.
     *
     * @return ScopeEntityInterface[]
     */
    public function getScopes()
    {
        $scopes = [];

        foreach ($this->scopes as $scope) {
            $scopes[] = new Scope($scope);
        }

        return $scopes;
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $scope = (string) $scope->getIdentifier();

        if (!in_array($scope, $this->scopes, true)) {
            $this->scopes[] = $scope;
        }
    }
}
