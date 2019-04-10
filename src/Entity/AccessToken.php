<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

use Carbon\Carbon;
use DateTime;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Ключ доступа
 */
class AccessToken implements AccessTokenEntityInterface
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
     * Шифратор JWT
     *
     * @var Signer|null
     */
    private $signer;

    /**
     * Конструктор
     *
     * @param Signer $signer
     */
    public function __construct(Signer $signer)
    {
        $this->createdAt = Carbon::now()->toMutable();
        $this->expiredAt = $this->createdAt;
        $this->signer    = $signer;
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

    /**
     * Создает JWT
     *
     * @param CryptKey $privateKey
     *
     * @return Token
     */
    public function convertToJWT(CryptKey $privateKey)
    {
        $builder = new Builder();
        $builder->setId($this->id, true);
        $builder->setAudience($this->client ? (string) $this->client->getIdentifier() : '');
        $builder->setSubject($this->user);
        $builder->setIssuedAt($this->createdAt->getTimestamp());
        $builder->setNotBefore($this->createdAt->getTimestamp());
        $builder->setExpiration($this->expiredAt->getTimestamp());
        $builder->set('scopes', $this->scopes);

        $key = new Signer\Key($privateKey->getKeyPath(), $privateKey->getPassPhrase());
        $builder->sign($this->signer, $key);

        return $builder->getToken();
    }
}
