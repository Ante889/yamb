<?php

declare(strict_types=1);

namespace App\Mercure\Infrastructure\Service;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class LcobucciMercureTokenGenerator
{
    private Configuration $config;

    public function __construct(
        #[Autowire(env: 'MERCURE_JWT_SECRET')]
        string $mercureJwtSecret,
        #[Autowire(value: '3600')]
        private string $ttlSeconds,
    ) {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($mercureJwtSecret),
        );
    }

    /**
     * @param array<int, string> $subscribeTopics
     */
    public function generate(array $subscribeTopics): string
    {
        $now = new \DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify("+{$this->ttlSeconds} seconds"))
            ->withClaim('mercure', ['subscribe' => $subscribeTopics])
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }
}
