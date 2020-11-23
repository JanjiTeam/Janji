<?php

namespace App\Entity;

use App\Repository\SlotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SlotRepository::class)
 */
class Slot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_slots"})
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get_slots"})
     */
    private \DateTimeImmutable $start;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get_slots"})
     */
    private \DateTimeImmutable $end;

    /**
     * @ORM\ManyToOne(targetEntity=Calendar::class, inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Calendar $calendar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(\DateTimeImmutable $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeImmutable
    {
        return $this->end;
    }

    public function setEnd(\DateTimeImmutable $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): self
    {
        $this->calendar = $calendar;

        return $this;
    }
}
