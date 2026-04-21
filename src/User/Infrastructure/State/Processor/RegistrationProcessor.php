<?php

declare(strict_types=1);

namespace App\User\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\User\Application\Command\RegistrationCommand;
use App\User\Application\Command\RegistrationCommandHandler;
use App\User\Infrastructure\Resource\RegistrationResource;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class RegistrationProcessor implements ProcessorInterface
{
    /**
     * @implements ProcessorInterface<RegistrationResource, JsonResponse>
     */
    public function __construct(
        private RegistrationCommandHandler $registrationCommandHandler
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        if (!$data instanceof RegistrationResource) {
            throw new \LogicException('Invalid data');
        }

        $this->registrationCommandHandler->__invoke(
            new RegistrationCommand(
                username: $data->username,
                email: $data->email,
                password: $data->password,
                repeatPassword: $data->repeatPassword
            )
        );

        return new JsonResponse([
            'message' => 'User registered successfully',
        ], 201);
    }
}
