<?php

namespace App\Game\Domain\Model;

use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\User\Domain\Model\User;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'game_participant')]
class GameParticipant
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

    #[ORM\ManyToOne(
        targetEntity: Game::class,
        inversedBy: 'participants'
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private Game $game;

    #[ORM\ManyToOne(
        targetEntity: User::class,
        inversedBy: 'participants'
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private User $user;

    #[ORM\Column(type: 'string', length: 20, enumType: GameParticipantStatusEnum::class)]
    private GameParticipantStatusEnum $status;

    #[ORM\Column(
        name: 'position',
        type: 'integer',
        nullable: true
    )]
    private ?int $position;

    #[ORM\Column(
        name: 'joinedAt',
        type: 'datetime_immutable',
        nullable: true
    )]
    private \DateTimeInterface $joinedAt;

    #[ORM\OneToMany(
        targetEntity: ScoreEntry::class,
        mappedBy: 'gameParticipant',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $scoreEntry;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $points = null;

    public function __construct(
        User $user,
        Game $game,
        GameParticipantStatusEnum $status,
        int $position,
        DateTimeInterface $joinedAt
    ) {
        $this->user = $user;
        $this->game = $game;
        $this->status = $status;
        $this->position = $position;
        $this->joinedAt = $joinedAt;

        $this->scoreEntry = new ArrayCollection();
    }

    public static function create(
        User $user,
        Game $game,
        GameParticipantStatusEnum $status,
        int $position = 1
    ): self
    {
        return new self(
            $user,
            $game,
            $status,
            $position,
            new \DateTimeImmutable()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
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


    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): GameParticipantStatusEnum
    {
        return $this->status;
    }

    public function setStatus(GameParticipantStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getJoinedAt(): \DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    public function getScoreEntry(): Collection
    {
        return $this->scoreEntry;
    }

    public function setScoreEntry(Collection $scoreEntry): void
    {
        $this->scoreEntry = $scoreEntry;
    }

    public function addScoreEntry(ScoreEntry $scoreEntry): void
    {
        $this->scoreEntry->add($scoreEntry);
    }
}
