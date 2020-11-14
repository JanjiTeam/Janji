<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_events"})
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get_events"})
     */
    private \DateTimeImmutable $start;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get_events"})
     * @Assert\GreaterThan(propertyPath="start")
     */
    private \DateTimeImmutable $end;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Calendar::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private Calendar $calendar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): \DateTimeImmutable
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(Calendar $calendar): self
    {
        $this->calendar = $calendar;

        return $this;
    }
}
