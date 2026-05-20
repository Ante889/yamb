<?php

declare(strict_types = 1);

namespace App\Shared\Domain\Cache;

use Psr\Cache\InvalidArgumentException;

interface RedisCacheServiceInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key): mixed;

    /**
     * @throws InvalidArgumentException
     */
    public function set(string $key, mixed $value, int $ttlSeconds): void;

    public function delete(string $key): void;
}
