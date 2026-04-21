<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\User\Infrastructure\State\Processor\RegistrationProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'RegistrationData',
    operations: [
        new Post(
            uriTemplate: '/register',
            description: 'Registration route',
            processor: RegistrationProcessor::class
        )
    ],
)]
final readonly class RegistrationResource
{
    public function __construct(
        #[Assert\NotBlank(groups: ['registration'])]
        #[Assert\Length(min: 1, max: 180, groups: ['registration'])]
        public ?string $username = null,

        #[Assert\NotBlank(groups: ['registration'])]
        #[Assert\Email(groups: ['registration'])]
        public ?string $email = null,

        #[Assert\NotBlank(groups: ['registration'])]
        #[Assert\Length(min: 6, groups: ['registration'])]
        public ?string $password = null,

        #[Assert\NotBlank(groups: ['registration'])]
        public ?string $repeatPassword = null,
    ) {}
}
