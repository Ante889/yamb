<?php

namespace App\Game\Domain\Model;

use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Enum\GameTypeEnum;
use App\Game\Domain\Event\GameCanceled;
use App\Shared\Domain\Trait\RecordsDomainEvents;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'game')]
class Game
{
    use RecordsDomainEvents;

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
        name: 'status',
        type: 'boolean'
    )]
    private bool $status = true;

    #[ORM\Column(
        name: 'game_status',
        type: 'string',
        length: 50
    )]
    private string $gameStatus;

    #[ORM\Column(
        name: 'min_players',
        type: 'integer'
    )]
    private int $minPlayers;

    #[ORM\Column(
        name: 'max_players',
        type: 'integer'
    )]
    private int $maxPlayers;

    #[ORM\Column(
        name: 'dice_number',
        type: 'integer'
    )]
    private int $diceNumber;

    #[ORM\Column(
        name: 'join_code',
        type: 'string',
        length: 20,
        nullable: true
    )]
    private ?string $joinCode = null;

    #[ORM\Column(
        name: 'startedAt',
        type: 'datetime_immutable',
        nullable: true
    )]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(
        name: 'finishedAt',
        type: 'datetime_immutable',
        nullable: true
    )]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\OneToMany(
        targetEntity: GameParticipant::class,
        mappedBy: 'game',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $participants;

    #[ORM\Column(type: 'string', length: 20, enumType: GameTypeEnum::class)]
    private GameTypeEnum $gameType;

    #[ORM\Column(
        name: 'isPublic',
        type: 'boolean'
    )]
    private bool $isPublic = true;

    private array $recordedEvents = [];

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function finish(?GameParticipant $gameParticipant, int $points = 0): void
    {
        if ($this->gameStatus === GameStatusEnum::FINISHED->value) {
            return;
        }

        $this->gameStatus = GameStatusEnum::FINISHED->value;
        $this->finishedAt = new \DateTimeImmutable();

        $gameParticipant?->setPoints($points);
        $gameParticipant?->setStatus(GameParticipantStatusEnum::FINISH);
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

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getGameStatus(): string
    {
        return $this->gameStatus;
    }

    public function setGameStatus(string $gameStatus): self
    {
        $this->gameStatus = $gameStatus;

        return $this;
    }

    public function cancel(): void
    {
        if ($this->gameStatus === GameStatusEnum::CANCELLED->value) {
            return;
        }

        $this->gameStatus = GameStatusEnum::CANCELLED->value;

        $this->recordThat(
            new GameCanceled(
                gameId: $this->id
            )
        );
    }

    public function getMinPlayers(): int
    {
        return $this->minPlayers;
    }

    public function setMinPlayers(int $minPlayers): self
    {
        $this->minPlayers = $minPlayers;

        return $this;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): self
    {
        $this->maxPlayers = $maxPlayers;

        return $this;
    }

    public function getDiceNumber(): int
    {
        return $this->diceNumber;
    }

    public function setDiceNumber(int $diceNumber): self
    {
        $this->diceNumber = $diceNumber;

        return $this;
    }

    public function getJoinCode(): ?string
    {
        return $this->joinCode;
    }

    public function setJoinCode(?string $joinCode): self
    {
        $this->joinCode = $joinCode;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function setParticipants(Collection $participants): self
    {
        $this->participants = $participants;

        return $this;
    }

    public function addParticipant(GameParticipant $participant): void
    {
        $this->participants->add($participant);
    }

    public function getGameType(): GameTypeEnum
    {
        return $this->gameType;
    }

    public function setGameType(GameTypeEnum $gameType): self
    {
        $this->gameType = $gameType;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}
