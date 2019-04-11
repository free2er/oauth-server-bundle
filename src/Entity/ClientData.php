<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Entity;

/**
 * Сведения о клиенте
 */
class ClientData
{
    /**
     * Пароль
     *
     * @var string|null
     */
    private $secret;

    /**
     * Количество итераций шифрования пароля
     *
     * @var string|null
     */
    private $cost;

    /**
     * URI перенаправления
     *
     * @var string|null
     */
    private $uri;

    /**
     * Допустимые типы авторизации
     *
     * @var string[]
     */
    private $grants;

    /**
     * Права доступа
     *
     * @var string[]
     */
    private $scopes;

    /**
     * Отзыв допустимых типов авторизации или прав доступа
     *
     * @var bool
     */
    private $revoke;

    /**
     * Конструктор
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->secret = $data['secret'] ?? null;
        $this->cost   = $data['cost'] ?? null;
        $this->uri    = $data['uri'] ?? null;
        $this->grants = $data['grants'] ?? [];
        $this->scopes = $data['scopes'] ?? [];
        $this->revoke = $data['revoke'] ?? false;
    }

    /**
     * Устанавливает сведения о клиенте
     *
     * @param Client $client
     */
    public function apply(Client $client): void
    {
        if ($this->secret !== null) {
            $client->setSecret($this->secret, intval($this->cost) ?: null);
        }

        if ($this->uri !== null) {
            $client->setRedirectUri($this->uri);
        }

        if ($this->grants && !$this->revoke) {
            $client->enableGrants($this->grants);
        }

        if ($this->grants && $this->revoke) {
            $client->disableGrants($this->grants);
        }

        if ($this->scopes && !$this->revoke) {
            $client->enableScopes($this->grants);
        }

        if ($this->scopes && $this->revoke) {
            $client->disableScopes($this->grants);
        }
    }
}
