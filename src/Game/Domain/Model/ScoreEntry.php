<?php

namespace App\Game\Domain\Model;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'score_entry')]
class ScoreEntry
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
        targetEntity: GameParticipant::class,
        inversedBy: 'scoreEntry'
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private GameParticipant $gameParticipant;

    #[ORM\Column(type: Types::STRING, enumType: ScoreEntryFieldEnum::class)]
    private ScoreEntryFieldEnum $field;

    #[ORM\Column(
        type: Types::STRING,
        enumType: ScoreEntryColumnEnum::class
    )]
    private ScoreEntryColumnEnum $entryColumn;

    #[ORM\Column(
        type: 'integer',
        nullable: true
    )]
    private ?int $value = null;

    public static function create(
        GameParticipant $gameParticipant,
        ScoreEntryFieldEnum $field,
        ScoreEntryColumnEnum $column,
        int $value
    ): self {
        $scoreEntry = new self();

        $scoreEntry->gameParticipant = $gameParticipant;
        $scoreEntry->field = $field;
        $scoreEntry->entryColumn = $column;
        $scoreEntry->value = $value;

        return $scoreEntry;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getGameParticipant(): GameParticipant
    {
        return $this->gameParticipant;
    }

    public function setGameParticipant(GameParticipant $gameParticipant): self
    {
        $this->gameParticipant = $gameParticipant;

        return $this;
    }

    public function getField(): ScoreEntryFieldEnum
    {
        return $this->field;
    }

    public function setField(ScoreEntryFieldEnum $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getEntryColumn(): ScoreEntryColumnEnum
    {
        return $this->entryColumn;
    }

    public function setEntryColumn(ScoreEntryColumnEnum $entryColumn): self
    {
        $this->entryColumn = $entryColumn;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
