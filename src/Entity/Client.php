<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

use Carbon\Carbon;
use DateTimeInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Клиент
 */
class Client implements ClientEntityInterface
{
    /**
     * Идентификатор
     *
     * @var string
     */
    private $id;

    /**
     * URI перенаправления
     *
     * @var string
     */
    private $uri = '';

    /**
     * Дата создания
     *
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * Конструктор
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id        = $id;
        $this->createdAt = Carbon::now();
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
     * Возвращает наименование
     *
     * @return string
     */
    public function getName()
    {
        return $this->id;
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
}
