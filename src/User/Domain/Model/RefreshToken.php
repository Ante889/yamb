<?php

namespace App\User\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken as BaseRefreshToken;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens')]
class RefreshToken extends BaseRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected string|int|null $id = null;

    #[ORM\Column(type: 'string', length: 180)]
    protected ?string $refreshToken;

    #[ORM\Column(type: 'string', length: 180)]
    protected ?string $username;

    #[ORM\Column(type: 'datetime')]
    protected ?\DateTimeInterface $valid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken($refreshToken = null): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getValid(): \DateTimeInterface
    {
        return $this->valid;
    }

    public function setValid($valid): static
    {
        $this->valid = $valid;

        return $this;
    }
}
