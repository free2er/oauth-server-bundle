<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

use Carbon\Carbon;
use DateTime;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * Ключ продления доступа
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    /**
     * Идентификатор
     *
     * @var string
     */
    private $id = '';

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
     * Ключ доступа
     *
     * @var AccessTokenEntityInterface|null
     */
    private $accessToken;

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
     * Возвращает ключ доступа
     *
     * @return AccessTokenEntityInterface|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Устанавливает ключ доступа
     *
     * @param AccessTokenEntityInterface $accessToken
     */
    public function setAccessToken(AccessTokenEntityInterface $accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
