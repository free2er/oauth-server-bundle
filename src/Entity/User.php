<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Пользователь
 */
class User implements UserEntityInterface
{
    /**
     * Идентификатор
     *
     * @var string
     */
    private $id;

    /**
     * Конструктор
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
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
}
