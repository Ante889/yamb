<?php

declare(strict_types = 1);

namespace App\Shared\Domain\Mercure;

interface MercureServiceInterface
{
    public function create(string $topic, array $data): void;

    public function update(string $topic, array $data): void;

    public function delete(string $topic, array $data): void;

    public function read(string $topic, array $data): void;
}
