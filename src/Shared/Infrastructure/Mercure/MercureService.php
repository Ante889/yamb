<?php

namespace App\Shared\Infrastructure\Mercure;

use App\Shared\Domain\Mercure\MercureServiceInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

readonly class MercureService implements MercureServiceInterface
{
    public function __construct(
        private HubInterface $hub,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function create(string $topic, array $data): void
    {
        $this->publish($topic, 'created', $data);
    }

    /**
     * @throws \JsonException
     */
    public function update(string $topic, array $data): void
    {
        $this->publish($topic, 'updated', $data);
    }

    /**
     * @throws \JsonException
     */
    public function delete(string $topic, array $data): void
    {
        $this->publish($topic, 'deleted', $data);
    }

    /**
     * @throws \JsonException
     */
    public function read(string $topic, array $data): void
    {
        $this->publish($topic, 'read', $data);
    }

    /**
     * @throws \JsonException
     */
    private function publish(
        string $topic,
        string $action,
        array $data
    ): void {
        $update = new Update(
            topics: $topic,
            data: json_encode([
                'action' => $action,
                'payload' => $data,
                'timestamp' => (new \DateTimeImmutable())->format(DATE_ATOM),
            ], JSON_THROW_ON_ERROR),
        );

        $this->hub->publish($update);
    }
}
