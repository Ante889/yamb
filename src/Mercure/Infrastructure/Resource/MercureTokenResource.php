<?php

declare(strict_types=1);

namespace App\Mercure\Infrastructure\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Mercure\Infrastructure\State\Provider\MercureTokenProvider;

#[ApiResource(
    shortName: 'MercureData',
    operations: [
        new Get(
            uriTemplate: '/mercure-token',
            description: 'Get mercure subscriber token',
            security: "is_granted('ROLE_USER')",
            provider:MercureTokenProvider::class
        ),
    ],
)]
final class MercureTokenResource
{
    public ?string $token = null;
}
