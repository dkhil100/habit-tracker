<?php

namespace App\Entity;

use App\Repository\HabitLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitLogRepository::class)]
class HabitLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $completedDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habit $habit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompletedDate(): ?\DateTime
    {
        return $this->completedDate;
    }

    public function setCompletedDate(\DateTime $completedDate): static
    {
        $this->completedDate = $completedDate;

        return $this;
    }

    public function getHabit(): ?Habit
    {
        return $this->habit;
    }

    public function setHabit(?Habit $habit): static
    {
        $this->habit = $habit;

        return $this;
    }
}
