<?php

namespace App\Shared\Infrastructure\Cache;

use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

readonly class RedisCacheService implements RedisCacheServiceInterface
{
    public function __construct(
        private CacheInterface $cache
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
        $this->cache->delete($key);

        $this->cache->get($key, function (ItemInterface $item) use ($value, $ttlSeconds) {
            $item->expiresAfter($ttlSeconds);
            return $value;
        });
    }
}
