<?php

namespace App\Game\Domain\Model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'score_sheet')]
class ScoreSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(
        targetEntity: Game::class
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private Game $game;

    #[ORM\ManyToOne(
        targetEntity: GameParticipant::class
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private GameParticipant $participant;

    #[ORM\OneToMany(
        targetEntity: ScoreEntry::class,
        mappedBy: 'sheet',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getParticipant(): GameParticipant
    {
        return $this->participant;
    }

    public function setParticipant(GameParticipant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function setEntries(Collection $entries): self
    {
        $this->entries = $entries;

        return $this;
    }
}
