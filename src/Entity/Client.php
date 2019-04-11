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
     * Пароль
     *
     * @var string
     */
    private $secret;

    /**
     * URI перенаправления
     *
     * @var string
     */
    private $uri = '';

    /**
     * Допустимые типы авторизации
     *
     * @var string[]
     */
    private $grants = [];

    /**
     * Права доступа
     *
     * @var string[]
     */
    private $scopes = [];

    /**
     * Дата создания
     *
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * Дата блокировки
     *
     * @var DateTimeInterface|null
     */
    private $lockedAt;

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
     * Проверяет наличие пароля
     *
     * @return bool
     */
    public function hasSecret(): bool
    {
        return $this->secret === '';
    }

    /**
     * Проверяет пароль
     *
     * @param string $secret
     *
     * @return bool
     */
    public function verifySecret(string $secret): bool
    {
        if (!$secret && !$this->secret) {
            return true;
        }

        return password_verify($secret, $this->secret);
    }

    /**
     * Устанавливает пароль
     *
     * @param string $secret
     * @param int    $cost
     */
    public function setSecret(string $secret, int $cost = 14): void
    {
        if (!$secret) {
            $this->secret = '';
            return;
        }

        if ($cost <= 0) {
            $cost = 14;
        }

        $this->secret = (string) password_hash($secret, PASSWORD_BCRYPT, ['cost' => $cost]);
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
    public function setRedirectUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Проверяет наличие типа авторизации
     *
     * @param string $grant
     *
     * @return bool
     */
    public function hasGrant(string $grant): bool
    {
        return in_array($grant, $this->grants, true);
    }

    /**
     * Возвращает допустимые типы авторизации
     *
     * @return string[]
     */
    public function getGrants(): array
    {
        return $this->grants;
    }

    /**
     * Устанавливает допустимые типы авторизации
     *
     * @param string[] $grants
     */
    public function setGrants(array $grants): void
    {
        $this->grants = $grants;
    }

    /**
     * Возвращает права доступа
     *
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Устанавливает права доступа
     *
     * @param string[] $scopes
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    /**
     * Возвращает дату создания
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Возвращает дату блокировки
     *
     * @return DateTimeInterface|null
     */
    public function getLockedAt(): ?DateTimeInterface
    {
        return $this->lockedAt;
    }

    /**
     * Проверяет залокирован ли клиент
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->lockedAt !== null;
    }

    /**
     * Блокирует клиента
     */
    public function lock(): void
    {
        if (!$this->lockedAt) {
            $this->lockedAt = Carbon::now();
        }
    }

    /**
     * Разблокирует клиента
     */
    public function unlock(): void
    {
        $this->lockedAt = null;
    }
}
