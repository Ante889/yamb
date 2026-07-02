<?php

namespace App\Game\Domain\Model;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'chat')]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(
        name: 'created',
        type: 'datetime',
        nullable: true
    )]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeInterface $created;

    #[ORM\Column(
        name: 'updated',
        type: 'datetime',
        nullable: true
    )]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeInterface $updated;

    #[ORM\Column(
        name: 'message',
        type: 'text'
    )]
    private string $text = '';

    #[ORM\ManyToOne(
        targetEntity: GameParticipant::class
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private GameParticipant $gameParticipant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function create(
        GameParticipant $gameParticipant,
        string $text
    ): self {
        $chat = new self();
        $chat->gameParticipant = $gameParticipant;
        $chat->text = $text;

        return $chat;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getGameParticipant(): GameParticipant
    {
        return $this->gameParticipant;
    }

    public function setGameParticipant(GameParticipant $gameParticipant): self
    {
        $this->gameParticipant = $gameParticipant;

        return $this;
    }
}
