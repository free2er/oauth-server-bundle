<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Право доступа
 */
class Scope implements ScopeEntityInterface
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

    /**
     * Сериализует объект в JSON
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->id;
    }
}
