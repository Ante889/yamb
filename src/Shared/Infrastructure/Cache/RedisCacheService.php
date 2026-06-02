<?php

namespace App\Shared\Infrastructure\Cache;

use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use Psr\Cache\CacheItemPoolInterface;

readonly class RedisCacheService implements RedisCacheServiceInterface
{
    public function __construct(
        private CacheItemPoolInterface $cache
    ) {}

    public function get(string $key): mixed
    {
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            return null;
        }

        return $item->get();
    }

    public function set(string $key, mixed $value, int $ttlSeconds): void
    {
        $item = $this->cache->getItem($key);

        $item->set($value);
        $item->expiresAfter($ttlSeconds);

        $this->cache->save($item);
    }

    public function delete(string $key): void
    {
        $this->cache->deleteItem($key);
    }
}
